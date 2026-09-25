# Anspruch: Flowkom ist High-Value-Software (MANDATORY)

> Festgelegt von Sebastian am 22.09.2026, Fassung 2 vom 25.09.2026. Gilt für **alle
> Flowkom-Repos** und für jede Arbeit daran: Code, Specs, Brainstorming, Mockups, Hilfeseiten,
> Belege, Apps.
>
> **Warum Fassung 2:** Fassung 1 kannte fast nur Gas. Specs, Pläne und Prüfschleifen wuchsen
> schneller als der Code: vom 1. bis 25.09.2026 rund 186.000 Zeilen Specs und Pläne, einzelne
> Wellenpläne mit über 7.000 Zeilen, für PROJ-941 ein Plan mit 223 Tasks und rund 172.000 Zeilen
> bei sechs Mandanten, rund 30 Agentenläufe für 10 Tasks in PROJ-934 W4. Fassung 2 behält den
> Anspruch an die Lösung und begrenzt Umfang und Ablauf (§4, §6, §7).
>
> Kanonische Fassung: `Flowkom/flowkom` → `.claude/rules/anspruch.md`. Wortgleiche Kopien liegen
> in flowkom-administration, flowkom-docs, flowkom-mobilewarehouse, flowkom-print-agent,
> flowkom-freescout (Ordner `flowkom-freescout-suite`), flowkom-freescout-connector und
> flowkom-site. `~/dev/CLAUDE.md` bindet die kanonische Fassung für Sitzungen ein, die in `~/dev`
> starten. Timekom führt eine eigene, auf das Produkt zugeschnittene Fassung
> (`Timekom/timekom` → `.claude/rules/anspruch.md`).
> **Wer diese Datei ändert, zieht alle Kopien im selben Zug nach; ändern sich die Grundsätze
> (§4, §6, §7), auch die Timekom-Fassung.**

Diese Datei geht **allen Skill-Vorgaben vor** (Superpowers und andere). Wo ein Skill etwas
anderes sagt, gilt diese Datei.

**Qualität der Lösung (§1 bis §3) und Maß des Umfangs (§6) sind gleichrangig.** Wer nur das eine
liest, liest die Datei falsch.

## 1. Der Maßstab

Flowkom ist ein vollwertiges ERP. Kunden zahlen mehrere tausend Euro im Monat und führen ihr
Geschäft darauf. Daraus folgt:

- **Die fachlich beste Lösung gewinnt.** Zwischen zwei Wegen entscheidet die Qualität, nicht die
  Bauzeit. Das gilt für die Wahl der Lösung — nicht für ihren Umfang (§6) und nicht für den
  Ablauf drumherum (§4, §7).
- **Was gebaut wird, wird fertig gebaut:** alle Zustände, Rechte, Fehlerfälle und Texte. Kein
  „erstmal einfach, später richtig" für das, was ausgeliefert wird.
- **Ursache statt Symptom.** Auch ein Bugfix wird ganzheitlich gelöst: Wurzel finden,
  Nachbarstellen mitprüfen, dafür sorgen, dass die Fehlerklasse nicht wiederkommt.
- **Ehrliche Empfehlung.** Claude empfiehlt, was fachlich am meisten Sinn ergibt — nicht, was am
  schnellsten geht, und nicht, was gerade vorgeschlagen wurde. Widerspruch wird offen
  ausgesprochen, auch gegen einen zu großen Umfang.

## 2. Vorbild: wie es professionelle SaaS-Anbieter machen

Flowkom löst Themen so, wie führende SaaS-Unternehmen es tun — fachlich, technisch und in der
Bedienung. Die Frage ist: **Wie lösen das die Besten, und wo können wir besser sein?**

| Gebiet | Vorbilder |
|---|---|
| ERP, Warenwirtschaft, Lager | NetSuite, SAP Business One, Odoo, Xentral, JTL-Wawi, plentyONE, Billbee |
| Handel, Marktplätze | Shopify Admin, Amazon Seller Central |
| Belege, Buchhaltung | DATEV, Lexware Office, sevDesk |
| Kundenservice | Help Scout, Zendesk |
| SaaS-Handwerk (Rechte, Audit, API, Abrechnung) | Stripe, Linear, Notion |
| Design und Bedienung | Apple (macOS, iOS), Linear, Stripe Dashboard |

