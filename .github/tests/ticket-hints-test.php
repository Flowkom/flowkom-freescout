<?php
/**
 * CLI-Test fuer Services/TicketHints::extract (ohne FreeScout).
 *   php .github/tests/ticket-hints-test.php
 * Liegt unter .github/, damit er nicht ins Release-ZIP wandert.
 * Faelle nach echten Tickets (helpdesk.pixkom.com), Namen/Nummern aus Pixkom Live.
 */
require __DIR__ . '/../../Services/MailCleaner.php';
require __DIR__ . '/../../Services/TicketHints.php';

use Modules\Flowkom\Services\TicketHints;

$fail = 0;
function check($name, $actual, $expected) {
    global $fail;
    foreach ($expected as $k => $v) {
        if (($actual[$k] ?? null) !== $v) {
            $fail++;
            echo "FAIL  $name: $k = " . var_export($actual[$k] ?? null, true) . ", erwartet " . var_export($v, true) . "\n";
            return;
        }
    }
    echo "ok    $name\n";
}

// 1) Ticket 99718: Betreff traegt die ARTIKELnummer, kein Bestellnr. im Text
check('eBay 99718 – Artikelnr. aus Betreff ist nie Bestellnummer', TicketHints::extract(
    'kaeufer_b42_x7@members.ebay.de',
    'kaeufer_b42 hat eine Nachricht gesendet zu Stecknuss-Adapter 3er Set Sechskant #197526923798',
    'eBay - kaeufer_b42',
    ['<div id="UserInputtedText">Wo bleibt meine Ware?</div>']
), [
    'channel' => 'ebay', 'ebay_username' => 'kaeufer_b42', 'ebay_item_id' => '197526923798',
    'ebay_order_id' => null, 'order_number' => null,
]);

// 2) eBay-Nachricht mit Bestelldaten im Text (echtes Format, Ticket #81514)
check('eBay – Bestellnr. aus dem Nachrichtentext', TicketHints::extract(
    'kaeufer_m88_x1@members.ebay.de',
    'kaeufer_m88 hat eine Nachricht gesendet zu Ventilkappen 10 Stück #196970679952',
    'eBay - kaeufer_m88',
    ['<p>Hallo ich habe die Ware nicht erhalten</p><table><tr><td>Artikelnr.: 196970679952</td></tr><tr><td>Bestellnr.: 27-10000-00003</td></tr><tr><td>Transaktionsnr.: 10000000000001</td></tr></table>']
), [
    'channel' => 'ebay', 'ebay_username' => 'kaeufer_m88', 'ebay_order_id' => '27-10000-00003', 'ebay_item_id' => '196970679952',
    'order_number' => '27-10000-00003',
]);

// 3) Kaeufername nur aus dem Kundennamen
check('eBay – Kaeufer aus Kundenname', TicketHints::extract(
    'x@members.ebay.com', 'Frage zum Artikel', 'eBay - Kaeufer_J75', []
), ['ebay_username' => 'kaeufer_j75', 'ebay_item_id' => null]);

// 4) Amazon
check('Amazon – Bestellnummer', TicketHints::extract(
    'abcdefghijk1234@marketplace.amazon.de', 'Anfrage zur Bestellung 304-1000000-0000001', 'Max', []
), ['channel' => 'amazon', 'amazon_order_id' => '304-1000000-0000001', 'order_number' => '304-1000000-0000001']);

// 5) Webshop-Mail
check('Webshop – #11038', TicketHints::extract(
    'kunde@example.de', 'AW: Ihre Bestellung #11038 wurde versendet', 'Anna Kunde', []
), ['channel' => 'email', 'order_number' => '11038', 'ebay_username' => null]);

// 6) Webshop-Mail mit 12-stelliger #Zahl: nie als Bestellnummer
check('Webshop – 12-stellige #Zahl ignoriert', TicketHints::extract(
    'kunde@example.de', 'Frage zu #197526923798', '', []
), ['order_number' => null]);

// 7) Otto
check('Otto – Auftrags-Nr', TicketHints::extract(
    'service@otto.de', 'Rückfrage zu Auftrags-Nr. cbn4test02', '', []
), ['order_number' => 'cbn4test02']);

// 8) FreeScout-Ticketnummer im Betreff einer Kundenmail ist kein eBay-Kontext
check('Leeres Ticket', TicketHints::extract('', '', '', []), ['channel' => 'email', 'email' => null, 'order_number' => null]);

if ($fail > 0) { echo "\n$fail Fehler\n"; exit(1); }
echo "\nalle Faelle ok\n";
