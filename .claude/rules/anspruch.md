# Anspruch: Flowkom ist High-Value-Software (MANDATORY)

> Festgelegt von Sebastian am 22.09.2026. Gilt für **alle Flowkom-Repos** und für jede Arbeit
> daran: Code, Specs, Brainstorming, Mockups, Hilfeseiten, Belege, Apps.
>
> Kanonische Fassung: `Flowkom/flowkom` → `.claude/rules/anspruch.md`. Wortgleiche Kopien liegen
> in flowkom-administration, flowkom-docs, flowkom-mobilewarehouse, flowkom-print-agent,
> flowkom-freescout-suite, flowkom-freescout-connector und flowkom-site. `~/dev/CLAUDE.md`
> bindet die kanonische Fassung für Sitzungen ein, die in `~/dev` starten.
> **Wer diese Datei ändert, zieht alle Kopien im selben Zug nach.**

Diese Datei geht **allen Skill-Vorgaben vor** (Superpowers und andere). Wo ein Skill etwas
anderes sagt, gilt diese Datei.

## 1. Der Maßstab

Flowkom ist ein vollwertiges ERP. Kunden zahlen mehrere tausend Euro im Monat, teils fünf- bis
sechsstellig im Jahr, und führen ihr Geschäft darauf. Daraus folgt:

- **Zeit und Budget sind nie ein Argument.** Nicht in Specs, nicht in Empfehlungen, nicht bei der
  Wahl zwischen zwei Wegen. Es zählt allein, welche Lösung fachlich die beste ist.
- **Keine Zwischenlösungen.** Kein „erstmal einfach, später richtig", kein MVP, kein „reicht fürs
  Erste", kein stilles Weglassen von Spec-Punkten. Was gebaut wird, wird fertig gebaut.
- **Ursache statt Symptom.** Auch ein Bugfix wird ganzheitlich gelöst: Wurzel finden,
  Nachbarstellen mitprüfen, dafür sorgen, dass die Fehlerklasse nicht wiederkommt.
- **Ehrliche Empfehlung.** Claude empfiehlt, was fachlich am meisten Sinn ergibt — nicht, was am
  schnellsten geht, und nicht, was gerade vorgeschlagen wurde. Widerspruch wird offen ausgesprochen.

## 2. Vorbild: wie es professionelle SaaS-Anbieter machen

Flowkom entwickelt nach professionellen SaaS-Standards und löst Themen grundsätzlich so, wie
führende SaaS-Unternehmen es tun — fachlich, technisch und in der Bedienung. Bei jeder
Entscheidung steht die Frage im Raum: **Wie lösen das die Besten, und wo können wir besser sein?**

| Gebiet | Vorbilder |
|---|---|
| ERP, Warenwirtschaft, Lager | NetSuite, SAP Business One, Odoo, Xentral, JTL-Wawi, plentyONE, Billbee |
| Handel, Marktplätze | Shopify Admin, Amazon Seller Central |
| Belege, Buchhaltung | DATEV, Lexware Office, sevDesk |
| Kundenservice | Help Scout, Zendesk |
| SaaS-Handwerk (Rechte, Audit, API, Abrechnung) | Stripe, Linear, Notion |
| Design und Bedienung | Apple (macOS, iOS), Linear, Stripe Dashboard |

Abweichen ist erlaubt, wenn unsere Lösung besser ist. Die Begründung steht dann in der Spec.

## 3. Design: Apple-Niveau, innovativ, perfekte Ordnung

Design ist bei Flowkom kein Anstrich, sondern Teil der Produktqualität — genauso wichtig wie
korrekte Zahlen.

- **Apple-Niveau.** Edel und bis ins Detail durchgearbeitet. Eine saubere, korrekte
  Standard-SaaS-Oberfläche reicht nicht.
- **Innovativ statt Vorlage.** Kein generischer Baukasten-Look. Jede Fläche wird bewusst
  gestaltet; wo eine neue Lösung klarer ist als das Übliche, wird sie gebaut.
- **Perfekte Ordnung.** Klare Hierarchie, jedes Element an seinem Platz; Gleiches sieht überall
  gleich aus und verhält sich gleich. Kein Durcheinander: Was nur in einem Zustand gebraucht wird,
  ist nicht dauerhaft sichtbar. Dichte statt zelebriertem Weißraum — aber geordnet.
- **Material und Bewegung.** Tiefe und Übergänge gezielt einsetzen, nie als Selbstzweck.
  „Dezent" heißt geordnet, nicht blass.
- **Jeder Zustand ist gestaltet:** leer, lädt, Fehler, keine Rechte, sehr viele Daten, sehr lange
  Texte, hell und dunkel, Telefon bis großer Monitor.
- **Entschieden wird am Bild.** Design-Entscheidungen fallen über Mockups: Varianten nebeneinander,
  Vorher/Nachher, hell und dunkel — nicht über Beschreibungen.
- Die verbindlichen Marken- und Token-Regeln (Flowkom-Blau, Geist, Neutrale, Dunkelmodus) stehen
  in `.claude/rules/design.md` im Flowkom-Repo und im Corporate Design. Dieser Abschnitt setzt
  den Anspruch, design.md die Regeln.

