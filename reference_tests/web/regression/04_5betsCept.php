<?php
//shell_exec('RunDll32.exe InetCpl.cpl,ClearMyTracksByProcess 2'); // Clear cookies and cache
require 'tests/acceptance/_bootstrap.php';
global $races;
$races=array();
global $meetings;

// @group general

$I = new AcceptanceTester($scenario);
$I->wantTo('Place a bet on 5 greyhounds races, all singles and multiples');

$I->amOnPage('/');
$I->maximizeWindow();

$I->amGoingTo('Verify elements on Landing page');
$I->waitForElementVisible('[title="Sports"]',30);

$I->amGoingTo('Open Sports page'); 
$I->click('[title="Sports"]'); //click on Sports tab
$I->waitForElement('iframe',30); //wait for Twitter iframe

$serverid = $I->grabTextFrom('.hiddensrv'); // Get Server ID
$I->greenText('Server ID is: '.$serverid);

$I->weblogin($username_01,$password_01);

$I->amGoingTo('Click on Greyhounds');
$I->click('Greyhounds', 'li ul.leftnav_icons.showdefault');
$I->waitForElementVisible('.racecard_time_item',90); //wait for navigation tabs to be visible

// To count number of meetings
global $x;
$meetings = $I->elementsArray('.today'); 
	for ($x=3;$x<=sizeof($meetings)+1;$x++){ //Start from 3 in greyhounds (First real race excluding 5 next meetings line)

		$races[$x] = $I->elementsArray('.today:nth-of-type('.$x.') .racecard_time_item'); // To count number of races in each meeting
	}

$I->amGoingTo('Click on last races');


$s=0; // number of selected races
$i=0; // number of iterations if there is not enough different races

do{

	for($x=3;$x<=sizeof($meetings)+1;$x++){
	
		if((sizeof($races[$x])>=3) && $s<=4){
			$I->click('.today:nth-of-type('.$x.') .racecard_time_item:nth-of-type('.(sizeof($races[$x])-$i).') input'); 
			$s=$s+1;
		}
	}
     $i=$i+1;
}while($s<=4);

//$I->pauseExecution();

$I->click('.racetime_racename_wrapper_link.btn_orange');

$I->amGoingTo('select the first horse of each race');
$dognames=array(); //Declare Array to store Dog Names

$I->waitForElementNotVisible('.racetime_racename_wrapper div',30);
$I->waitForElement('.racecardracemeetingcontainer span',30);

try{
$I->waitForElementVisible('.main div:nth-child(21).horse-racing .tabs:nth-child(1) tr.row_large.solidborder:nth-child(1)',3);
$rac=1;
}catch (Exception $e){
$I->waitForElementVisible('.main div:nth-child(21).horse-racing .tabs:nth-child(3) tr.row_large.solidborder:nth-child(1)',3);
$rac=3;
}
/*
$racename[1]=$I->grabTextFrom('.main div:nth-child(21) .tabs:nth-child('.$rac.') .racecardracemeetingcontainer .selectbox_cloned');
$racetime[1]=$I->grabTextFrom('.main div:nth-child(21) .tabs:nth-child('.$rac.') .racecardracetimescontainer .selectbox_cloned');
$dognames[1]=$I->grabTextFrom('.tabs:nth-child('.$rac.') .tbodywinnerclick tr:nth-child(1) .selection');
$I->wait(1);
$I->click('.main div:nth-child(21).horse-racing .tabs:nth-child('.$rac.') tr.row_large.solidborder:nth-child(1) [type="price-column"] span');
$I->wait(1);
*/

for ($race=($rac);$race<=($rac+8);$race+=2){
	
	//$I->executeJS('document.querySelector(".tabs:nth-child('.$race.') tr:nth-child(3) a.selectionclick").scrollIntoView(false)');
	$I->scrollTo('.tabs:nth-child('.$race.') .tbodywinnerclick tr:nth-child(1) .selection',0,-300); // scroll to make racecard visible
	$racename[$race]=$I->grabTextFrom('.main div:nth-child(21) .tabs:nth-child('.$race.') .racecardracemeetingcontainer .selectbox_cloned');
	$racetime[$race]=$I->grabTextFrom('.main div:nth-child(21) .tabs:nth-child('.$race.') .racecardracetimeshide');
	$dognames[$race]=$I->grabTextFrom('.main div:nth-child(21) .tabs:nth-child('.$race.') .tbodywinnerclick tr:nth-child(1) .selection h5');
	$I->amGoingTo('Current Selection: '.$dognames[$race].' in '.$racename[$race].' race at '.$racetime[$race]);
	
//	$I->pauseExecution();
	
	$I->wait(1);
	$I->waitForElementVisible('.main div:nth-child(21).horse-racing .tabs:nth-child('.$race.') tr.row_large.solidborder:nth-child(1) [type="price-column"] span',30);
	$I->click('.main div:nth-child(21).horse-racing .tabs:nth-child('.$race.') tr.row_large.solidborder:nth-child(1) [type="price-column"] span');
	$I->wait(1);

}