Abweichen ist erlaubt, wenn unsere Lösung besser ist; die Begründung steht dann in der Spec.
Vorbilder zeigen, **wie gut** etwas gelöst wird, nicht **wie viel** gebaut wird: Was NetSuite für
zehntausende Kunden baut, braucht Flowkom deshalb noch nicht.

## 3. Design: Apple-Niveau, innovativ, perfekte Ordnung

Design ist bei Flowkom kein Anstrich, sondern Teil der Produktqualität — genauso wichtig wie
korrekte Zahlen.

- **Apple-Niveau.** Edel und bis ins Detail durchgearbeitet. Eine saubere, korrekte
  Standard-SaaS-Oberfläche reicht nicht.
- **Innovativ statt Vorlage.** Kein generischer Baukasten-Look. Wo eine neue Lösung klarer ist als
  das Übliche, wird sie gebaut.
- **Perfekte Ordnung, ruhige Flächen.** Klare Hierarchie; Gleiches sieht überall gleich aus und
  verhält sich gleich. Je Fläche eine Hauptinformation, der Rest per Aufklappen, Hover oder
  Detailblatt. Was nur in einem Zustand gebraucht wird, ist nicht dauerhaft sichtbar; im
  Normalfall ist fast nichts Zusätzliches zu sehen. Dichte statt zelebriertem Weißraum — aber
  geordnet, nicht voll.
- **Material und Bewegung.** Tiefe und Übergänge gezielt einsetzen, nie als Selbstzweck.
  „Dezent" heißt geordnet, nicht blass.
- **Jeder Zustand ist gestaltet:** leer, lädt, Fehler, keine Rechte, sehr viele Daten, sehr lange
  Texte, hell und dunkel, Telefon bis großer Monitor.
- **Entschieden wird am Bild, wo es etwas zu entscheiden gibt:** bei einer neuen Hauptfläche oder
  einer echten Variantenwahl — Varianten nebeneinander, hell und dunkel. Kleine Fragen
  entscheidet Claude im Text und nennt die Wahl; für Nachbesserungen gibt es keine neue
  Bilderrunde. Ein Mockup, das gebaut wird, ist vollständig gestaltet.
- Die verbindlichen Marken- und Token-Regeln (Flowkom-Blau, Geist, Neutrale, Dunkelmodus) stehen
  in `.claude/rules/design.md` im Flowkom-Repo und im Corporate Design. Dieser Abschnitt setzt
  den Anspruch, design.md die Regeln.

## 4. Ablauf nach Größe

### 4.1 Drei Größen

Vor dem ersten Schritt sagt Claude in einem Satz, welche Größe die Aufgabe hat; Sebastian kann
umstufen. Die Größen entsprechen den Pfaden des Brainstorming-Skills.

| Größe | Was dazu gehört | Ablauf |
|---|---|---|
| **Fix** | Bug, Issue, Textkorrektur, Nachlese | Ursache finden (§1), Fix mit Test, PR. Kein Brainstorming, keine Spec, kein Plan. |
| **Begrenzt** (Skill: *bounded*) | Erweiterung eines vorhandenen Ablaufs: Feld, Filter, Aktion, Einstellung, kleine Seite | Kurzer Entwurf im Chat, Freigabe, bauen. Kurz-Spec (§5), kein Plan-Dokument. |
| **Groß** (Skill: *architectural*) | Neues Modul, neue Integration, neues Datenmodell, Umbau über mehrere Bereiche | Brainstorming (4.2), Spec (§5), Hauptplan und Wellenpläne (4.3). |

- **Im Zweifel die kleinere Größe.** Die Skill-Regel „im Zweifel die schwerere" gilt hier nicht.
  Zeigt sich beim Bau verborgene Komplexität, wird hochgestuft: anhalten, sagen, weiter.
- **Issue abarbeiten heißt: das Issue lösen**, samt Ursache und Nachbarstellen. Ist ein Issue in
  Wahrheit ein Feature, sagt Claude das vor dem Start in einem Satz und fragt, ob es in diese
  Runde gehört.

### 4.2 Brainstorming (Größe Groß)

