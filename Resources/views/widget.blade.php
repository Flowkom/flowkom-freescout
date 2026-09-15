<style {!! \Helper::cspNonceAttr() !!}>
#flowkom-widget{margin-top:10px;border:1px solid #d6dce2;border-radius:4px;overflow:hidden;font-size:12px}
#flowkom-widget .fk-hd{padding:7px 10px;background:linear-gradient(135deg,#1e40af,#3b82f6);display:flex;align-items:center;justify-content:space-between}
#flowkom-widget .fk-hd span{color:#fff;font-weight:600;font-size:11px;letter-spacing:.5px}
#flowkom-widget .fk-hd button{background:rgba(255,255,255,.2);border:0;border-radius:3px;color:#fff;font-size:11px;padding:2px 8px;cursor:pointer}
#flowkom-widget .fk-sec{padding:8px 10px;background:#fff;border-top:1px solid #e5e8ed;color:#333}
#flowkom-widget .fk-lbl{font-size:10px;color:#888;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px}
#flowkom-widget .fk-hl{border-left:3px solid #3b82f6;background:#eff6ff}
#flowkom-widget .fk-badge{display:inline-block;font-size:10px;padding:1px 6px;border-radius:10px;font-weight:600;background:#dcfce7;color:#166534}
#flowkom-widget .fk-links{display:flex;gap:4px;margin-top:6px}
#flowkom-widget .fk-links a{flex:1;display:block;text-align:center;padding:4px 0;border-radius:3px;font-size:10px;text-decoration:none;color:#fff;font-weight:500}
#flowkom-widget .fk-lnk-f{background:#1e40af}
#flowkom-widget .fk-lnk-m{background:#ff9900}
#flowkom-widget .fk-trk a{font-size:11px;color:#0068c8;text-decoration:none;word-break:break-all}
#flowkom-widget .fk-err{padding:12px 10px;text-align:center;color:#888;font-size:12px}
#flowkom-widget .fk-match{display:flex;align-items:flex-start;gap:7px;padding:7px 10px;border-top:1px solid #e5e8ed;background:#fff}
#flowkom-widget .fk-dot{flex:none;width:9px;height:9px;border-radius:50%;margin-top:3px}
#flowkom-widget .fk-match-exact .fk-dot{background:#16a34a}
#flowkom-widget .fk-match-probable .fk-dot{background:#d97706}
#flowkom-widget .fk-match-conflict .fk-dot{background:#dc2626}
#flowkom-widget .fk-match-none .fk-dot{background:#9ca3af}
#flowkom-widget .fk-match-conflict{background:#fef2f2}
#flowkom-widget .fk-match-probable{background:#fffbeb}
#flowkom-widget .fk-match-t{font-size:11px;font-weight:600;color:#1f2937}
#flowkom-widget .fk-match-r{font-size:10px;color:#6b7280;line-height:1.35;margin-top:1px}
</style>

<div id="flowkom-widget">
    <div class="fk-hd">
        <span>FLOWKOM</span>
        <button id="fk-refresh" type="button">&orarr;</button>
    </div>
    <div class="fk-sec" id="fk-content">Lade Daten...</div>
</div>

