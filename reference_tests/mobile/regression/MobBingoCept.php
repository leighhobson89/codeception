<?php
require 'tests/acceptance/_bootstrap.php';

// @group general

$I = new AcceptanceTester($scenario);

$I->wantTo('Play a Bingo round on mobile device');

$I->amOnPage('/');
$I->resizeWindow(412, 732); // Nexus5 size

$I->amGoingTo('Verify elements on Landing page');
$I->waitForElementVisible('.cta',30);

$I->amGoingTo('Open Login');
$I->click('.cta');
$I->waitForElement('.login-button-link',30);

//Login
$I->amGoingTo('Log in');
$I->fillField('.form-item-username input',$usermobbingo);
$I->fillField('.form-item-password input',$passmobbingobingo);
$I->click('.login-button-link');

$I->waitForElementVisible('.login-modal-continue-container',30); //Wait for popup to appear
$I->click('.login-modal-continue-container');

//Get Balance
$I->waitForElementVisible('.tradingbalance',30);
$funds=$I->grabTextFrom('.tradingbalance');
$funds= filter_var($funds, FILTER_SANITIZE_NUMBER_FLOAT); //get actual balance in Cents
$I->amGoingTo("User Balance is £".($funds/100));

//Open Bingo
$I->amGoingTo('Open Bingo');
$I->waitForElementVisible('#bingoMenuBtnId',30);
$I->click('#bingoMenuBtnId');

//Verify Bingo page
$I->waitForElementVisible('.balanceDisplay',30);
$I->waitForElementVisible('.roomTile:nth-child(1) .atvImg-shine',30);
$I->wait(3);

$fundsBingo=$I->grabTextFrom('.balanceDisplay');
	
	if (strpos($fundsBingo, '.') !== false) {
		$fundsBingo=filter_var($fundsBingo, FILTER_SANITIZE_NUMBER_FLOAT); //get actual Bingo lobby balance in Cents
	}else{
		$fundsBingo=filter_var($fundsBingo, FILTER_SANITIZE_NUMBER_FLOAT); //get actual Bingo lobby balance in Cents
		$fundsBingo = $fundsBingo*100;
	}

$I->amGoingTo('Actual Funds => '.($funds/100).' and Actual Bingo Lobby funds => '.($fundsBingo/100));

checkTimer: // to come back if the game round is about to start
$roundTimer=$I->grabTextFrom('.roomTile:nth-child(4) .timerField');
$roundTimer=filter_var($roundTimer, FILTER_SANITIZE_NUMBER_FLOAT); //get round time in seconds

	if ($roundTimer > 10){
		$I->amGoingTo($roundTimer.' left until Bingo round start. Buying a ticket.');
	} else{
		$I->amGoingTo($roundTimer.' left until Bingo round start. Waiting for new round.');
		$I->wait(5);
		goto checkTimer;
	}

//check if the current round is free and if it is, wait until next round
$roundPrice=$I->grabTextFrom('.roomTile:nth-child(4) .ticketPriceField');	
$roundPrice=filter_var($roundPrice, FILTER_SANITIZE_NUMBER_FLOAT); //get round time in seconds

	if ($roundPrice > 1){
		$I->amGoingTo('The ticket is NOT for FREE. Buying a ticket.');
	} else{
		$I->amGoingTo('Bingo ticket is for FREE. Waiting for new round.');
		$I->wait(5);
		goto checkTimer;
	}
	
	
	
//Open Bingo room
$I->amGoingTo('Open Bingo room');
$I->waitForElementVisible('.roomTile:nth-child(4) .atvImg-shine',30);
$I->click('.roomTile:nth-child(4) .atvImg-shine');

//Check if Game is inplay already
	try{
		$I->waitForElementVisible('.timerField',60);
		
	}catch(Exception $gameIsInPlay){
		
		$I->amGoingTo('Current Users is already playing in this room. Wait a bit and try again later.');
		$I->waitForElementNotVisible('.ticket.mobile.bought',90);
	}	
		
	try{
		$I->waitForElementVisible('#winSummaryDisplayMobile .closeButton',2);
		$I->click('#winSummaryDisplayMobile .closeButton');
		
	}catch(Exception $noSumamry){
		
	}

	$I->waitForElementVisible('.timerField',30);
	$I->waitForElementVisible('.header',30);
	$I->waitForElementVisible('.ticketPriceField',30);
	$I->waitForElementVisible('.total',30);
	$I->waitForElementVisible('.buyButton',30);


$I->amGoingTo('buy one Bingo ticket');
$ticketPrice=$I->grabTextFrom('.ticketPriceField');
$roomName=$I->grabTextFrom('.header');

//$I->click('.ticketBuyKeyboardButton:nth-child(1)');
//$I->wait(1);
//$I->see('1','#mobileTicketCountInput');
$I->see('Total '.$ticketPrice,'.total');

$I->click('.buyButton');
//$I->waitForText('ticket(s) purchased / '.$ticketPrice,10,'#ticketsPurchasedField');
$I->waitForText('You have bought 1 ticket.',10,'.box-msg');
$I->wait(2);

$I->amGoingTo('Check Bingo balance');
$fundsBingoAfter=$I->grabTextFrom('.balanceDisplay');


	if (strpos($fundsBingoAfter, '.') !== false) {
		$fundsBingoAfter=filter_var($fundsBingoAfter, FILTER_SANITIZE_NUMBER_FLOAT); //get actual Bingo lobby balance in Cents
	}else{
		$fundsBingoAfter=filter_var($fundsBingoAfter, FILTER_SANITIZE_NUMBER_FLOAT); //get actual Bingo lobby balance in Cents
		$fundsBingoAfter = $fundsBingoAfter*100;
	}

$I->amGoingTo('Actual Funds => £'.$fundsBingoAfter/100); //Shows Bingo Funds

$diff = $fundsBingo - $fundsBingoAfter; // balance difference before purchase and after
$ticketPriceSanitized=filter_var($ticketPrice, FILTER_SANITIZE_NUMBER_FLOAT); 


	if ($diff == $ticketPriceSanitized){
		$I->greenText('Bingo Balance was updated correctly');
	} else{
		$I->redText('Bingo Balance was NOT updated correctly');
		$I->see('TERMINATE TEST DUE FAILURE');
	}


//LogOut
$I->amGoingTo('Log Out');
$I->wait(1);
$I->wait(1);
$I->click('#backButton');
$I->wait(1);
$I->click('#backButton');
$I->waitForElementVisible('#MyAccountAnchor',30);

$I->click('Account','.menu-nav-links.menu_table');

$I->waitForElementVisible('#logoutId',30);
$I->click('#logoutId');

$I->waitForElementVisible('.urlAftLogin',30);

$I->amGoingTo('RESULTS');
$I->greenText('Bingo ticket was bought for '.$ticketPrice.' in '.$roomName.' room.');

?> 
