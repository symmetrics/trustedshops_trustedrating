* DOCUMENTATION

** INSTALLATION
Extrahieren Sie den Inhalt dieses Archivs in Ihr Magento Verzeichnis.

** USAGE
Dieses Modul implementiert das Bewertungssystem von Trusted Shops (Trusted Ratings) in einen Magento Shop.

** FUNCTIONALITY
*** A: Im Konfigurationsbereich im Backend kann man Trusted Rating aktivieren / deaktiveren.
*** B: Über das eingebundene Widget kann der Kunde die Anzahl der bisherigen Bewertungen sehen sowie beim klick auf das Image eine Bewertung vornehmen.

** TECHNINCAL
	Per layout modifikator wird ein eigenes Template über eine eigene Blockklasse auf der Startseite eingebunden, das das Widget darstellt.

** PROBLEMS
keine bekannt.

* TESTCASES

** BASIC
*** A:	1. Geben Sie im Konfigurationsbereich "Verkäufe => Trusted Shops Rating => Daten" die geforderten Daten ein (logindaten, api url, id)
	    2. Stellen Sie im Tab "Status" "Trusted Rating aktivieren?" auf Ja
	    3. Speichern sie die Konfiguration und prüfen sie ob die Meldung (notice) "returnValue: OK" erscheint, wenn ja hat die Kommunikation mit Trusted Shops geklappt, das heißt die Daten sind korrekt (diese Meldung muß auch kommen wenn Sie den Status auf "Nein" stellen).
*** B:  1. Prüfen sie ob auf der Startseite das Trusted Rating Widget erscheint [SCREENSHOT: trustedrating-widget.png].
		2. Prüfen sie ob das Widget nur erscheint wenn im Backend der Status auf Ja gestellt ist, bei Nein muß es verschwinden.
        3. Prüfen sie ob Sie beim klick auf das Widget zu der Bewertungsseite weitergeleitet werden [SCREENSHOT: trustedratingsite.png]
		4. Führen sie eine Bewertung durch, bestätigen sie die Bewertung per e-mail (Sie bekommen einen Link zugeschickt).
		5. Loggen sie sich auf https://qa.trustedshops.de/shop/login.html in Ihren Account ein und prüfen sie ob die Bewertung angekommen ist, bestätigen sie die Bewertung.
		6. Das Widget wird von Trusted Shops nur einmal am Tag neu gecached, man kann das cachen aber erzwingen indem man auf "Bewertungen" => "Widget Einstellungen" geht, den Shop auswählt und bei dem Bild einmal auf "ändern" und dann wieder auf "speichern" klickt. Das Widget sollte sich jetzt ändern und den Kommentar der letzten Bewertung zeigen sowie die neue Anzahl der Bewertungen.