- **Recherche statt Gedächtnis.** 2 bis 3 Vorbilder aus §2 werden konkret nachgesehen (Web,
  Hersteller-Doku, Hilfeseiten).
- **Claude denkt mit und bringt eigene Ideen ein**, auch mutige. Ideen sind Vorschläge, kein
  Umfang: Sie stehen gesammelt unter „Ideen"; in den Umfang kommt nur, was Sebastian nimmt.
- **Liefern statt verhören.** Ausgearbeitete Varianten mit eigener Empfehlung; Fragen nur als
  Anker, eine bis zwei pro Runde. Wo eine vernünftige Voreinstellung existiert, nimmt Claude sie
  und nennt sie.
- **Erst breit, dann eng — und dann fertig.** Breit denken ist erwünscht; am Ende steht eine
  verdichtete Lösung, nicht noch eine Runde.
- **Rotes Team nur, wo Fehler teuer sind:** Sicherheit, Rechte, Geld und Belege, Löschen und
  Datenverlust. Seine Befunde werden nach §6 gewogen.
- **Große Vorhaben werden geschnitten** in Specs, die je für sich nutzbar sind. Ein Programm aus
  mehreren Specs wird nicht vorab komplett bis ins Detail geplant.
- **YAGNI gilt gegen Technik auf Vorrat** (Abstraktionen oder Konfigurierbarkeit ohne Bedarf) und
  gegen Bausteine ohne Bedarf (§6) — nie gegen die Qualität dessen, was gebaut wird.
- **Subagenten bekommen den Anspruch mit**, §6 eingeschlossen. Wer Planung oder Recherche an
  Subagenten gibt, schreibt ihn in den Auftrag: Die eingebauten Explore- und Plan-Agenten lesen
  weder CLAUDE.md noch diese Datei.

### 4.3 Pläne (gilt vor `superpowers:writing-plans`)

- **Ein Plan beschreibt, er programmiert nicht vor.** Je Task: Ziel, Dateien, Schnittstellen
  (Signaturen, Tabellen, Routen), Entscheidungen und die Testfälle als Liste. Fertigen Code
  enthält ein Plan nur, wo der Code selbst die Entscheidung ist (Migration mit RLS, SQL-Funktion
  mit Sperrlogik, heikle Berechnung). Die Skill-Regeln „vollständiger Code in jedem Schritt",
  „Similar to Task N: den Code wiederholen" und „Schritte von 2 bis 5 Minuten" gelten nicht.
- **Hauptplan und Wellen.** Der Hauptplan legt Reihenfolge, Schnittstellen zwischen den Wellen und
  Freigaben fest. Den Detailplan bekommt nur die nächste Welle, direkt vor ihrem Bau und gegen den
  dann aktuellen Stand von main.
- **Obergrenzen** — keine Zielgrößen, kürzer ist besser:

  | Dokument | höchstens |
  |---|---|
  | Kurz-Spec (Begrenzt) | 60 Zeilen |
  | Spec (Groß) | 400 Zeilen |
  | Hauptplan | 150 Zeilen |
  | Wellenplan | 600 Zeilen und 12 Tasks |
  | Übergabe (`WEITER-HIER`, Stand-Dateien) | 60 Zeilen: Stand, nächster Schritt, offene Entscheidungen |

  Wer darüber kommt, hat zu groß geschnitten: anhalten (§6). Übergaben sind kein
  Verlaufsprotokoll — der Verlauf steht in Git und `features/log/`.

## 5. Spec-Abschnitte

**Spec (Größe Groß)** trägt neben Ziel, Akzeptanzkriterien und Umsetzungsentwurf diese
Pflichtabschnitte, jeweils kurz:

1. **„So lösen es Profi-SaaS"** — die recherchierten Vorbilder mit Quelle: was wir übernehmen, wo
   wir bewusst anders sind und warum.
2. **„Design"** — Mockup-Pfad, gewählte Variante, gestaltete Zustände. Entfällt ohne Oberfläche.
3. **„Bewusst nicht gebaut"** — was weggelassen wurde und warum (§6).
4. **„Profi-Checkliste"** — nur die Punkte, die dieses Feature wirklich berühren, je eine Zeile
   mit Fundstelle in der Spec. Was nicht berührt wird, wird weder aufgeführt noch begründet.

