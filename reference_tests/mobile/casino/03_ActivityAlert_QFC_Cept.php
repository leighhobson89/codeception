<?php
require 'tests/acceptance/_bootstrap.php';

// @group general
// @group activity_alert

$I = new AcceptanceTester($scenario);

$I->wantTo('verify Activity Alert - QFC - Thunderstruck II');

$I->amOnPage('/');
$I->resizeWindow(1024,768);

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
$I->fillField('#searchGames input','Thunderstruck');
$I->wait(3);

$li=0; //Variable to 0 to start from the 1st game
$tabID=$I->grabAttributeFrom('.tab-content.active','id'); //grab the currently selected Casino TAB ID
$I->amGoingTo('Currently active Casino TAB ID: '.$tabID);
$I->waitForElementVisible('#'.$tabID.' .views-row:nth-of-type(1) .casino-icon-high-resolution',30); //wait for the first game in the selected TAB

do{
	$li++; //increase li before grabbing text to start from 1st 
	$gamename=$I->grabTextFrom('#'.$tabID.' .views-fluid-grid li:nth-of-type('.$li.') .game-name');
	} while($gamename!="Thunderstruck II");



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
			} while($gamename!="Thunderstruck II");
			
}catch(Exception $NoGameFound){
	
	$I->executeJS('document.querySelector(".stanjames-class:nth-of-type('.$casinopage.')").setAttribute("style","display: none;")'); //making Casino menu element hidden to make other tabs visible. Swiping should be implemented in teh future
	$casinopage++;
	$I->click('.stanjames-class:nth-of-type('.$casinopage.')'); //Activate next Casino tab
	$I->wait(1);
	$I->amGoingTo('Currently open Casino page: '.$casinopage);
	goto findgame;
}
*/



//$I->waitForElementVisible('#'.$tabID.'-content .views-row:nth-of-type(1) .casino-icon-high-resolution',30); //wait for the first game in the selected TAB

$I->amGoingTo("Launch Thunderstruck II game");
$I->wait(3);
$I->click('#'.$tabID.' .views-fluid-grid li:nth-of-type('.$li.')'); // open game
$I->waitForElementVisible('#mobile-casino-dialog[style=""] .mobile-casino-real-money-button',30);
$I->click('#mobile-casino-dialog[style=""] .mobile-casino-real-money-button .ui-btn-text');

//Check the game is loaded
$I->waitForElementVisible('#txtSpin',60);

$I->waitForElement("iframe#httpswww_stanjamescomrcapimicrogaming",30);
$I->switchToIFrame("httpswww_stanjamescomrcapimicrogaming"); // Swithching to Activity Alert iframe

// Verify the Activity alert
$I->amGoingTo("Verify the Activity alert");
$I->waitForElementVisible('#dialogbox',30);
$I->see('Reality Check','#dialogbox h2');
$I->see('Continue Playing','#dialogbox button:nth-of-type(1)'); //Continue playing button
$I->see('Leave Game','#dialogbox button:nth-of-type(2)'); //Leave game button

// Leave the game
$I->amGoingTo("leave the game");
$I->click('#dialogbox button:nth-of-type(2)');
//$I->waitForElement('#dialogbox2 h2');
$I->wait(1);
$I->see('Leave Game','#dialogbox2 h2');
$I->see('Game History','#dialogbox2 button:nth-of-type(1)'); //Game History button
$I->see('Lobby','#dialogbox2 button:nth-of-type(2)'); //Lobby button
$I->click('#dialogbox2 button:nth-of-type(2)');
$I->switchToIFrame();

$I->waitForElementVisible('.header-image',30);


?>