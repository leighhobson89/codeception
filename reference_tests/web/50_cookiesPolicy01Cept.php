<?php
require 'tests/acceptance/_bootstrap.php';

// @group general
// @group cookies

/*
$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver){ //Delete all cookies
	
	$webdriver->manage()->deleteAllCookies();
	
});	
*/

$I = new AcceptanceTester($scenario);
$I->wantTo('Test Cookies policy message on WEB');

// Normal flow: cookies message on landing page -> no message on homepage
$I->amOnPage('/');
$I->maximizeWindow();

$I->amGoingTo('Verify elements on Landing page');
$I->waitForElementVisible('div.account h4:nth-child(3) a',30); // Wait for Registration button

$I->amGoingTo('Verify Cookies bar');
$I->waitForElementVisible('#cookie-bar',30); // Wait for Cookies bar
$I->seeElement('#cookie-bar');
$I->seeElement('a#seeCookiesPolicyAnchor');
$I->seeElement('.cb-enable');
$I->see('We use cookies to improve your experience. By continuing, you accept such use. If you wish, you can change your settings at any time. For more details.','#cookie-bar p');

$I->seeCookie('warplandingpage');
$I->seeCookie('cb-enabled');

$I->amGoingTo('Navigate to homepage');
$I->click('.main-logo a');
$I->waitForElement('iframe',30);

$I->seeCookie('warplandingpage');
$I->seeCookie('cb-enabled');

$serverid = $I->grabTextFrom('.hiddensrv'); // Get Server ID
$I->greenText('Server ID is: '.$serverid);

$I->amGoingTo('Ensure that cookies message does not appear');
$I->dontSeeElement('#cookie-bar');
$I->dontSeeElement('a#seeCookiesPolicyAnchor');
$I->dontSeeElement('.cb-enable');

$I->amGoingTo('Delete necessary cookies');
$I->resetCookie('warplandingpage'); // Landing page cookie to display landing page again
$I->resetCookie('cb-enabled'); // Cookies policy cookie to display notification again

// cookies message on landing page -> cookies button -> no message on Help page -> no message on homepage
$I->amOnPage('/');
$I->maximizeWindow();

$I->amGoingTo('Verify elements on Landing page');
$I->waitForElementVisible('div.account h4:nth-child(3) a',30); // Wait for Registration button

$I->amGoingTo('Verify Cookies bar');
$I->waitForElementVisible('#cookie-bar',30); // Wait for Cookies bar
$I->seeElement('#cookie-bar');
$I->seeElement('a#seeCookiesPolicyAnchor');
$I->seeElement('.cb-enable');
$I->see('We use cookies to improve your experience. By continuing, you accept such use. If you wish, you can change your settings at any time. For more details.','#cookie-bar p');

$I->seeCookie('warplandingpage');
$I->seeCookie('cb-enabled');

$I->amGoingTo('Click on Cookies policy button');
$I->click('a#seeCookiesPolicyAnchor'); //click on cookies policy button
$I->waitForElementVisible('.help_content',30);
$I->see('Cookies policy','.current_tab');

$I->seeCookie('warplandingpage');
$I->seeCookie('cb-enabled');

$serverid = $I->grabTextFrom('.hiddensrv'); // Get Server ID
$I->greenText('Server ID is: '.$serverid);

$I->amGoingTo('Ensure that cookies message does not appear');
$I->dontSeeElement('#cookie-bar');
$I->dontSeeElement('a#seeCookiesPolicyAnchor');
$I->dontSeeElement('.cb-enable');

$I->amGoingTo('Navigate to homepage');
$I->click('.main-logo a');
$I->waitForElement('iframe',30);

$I->seeCookie('warplandingpage');
$I->seeCookie('cb-enabled');

$I->amGoingTo('Ensure that cookies message does not appear');
$I->dontSeeElement('#cookie-bar');
$I->dontSeeElement('a#seeCookiesPolicyAnchor');
$I->dontSeeElement('.cb-enable');

$I->amGoingTo('Delete necessary cookies');
$I->resetCookie('warplandingpage'); // Landing page cookie to display landing page again
$I->resetCookie('cb-enabled'); // Cookies policy cookie to display notification again

// cookies message on landing page -> close message -> no message on homepage
$I->amOnPage('/');
$I->maximizeWindow();

$I->amGoingTo('Verify elements on Landing page');
$I->waitForElementVisible('div.account h4:nth-child(3) a',30); // Wait for Registration button

$I->seeCookie('warplandingpage');
$I->seeCookie('cb-enabled');

$I->amGoingTo('Verify Cookies bar');
$I->waitForElementVisible('#cookie-bar',30); // Wait for Cookies bar
$I->seeElement('#cookie-bar');
$I->seeElement('a#seeCookiesPolicyAnchor');
$I->seeElement('.cb-enable');
$I->see('We use cookies to improve your experience. By continuing, you accept such use. If you wish, you can change your settings at any time. For more details.','#cookie-bar p');

$I->amGoingTo('Close the Cookies Message');
$I->click('.cb-enable'); // close message
$I->wait(1);

$I->amGoingTo('Ensure that cookies message is closed');
$I->dontSeeElement('#cookie-bar');
$I->dontSeeElement('a#seeCookiesPolicyAnchor');
$I->dontSeeElement('.cb-enable');
$I->seeInCurrentUrl('Landing.aspx');

$I->amGoingTo('Navigate to homepage');
$I->click('.main-logo a');
$I->waitForElement('iframe',30);

$I->seeCookie('warplandingpage');
$I->seeCookie('cb-enabled');

$serverid = $I->grabTextFrom('.hiddensrv'); // Get Server ID
$I->greenText('Server ID is: '.$serverid);

$I->amGoingTo('Ensure that cookies message does not appear');
$I->dontSeeElement('#cookie-bar');
$I->dontSeeElement('a#seeCookiesPolicyAnchor');
$I->dontSeeElement('.cb-enable');


?>

