* DOCUMENTATION

** INSTALLATION
Extrahieren Sie den Inhalt dieses Archivs in Ihr Magento Verzeichnis.

** USAGE
Dieses Modul implementiert das Bewertungssystem von Trusted Shops 
(Trusted Ratings) in einem Magento Shop. Das Moduls beschäftigt sich mit
der Anzeige des Bewertungswidgets und Email-Versand für die Kundenbewertung.
Im Konfigurationsbereich im Backend kann man Trusted Rating aktivieren / 
deaktiveren. Man kann einstellen, nach wieviel Tagen einer Bestellung, der Kunde
eine Mail mit Bewerten-Button bekommt. Die Kunden, die vor der Installation 
des Moduls im Shop eingekauft haben, werden nicht angeschrieben werden.
Um mehrere Sprachen pro Shop zu unterstützen, benötigt man eine 
eindeutige Trusted Shops ID für jede Sprache bzw. pro StoreView. 
Es ist auch wichtig, dass die ausgewählte Sprache unter "Verkäufe => 
Trusted Shops Kundenbewertung => Freischaltung => Shop Sprache" der 
Lokalisierung unter "Allgemein => Option für Lokalisierungen => 
Lokalisierung" gleicht.

** FUNCTIONALITY
*** A: Im Konfigurationsbereich im Backend kann man Trusted Rating aktivieren / deaktiveren.
*** B: Email-Versand berücksichtigt die Bestellungen, die ab Aktivierungsdatum des Moduls 
        getätigt wurden.
*** C: Man kann einstellen, nach wieviel Tagen einer Bestellung, der Kunde
        eine Mail mit Bewerten-Button bekommt. Der sinvolleste minimalle Wert ist ein Tag.
*** D: Über das eingebundene Widget kann der Kunde die Anzahl der bisherigen Bewertungen
        sehen sowie beim klick auf das Image eine Bewertung vornehmen.
*** E: Auf der Bestellbestätigungsseite erscheint ein "Bewerten" - Button mit dem gleichzeitig
        die Kunden-email sowie die OrderId übergeben wird.
*** F: Je nach eingestellter Sprache im Shop wird das dazugehörige Widget geladen. Achtung: Die TrustedRating
        Sprache muss der Shop-Sprache gleichen!
*** G: 1. There's multilanguage information banner in System Configuration.
       2. In the Backend are 2 links available, once to register and once to
          the documentation as a PDF.
*** H: The module provides for sending email to the buyer after the dispatch
       of the goods with a proposal to evaluate this purchase.

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

G: Magento template (with multilanguage support) is connected to Info-Box in
   the Admin Panel Configuration by frontend model renderer
   (adminhtml_system_config_info).
H: Added multilanguages templates for email adresses
   (from config.xml/default/trustedratingmail/emails/default)
   and the ability to assign each store with the email template.
   
** PROBLEMS
Es sind keine Probleme bekannt.

* TESTCASES
** BASIC
*** A:	1. Geben Sie im Konfigurationsbereich "System -> Konfiguration
            -> Verkäufe -> Trusted Shops Kundenbewertung -> Freischaltung"
            die Trusted Shops ID und die Sprache ein.
	    2. Stellen Sie im Tab "Aktivierung" "Trusted Rating aktivieren?" auf Ja
	    3. Speichern Sie die Konfiguration und prüfen sie ob die Meldung (notice)
            "TrustedShops Antwort: OK" erscheint, wenn ja hat die Kommunikation mit 
            Trusted Shops geklappt, das heißt die Daten sind korrekt (diese 
            Meldung muß auch kommen wenn Sie den Status auf "Nein" stellen).
*** B:  1. Das Datum, das mit folgende SQL Abfrage zu bekommen ist
            "select * from core_config_data where path like '%trusted%';"
            sollte dem Installationszeitpunkt gleichen. Prüfen Sie dies. 
            Beachten Sie hierbei, dass dort die Magento interne Zeit 
            benutzt wird, welche GMT entspricht. Zeitzonenabweichungen 
            und/oder DST (Sommerzeit) wird hier nicht berücksichtigt um 
            eine einheitliche Rechengrundlage zu haben.
*** C: 1. Stellen Sie in der Trusted-Rating Konfiguration den Wert "0" unter "
           System -> Konfiguration -> Verkäufe -> Trusted Shops Kundenbewertun -> 
           E-Mail zur Bewertungsaufforderung -> Tage nach Sendung" ein.
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
*** E: Prüfen sie ob auf der Bestellbestätigungsseite eine "Bewerten" - Grafik erscheint und in dem Formular auf das der
        Link verweist, bereits die Kunden-Emailadresse sowie die OrderID eingetragen ist.
*** F: Schalten Sie in der Konfiguration auf eine andere StoreView mit englischer, spanischer oder französischer Sprache um,
        trage Sie eine neue (gültige) TS-ID ein und stellen Sie die Sprache entsprechend ein. Prüfen Sie ob im Frontend,
        wenn Sie auf die jeweilige StoreView umschalten, das richtige Widget geladen wird, das gleiche gilt für das
        E-Mail - Widget.
*** G: 1. Open "Admin Panel / System / Configuration / Sales /
          Customer Rating / Info" and compare the contents of a banner
          with a screenshot [SCREENSHOT: Info-Banner_en.png].
          Change the backend language from English to German, in this case,
          the banner should display the German text
          [SCREENSHOT: Info-Banner_de.png].
       2. Check whether, in the configuration section under the "Admin Panel /
          System / Configuration / Sales / Customer Rating / Info" in the info
          block below button "Checkout our Specialoffer now!" and registration
          is out.
          There is also a documentation block must be in "Admin Panel /
          System / Configuration / Sales / Customer Rating / Documentation" 
          with the link to the PDF and give work.
*** H: 1. Adjust for any store or one of the templates that are added by default
          ("Trusted Rating Notification E-Mail (DE)", "Trusted Rating Notification E-Mail (EN)"
          or "Trusted Rating Notification E-Mail (FR)") in "Admin Panel / System / 
          Configuration / Sales / Customer Rating / Email with rating request / Email Template"
       2. Take in the store purchase.
       3. Make one Shipment in AdminPanel in Orders, and make log out / log in.
       4. Check whether the letter came in the prescribed language.

** CATCHABLE
*** B: Bei einem ungültigen Datum (sollte aufgrund der drop-down nicht möglich sein) werden keine Mails verschickt.
