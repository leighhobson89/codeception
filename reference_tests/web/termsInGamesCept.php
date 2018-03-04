<?php
require 'tests/acceptance/_bootstrap.php';

// @group general
// @group t_c_web

$I = new AcceptanceTester($scenario);

$I->wantTo('Check that Terms & Conditions PopUp appears in Game windows');

//Clear T&Cs flag before starting
$I->amOnUrl($termsClearPath.$environment);
$I->waitForText('T&Cs Value Successfully Changed',30);

//Open homepage
$I->amGoingTo('Open website');
$I->amOnUrl($url);
$I->maximizeWindow();

$I->amGoingTo('verify elements on Landing page');
$I->waitForElementVisible('[title="Casino"]',30); //Wait for Casino link

// Test Game window
$I->amGoingTo('open Casino page');
$I->click('[title="Casino"]');
$I->waitForElementVisible('.active[classname="casino"]', 30);
$I->waitForElementVisible('.hometime',60);

$serverid = $I->grabTextFrom('.hiddensrv'); // Get Server ID
$I->greenText('Server ID is: '.$serverid);

$I->amGoingTo('open random game from popular category');
$rand_game = rand(1,16);
$I->waitForElementVisible('.gl_Content:nth-child(1)  div:nth-child('.$rand_game.') > .gl_GameFrame .gl_Game');
$I->click('.gl_Content:nth-child(1)  div:nth-child('.$rand_game.') > .gl_GameFrame .gl_Game');

// select low, regular or high stakes before launching a game
	try{
		$I->waitForElementVisible('#gl_Lightbox',2);
		$I->waitForElementVisible('#gl_Lightbox .gl_StakeOption:nth-of-type(1)',2);
		$I->click('#gl_Lightbox .gl_StakeOption:nth-of-type(1)');
		
	}catch(Exception $gameStartedDirectly){
		
	}

//change to game window
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

$I->amGoingTo('verify game window');
$gamewindowframe=$I->grabAttributeFrom('iframe', 'id'); //Pick IFrame ID
$I->switchToIFrame($gamewindowframe);

$I->waitForElement('.login', 30);
$I->waitForElement('#LoginUsername', 30);
$I->waitForElement('#LoginPassword', 30);
$I->waitForElement('#LoginSubmit', 30);

$I->amGoingTo('Log in');
$I->fillField('#LoginUsername',$termsuser);
$I->fillField('#LoginPassword',$termsuserpass);
$I->click('#LoginSubmit');
$I->switchToIFrame();

$I->waitForElementVisible('.overlay_message.termsandconditions',30); //Wait for popup to appear

$I->see('CHANGES TO OUR TERMS AND CONDITIONS','div.termsheader');
$I->see('Our Terms and Conditions have changed.','div.termtext');
$I->see('This game window will now close to allow you to read them.','div.termtext');
$I->seeElement('div.termbuttons');

//Change to main window
$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webDriver) { //Switch to the parent window
	
	global $curr_window;
	$webDriver->switchTo()->window($curr_window);
				
});

$I->amGoingTo('Verify popup');
$I->waitForElementVisible('.accountsave.termscancelbutton',30);
$I->seeElement('.overlay_deposit_setup_btn.btn_orange.termsorangebut');
$I->see('Our Terms and Conditions have changed, by clicking','.termtext');
$I->see('Accept','.termtext');
$I->see('you are agreeing to our new','.termtext');
$I->see('Terms and Conditions','.termtext .greenhighlighttext');

$I->amGoingTo('Cancel Acceptance and Log Out');
$I->click('.accountsave.termscancelbutton');
$I->waitForElementVisible('[name="username"]',60);
$I->dontSeeElement('a span.balance_visible');

// Test Live Casino window
$I->amGoingTo('open Live Casino page');
$I->click('.livecasino');
$I->waitForElementVisible('.slides_container',30); //wait for Live Casino sliders

$I->amGoingTo('open random game from popular category');
$rand_game = rand(2,6);
$I->waitForElementVisible('.gl_GameFrameLive:nth-child('.$rand_game.') .gl_Game.gl_imgstyle');
$I->click('.gl_GameFrameLive:nth-child('.$rand_game.') .gl_Game.gl_imgstyle');

//change to game window
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

$I->amGoingTo('verify game window');
$gamewindowframe=$I->grabAttributeFrom('iframe', 'id'); //Pick IFrame ID
$I->switchToIFrame($gamewindowframe);

$I->waitForElement('.login', 30);
$I->waitForElement('#LoginUsername', 30);
$I->waitForElement('#LoginPassword', 30);
$I->waitForElement('#LoginSubmit', 30);

$I->amGoingTo('Log in');
$I->fillField('#LoginUsername',$termsuser);
$I->fillField('#LoginPassword',$termsuserpass);
$I->click('#LoginSubmit');
$I->switchToIFrame();

$I->waitForElementVisible('.overlay_message.termsandconditions',30); //Wait for popup to appear

$I->see('CHANGES TO OUR TERMS AND CONDITIONS','div.termsheader');
$I->see('Our Terms and Conditions have changed.','div.termtext');
$I->see('This game window will now close to allow you to read them.','div.termtext');
$I->seeElement('div.termbuttons');

//Change to main window
$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webDriver) { //Switch to the parent window
	
	global $curr_window;
	$webDriver->switchTo()->window($curr_window);
				
});

$I->amGoingTo('Verify popup');
$I->waitForElementVisible('.accountsave.termscancelbutton',30);
$I->seeElement('.overlay_deposit_setup_btn.btn_orange.termsorangebut');
$I->see('Our Terms and Conditions have changed, by clicking','.termtext');
$I->see('Accept','.termtext');
$I->see('you are agreeing to our new','.termtext');
$I->see('Terms and Conditions','.termtext .greenhighlighttext');

$I->amGoingTo('Cancel Acceptance and Log Out');
$I->click('.accountsave.termscancelbutton');
$I->waitForElementVisible('[name="username"]',60);
$I->dontSeeElement('a span.balance_visible');


?> 