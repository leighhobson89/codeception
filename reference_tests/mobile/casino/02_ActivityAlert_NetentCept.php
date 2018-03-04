<?php
require 'tests/acceptance/_bootstrap.php';

// @group general
// @group activity_alert

$I = new AcceptanceTester($scenario);

$I->wantTo('verify Activity Alert - NetEnt - Guns n Roses');

$I->amOnPage('/');
$I->resizeWindow(411, 731); // Nexus5 size

$I->amGoingTo('Verify elements on Landing page');
$I->waitForElementVisible('.cta',30);

$I->amGoingTo('Open Login');
$I->click('.cta');
$I->waitForElement('.login-button-link',30);

//Login
$I->amGoingTo('Log in');
$I->fillField('.form-item-username input',$activitytestuser);
$I->fillField('.form-item-password input',$activitytestPass);
$I->click('.login-button-link');

$I->waitForElementVisible('.login-modal-continue-container',30); //Wait for popup to appear
$I->click('.login-modal-continue-container');

$I->amGoingTo("Navigate to Casino");
$I->waitForElementVisible('#casinoMenuBtnId',30); //Wait for casino button
$I->click('#casinoMenuBtnId');
$I->waitForElementVisible('.views-fluid-grid li:nth-of-type(1)',30); //wait for the first game

$I->amGoingTo("Find required game");
$I->waitForElementVisible('#searchGames input',30); //wait for search bar
$I->fillField('#searchGames input','roses');
$I->wait(3);

$li=0; //Variable to 0 to start from the 1st game

$tabID=$I->grabAttributeFrom('.tab-content.active','id'); //grab the currently selected Casino TAB ID
$I->amGoingTo('Currently active Casino TAB ID: '.$tabID);
$I->waitForElementVisible('#'.$tabID.' .views-row:nth-of-type(1) .casino-icon-high-resolution',30); //wait for the first game in the selected TAB

do{
	$li++; //increase li before grabbing text to start from 1st 
	$gamename=$I->grabTextFrom('#'.$tabID.' .views-fluid-grid li:nth-of-type('.$li.') .game-name');
	} while($gamename!="Guns and Roses");


/*
$I->amGoingTo("Find required game");
$casinopage=1;
findgame:
try{	$li=0; //Variable to 0 to start from the 1st game

		$tabID=$I->grabAttributeFrom('.stanjames-class.active','id'); //grab the currently selected Casino TAB ID
		$I->amGoingTo('Currently active Casino TAB ID: '.$tabID);
		$I->waitForElementVisible('#'.$tabID.'-content .views-row:nth-of-type(1) .casino-icon-high-resolution',30); //wait for the first game in the selected TAB
		
		do{
			$li++; //increase li before grabbing text to start from 1st 
			$gamename=$I->grabTextFrom('#'.$tabID.'-content .views-fluid-grid li:nth-of-type('.$li.') .game-name');
			} while($gamename!="Guns and Roses");
			
}catch(Exception $NoGameFound){
	
	$I->executeJS('document.querySelector(".stanjames-class:nth-of-type('.$casinopage.')").setAttribute("style","display: none;")'); //making Casino menu element hidden to make other tabs visible. Swiping should be implemented in teh future
	$casinopage++;
	$I->click('.stanjames-class:nth-of-type('.$casinopage.')'); //Activate next Casino tab
	$I->wait(1);
	$I->amGoingTo('Currently open Casino page: '.$casinopage);
	goto findgame;
}
*/

$I->amGoingTo("Launch Guns n Roses game");
$I->wait(3);
$I->click('#'.$tabID.' .views-fluid-grid li:nth-of-type('.$li.')'); // open game
$I->waitForElementVisible('#mobile-casino-dialog[style=""] .mobile-casino-real-money-button',30);
$I->click('#mobile-casino-dialog[style=""] .mobile-casino-real-money-button .ui-btn-text');

//Check the game is loaded
$I->waitForElementVisible('.interface-settingsButton_baseButton',60);

// Verify the Activity alert
$I->amGoingTo("Verify the Activity alert");
$I->waitForElementVisible('#dialogWindowScroll_container',20);
$I->waitForElementVisible('#dialogWindowTitle',30);
$I->see('Reality Check','#dialogWindowTitle');
$I->see('Continue Playing','#dialogWindowLeftDualButton'); //Continue playing button
$I->see('Leave Game','#dialogWindowRightDualButton'); //Leave game button

// Leave the game
$I->amGoingTo("leave the game");
$I->click('#dialogWindowRightDualButton');
$I->wait(2);
$I->see('Leave Game','#dialogWindowTitle');
$I->see('Game History','#dialogWindowLeftDualButton'); //Game History button
$I->see('Lobby','#dialogWindowRightDualButton'); //Lobby button
$I->click('#dialogWindowRightDualButton');

$I->waitForElementVisible('.header-image',30);


?>