**Kurz-Spec (Größe Begrenzt):** Ziel, Akzeptanzkriterien, bewusst nicht gebaut und die berührten
Checklistenpunkte.

Die Checkliste ist eine **Denkhilfe zum Durchgehen, kein Formular zum Ausfüllen.** Sie verhindert,
dass etwas Nötiges vergessen wird. Sie ist kein Grund, etwas zu bauen, das niemand braucht.

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
sinngemäß.

## 6. Das Maß: perfekt heißt nicht überladen

Kein Budget ist kein Freibrief für Umfang. Perfekt heißt: durchdacht, vollständig im Gebauten,
robust, einfach zu bedienen und wartbar — nicht: für jeden denkbaren Fall gebaut. Die beste
Lösung ist oft die einfachere, für den Nutzer und im Code.

- **Maßstab ist der echte Betrieb:** eine Handvoll Mandanten, ein Betreiber, ein Entwickler mit
  Claude. Vor jedem Baustein fragt Claude: Wie oft kommt das vor? Wen betrifft es? Was passiert
  ohne ihn? Was ist die einfachste vollständige Lösung?
- **Weglassen ist erwünscht, wenn es begründet ist.** „Bewusst nicht gebaut, weil …" ist ein
  Qualitätsmerkmal. Seltenes (wenige Fälle im Jahr), das Sebastian ohnehin persönlich begleitet,
  läuft über Betreiber oder Kundenservice statt über Selbstbedienung.
- **Nicht gebaut ist nicht halb gebaut.** §1 verbietet halb Fertiges; er verlangt nicht, alles zu
  bauen.
- **Befunde werden gewogen, bevor sie Bausteine werden.** Review, QA und Rotes Team finden immer
  noch einen Fall. Kritische und wichtige Befunde werden behoben. Alles andere wird mitbehoben,
  wenn es im Umfang liegt, sonst wird es ein GitHub-Issue und im Abschluss genannt. Aus einem
  Befund wird kein neues Vorhaben, keine neue Mockup-Runde und keine neue Welle.
- **Der Umfang einer laufenden Welle ist eingefroren.**
- **Stopp-Regel.** Claude hält an und fragt Sebastian, sobald
  - der Umfang gegenüber der Freigabe wächst (neue Welle, neues Modul, weiteres Repo),
  - eine Obergrenze aus 4.3 überschritten würde,
  - die Planung absehbar länger dauert als der Bau,
  - aus einem Issue ein Feature wird.
- Wird ein Bereich über die Zeit zu komplex und fehleranfällig, ist Zurücksetzen und sauberes
  Neuaufsetzen der Profi-Weg, nicht Weiterflicken.

## 7. Umsetzung und Prüfung

Gilt vor `superpowers:subagent-driven-development`, `superpowers:executing-plans` und
`superpowers:requesting-code-review`:

- **Standard ist die Native-Ausführung** (`superpowers:executing-plans`): Die Sitzung baut die
  Welle selbst, mit TDD je Task, danach **ein** Review über den ganzen Zweig. Subagent-driven
  (Umsetzer und Prüfer je Task) nur, wenn Sebastian es ausdrücklich wählt.
- **Parallel arbeiten heißt: unabhängige PRs auf eigenen Arbeitsbäumen**, nicht Subagenten je Task.
- **Ein Review je PR.** Kritische und wichtige Befunde werden behoben, danach werden genau diese
  Stellen einmal nachgeprüft. Kein zweites volles Review, keine Review-Runden für Kleinigkeiten.
- **Tests:** lokal die betroffenen Suiten; über den Rest entscheidet das CI-Gate.
- **QA (`/qa`)** läuft bei Größe Groß einmal vor dem Merge der Welle, die das Feature für Nutzer
  freischaltet. Bei Fix und Begrenzt genügen Tests, CI-Gate und das Review.
- **Geprüft wird gegen die Spec, nicht gegen alles Denkbare:** Akzeptanzkriterien, die berührten
  Checklistenpunkte und die Design-Zustände (§3). Die Prüfung achtet auch auf §6: Ist etwas
  gebaut, das niemand braucht? Was außerhalb des Umfangs liegt, ist ein Issue, kein Blocker.
