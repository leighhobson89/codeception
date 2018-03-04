<?php
require 'tests/acceptance/_bootstrap.php';

// @group general

$I = new AcceptanceTester($scenario);

$I->wantTo('Place a bet on Mobile');

$I->amOnPage('/');
$I->resizeWindow(412, 732); // Nexus5 size

$I->amGoingTo('Verify elements on Landing page');
$I->waitForElementVisible('.cta',30);

$I->amGoingTo('Open Login');
$I->click('.cta');
$I->waitForElement('.login-button-link',30);

//Login
$I->amGoingTo('Log in');
$I->fillField('.form-item-username input',$username_04);
$I->fillField('.form-item-password input',$password_04);
$I->click('.login-button-link');

$I->waitForElementVisible('.login-modal-continue-container',30); //Wait for popup to appear
$I->click('.login-modal-continue-container');

//Get Balance
$I->waitForElementVisible('.tradingbalance',30);
$startbalance=$I->grabTextFrom('.tradingbalance');
$startbalance= filter_var($startbalance, FILTER_SANITIZE_NUMBER_FLOAT); //get actual balance in Cents
$I->amGoingTo("User Balance is £".($startbalance/100));

//Calculate available markets under Featured Events
$sports=$I->executeJS('return document.querySelectorAll("#homepage > div.content-header").length;'); //calculate available sports on Featured events
$sport=rand(1,$sports); // Select Random Sport Number
$I->amGoingTo('Counted: '.$sports.' sports available under Featured Events. I randomly selected: '.$sport);
$sport = $sport*2; // getting correct child number for the selector

$markets=$I->executeJS('return document.querySelectorAll("#homepage > div:nth-child('.$sport.') .sport-event-name").length;'); // calculate available markets number under selected sport
$market=rand(1,$markets); // Select Random Market
$I->amGoingTo('Counted: '.$markets.' markets available under selected Sport. I randomly selected: '.$market.' market.');
$market = 2+(($market-1)*3); // getting correct child number for the selector

$I->waitForElementVisible('#homepage > div:nth-child('.$sport.') > .ajaxWrapper:nth-child('.$market.') .sport-event-name',30);
$I->click('#homepage > div:nth-child('.$sport.') > .ajaxWrapper:nth-child('.$market.') .sport-event-name');

$I->amGoingTo('Open Selected Event page');
$I->waitForElementVisible('div.content-header > h1',30);
$I->waitForElement('.innertube',30);
$I->wait(3);


$eventName = $I->grabTextFrom('div.content-header > h1'); // Grab Event title

$selections=$I->executeJS('return document.querySelectorAll(".event-content-expand .quick-bet-item").length;'); //calculate available selections on selected Event
	
	if ($selections != 0){ // it is general sport coupon
		
		$selection = rand(1,$selections); // Select Random Selection
		$I->waitForElementVisible('.event-content-expand .quick-bet-item',2);
		$selectionName = $I->grabTextFrom('.event-content-expand .quick-bet-item:nth-child('.$selection.') .bet-select-name');
		$selectionPrice = $I->grabTextFrom('.event-content-expand .quick-bet-item:nth-child('.$selection.') .bet-select-hcp:nth-child(3)');

		$I->greenText('Selected '.$selectionName.' selection at '.$eventName.' event for '.$selectionPrice.' price.');
		$I->click('.event-content-expand .quick-bet-item:nth-child('.$selection.')');
		$I->waitForElementVisible('.betslip-container',30);
		
	}else{
		
		$selections=$I->executeJS('return document.querySelectorAll(".bet-item").length;'); //calculate available selections on selected Event
					
		if ($selections != 0){ // it is horses racecard
		
			$selection = rand(1,$selections); // Select Random Selection
			$I->waitForElementVisible('.bet-selection-wrapper',2);
			$selectionName = $I->grabTextFrom('.bet-selection-wrapper:nth-child('.$selection.') .bet-item .content-horce-racing .racecard-name');
			$selectionPrice = $I->grabTextFrom('.bet-selection-wrapper:nth-child('.$selection.') .bet-item #rightcolumn');
			
			$I->greenText('Selected '.$selectionName.' horse at '.$eventName.' race for '.$selectionPrice.' price.');
			$I->click('.bet-selection-wrapper:nth-child('.$selection.')');
			$I->waitForElementVisible('.betslip-container',30);
			
		}else{ // it is outrights page
		
			$selections=$I->executeJS('return document.querySelectorAll(".innertube").length;'); //calculate available selections on selected Event
			$selection = rand(1,$selections); // Select Random Selection
			$selectionName = $I->grabTextFrom('.bet-selection-wrapper:nth-child('.$selection.') .innertube');
			$selectionPrice = $I->grabTextFrom('.bet-selection-wrapper:nth-child('.$selection.') #rightcolumn');
			
			$I->greenText('Selected '.$selectionName.' selection at '.$eventName.' outright event for '.$selectionPrice.' price.');
			$I->click('.bet-selection-wrapper:nth-child('.$selection.') .innertube');
			$I->waitForElementVisible('.betslip-container',30);

		}
	
	}

