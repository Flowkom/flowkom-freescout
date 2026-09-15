<?php

namespace Modules\Flowkom\Services;

/**
 * Typisierte Merkmale eines Tickets fuer die Bestellzuordnung in Flowkom.
 *
 * WARUM (Ticket 99718, 15.09.2026): Das Widget las frueher nur den Betreff und
 * schickte jede "#Zahl" als Bestellnummer. Bei eBay-Nachrichten ist das die
 * ARTIKELnummer ("… hat eine Nachricht gesendet zu <Titel> #197526923798").
 * Flowkom fand damit den Auftrag eines fremden Kaeufers desselben Artikels.
 *
 * Jetzt: Erkennung serverseitig aus Absender, Betreff, Kundenname UND den
 * letzten Kundennachrichten (dort steht bei eBay "Bestellnr.: 27-10000-00003").
 * Jedes Merkmal wird mit seinem TYP geschickt — eine Artikelnummer ist nie
 * eine Bestellnummer. Flowkom entscheidet dann, wie eindeutig die Zuordnung ist.
 */
class TicketHints
{
    /** So viele juengste Kundennachrichten werden nach Merkmalen durchsucht. */
    const MAX_THREADS = 5;

    /** Pro Request je Konversation nur einmal rechnen (Widget + QuickLinks). */
    private static $cache = [];

    public static function fromConversation($conversation)
    {
        if (empty($conversation)) {
            return self::extract('', '', '', []);
        }
        $key = (string) ($conversation->id ?? '');
        if ($key !== '' && isset(self::$cache[$key])) {
            return self::$cache[$key];
        }
        return self::$cache[$key] = self::compute($conversation);
    }

    private static function compute($conversation)
    {
        $email = (string) ($conversation->customer_email ?? '');
        $customer = $conversation->customer;
        if ($email === '' && $customer && method_exists($customer, 'getMainEmail')) {
            $email = (string) $customer->getMainEmail();
        }
        $customerName = $customer
            ? trim(($customer->first_name ?? '') . ' ' . ($customer->last_name ?? ''))
            : '';

        $bodies = [];
        try {
            $threads = $conversation->threads()
                ->where('type', \App\Thread::TYPE_CUSTOMER)
                ->orderBy('created_at', 'desc')
                ->limit(self::MAX_THREADS)
                ->get();
            foreach ($threads as $thread) {
                $bodies[] = (string) $thread->body;
            }
        } catch (\Throwable $e) {
            // fail-open: ohne Nachrichtentext bleiben Betreff und Absender
        }

        return self::extract($email, (string) $conversation->subject, $customerName, $bodies);
    }

    /**
     * Reine Funktion (ohne FreeScout-Abhaengigkeiten) — testbar per CLI,
     * siehe .github/tests/ticket-hints-test.php.
     *
     * @param string   $email        Absender des Tickets
     * @param string   $subject      Betreff
     * @param string   $customerName FreeScout-Kundenname ("eBay - kaeufer_b42")
     * @param string[] $bodies       Roh-Bodies der juengsten Kundennachrichten (neueste zuerst)
     */
    public static function extract($email, $subject, $customerName, array $bodies)
    {
        $email = strtolower(trim((string) $email));
        $subject = (string) $subject;

        $texts = [];
        foreach ($bodies as $body) {
            $texts[] = MailCleaner::htmlFragmentToText((string) $body);
        }

        $hints = [
            'channel'         => self::channel($email),
            'email'           => $email !== '' ? $email : null,
            'ebay_username'   => null,
            'ebay_order_id'   => null,
            'ebay_item_id'    => null,
            'amazon_order_id' => null,
            'order_number'    => null,
        ];

        if ($hints['channel'] === 'ebay') {
            $hints['ebay_username'] = self::ebayUsername($subject, $customerName);
            $hints['ebay_order_id'] = self::firstMatch(
                array_merge([$subject], $texts),
                [
                    '/(?:Bestellnr\.?|Bestellnummer|Order number)\s*:?\s*(\d{2}-\d{5}-\d{5})\b/iu',
                    '/\b(\d{2}-\d{5}-\d{5})\b/u',
                ]
            );
            $hints['ebay_item_id'] = self::firstMatch(
                array_merge([$subject], $texts),
                ['/(?:Artikelnr\.?|Artikelnummer|Item number)\s*:?\s*(\d{9,14})\b/iu']
            );
            if ($hints['ebay_item_id'] === null && preg_match('/#(\d{12,14})\b/u', $subject, $m)) {
                $hints['ebay_item_id'] = $m[1];
            }
            // Auch als order_number: aeltere Flowkom-Server kennen ebay_order_id
            // noch nicht. Nie die Artikelnummer.
            $hints['order_number'] = $hints['ebay_order_id'];
            return $hints;
        }

        if ($hints['channel'] === 'amazon') {
            $hints['amazon_order_id'] = self::firstMatch(
                array_merge([$subject], $texts),
                ['/\b(\d{3}-\d{7}-\d{7})\b/u']
            );
            $hints['order_number'] = $hints['amazon_order_id'];
            return $hints;
        }

        // Normale E-Mail (Webshop, Otto, Kaufland …): nur ausdruecklich
        // erkennbare Bestellnummern aus dem Betreff.
        $patterns = [
            '/\b(\d{3}-\d{7}-\d{7})\b/u',                       // Amazon-Nr. in einer normalen Mail
            '/\b(\d{2}-\d{5}-\d{5})\b/u',                       // eBay-Nr. in einer normalen Mail
            '/Auftrags-Nr\.?\s*:?\s*([a-z0-9]{8,})\b/iu',       // Otto
            '/(?:Bestellung|Bestellnummer|Bestell-Nr\.?|Order)\s*:?\s*#?\s*(\d{4,10})\b/iu',
            '/#(\d{4,10})\b/u',                                 // Shop-Nr. "#11038" (nie 12+ Stellen)
        ];
        $hints['order_number'] = self::firstMatch([$subject], $patterns);

        return $hints;
    }

    public static function channel($email)
    {
        if (preg_match('/@members\.ebay\.[a-z]{2,3}(\.[a-z]{2})?$/', $email)) {
            return 'ebay';
        }
        if (preg_match('/@marketplace\.amazon\.[a-z]{2,3}(\.[a-z]{2})?$/', $email)) {
            return 'amazon';
        }
        return 'email';
    }

    private static function ebayUsername($subject, $customerName)
    {
        if (preg_match('/(?:^|\s)([A-Za-z0-9._\-*]{2,64})\s+(?:hat eine Nachricht gesendet|hat eine Frage|sent a message|has sent a question)/u', $subject, $m)) {
            return strtolower($m[1]);
        }
        if (preg_match('/^eBay\s*-\s*([A-Za-z0-9._\-*]{2,64})$/iu', trim((string) $customerName), $m)) {
            return strtolower($m[1]);
        }
        return null;
    }

    private static function firstMatch(array $texts, array $patterns)
    {
        foreach ($patterns as $pattern) {
            foreach ($texts as $text) {
                if ($text !== '' && preg_match($pattern, $text, $m)) {
                    return $m[1];
                }
            }
        }
        return null;
    }
}
