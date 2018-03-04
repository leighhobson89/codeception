<?php
require 'tests/acceptance/_bootstrap.php';

// @group general
// @group selfExclusion

$I = new AcceptanceTester($scenario);
$I->wantTo('Load Live Casino Game and ensure user is Excluded');

$I->amOnPage('/');
$I->maximizeWindow();

$I->amGoingTo('Verify elements on Landing page');
$I->waitForElementVisible('[title="Live Casino"]',30);

$I->amGoingTo('Select Live Casino');
$I->click('[title="Live Casino"]');

$I->waitForElementVisible('.active[classname="livecasino"]', 30);
$I->waitForElementVisible('.hometime',60);

$I->weblogin($excludedtestuser,$excludedtestPass);

$serverid = $I->grabTextFrom('.hiddensrv'); // Get Server ID
$I->greenText('Server ID is: '.$serverid);

$I->waitForElementVisible('#slides .pagination li:nth-of-type(1) a',60);
$I->click('#slides .pagination li:nth-of-type(1) a');
$I->waitForElement('.slides_container img',30);

//Live Roulette
$I->waitForElement('.gameswarpdealer a.gl_GameFrameLive:nth-of-type(1)',30);
$I->click('.gameswarpdealer a.gl_GameFrameLive:nth-of-type(1)');

$I->wait(2);

//change to game window
$I->executeInSelenium(function (\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver) {
     $handles=$webdriver->getWindowHandles();
     $last_window = end($handles);
     $webdriver->switchTo()->window($last_window);
});


/*
global $gamewindow,$curr_window;
$curr_window="hola";
$gamewindow="hola";

$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver){ //Store current window id
	
	global $gamewindow;
	global $curr_window;
	$gamewindow= end($webdriver->getWindowHandles());
	$curr_window = $webdriver->getWindowHandle();
	$webdriver->switchTo()->window($gamewindow);
	
});	
*/


$I->waitForElement('.gameiframe',30); // wait for 1st iframe
$I->wait(1);
$I->waitForElement('#iframegamecontainer',30);
$I->wait(1);
$I->switchToIFrame('iframegamecontainer');
$I->wait(3);

//$I->see('Object moved');
$I->see('Initializing Games Room','#initiategamesroomstart');
$I->see('There has been an error','#initiategamesroomerror');
$I->see('error-number unknown','#initiategamesroomerror');
$I->see('Please contact customer services quoting this error or try again.','#initiategamesroomerror');

//Change back to main window
$I->switchToWindow();

/*
$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webDriver) { //Switch to the parent window
	
	global $curr_window;
	$webDriver->switchTo()->window($curr_window);
				
});
*/

$I->weblogout();	
		
?> 