//$I->pauseExecution();

//$I->executeJS('document.querySelector(".bet_selection_normal_state:nth-child(1) .ui-spinner-input").scrollIntoView(false)');

for ($bet=1;$bet<=9;$bet+=2){
	
	//a workaround to readd the bet value, because sometimes it clears down by itself
	a:
	try{
		$I->waitForElementVisible('.bet_selection_normal_state:nth-child('.$bet.') input.ui-spinner-input',5);
		$I->fillField('.bet_selection_normal_state:nth-child('.$bet.') input.ui-spinner-input','0.01');
		$value=$I->grabValueFrom('.bet_selection_normal_state:nth-child('.$bet.') input.ui-spinner-input');
		//$I->amGoingTo($value);
		if($value<>0.01){goto a;}
	}catch(Exception $e){
		goto a;
	}
}

for ($multibet=1;$multibet<=9;$multibet++){
	
	//a workaround to readd the bet value, because sometimes it clears down by itself
	b:
	try{
		$I->waitForElementVisible('.multiple_bet_border:nth-child('.$multibet.') .bets-stake input',5);
		$I->fillField('.multiple_bet_border:nth-child('.$multibet.') .bets-stake input','0.01');
		$value=$I->grabValueFrom('.multiple_bet_border:nth-child('.$multibet.') .bets-stake input');
		//$I->amGoingTo($value);
		if($value<>0.01){goto b;}
	}catch(Exception $e){
		goto b;
	}
}

$I->amGoingTo('Check the balance before placing a bet');
$I->click('.accountbalancerefresh');
$funds=$I->grabTextFrom('span.balance_visible');
$funds= filter_var($funds, FILTER_SANITIZE_NUMBER_FLOAT); //get actual balance in Cents
$I->amGoingTo('Actual Funds => '.$funds); //Shows Funds
$I->amGoingTo('Check Total Stake Amount');
$tstake=$I->grabTextFrom('.bet_placement span');
$tstake= filter_var($tstake, FILTER_SANITIZE_NUMBER_FLOAT); 
$I->amGoingTo('Total Stake Amount => '.$tstake); 


//$I->executeJS('document.querySelector("a.place_bet").scrollIntoView(false)');
$I->scrollTo('a.place_bet',0,-200); // Scroll to see the place bet button
$I->amGoingTo('Place the bet');
$I->click('a.place_bet');

//$I->pauseExecution();
try{

	$I->waitForElementVisible('[title="Confirm Bets"]',3); //wait for Confirm Button
	//$I->executeJS('document.querySelector("a.place_bet").scrollIntoView(false)');
	$I->scrollTo('a.place_bet',0,-200); // Scroll to see the place bet button
	$I->wait(1);
	
	for ($race=1;$race<=9;$race+=2){
		$I->see($dognames[$race],'div[id$="confirmation-required-before-bet-slip"] .bet_selections');
	}
	
	$I->click('[title="Confirm Bets"]');

}catch(Exception $e){

}

//$I->pauseExecution();

$I->waitForElementVisible('[title="Continue"]',30);
//$I->executeJS('document.querySelector("a.place_bet").scrollIntoView(false)');
$I->scrollTo('a.place_bet',0,-200); // Scroll to see the place bet button

for ($race=1;$race<=9;$race+=2){
	$I->see($dognames[$race],'div[id$="placed-bet-slip"] .bet_selections');
}

$I->click('[title="Continue"]');





$I->amGoingTo('Check Stake is deducted from Funds');
$I->click('.accountbalancerefresh');
$afunds=$I->grabTextFrom('span.balance_visible');
$afunds= filter_var($afunds, FILTER_SANITIZE_NUMBER_FLOAT); //get actual balance in Cents

$I->see((($funds-$tstake)/100),'span.balance_visible'); //check Stake is deducted from funds

$I->amGoingTo('Check Statement');
$I->waitForElement('[title="Display account details"]',30); //wait for Bet Slider to be visible
$I->click('[title="Display account details"]');
$I->waitForElementVisible('.accountnumberselection li:nth-child(3) a',30); //wait for Open Bets to be visible
$I->click('.accountnumberselection li:nth-child(3) a');
$I->waitForElementVisible('.statement-history',30); //wait for Open Bets to be visible
	
for ($race=1;$race<=9;$race+=2){
	$I->see($dognames[$race],'.statement-history');
	$I->see($racename[$race].' '.$racetime[$race],'.statement-history');
}

$I->weblogout();

$I->greenText('</br>Funds before bet => £'.($funds/100).'<br>Funds After Bet => £'.($afunds/100).'</br>Total stake => £'.($tstake/100));

?> 