//Open BetSlip
$I->amGoingTo('Open BetSlip');
$I->click('.betslip-container');	
$I->waitForElementVisible('.bet-slip-button-place.form-place',30); // wait for Place bet field
$I->waitForElementVisible('.bet-slip-stake-field',30); // wait for Stake field

$I->see($selectionName,'.betslip-selectionName-text'); //Verify the Selection name in Betslip
$I->see($selectionPrice,'.bet-slip-item-container .bet-slip-odds'); //Verify the Price in Betslip

$I->amGoingTo('Add stake and place a bet');
$bet = ".01";
$I->fillField('.bet-slip-stake-field',$bet); // add stake
$I->wait('2');
$I->see($bet,'.total-stake-value'); //Verify Total Stake is correct

$I->click('.bet-slip-button-place.form-place'); // Place a bet
$I->waitForText('Bet placed',30,'#betslip-receipt .bet-receipt-header'); // wait for bet confirmation text
$I->see('Bet placed, reference','#betslip-receipt .bet-receipt-header');
$I->see($selectionName,'.bet-slip-receipt-bet-details .selection-name'); //Verify the Selection name in Bet Confirmation page
$I->see($selectionPrice,'.bet-slip-receipt-price'); //Verify the Price in Bet Confirmation page
$I->see($bet,'.bet-receipt-total-stake-container .bet-total-stake'); //Verify Total Stake is correct 
$I->see($bet,'.bet-receipt-stake'); //Verify Total Stake is correct 

//Get Balance
$I->waitForElementVisible('.tradingbalance',30);
$endbalance=$I->grabTextFrom('.tradingbalance');
$endbalance= filter_var($endbalance, FILTER_SANITIZE_NUMBER_FLOAT); //get actual balance in Cents

//Check if the balance was correctly updated
$I->amGoingTo('Check if Balance was updated correctly');
if($startbalance-($bet*100)==$endbalance){
	$endbalance=$endbalance/100;
	$endbalance=number_format($endbalance, 2, '.', ',');
	$I->amGoingTo('Balance after bet placement => '.$endbalance);
	$I->see($endbalance,'.tradingbalance');
	$I->amGoingTo('The balance was correctly updated');
}Else{
	$I->amGoingTo('The balance was not correctly updated, Balance before place Bet=> '.$startbalance.' , bet placed was=> '.$bet.' and Balance after placing the bet is=> '.$endbalance);
}

//Check bet history
$I->amGoingTo('Check Bet History');
$I->click('Account','.menu-nav-links.menu_table');

$I->waitForElementVisible('.account-image-bet-history',30);
$I->click('.account-image-bet-history');
$I->waitForElement('#radioBets',30);
$I->waitForElementVisible('.OpenBet',30);

$I->see($selectionName,'.last-10 .bet-history-date-item:nth-child(2) .history-name'); //Verify the Selection name in Bet History
$I->see($selectionPrice,'.last-10 .bet-history-date-item:nth-child(2) .history-name'); //Verify the Price in Bet History
$I->see($bet,'.last-10 .bet-history-date-item:nth-child(2) .bet-history-stake'); //Verify Stake is correct in Bet History

//LogOut
$I->amGoingTo('Log Out');
$I->wait(1);
$I->click('Account','.menu-nav-links.menu_table');

$I->waitForElementVisible('#logoutId',30);
$I->click('#logoutId');

$I->waitForElementVisible('.urlAftLogin',30);

?> 
