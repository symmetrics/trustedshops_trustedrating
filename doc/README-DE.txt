* DOCUMENTATION

** INSTALLATION
Extrahieren Sie den Inhalt dieses Archivs in Ihr Magento Verzeichnis.

** USAGE
Dieses Modul implementiert das Bewertungssystem von Trusted Shops 
(Trusted Ratings) in einen Magento Shop.

Um mehrere Sprachen pro Shop zu unterstützen, benötigt man eine 
eindeutige Trusted Shops ID für jede Sprache bzw. pro StoreView. 
Es ist auch wichtig, dass die ausgewählte Sprache unter "Verkäufe => 
Trusted Shops Kundenbewertung => Freischaltung => Shop Sprache" der 
Lokalisierung unter "Allgemein => Option für Lokalisierungen => 
Lokalisierung" gleicht.

** FUNCTIONALITY
*** A: Im Konfigurationsbereich im Backend kann man Trusted Rating aktivieren / deaktiveren.
*** B: Man kann einstellen, ab welchem Datum Bestellungen bzw. Sendungen berücksichtigt werden
*** C: Man kann einstellen nach wieviel Tagen einer Bestellung der User eine mail mit Bewerten-Button bekommt.
*** D: Über das eingebundene Widget kann der Kunde die Anzahl der bisherigen Bewertungen
        sehen sowie beim klick auf das Image eine Bewertung vornehmen.
*** E: Auf der Bestellbestätigungsseite erscheint ein "Bewerten" - Button mit dem gleichzeitig
        die Kunden-email sowie die OrderId übergeben wird.
*** F: Je nach eingestellter Sprache im Shop wird das dazugehörige Widget geladen. Achtung: Die TrustedRating
        Sprache muss der Shop-Sprache gleichen!
*** G: Im Backend stehen 2 links zur Verfügung, einmal zur Registrierung, einmal zu der Dokumentation als PDF.

** TECHNICAL
	Per layout modifikator wird ein eigenes Template über eine eigene Blockklasse auf der Startseite
	eingebunden, das das Widget darstellt. In <checkout_onepage_success> wird ein neuer Block
	hinzugefügt der die Grafik für die Bewertung darstellt (das ist eine andere Grafik und
	verweist auf eine andere Bewertungsseite, hier wird gleichzeitig die email und die OrderId übertragen.)
    Wenn sich jemand ins Backend einloggt wird ein Event ausgelöst das alle Bestellungen die mindestens x
    (Tage in der config einstellbar) Tage her sind, aber nach dem eingestellen Datum liegen, überprüft
    ob sie schon eine TR-Mail bekommen haben. Wenn nein, wird eine versendet und die shipping-ID der
    Sendung in einer eigenen Tabelle gespeichert.
    Der Zugriff auf diese Tabelle funktioniert über 3 Resource-Models (Data, Mysql, Collection).
    Per Javascript werden im Backend 2 links hinzugefügt (siehe F)

** PROBLEMS
Es sind keine Probleme bekannt.

* TESTCASES
** BASIC
*** A:	1. Geben Sie im Konfigurationsbereich "Verkäufe => Trusted Shops Kundenbewertung => Freischaltung"
            die Trusted Shops ID und die Sprache ein.
	    2. Stellen Sie im Tab "Status" "Trusted Rating aktivieren?" auf Ja
	    3. Speichern Sie die Konfiguration und prüfen sie ob die Meldung (notice) "returnValue: OK" erscheint,
	        wenn ja hat die Kommunikation mit Trusted Shops geklappt, das heißt die Daten sind korrekt
	        (diese Meldung muß auch kommen wenn Sie den Status auf "Nein" stellen).
*** B:  1. Das Datum unter "Verkäufe => Trusted Shops Kundenbewertung => Freischaltung" sollte dem Installationszeitpunkt
            gleichen. Prüfen Sie dies. Beachten Sie hierbei, dass dort die Magento interne Zeit benutzt wird, welche
            GMT entspricht. Zeitzonenabweichungen und/oder DST (Sommerzeit) wird hier nicht berücksichtigt um eine einheitliche
            Rechengrundlage zu haben.
*** C: 1. Stellen Sie in der Trusted-Rating Konfiguration den Wert 0.0001 bei "Tagen" ein.
       2. Gegebenenfalls kann man das Datum unter "Verkäufe => Trusted Shops Kundenbewertung => Freischaltung" anpassen, um 
            Sendungen aus der Vergangenheit zu berücksichtigen.
       2. Tätigen Sie eine Bestellung
       3. Versenden Sie sie
       4. Loggen Sie sich aus dem Back-End und wieder ein und prüfen Sie, ob Sie eine E-Mail mit dem Bewertungs-Widget bekommen haben.
*** D:  1. Prüfen Sie ob auf der Startseite das Trusted Rating Widget erscheint [SCREENSHOT: trustedrating-widget.png].
		2. Prüfen Sie ob das Widget nur erscheint wenn im Backend der Status auf Ja gestellt ist, bei Nein muß es verschwinden.
        3. Prüfen Sie ob Sie beim Klick auf das Widget zu der Bewertungsseite weitergeleitet werden [SCREENSHOT: trustedratingsite.png]
		4. Führen Sie eine Bewertung durch, bestätigen sie die Bewertung per E-mail (Sie bekommen einen Link zugeschickt).
		5. Loggen Sie sich auf https://qa.trustedshops.de/shop/login.html (Testumgebung) oder
		    https://www.trustedshops.de/shop/login.html (Live-Umgebung) in Ihren Account ein und prüfen sie ob die Bewertung
		    angekommen ist, bestätigen sie die Bewertung.
		6. Das Widget wird von Trusted Shops nur einmal am Tag neu gecached, man kann das cachen aber erzwingen indem man auf
		    "Bewertungen" => "Widget Einstellungen" geht, den Shop auswählt und bei dem Bild einmal auf "ändern" und dann
		    wieder auf "speichern" klickt. Das Widget sollte sich jetzt ändern und den Kommentar der letzten Bewertung zeigen
		    sowie die neue Anzahl der Bewertungen.
*** E: 1. Prüfen sie ob auf der Bestellbestätigungsseite eine "Bewerten" - Grafik erscheint und in dem Formular auf das der
            Link verweist, bereits die Kunden-Emailadresse sowie die OrderID eingetragen ist.
*** F: Schalten Sie in der Konfiguration auf eine andere StoreView mit englischer, spanischer oder französischer Sprache um,
            trage Sie eine neue (gültige) TS-ID ein und stellen Sie die Sprache entsprechend ein. Prüfen Sie ob im Frontend,
            wenn Sie auf die jeweilige StoreView umschalten, das richtige Widget geladen wird, das gleiche gilt für das
            E-Mail - Widget.
*** G: Prüfen Sie ob unter im Konfigurationsbereich unter "Verkäufe => Trusted Shops Kundenbewertung" im Info-Block unten ein
        Link zur PDF existiert und ob dieser auch funktioniert. Prüfen Sie ebenfalls ob in dem Tab "Jetzt kostenlos registrieren!"
        ein Link existiert der Sie zur Registrierung führt. 

** CATCHABLE
*** B: Bei einem ungültigen Datum (sollte aufgrund der drop-down nicht möglich sein) werden keine Mails verschickt.
