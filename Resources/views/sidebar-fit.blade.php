<script {!! \Helper::cspNonceAttr() !!}>
(function () {
    /* Notbremse: absurde Werte (fremde Layouts, Messfehler) nie anwenden. */
    var MAX = 6000;
    var timer = null;

    function anpassen() {
        timer = null;
        var side = document.getElementById('conv-layout-customer');
        var main = document.getElementById('conv-layout-main');
        if (!side || !main) {
            return;
        }
        /* Erst die eigene Korrektur zuruecknehmen, dann frisch messen. */
        main.style.minHeight = '';
        /* Nur das Desktop-Layout hat die absolut positionierte Seitenleiste. */
        if (window.getComputedStyle(side).position !== 'absolute') {
            return;
        }
        var noetig = side.getBoundingClientRect().top + side.scrollHeight;
        var vorhanden = main.getBoundingClientRect().bottom;
        var fehlt = Math.ceil(noetig - vorhanden);
        if (fehlt > 0 && fehlt < MAX) {
            main.style.minHeight = (main.offsetHeight + fehlt) + 'px';
        }
    }

    /* Entprellen per Timer — KEIN requestAnimationFrame: in einem Hintergrund-Tab
       feuert der Frame nicht, ein gesetzter Merker bliebe stehen und jede
       spaetere Neuberechnung unterbliebe. */
    function planen() {
        if (timer) {
            clearTimeout(timer);
        }
        timer = setTimeout(anpassen, 50);
    }

    planen();

    var side = document.getElementById('conv-layout-customer');
    if (side && window.MutationObserver) {
        /* Das Widget laedt seine Daten nach — jede Aenderung neu vermessen. */
        new MutationObserver(planen).observe(side, { childList: true, subtree: true, characterData: true });
    }

    /* Fensterereignisse nur einmal pro Seitenladung binden (pjax rendert die
       Seitenleiste mehrfach); anpassen() sucht seine Elemente jedes Mal neu. */
    if (!window.flowkomSeitenleisteGebunden) {
        window.flowkomSeitenleisteGebunden = true;
        window.addEventListener('resize', planen);
        window.addEventListener('load', planen);
    }
})();
</script>
