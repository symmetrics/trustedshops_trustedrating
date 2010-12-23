* DOCUMENTATION

** INSTALLATION
Extract content of this archive to your Magento directory.

** USAGE
This module implements the rating system of Trusted Shops
(Trusted ratings) in a Magento shop. The module handles the display of 
the rating widget and e-mail sending for the customer rating. 
In configuration section in backend one can activate / deactivate 
Trusted Rating. One can set after how many days from the order the
customer receives an e-mail with Evaluate button. Customers that bought in
the shop before installation of the module, will not be written.
In order to support several languages per shop, one needs explicit 
Trusted Shops ID for each language or per StoreView. It is also important
that the selected language under "Sales -> Trusted Shops customer 
rating -> activation -> Shop language" is the same as localization 
under "General -> Option for localization -> Localization".

** FUNCTIONALITY
*** A: In configuration section in backend one can activate / deactivate Trusted Rating 
*** B: E-mail sending takes into consideration the orders that were made from 
       the activation date of the module. 
*** C: One can set after how many days from the order the customer receives an e-mail 
        with Evaluate button. The most reasonable minimal value is one day.
*** D: Through the integrated widget the customer can see the number of the existing 
		evaluations as well as make a rating by click on the image.
*** E: On the order confirmation page "Evaluate" button appears with which 
		simultaneously the customer e-mail as well as Order ID is passed
*** F: According to the language set in shop, the widget belonging to it is loaded. Attention: the TrustedRating language 
		must be the same as the shop language.
*** G: 1. There's multilanguage information banner in System Configuration.
       2. In the Backend 2 links are available, one to register and one to
          the documentation as a PDF.
*** H: The module provides for sending email to the buyer after the dispatch
		of the goods with a proposal to evaluate this purchase.

** TECHNICAL
Per layout modifier a special template is integrated through a special block class on the homepage,
that represents the widget. In <checkout_onepage_success> a new block is added which provides
graphic for the rating (this is another graphic and refers to another rating page, here
simultaneously e-mail and OrderID is passed.)  
    When someone logs in backend an event is initiated that checks for all orders that are at least
    x days old (the days are customizable in the config), but are made after the set date,  
    if they have already got a TR mail. If not, it is sent and the shipping-ID of the sending
    is saved in a special table.
    Access to these tables works through 3 resource models (Data, Mysql, Collection).
    Per JavaScript 2 links are added in backend (see F)

G: Magento template (with multilanguage support) is connected to Info-Box in
   the Admin Panel Configuration by frontend model renderer
   (adminhtml_system_config_info). 
H: Added multilanguages templates for email adresses
   (from config.xml/default/trustedratingmail/emails/default)
   and the ability to assign the email template to each store.
   
** PROBLEMS
No problems are known.

* TESTCASES
** BASIC

*** A: 1. Specify Trusted Shops ID and the language in configuration section 
	    "System -> Configuration -> Sales -> 
	    Trusted Shops customer rating -> Activation"
    2. Set in tab "Activation" "Activate Trusted Rating?" on yes
    3. Save the configuration and check if the notice "Trusted Shops answer: Ok" appears, 
		if yes, the communication with Trusted Shops was successful,
		this means that the data are correct (this message should also appear
		when you set the status on "no").
*** B:  1. Date, which is to be obtained using the following SQL command
        "select * from core_config_data where path like '%trusted%';"
        should be the same as the installation date. Check this.
	    Though pay attention that there the Magento internal time 
	    is used, which corresponds to GMT. Time zone differences
	    and/or DST is not taken into consideration here in order 
	    to ensure a uniform calculation.
*** C: 1. Set in Trusted-Rating Configuration the value "0" under "
        System -> Configuration -> Sales -> Trusted Shops Customer ratings -> 
        E-Mail for rating request -> Days after shipping".
		2. Carry out an order
		3. Send it
		4. Log out from backend and log in again, and check if you have received an e-mail with rating widget.
*** D: 	1. Check if the Trusted Rating Widget appears on the homepage [SCREENSHOT: trustedrating-widget.png].
		2. Check if the widget appears only when in backend the staus is set on Yes, when the status is on No, it must disappear.
		3. Check if upon click you are redirected to the ratings page on the widget [SCREENSHOT: trustedratingsite.png]
		4. Give a rating, confirm the rating by e-mail (a link is sent to you).
		5. Login to https://qa.trustedshops.de/shop/login.html (Test environment) or
		https://www.trustedshops.de/shop/login.html (Live-environment) in your account and check if the rating is there, confirm the rating.
*** E: 	Check if "Rate" graphic appears on the order confirmation page and refers to the link in form, 
		the customer e-mail address as well as the Order ID are already specified.
*** F: 	Switch in configuration on another StoreView with english, spanish or french language,
		enter a new (valid) TS-ID and set the language respectively. Check in frontend,
		when you switch to some Storeview, that correct widget is loaded, the same
		applies for the e-mail widget.
*** G: 	1. Open "Admin Panel / System / Configuration / Sales /
		Customer Rating / Info" and compare the contents of a banner
		with a screenshot [SCREENSHOT: Info-Banner_en.png].
		Change the backend language from English to German, in this case,
		the banner should display the German text
		[SCREENSHOT: Info-Banner_de.png].
		2. Check whether, in the configuration section under the "Admin Panel /
		System / Configuration / Sales / Customer Rating / Info" in the info
		block below button "Checkout our Specialoffer now!" and registration
		is out.
		There must also be a documentation block in "Admin Panel /
		System / Configuration / Sales / Customer Rating / Documentation" 
		with the link to the PDF and give work.
*** H: 1. Adjust for any store or one of the templates that are added by default
          ("Trusted Rating Notification E-Mail (DE)", "Trusted Rating Notification E-Mail (EN)"
          or "Trusted Rating Notification E-Mail (FR)") in "Admin Panel / System / 
          Configuration / Sales / Customer Rating / Email with rating request / Email Template"
       2. Take in the store purchase.
       3. Make one Shipment in AdminPanel in Orders, and log out / log in.
       4. Check whether the letter came in the prescribed language.		

** CATCHABLE
*** B: Upon an invalid date (should not be possible because of the drop-down) no e-mails are sent.