<script {!! \Helper::cspNonceAttr() !!}>
(function(){
    var API_URL = {!! json_encode($apiUrl) !!};
    var API_KEY = {!! json_encode($apiKey) !!};
    // PROJ-861: typisierte Ticketmerkmale, serverseitig erkannt (TicketHints).
    var HINTS = {!! json_encode($hints ?? new \stdClass()) !!};
    var TRACKING_TPL  = {!! json_encode($trackingTemplate ?? '') !!};
    var TRACKING_ON   = {!! json_encode(!empty($trackingOn)) !!};
    var w = document.getElementById('flowkom-widget');
    var content = document.getElementById('fk-content');

    document.getElementById('fk-refresh').addEventListener('click', loadData);
    loadData();

    function loadData() {
        content.textContent = 'Lade Daten...';
        content.className = 'fk-sec';
        clearW();
        w.appendChild(content);

        var keys = ['email', 'ebay_username', 'ebay_order_id', 'ebay_item_id', 'amazon_order_id', 'order_number', 'channel'];
        var params = [], hasHint = false;
        for (var i = 0; i < keys.length; i++) {
            var v = HINTS[keys[i]];
            if (v === null || v === undefined || v === '') continue;
            params.push(keys[i] + '=' + encodeURIComponent(v));
            if (keys[i] !== 'channel') hasHint = true;
        }
        if (!hasHint) { showMsg('Keine Merkmale im Ticket gefunden.'); return; }

        fetch(API_URL + '/api/freescout/lookup?' + params.join('&'), {
            headers: { 'Authorization': 'Bearer ' + API_KEY }
        })
        .then(function(r) { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
        .then(renderData)
        .catch(function(err) { showMsg('Fehler: ' + err.message); });
    }

    function showMsg(msg) {
        clearW();
        var d = mk('div','fk-err'); d.textContent = msg; w.appendChild(d);
    }

    function clearW() {
        while (w.children.length > 1) w.removeChild(w.lastChild);
    }

    function mk(tag, cls) { var e = document.createElement(tag); if (cls) e.className = cls; return e; }
    function sl(s) { return {open:'Offen',in_progress:'In Bearbeitung',shipped:'Versendet',completed:'Abgeschlossen',cancelled:'Storniert',pending:'Ausstehend',picked:'Gepickt'}[s]||s; }
    function cl(c) { return {amazon:'Amazon',shopify:'Shopify',ebay:'eBay',kaufland:'Kaufland',otto:'Otto',manual:'Manuell'}[c]||c; }
    function fd(d) { return d ? new Date(d).toLocaleDateString('de-DE') : ''; }

    // Ampel: wie sicher gehört das Ticket zu dieser Bestellung?
    var LEVEL_TITLE = {
        exact: 'Eindeutig zugeordnet',
        probable: 'Nicht eindeutig — bitte prüfen',
        conflict: 'Widersprüchlich — nicht zugeordnet',
        none: 'Nicht zugeordnet'
    };
    var VIA_LABEL = { order_number: 'Bestellnummer', ebay_username: 'eBay-Käufer', ebay_item: 'Artikelnummer', email: 'E-Mail' };

    function renderMatch(match) {
        var level = LEVEL_TITLE[match.level] ? match.level : 'none';
        var box = mk('div', 'fk-match fk-match-' + level);
        box.appendChild(mk('span', 'fk-dot'));
        var txt = mk('div');
        var t = mk('div', 'fk-match-t');
        var via = (match.via || []).map(function(v) { return VIA_LABEL[v] || v; });
        t.textContent = LEVEL_TITLE[level] + (via.length ? ' · über ' + via.join(' + ') : '');
        txt.appendChild(t);
        if (match.reason) { var r = mk('div', 'fk-match-r'); r.textContent = match.reason; txt.appendChild(r); }
        box.appendChild(txt);
        return box;
    }

    function renderData(data) {
        clearW();
        var match = data.match || null; // null = älterer Flowkom-Server ohne Ampel
        if (match) w.appendChild(renderMatch(match));

        if (match && match.level === 'conflict') {
            var list = data.conflict_orders || [];
            if (list.length) {
                var cs = mk('div', 'fk-sec');
                var lb = mk('div', 'fk-lbl'); lb.textContent = 'Passende Aufträge (bitte prüfen)'; cs.appendChild(lb);
                list.forEach(function(o) { cs.appendChild(renderCompact(o, true)); });
                w.appendChild(cs);
            }
            return;
        }

        if (!data.customer && !(data.orders||[]).length) {
            if (!match) showMsg('Kein Kunde in Flowkom gefunden.');
            return;
        }

        if (data.customer) {
            var cs = mk('div','fk-sec');
            var lb = mk('div','fk-lbl'); lb.textContent = 'Kunde'; cs.appendChild(lb);
            var nm = mk('div'); nm.style.fontWeight = '600'; nm.textContent = data.customer.name||''; cs.appendChild(nm);
            if (data.customer.address) {
                var a = data.customer.address, ad = mk('div');
                ad.style.cssText = 'color:#555;font-size:11px;line-height:1.4';
                ad.textContent = [a.street,[a.zip,a.city].filter(Boolean).join(' '),a.country].filter(Boolean).join(', ');
                cs.appendChild(ad);
            }
            if (data.customer.phone) { var ph = mk('div'); ph.style.cssText='color:#555;font-size:11px'; ph.textContent = data.customer.phone; cs.appendChild(ph); }
            var tc = mk('div'); tc.style.cssText='margin-top:3px;font-size:10px;color:#888'; tc.textContent = (data.customer.total_orders||0)+' Bestellungen'; cs.appendChild(tc);
            w.appendChild(cs);
        }

        // Flowkom liefert den Hauptauftrag immer zuerst.
        var orders = data.orders||[], main = orders[0] || null, rest = orders.slice(1);
        if (main) w.appendChild(renderOrder(main, match));
        if (rest.length) {
            var ms = mk('div','fk-sec'), det = document.createElement('details'), sum = document.createElement('summary');
            sum.style.cssText='cursor:pointer;font-size:11px;color:#555;font-weight:500';
            sum.textContent = 'Weitere ('+rest.length+')'; det.appendChild(sum);
            for (var r=0;r<rest.length;r++) det.appendChild(renderCompact(rest[r], false));
            ms.appendChild(det); w.appendChild(ms);
        }
    }

    function renderOrder(o, match) {
        var s = mk('div','fk-sec fk-hl');
        var hr = mk('div'); hr.style.cssText='display:flex;justify-content:space-between;align-items:center;margin-bottom:3px';
        var lb = mk('div','fk-lbl'); lb.textContent = (match && match.level === 'probable') ? 'Neueste Bestellung' : 'Aktuelle Bestellung'; hr.appendChild(lb);
        var bg = mk('span','fk-badge'); bg.textContent=sl(o.status); hr.appendChild(bg); s.appendChild(hr);
        var nm = mk('div'); nm.style.cssText='font-size:12px;font-weight:600;color:#1e40af'; nm.textContent=o.order_number||''; s.appendChild(nm);
        var mt = mk('div'); mt.style.cssText='font-size:10px;color:#888;margin-bottom:5px'; mt.textContent=cl(o.channel)+' · '+fd(o.created_at); s.appendChild(mt);
        (o.items||[]).forEach(function(it){ var d=mk('div'); d.style.cssText='font-size:11px;padding:1px 0'; d.textContent=it.quantity+'× '+(it.name||''); s.appendChild(d); });
        (o.shipments||[]).forEach(function(sh){
            var tr=mk('div','fk-trk'); tr.style.marginTop='3px';
            var c=mk('span'); c.style.fontWeight='600'; c.textContent=(sh.carrier||'')+': '; tr.appendChild(c);
            if(sh.tracking_url){var a=document.createElement('a');a.setAttribute('href',sh.tracking_url);a.setAttribute('target','_blank');a.textContent=sh.tracking_number;tr.appendChild(a);}
            else{var tn=mk('span');tn.textContent=sh.tracking_number||'';tr.appendChild(tn);}
            s.appendChild(tr);
        });
        // Tracking-Button nur bei eindeutiger oder käufer-sicherer Zuordnung;
        // bei „nicht eindeutig" steht die Bestellnummer im Button.
        var trackingAllowed = !match || match.level === 'exact' || match.level === 'probable';
        if (TRACKING_ON && trackingAllowed && (o.shipments||[]).length && o.shipments[0].tracking_number) {
            var tb=mk('button','fk-track-reply'); tb.type='button';
            var probable = match && match.level === 'probable';
            tb.style.cssText='margin-top:6px;width:100%;padding:5px 0;border:0;border-radius:3px;color:#fff;font-size:11px;font-weight:600;cursor:pointer;background:' + (probable ? '#d97706' : '#16a34a');
            tb.textContent = probable
                ? '✉ Tracking von ' + (o.order_number || 'dieser Bestellung') + ' einfügen'
                : '✉ Antwort mit Tracking einfügen';
            tb.addEventListener('click', function(){ fkInsertTracking(o); });
            s.appendChild(tb);
        }
        var lk=mk('div','fk-links');
        if(o.flowkom_url){var a1=document.createElement('a');a1.className='fk-lnk-f';a1.setAttribute('href',o.flowkom_url);a1.setAttribute('target','_blank');a1.textContent='Flowkom';lk.appendChild(a1);}
        if(o.marketplace_url){var a2=document.createElement('a');a2.className='fk-lnk-m';a2.setAttribute('href',o.marketplace_url);a2.setAttribute('target','_blank');a2.textContent=cl(o.channel);lk.appendChild(a2);}
        s.appendChild(lk);
        return s;
    }

    function renderCompact(o, withLink) {
        var d=mk('div'); d.style.cssText='padding:4px 0;border-top:1px solid #eee;margin-top:3px';
        var r=mk('div'); r.style.cssText='display:flex;justify-content:space-between;align-items:center';
        var n=mk('span'); n.style.cssText='font-size:11px;font-weight:600'; n.textContent=o.order_number||''; r.appendChild(n);
        var b=mk('span','fk-badge'); b.style.fontSize='9px'; b.textContent=sl(o.status); r.appendChild(b); d.appendChild(r);
        var m=mk('div'); m.style.cssText='font-size:10px;color:#888'; m.textContent=cl(o.channel)+' · '+fd(o.created_at); d.appendChild(m);
        if (withLink && o.flowkom_url) {
            var a=document.createElement('a'); a.setAttribute('href',o.flowkom_url); a.setAttribute('target','_blank');
            a.style.cssText='font-size:10px;color:#1e40af'; a.textContent='In Flowkom prüfen'; d.appendChild(a);
        }
        return d;
    }
    /* Tracking-Antwort in den Reply-Editor einfuegen (vor der Signatur). */
    function fkInsertTracking(o) {
        var sh = (o.shipments||[])[0] || {};
        var carrier = sh.carrier || 'dem Versanddienstleister';
        if (carrier.length <= 4) { carrier = carrier.toUpperCase(); }
        else { carrier = carrier.charAt(0).toUpperCase() + carrier.slice(1); }
        var text = TRACKING_TPL
            .replace(/\{carrier\}/g, carrier)
            .replace(/\{tracking_number\}/g, sh.tracking_number || '')
            .replace(/\{tracking_url\}/g, sh.tracking_url || '')
            .replace(/\{order_number\}/g, o.order_number || '');
        var esc = document.createElement('div'); esc.textContent = text;
        var html = '<div>' + esc.innerHTML.replace(/\n/g, '<br>') + '</div><br>';

        function insert() {
            if (typeof $ === 'undefined' || !$('#body').length || !$('.note-editable:visible').length) return false;
            var cur = $('#body').summernote('code') || '';
            $('#body').summernote('code', html + cur);
            return true;
        }
        if (insert()) return;
        /* Reply-Formular erst oeffnen, dann einfuegen */
        var replyBtn = document.querySelector('.conv-reply');
        if (replyBtn) replyBtn.click();
        var tries = 0;
        var iv = setInterval(function() {
            if (insert() || ++tries > 20) clearInterval(iv);
        }, 250);
    }
})();
</script>
