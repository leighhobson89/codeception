<?php
require 'tests/acceptance/_bootstrap.php';

// @group general
// @group selfExclusion

$I = new AcceptanceTester($scenario);
$I->wantTo('Load Bingo and ensure user is Excluded');

$I->amOnPage('/');
$I->maximizeWindow();

$I->amGoingTo('Verify elements on Landing page');
$I->waitForElementVisible('a.loginbtn',30); // Wait for Login button

$I->amGoingTo('Open Bingo page'); 
$I->click('[title="Bingo"]'); //click on Bingo tab
$I->waitForElement('#bingoiframe',30); //wait for Bingo iframe

$I->weblogin($excludedtestuser,$excludedtestPass);

$serverid = $I->grabTextFrom('.hiddensrv'); // Get Server ID
$I->greenText('Server ID is: '.$serverid);

$I->waitForElementVisible('span.balance_visible',30);

$I->waitForElement('#bingoiframe',30); //wait for Bingo iframe
$I->switchToIFrame('bingoiframe'); // Swithching to Bingo iframe

$I->waitForElement('#rightSide',30); //wait for Bingo right Panel
$I->waitForElement('#lobbyHeader',30); //wait for Bingo lobby header
$I->waitForElement('#gamesChooser',30); //wait for Bingo games choose

$I->dontSeeElement('.balanceDisplay .changed'); // No balance in Bingo iframe

$I->amGoingTo('Try play Mini Game'); 
$I->click('.miniGames .item:nth-of-type(1)'); //click on 1st Minigame
$I->wait(3);
$I->dontSeeElement('#sideGame'); // Mini game is not launched

$I->amGoingTo('Try to open Bingo Game');
$I->waitForElementVisible('.roomTile:nth-child(1) .atvImg-shine',30); //wait for Bingo lobby
$I->click('.roomTile:nth-child(1) .atvImg-shine');
$I->wait(3);
$I->dontSeeElement('#jackpotDisplay'); // Jackpot popup is not available
$I->dontSeeElement('#ticketControls'); // Bingo ticket control panel is not available
$I->dontSeeElement('#loyaltyMeter'); // Bingo loyalty Meter is not available
$I->dontSeeElement('#scroller > div:nth-child(1)'); //Bingo ticket is not available

$I->switchToIFrame(); // Swithching out of iframe

$I->weblogout();	
		
?> 