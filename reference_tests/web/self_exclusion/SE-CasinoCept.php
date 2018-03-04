<?php
require 'tests/acceptance/_bootstrap.php';

// @group general
// @group selfExclusion

$I = new AcceptanceTester($scenario);
$I->wantTo('Load Casino Games and ensure user is Excluded');

$I->amOnPage('/');
$I->maximizeWindow();

$I->amGoingTo('Verify elements on Landing page');
$I->waitForElementVisible('[title="Casino"]',30);

$I->amGoingTo('Check Casino');
$I->click('[title="Casino"]');

$I->waitForElementVisible('.active[classname="casino"]', 30);
$I->waitForElementVisible('.hometime',60);

$I->weblogin($excludedtestuser,$excludedtestPass);

$serverid = $I->grabTextFrom('.hiddensrv'); // Get Server ID
$I->greenText('Server ID is: '.$serverid);

$I->waitForElementVisible('#slides .pagination li:nth-of-type(1) a',60);
$I->click('#slides .pagination li:nth-of-type(1) a');
$I->waitForElement('.slides_container img',30);
$I->waitForElementVisible('#searchGames input',30);

$funds=$I->grabTextFrom('span.balance_visible');
$funds= filter_var($funds, FILTER_SANITIZE_NUMBER_FLOAT); //get actual balance in Cents
$I->amGoingTo('Actual Funds => £'.$funds/100); //Shows Funds

//NetEnt game
$I->amGoingTo('Check NetEnt game');

//Find a game
$I->fillField('#searchGames input','gonzo');
$I->waitForElementVisible('.gamesFilteredDisplay [alt="Gonzo´s Quest"]',30);
$I->wait(1);
$I->click('.gamesFilteredDisplay [alt="Gonzo´s Quest"]');

$I->wait(2);

//change to game window
$I->executeInSelenium(function (\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver) {
     $handles=$webdriver->getWindowHandles();
     $last_window = end($handles);
     $webdriver->switchTo()->window($last_window);
});


/*
//change to game window
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
$I->wait(1);

$I->waitForElement('#GameIframe',30);
$I->wait(1);
$I->switchToIFrame('GameIframe');
$I->wait(1);

$I->waitForElement('#gamecontainer',30);
$I->see('An Error occurred while retrieving the Game. Login failed','#gamecontainer');

//Change back to main window
$I->switchToWindow();

/*
$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webDriver) { //Switch to the parent window
	
	global $curr_window;
	$webDriver->switchTo()->window($curr_window);
				
});
*/

//Chartwell game
$I->amGoingTo('Check Chartwell game');

//Find a game
$I->fillField('#searchGames input','shields');
$I->waitForElementVisible('.gamesFilteredDisplay [alt="300 Shields"]',30);
$I->wait(1);
$I->click('.gamesFilteredDisplay [alt="300 Shields"]');

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

$I->wait(2);

$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver){ //Store current window id
	
	global $gamewindow;
	global $curr_window;
	$gamewindow= end($webdriver->getWindowHandles());
	$curr_window = $webdriver->getWindowHandle();
	$webdriver->switchTo()->window($gamewindow);
	
});
*/


$I->waitForElement('#iframegamecontainer',30);
$I->wait(1);
$I->switchToIFrame('iframegamecontainer');
$I->wait(1);

$I->waitForElement('#GameIframe',30);
$I->wait(1);
$I->switchToIFrame('GameIframe');
$I->wait(1);

$I->see('There has been a problem accessing your account at present.');
$I->see('Error Code: 1003');

//Change back to main window
$I->switchToWindow();

/*
//Change back to main window
$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webDriver) { //Switch to the parent window
	
	global $curr_window;
	$webDriver->switchTo()->window($curr_window);
				
});
*/

$I->weblogout();	
		
?> 