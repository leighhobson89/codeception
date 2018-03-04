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
$I->wantTo('Test Cookies policy message on MOBILE');

// Normal flow: cookies message on landing page -> no message on homepage
$I->amOnPage('/');
$I->resizeWindow(411, 731); // Nexus5 size

$I->amGoingTo('Verify elements on Landing page');
$I->waitForElementVisible('.cta',30);

$I->amGoingTo('Verify Cookies bar');
$I->waitForElementVisible('#cookie-bar',30); // Wait for Cookies bar
$I->seeElement('#cookie-bar');
$I->seeElement('.cb-policy');
$I->seeElement('.cb-enable');
$I->see('We use cookies to improve your experience. By continuing, you accept such use. If you wish, you can change your settings at any time. For more details.','#cookie-bar p');

$I->seeCookie('landingmobile');
$I->seeCookie('cb-enabled');

$I->amGoingTo('Navigate to homepage');
$I->click('#logoSJ');
$I->waitForElement('#slidebar',30);
$I->waitForElement('.image',30);

$I->amGoingTo('Ensure that cookies message does not appear');
$I->dontSeeElement('#cookie-bar');
$I->dontSeeElement('.cb-policy');
$I->dontSeeElement('.cb-enable');

$I->amGoingTo('Delete necessary cookies');
$I->resetCookie('landingmobile'); // Landing page cookie to display landing page again
$I->resetCookie('cb-enabled'); // Cookies policy cookie to display notification again

// cookies message on landing page -> cookies button -> no message on Help page -> no message on homepage
$I->amOnPage('/');

$I->amGoingTo('Verify elements on Landing page');
$I->waitForElementVisible('.cta',30);

$I->amGoingTo('Verify Cookies bar');
$I->waitForElementVisible('#cookie-bar',30); // Wait for Cookies bar
$I->seeElement('#cookie-bar');
$I->seeElement('.cb-policy');
$I->seeElement('.cb-enable');
$I->see('We use cookies to improve your experience. By continuing, you accept such use. If you wish, you can change your settings at any time. For more details.','#cookie-bar p');

$I->seeCookie('landingmobile');
$I->seeCookie('cb-enabled');

$I->amGoingTo('Click on Cookies policy button');
$I->click('.cb-policy'); //click on cookies policy button
$I->waitForElementVisible('.responsible-gaming-page',30);
$I->see('COOKIES INFORMATION','h1');

$I->amGoingTo('Ensure that cookies message does not appear');
$I->dontSeeElement('#cookie-bar');
$I->dontSeeElement('.cb-policy');
$I->dontSeeElement('.cb-enable');

$I->amGoingTo('Navigate to homepage');
$I->click('.header-top-logo');
$I->waitForElement('#slidebar',30);
$I->waitForElement('.image',30);

$I->seeCookie('landingmobile');
$I->seeCookie('cb-enabled');

$I->amGoingTo('Ensure that cookies message does not appear');
$I->dontSeeElement('#cookie-bar');
$I->dontSeeElement('.cb-policy');
$I->dontSeeElement('.cb-enable');

$I->amGoingTo('Delete necessary cookies');
$I->resetCookie('landingmobile'); // Landing page cookie to display landing page again
$I->resetCookie('cb-enabled'); // Cookies policy cookie to display notification again

// cookies message on landing page -> close message -> no message on homepage
$I->amOnPage('/');

$I->amGoingTo('Verify elements on Landing page');
$I->waitForElementVisible('.cta',30);

$I->amGoingTo('Verify Cookies bar');
$I->waitForElementVisible('#cookie-bar',30); // Wait for Cookies bar
$I->seeElement('#cookie-bar');
$I->seeElement('.cb-policy');
$I->seeElement('.cb-enable');
$I->see('We use cookies to improve your experience. By continuing, you accept such use. If you wish, you can change your settings at any time. For more details.','#cookie-bar p');

$I->seeCookie('landingmobile');
$I->seeCookie('cb-enabled');

$I->amGoingTo('Close the Cookies Message');
$I->click('.cb-enable'); // close message
$I->wait(1);

$I->amGoingTo('Ensure that cookies message is closed');
$I->dontSeeElement('#cookie-bar');
$I->dontSeeElement('.cb-policy');
$I->dontSeeElement('.cb-enable');

$I->amGoingTo('Navigate to homepage');
$I->click('#logoSJ');
$I->waitForElement('#slidebar',30);
$I->waitForElement('.image',30);

$I->amGoingTo('Ensure that cookies message does not appear');
$I->dontSeeElement('#cookie-bar');
$I->dontSeeElement('.cb-policy');
$I->dontSeeElement('.cb-enable');



?>

