<?php
require 'tests/acceptance/_bootstrap.php';

// @group general
// @group activity

$I = new AcceptanceTester($scenario);
$I->wantTo('Test Activity Alert popup on Website - Casino games popup');

$I->amOnPage('/');
$I->maximizeWindow();

$I->amGoingTo('verify elements on Landing page');
$I->waitForElementVisible('[title="Casino"]',30); //Wait for Casino link

$I->amGoingTo('open Casino page');
$I->click('[title="Casino"]');
$I->waitForElementVisible('.active[classname="casino"]',30);
$I->waitForElementVisible('.hometime',60);

$serverid = $I->grabTextFrom('.hiddensrv'); // Get Server ID
$I->greenText('Server ID is: '.$serverid);

$I->amGoingTo('open random game from popular category');
$rand_game = rand(1,16);
$I->waitForElementVisible('.gl_Content:nth-child(1)  div:nth-child('.$rand_game.') > .gl_GameFrame .gl_Game',30);
$I->click('.gl_Content:nth-child(1)  div:nth-child('.$rand_game.') > .gl_GameFrame .gl_Game');

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
	$gamewindow = end($webdriver->getWindowHandles());
	$curr_window = $webdriver->getWindowHandle();
	$webdriver->switchTo()->window($gamewindow);
	
});	
*/

/*

// select low, regular or high stakes before launching a game
	try{
		$I->waitForElementVisible('#gl_Lightbox',2);
		$I->waitForElementVisible('#gl_Lightbox .gl_StakeOption:nth-of-type(1)',2);
		$I->click('#gl_Lightbox .gl_StakeOption:nth-of-type(1)');
		
	}catch(Exception $gameStartedDirectly){
		
	}

*/



$I->amGoingTo('verify game window');
$gamewindowframe=$I->grabAttributeFrom('iframe', 'id'); //Pick IFrame ID
$I->switchToIFrame($gamewindowframe);

$I->amGoingTo('Log in');
$I->fillField('#LoginUsername',$activitytestuser);
$I->fillField('#LoginPassword',$activitytestPass);
$I->click('#LoginSubmit');
$I->switchToIFrame();

$I->amGoingTo('Verify Activity Alert');
$I->waitForElementVisible('.overlay_message_activityalert',30);
$I->seeElement('.overlay_message_activityalert');
$I->seeElement('.overlay_message_activityalert .overlay_message_btn');
$I->see('Yes, I\'m fine','.overlay_message_activityalert .overlay_message_btn');
$I->seeElement('.overlay_message_activityalert .btn_gray');
$I->see('No, please log me out','.overlay_message_activityalert .btn_gray');
$I->seeElement('.overlay_message_activityalert .odds_text_link');
$I->see('Click here to view your account history','.overlay_message_activityalert .odds_text_link');

$I->amGoingTo('Click on Yes button');
$I->click('.overlay_message_activityalert .overlay_message_btn');
$I->waitForElementNotVisible('.overlay_message_activityalert',30);
$I->wait(1);


//Change to main window
$I->switchToWindow();

/*
$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webDriver) { //Switch to the parent window
	
	global $curr_window;
	$webDriver->switchTo()->window($curr_window);
				
});
*/

$I->amGoingTo('wait for Alert to appear again and Verify it');
$I->waitForElementVisible('.overlay_message_activityalert',30);
$I->seeElement('.overlay_message_activityalert');
$I->seeElement('.overlay_message_activityalert .overlay_message_btn');
$I->see('Yes, I\'m fine','.overlay_message_activityalert .overlay_message_btn');
$I->seeElement('.overlay_message_activityalert .btn_gray');
$I->see('No, please log me out','.overlay_message_activityalert .btn_gray');
$I->seeElement('.overlay_message_activityalert .odds_text_link');
$I->see('Click here to view your account history','.overlay_message_activityalert .odds_text_link');


?>