## 4. Brainstorming und Planung

Gilt vor den Vorgaben des Brainstorming-Skills:

- **Jedes Feature bekommt das volle Brainstorming** mit Spec und Plan. Die Kurzfassung für
  „kleine" Änderungen gilt in Flowkom nicht für Features.
- **Claude denkt mit und bringt eigene Ideen ein** — ungefragt, auch mutige. Nicht nur umsetzen,
  was genannt wurde, sondern vorschlagen, was das Feature auf Profi-Niveau hebt.
- **Recherche ist Pflicht.** Für jedes Feature-Thema wird konkret nachgesehen (Web,
  Hersteller-Doku, Hilfeseiten), wie 2 bis 4 Vorbilder aus §2 es lösen. Nicht aus dem Gedächtnis
  raten.
- **Ausführlich und verrückt ist erwünscht.** Ein Brainstorming darf lange dauern und mehrere
  Runden haben. Erst breit denken, auch wilde Ideen, dann auf die beste Lösung verdichten. Ziel
  ist die perfekte Lösung, nicht die schnelle.
- **Liefern statt verhören.** Ausgearbeitete Varianten und Mockups mit eigener Empfehlung zeigen;
  Fragen nur als Anker, eine bis zwei pro Runde.
- **YAGNI gilt nur gegen Technik auf Vorrat** (Abstraktionen oder Konfigurierbarkeit ohne Bedarf)
  — nie gegen Qualität, Vollständigkeit, Zustände, Rechte oder Bedienkomfort.
- **Subagenten bekommen den Anspruch mit.** Wer Planung oder Recherche an Subagenten gibt, schreibt
  diesen Anspruch in den Auftrag: Die eingebauten Explore- und Plan-Agenten lesen weder CLAUDE.md
  noch diese Datei.

## 5. Pflichtabschnitte jeder Feature-Spec

1. **„So lösen es Profi-SaaS"** — die recherchierten Vorbilder mit Quelle: was sie tun, was wir
   übernehmen, wo wir bewusst besser oder anders sind und warum.
2. **„Design"** — Mockups (Pfad), gewählte Variante, gestaltete Zustände.
3. **„Profi-Checkliste"** — jeder Punkt ist entweder ✅ mit Fundstelle in der Spec oder „nicht
   relevant" mit Begründung. Leer lassen gilt nicht.

| Bereich | Prüfpunkte |
|---|---|
| Rechte | Rollen und Rechte; Mandantentrennung; wer darf was sehen und ändern |
| Nachvollziehbarkeit | Audit-Spur (wer hat wann was geändert); Historie, wo Nutzer sie brauchen |
| Bedienung | Massenaktionen; Suche, Filter, Sortierung; Tastatur; Rückgängig oder Bestätigung bei allem, was löscht oder zerstört; Benachrichtigung, wo der Nutzer auf etwas wartet |
| Zustände | Leer, lädt, Fehler (verständlich, mit nächstem Schritt), keine Rechte, Teilerfolg |
| Skalierung | Verhalten bei 100.000+ Datensätzen: Paging, Indizes, keine N+1-Abfragen, Langläufer als Hintergrundjob |
| Design | Hell und dunkel; Telefon bis großer Monitor; Corporate Design; §3 |
| Sprache | DE, EN, TR; deutsche Zahlen- und Datumsformate |
| Barrierefreiheit | Fokusführung, Kontraste, Beschriftungen für Screenreader |
| Integration | Import und Export; API oder Webhooks, wo Kunden sie erwarten; Verhalten bei Ausfall der Gegenstelle |
| Betrieb | Logging und Überwachung; Fehlermeldung an den Betreiber; Migration mit Rückweg; Feature-Flag als UI-Schalter |
| Recht und Sicherheit | DSGVO (Speicherdauer, Auskunft, Löschung); GoBD bei Belegen; keine Geheimnisse im Client |
| Hilfe | Hilfeseite in flowkom-docs und HelpLink an der Oberfläche |

In Repos ohne Weboberfläche oder Datenbank (Print-Agent, FreeScout-Module, Website) gilt die Liste
sinngemäß; was nicht passt, wird mit Begründung als „nicht relevant" markiert.

## 6. Abgrenzung: perfekt heißt nicht überladen

Kein Budget ist kein Freibrief für Komplexität. Perfekt heißt: durchdacht, vollständig, robust,
einfach zu bedienen und wartbar. Die beste Lösung ist oft die einfachere — für den Nutzer und im
Code. Wird ein Bereich über die Zeit zu komplex und fehleranfällig, ist Zurücksetzen und sauberes
Neuaufsetzen der Profi-Weg, nicht Weiterflicken. Diese Regel bleibt gültig und ist Teil dieses
Anspruchs.

## 7. Prüfung

Vor jedem Merge wird gegen diesen Anspruch geprüft, in Flowkom über `/qa`: Pflichtabschnitte
vorhanden, Profi-Checkliste tatsächlich eingelöst, alle Design-Zustände gestaltet. Was fehlt, ist
ein Befund — kein „später".
