<?php
require 'tests/acceptance/_bootstrap.php';

global $accbutton;
$accbutton=array();

// @group general
// @group t_c_web

$I = new AcceptanceTester($scenario);

$I->wantTo('Check Terms & Conditions PopUp');

//Clear T&Cs flag before starting
$I->amOnUrl($termsClearPath.$environment);
$I->waitForText('T&Cs Value Successfully Changed',30);

//Open homepage
$I->amGoingTo('Open website');
$I->amOnUrl($url);
$I->maximizeWindow();

$I->amGoingTo('Verify elements on Landing page');
$I->waitForElementVisible('a.loginbtn',30); // Wait for Login button

$I->amGoingTo('Open HomePage');
$I->click('a.loginbtn'); //Click on Login
$I->waitForElement('iframe',30);

$serverid = $I->grabTextFrom('.hiddensrv'); // Get Server ID
$I->greenText('Server ID is: '.$serverid);

$I->amGoingTo('Log in');
$I->fillField('[name="username"]',$termsuser);
$I->fillField('[name="password"]',$termsuserpass);
$I->click('#xLogin.loginbtn');
$I->waitForElementVisible('div.overlay_message.box_shadow.termsandconditions',30); //Wait for popup to appear

$I->amGoingTo('Verify popup');
$I->waitForElementVisible('.accountsave.termscancelbutton',30);
$I->seeElement('.overlay_deposit_setup_btn.btn_orange.termsorangebut');
$I->see('Our Terms and Conditions have changed, by clicking','.termtext');
$I->see('Accept','.termtext');
$I->see('you are agreeing to our new','.termtext');
$I->see('Terms and Conditions','.termtext .greenhighlighttext');

$I->amGoingTo('Accept T&Cs');
$I->click('.overlay_deposit_setup_btn.btn_orange.termsorangebut');
$I->waitForElementNotVisible('div.overlay_message.box_shadow.termsandconditions',30); // Wait for popup to disappear
$I->wait(5);

//$I->waitForElementVisible('[title="LOGOUT"]'); The element is visible anyway, just greyed out

$I->weblogoutFull();

//Check pop up is not showing
$I->amGoingTo('Log in');
$I->weblogin($termsuser,$termsuserpass);

$I->amGoingTo('Verify popup is not in there');
$I->dontSeeElement('.overlay_deposit_setup_btn.btn_orange.termsorangebut');
$I->dontSee('Our Terms and Conditions have changed, by clicking','.termtext');
$I->dontSee('Accept','.termtext');
$I->dontSee('you are agreeing to our new','.termtext');

$I->weblogoutFull();

$I->amGoingTo('Clear T&Cs Flag');
$I->amOnUrl($termsClearPath.$environment);
$I->waitForText('T&Cs Value Successfully Changed',30);

//Check T&Cs Page Acceptance

//Top Accept Button
$I->amGoingTo('Open Homepage');
$I->amOnUrl($url);
$I->waitForElement('iframe',30);

$serverid = $I->grabTextFrom('.hiddensrv'); // Get Server ID
$I->greenText('Server ID is: '.$serverid);

$I->amGoingTo('Log in');
$I->fillField('[name="username"]',$termsuser); //Changed due to new T&C PopUp
$I->fillField('[name="password"]',$termsuserpass);
$I->click('#xLogin.loginbtn');
$I->waitForElementVisible('div.overlay_message.box_shadow.termsandconditions',30); //Wait for popup to appear

$I->amGoingTo('Verify popup');
$I->waitForElementVisible('.accountsave.termscancelbutton',30);
$I->seeElement('.overlay_deposit_setup_btn.btn_orange.termsorangebut');
$I->see('Our Terms and Conditions have changed, by clicking','.termtext');
$I->see('Accept','.termtext');
$I->see('you are agreeing to our new','.termtext');
$I->see('Terms and Conditions','.termtext .greenhighlighttext');

$I->amGoingTo('Open T&C Page from popup');
$I->wait(2);
$I->click('.termtext a');
$I->waitForElementVisible('.orrangebuthelp',30);

$I->amGoingTo('get the Accept Buttons Element IDs in T&C page');
$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver){
	
		global $accbutton;
		$accbutton = $webdriver->findElements(WebDriverBy::cssselector('.orrangebuthelp'));
		
	});
global $accbuton1;
global $accbutton2;

$accbutton1=$accbutton[0]->getAttribute('ID');  //Get Buttons Value to variables
$accbutton2=$accbutton[1]->getAttribute('ID');
$I->amGoingTo('Top button ID: '.$accbutton1.' and Bottom button ID: '.$accbutton2);

$I->amGoingTo('Ensure both Accept buttons are available in T&C page');
$I->waitForElementVisible('#'.$accbutton1,30);
$I->seeElement('#'.$accbutton1);
//$I->executeJS('document.querySelector("#'.$accbutton2.'").scrollIntoView(false)');
$I->scrollTo('.first_footertab');

$I->seeElement('#'.$accbutton2);

