<?php
require 'tests/acceptance/_bootstrap.php';

// @group general
// @group selfExclusion

$I = new AcceptanceTester($scenario);
$I->wantTo('Ensure Self Excluded player can\'t Place a Bet');

$I->amOnPage('/');
$I->maximizeWindow();

$I->amGoingTo('Verify elements on Landing page');
$I->waitForElementVisible('a.loginbtn',30); // Wait for Login button

$I->amGoingTo('Open Sports page'); 
$I->click('[title="Sports"]'); //click on Sports tab
$I->waitForElement('iframe',30); //wait for Twitter iframe

$serverid = $I->grabTextFrom('.hiddensrv'); // Get Server ID
$I->greenText('Server ID is: '.$serverid);

$I->weblogin($excludedtestuser,$excludedtestPass);

$funds=$I->grabTextFrom('span.balance_visible');
$funds=filter_var($funds, FILTER_SANITIZE_NUMBER_FLOAT); //get actual balance in Cents
$I->amGoingTo('Actual Funds => '.$funds); //Shows Funds

$I->amGoingTo('Count available markets on Homepage higlights section');

getNewColumn: //try again if TableData does not exist
$selection=rand(3,5); // randomly select HOME, DRAW or AWAY column
$market=$I->executeJS('return document.querySelectorAll("tbody.master td:nth-child('.$selection.') a").length;'); // calculating total amount of events on Higlights
	
	if ($market == 0){ // in case there is no DRAW column
		goto getNewColumn;
	}

$I->amGoingTo('Counted: '.$market.' markets on Homepage Higlights');
$market = $market *2; //number of tbody element for market	
	
selectNewEvent: //try again if TableData does not exist

	try{	
		$market = rand(2,$market); // Selecting Random market 
		
		$I->seeElement('tbody.master:nth-child('.$market.') td.widthIE8:nth-child(2) > a');
		
	}catch(Exception $WrongMarketSelected){
		
		$I->amGoingTo('TableRow '.$market.' does not exist. Trying another one');
		goto selectNewEvent;
	}
	
	
$I->amGoingTo('Select a random bet from highlights section');
$eventName = $I->grabTextFrom('tbody.master:nth-child('.$market.') td.widthIE8:nth-child(2) > a');  // Get Event name
$betPrice = $I->grabTextFrom('tbody.master:nth-child('.$market.') td:nth-child('.$selection.') a'); // Get Price
$I->amGoingTo('Bet on event: '.$eventName.' and price: '.$betPrice);

//$I->scrollTo('tbody.master:nth-child('.$market.') td:nth-child('.$selection.') a',0,50); 
$I->seeElement('tbody.master:nth-child('.$market.') td:nth-child('.$selection.') a'); 
$I->click('tbody.master:nth-child('.$market.') td:nth-child('.$selection.') a'); 
	
$I->amGoingTo('Place the bet');
$bet=0.01; //amount to place in bet

$I->waitForElementVisible('.bet_selection input.ui-spinner-input',30); //wait for input box to be visible
$I->wait(1);
$I->scrollTo('.bet_selection input.ui-spinner-input',0,-200); 
$I->wait(1);
$selectionName = $I->grabTextFrom('div[id$="SinglesContainer"] .info_tooltip');
$I->see($selectionName,'div[id$="SinglesContainer"] .info_tooltip'); //Verify the Event name in Betslip
$I->see($betPrice,'.bet_selection_single_odds'); //Verify the Bet price

$I->amGoingTo('Add stake and confirm');
//$I->fillField('.bet_selections input.ui-spinner-input', $bet); //Write the amount
$I->pressKey('.bet_selections input.ui-spinner-input','0');
//$I->wait(1);
$I->pressKey('.bet_selection input.ui-spinner-input','.');
//$I->wait(1);
$I->pressKey('.bet_selection input.ui-spinner-input','0');
//$I->wait(1);
$I->pressKey('.bet_selection input.ui-spinner-input','1');
$I->click('a.place_bet');

/*
	try{
		$I->waitForElementVisible('div[id$="confirmation-required-before"] .place_bet',2); //wait for Confirm Button
		$I->see($bet,'div[id$="confirmation-required-before-bet-slip"] span.left'); //Verify the Bet amount
		$I->see($selectionName,'div[id$="confirmation-required-before-bet-slip"] .info_tooltip'); //Verify the Event name in Betslip
		$I->see($betPrice,'div[id$="confirmation-required-before-bet-slip"] .info_tooltip'); //Verify the Price in Betslip
		$I->see($bet,'.betslip_twitter_div .span_stake'); //Verify the Bet amount
		$I->see($bet,'div[id$="confirmation-required-before-bet-slip"] .bet_placement strong'); //Verify the Total Stake
		$I->click('div[id$="confirmation-required-before"] .place_bet');
	}catch(Exception $e){
	}
*/

$I->waitForElementVisible('div[id$="rejected-3-other"]',30); //wait for Error message to appear
$I->waitForElementVisible('div[id$="rejected-3-other"] .overlay_submit',30); //wait for Error message to appear
$I->see('Bet placement failed','div[id$="rejected-3-other"] .red_text');
$I->see('Player excluded from betting','span[id$="rejected-3-text"]');

$I->click('div[id$="rejected-3-other"] .overlay_submit');

$I->amGoingTo('Confirm that the bet was not placed and balance not changed');
$I->waitForElementNotVisible('div[id$="rejected-3-other"] .overlay_submit',30); //wait for Continue button not visible
$I->click('span.accountbalancerefresh'); // Refresh the balance



$fundsafter=$I->grabTextFrom('span.balance_visible');
$fundsafter= filter_var($fundsafter, FILTER_SANITIZE_NUMBER_FLOAT); //get actual balance in Cents

if($funds==$fundsafter){ //Check if the balance was NOT updated
	$I->amGoingTo('The balance was NOT updated');
}Else{
	$I->redText('The balance was updated!! User placed a bet!!');
}


//Check Open Bets on Slider
$I->amGoingTo('Check Bet Statement');
$I->waitForElement('.betslip_bets_number',30); //wait for Bet Slider to be visible
$I->click('.betslip_bets_number');
$I->waitForElementVisible('div#betslipdefault.tab_content',30); //wait for Open Bets to be visible
$I->seeElement('a.open_bets_link');
$I->click('a.open_bets_link');

$I->waitForText('You don\'t currently have any open bets',30,'div[id$="no-open-bets"]'); //wait for empty Open Bets panel

$I->weblogout();

?>