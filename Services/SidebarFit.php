<?php

namespace Modules\Flowkom\Services;

/**
 * Sorgt dafuer, dass die Ticket-Seitenleiste immer vollstaendig erreichbar ist.
 *
 * WARUM: FreeScouts Layout haengt die Seitenleiste absolut positioniert mit
 * `height:100%` in `#conv-layout` — und dessen Hoehe ergibt sich allein aus der
 * Nachrichtenspalte (beide Spalten sind Floats, die niemand einschliesst;
 * gemessen: `#conv-layout` ist 0 Pixel hoch). Alles, was ueber die Hoehe der
 * Nachrichtenspalte hinausragt, schneidet `#app { overflow: hidden }` ab, und
 * weil die Seite dadurch gar nicht erst scrollbar wird, ist es unerreichbar.
 * Bei kurzen Mails traf das unsere Bloecke (QuickLinks + Widget mit Ampel,
 * Bestellung, Tracking-Knopf) — sichtbar war nur der obere Teil.
 *
 * FIX: die im Fluss stehende Nachrichtenspalte bekommt genau so viel
 * `min-height`, dass die Seitenleiste hineinpasst. Dann waechst `#app` mit, die
 * Seite wird normal scrollbar und nichts wird abgeschnitten. Kein DOM-Umbau,
 * nur eine gemessene Stilangabe; bei langen Verlaeufen und im Mobil-Layout
 * passiert nichts.
 *
 * Immer aktiv (kein Schalter): ohne diese Korrektur koennen die anderen
 * Funktionen der Suite unsichtbar bleiben.
 */
class SidebarFit
{
    public static function register()
    {
        // Prioritaet 90: laeuft nach Widget (20) und QuickLinks (30), damit
        // beim ersten Messen alle Bloecke im DOM stehen.
        \Eventy::addAction('conversation.after_customer_sidebar', function ($conversation) {
            try {
                echo view('flowkom::sidebar-fit')->render();
            } catch (\Throwable $e) {
                \Helper::log(FLOWKOM_MODULE, 'SidebarFit-ERROR (Layout unveraendert): ' . $e->getMessage());
            }
        }, 90, 1);
    }
}