$I->amGoingTo('Accept T&Cs with up button');
//$I->executeJS('document.querySelector("#'.$accbutton1.'").scrollIntoView(false)');
$I->scrollTo('#tabList',0,-50);

$I->click('#'.$accbutton1);
$I->wait(5);

//Check Accept buttons not visible
$I->amGoingTo('Open T&Cs page and ensure that there is no Accept buttons');
$I->click('.dropdown.help');
$I->waitForText('Terms & conditions',30,'.helpselection');
$I->click('Terms & conditions','.helpselection');
$I->wait(3);
$I->waitForElementVisible('.help_content',60);
$I->wait(2);
$I->dontSeeElement('.orrangebuthelp');
//$I->executeJS('document.querySelector(".footerinner").scrollIntoView(false)');
$I->scrollTo('.first_footertab');

$I->dontSeeElement('.orrangebuthelp');
$I->click('.main-logo a');
$I->wait(3);

$I->weblogoutFull();

//Check pop up is not showing
$I->amGoingTo('Log in');
$I->weblogin($termsuser,$termsuserpass);

$I->amGoingTo('Verify popup is not in there');
$I->dontSeeElement('.overlay_deposit_setup_btn.btn_orange.termsorangebut');
$I->dontSee('Our Terms and Conditions have changed, by clicking','.termtext');
$I->dontSee('Accept','.termtext');
$I->dontSee('you are agreeing to our new','.termtext');

$I->weblogoutFull();

$I->amGoingTo('Clear T&Cs Flag');
$I->amOnUrl($termsClearPath.$environment);
$I->waitForText('T&Cs Value Successfully Changed',30);

//Bottom Accept Button
$I->amGoingTo('Open Homepage');
$I->amOnUrl($url);
$I->waitForElement('iframe',30);

$serverid = $I->grabTextFrom('.hiddensrv'); // Get Server ID
$I->greenText('Server ID is: '.$serverid);

$I->amGoingTo('Log in');
$I->fillField('[name="username"]',$termsuser); //Changed due to new T&C PopUp
$I->fillField('[name="password"]',$termsuserpass);
$I->click('#xLogin.loginbtn');
$I->waitForElementVisible('div.overlay_message.box_shadow.termsandconditions',30); //Wait for popup to appear

$I->amGoingTo('Verify popup');
$I->waitForElementVisible('.accountsave.termscancelbutton',30);
$I->seeElement('.overlay_deposit_setup_btn.btn_orange.termsorangebut');
$I->see('Our Terms and Conditions have changed, by clicking','.termtext');
$I->see('Accept','.termtext');
$I->see('you are agreeing to our new','.termtext');
$I->see('Terms and Conditions','.termtext .greenhighlighttext');

$I->click('.termtext a');
$I->waitForElementVisible('.orrangebuthelp',30);

/*
$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver){ //Find Number of available meetings
	
		global $accbutton;
		$accbutton = $webdriver->findElements(WebDriverBy::cssselector('.orrangebuthelp'));
		
	});

$accbutton1=$accbutton[0]->getAttribute('ID');  //Get Buttons Value to variables
$accbutton2=$accbutton[1]->getAttribute('ID');
*/

$I->amGoingTo('Ensure both Accept buttons are available in T&C page');
$I->waitForElementVisible("#".$accbutton1,30);
$I->seeElement("#".$accbutton1);
//$I->executeJS('document.querySelector("#'.$accbutton2.'").scrollIntoView(false)');
$I->scrollTo('.first_footertab');
$I->seeElement("#".$accbutton2);

$I->amGoingTo('Accept T&Cs with bottom button');
$I->click('#'.$accbutton2);
$I->wait(5);

//Check Accept buttons not visible
$I->amGoingTo('Open T&Cs page and ensure that there is no Accept buttons');
$I->click('.dropdown.help');
$I->waitForText('Terms & conditions',30,'.helpselection');
$I->click('Terms & conditions','.helpselection');
//$I->amOnPage('/UK/802/Help#action=standard-terms-sports&tab=standardtermssports&psection=homepage');

$I->wait(3);
$I->waitForElementVisible('.help_content',60);
$I->wait(2);
$I->dontSeeElement('.orrangebuthelp');
//$I->executeJS('document.querySelector(".footerinner").scrollIntoView(false)');
$I->scrollTo('.first_footertab');
$I->dontSeeElement('.orrangebuthelp');
$I->click('.main-logo a');
$I->wait(3);

$I->weblogoutFull();

//Check pop up is not showing
$I->amGoingTo('Log in');
$I->weblogin($termsuser,$termsuserpass);

$I->amGoingTo('Verify popup is not in there');
$I->dontSeeElement('.overlay_deposit_setup_btn.btn_orange.termsorangebut');
$I->dontSee('Our Terms and Conditions have changed, by clicking','.termtext');
$I->dontSee('Accept','.termtext');
$I->dontSee('you are agreeing to our new','.termtext');

$I->weblogout();

$I->amGoingTo('Clear T&Cs Flag');
$I->amOnUrl($termsClearPath.$environment);
$I->waitForText('T&Cs Value Successfully Changed',30);


?> 