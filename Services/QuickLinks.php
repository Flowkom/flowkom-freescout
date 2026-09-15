<?php

namespace Modules\Flowkom\Services;

/**
 * Marktplatz-Deeplinks in der Ticket-Sidebar. Alle URLs kanonisch und
 * tokenfrei aus Metadaten gebaut (tokenisierte Links aus Original-Mails
 * wuerden in zitierte Antworten leaken und laufen ab).
 */
class QuickLinks
{
    public static function register()
    {
        \Eventy::addAction('conversation.after_customer_sidebar', function ($conversation) {
            try {
                $links = self::build($conversation);
            } catch (\Throwable $e) {
                return;
            }
            if (!$links) {
                return;
            }

            $isEbay = $links['source'] === 'ebay';
            echo '<div class="flowkom-quicklinks" style="margin-top:10px;border:1px solid #d6dce2;border-radius:4px;overflow:hidden;font-size:12px;">';
            echo '<div style="padding:7px 10px;background:' . ($isEbay ? '#3665f3' : '#232f3e') . ';color:#fff;font-weight:600;font-size:11px;letter-spacing:.5px;">'
                . ($isEbay ? 'eBay' : 'Amazon') . ' QUICKLINKS</div>';
            echo '<div style="padding:8px 10px;background:#fff;display:flex;flex-direction:column;gap:5px;">';
            foreach ($links['items'] as $item) {
                echo '<a href="' . htmlspecialchars($item[1], ENT_QUOTES) . '" target="_blank" rel="noopener noreferrer" '
                    . 'style="display:block;text-align:center;padding:5px 0;border-radius:3px;font-size:11px;text-decoration:none;color:#fff;font-weight:500;background:'
                    . ($isEbay ? '#3665f3' : '#e47911') . ';">'
                    . htmlspecialchars($item[0], ENT_QUOTES) . '</a>';
            }
            echo '</div></div>';
        }, 30, 1);
    }

    private static function build($conversation)
    {
        if (empty($conversation) || empty($conversation->customer_email)) {
            return null;
        }
        // Dieselbe Erkennung wie das Flowkom-Widget (PROJ-861), damit
        // QuickLinks und Bestellzuordnung nie unterschiedliche Merkmale sehen.
        $hints = TicketHints::fromConversation($conversation);

        if ($hints['channel'] === 'ebay') {
            $ebayDomain = Settings::ebayDomain();
            $items = [];
            $order = $hints['ebay_order_id'];
            $item = $hints['ebay_item_id'];
            $buyer = $hints['ebay_username'];

            if ($order) {
                $items[] = ['Bestellung im Seller Hub', 'https://www.' . $ebayDomain . '/sh/ord/details?orderid=' . rawurlencode($order)];
            }
            if ($item && $buyer) {
                $items[] = ['Konversation mit ' . $buyer, 'https://www.' . $ebayDomain . '/ulk/messages/reply?M2MContact&item=' . rawurlencode($item) . '&requested=' . rawurlencode($buyer) . '&redirect=0'];
            }
            if ($item) {
                $items[] = ['Artikelseite', 'https://www.' . $ebayDomain . '/itm/' . rawurlencode($item)];
            }

            return $items ? ['source' => 'ebay', 'items' => $items] : null;
        }

        if ($hints['channel'] === 'amazon') {
            $scDomain = Settings::scDomain();
            $items = [];
            if ($hints['amazon_order_id']) {
                $items[] = ['Bestellung in Seller Central', 'https://' . $scDomain . '/orders-v3/order/' . rawurlencode($hints['amazon_order_id'])];
                $items[] = ['Messaging-Postfach', 'https://' . $scDomain . '/messaging/inbox'];
            }
            return $items ? ['source' => 'amazon', 'items' => $items] : null;
        }

        return null;
    }
}
