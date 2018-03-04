<?php
require 'tests/acceptance/_bootstrap.php';

// @group general
// @group casino

$I = new AcceptanceTester($scenario);

$I->wantTo('Mobile Realistic - Black Jack');

$I->amOnPage('/');
$I->resizeWindow(412, 732); // Nexus5 size

$I->amGoingTo('Verify elements on Landing page');
$I->waitForElementVisible('.cta',30);

$I->amGoingTo('Open Login');
$I->click('.cta');
$I->waitForElement('.login-button-link',30);

//Login
$I->amGoingTo('Log in');
$I->fillField('.form-item-username input',$gamestestuser);
$I->fillField('.form-item-password input',$gamestestuserPass);
$I->click('.login-button-link');


$I->waitForElementVisible('.login-modal-continue-container',30); //Wait for popup to appear
$I->click('.login-modal-continue-container');

start:
//Get Balance
$I->waitForElementVisible('.tradingbalance',30);
$startbalance=$I->grabTextFrom('.tradingbalance');
$startbalance= filter_var($startbalance, FILTER_SANITIZE_NUMBER_FLOAT); //get actual balance in Cents
$I->amGoingTo("Starting Balance is £".($startbalance/100));

//Check if there is enough balance
if($startbalance < 100){ //If balance is less than £1
	$I->redText("The balance is low. Please add money to the account");
	$I->see('Test failed due insufficient balance');
}

$I->amGoingTo("Navigate to Casino");

$I->waitForElementVisible('#casinoMenuBtnId',30); //Wait for casino button
$I->click('#casinoMenuBtnId');
$I->waitForElementVisible('.views-fluid-grid li:nth-of-type(1)',30); //wait for the first game

$I->amGoingTo("Find required game");
$I->waitForElementVisible('#searchGames input',30); //wait for search bar
$I->fillField('#searchGames input','blackjack');
$I->wait(3);

$li=0; //Variable to 0 to start from the 1st game
$tabID=$I->grabAttributeFrom('.tab-content.active','id'); //grab the currently selected Casino TAB ID
$I->amGoingTo('Currently active Casino TAB ID: '.$tabID);
$I->waitForElementVisible('#'.$tabID.' .views-row:nth-of-type(1) .casino-icon-high-resolution',30); //wait for the first game in the selected TAB

do{
	$li++; //increase li before grabbing text to start from 1st 
	$gamename=$I->grabTextFrom('#'.$tabID.' .views-fluid-grid li:nth-of-type('.$li.') .game-name');
	} while($gamename!="Blackjack");

	
$I->amGoingTo("Launch Blackjack game");
$I->wait(3);
$I->click('#'.$tabID.' .views-fluid-grid li:nth-of-type('.$li.')'); // open game
$I->waitForElementVisible('#mobile-casino-dialog[style=""] .mobile-casino-real-money-button',30);
$I->click('#mobile-casino-dialog[style=""] .mobile-casino-real-money-button .ui-btn-text');


$previous=false; // game is not finished from previous session
$ending=false;  //

$I->waitForElementVisible('#btn_back1',60); // Wait while game will be loaded

try{ //Check if a previous game is pending		

	global $previous;
	$I->waitForElementVisible('#resumeButton',2);
	$I->click('#resumeButton');
	$previous=true;
	$startbalance=$startbalance+100; //If game is resumed game was already paid.
	$I->greenText("The game was resumed");

}catch(Exception $E){
	
}

$I->amGoingTo("Play the Game");

if(!$previous){ //If game is fresh then place a bet

	$I->waitForElementVisible('#betbox2',30);
	$I->wait(2);
	$I->click('#betbox2');
	$I->wait(1);
	$I->click('#btnDeal');
	
}

$I->wait(5);


//Click on hit while score is less than 17

$score=$I->grabTextFrom('#boxValue_p2');
$I->amGoingTo("Initial Score is ".$score);
$hit=0;
if($score<=16){
	
	do{
		
		global $score;
		
		try{ //Check if HIT is available, if not, try to click on NO Split
			
			global $score,$hit;
			$I->waitForElementVisible('#btnHit',5);
			$I->wait(2);
			$I->click('#btnHit');
			$hit++;
			$I->wait(1);
			$score=$I->grabTextFrom('#boxValue_p2');
			$I->amGoingTo("Score After Hit #".$hit." is ".$score);
			
		} catch(Exception $e){
			
			try{ // if user is Busted
				global $score;
				global $ending;
				$I->waitForElementVisible('#btnRepeatDeal',5);
				$ending=true;
				$I->wait(1);
				$score=$I->grabTextFrom('#boxValue_p2');
				goto finish;
				
			}catch(Exception $f){
				
			}
			
			try{ // checks if SPLIT option is available
				global $score;
				$I->waitForElementVisible('#btnSplitNo',5);
				$I->click('#btnSplitNo');
				$I->wait(2);
				$score=$I->grabTextFrom('#boxValue_p2');
				
			}catch(Exception $a){}
			
			try{  // checks if INSURANCE option is available
				global $score;
				$I->waitForElementVisible('#btnInsurYes',5);
				$I->click('#btnInsurNo');
				$I->wait(1);
				$score=$I->grabTextFrom('#boxValue_p2');
				
			}catch(Exception $f){
				
			}
			
			try{  // checks if EVENS option is available
				
				
				$I->waitForElementVisible('#btnEvenMoneyNo',5);
				$I->click('#btnEvenMoneyNo');
				$I->waitForElementVisible('#btnStand',5);
				$I->click('#btnStand');
								
			}catch(Exception $e){}
			
			try{  // checks if Warning is displayed
				global $score;
				$I->waitForElementVisible('#w_container',5);
				$I->click('#w_btnDone');
				$I->wait(1);
				$score=$I->grabTextFrom('#boxValue_p2');
				
			}catch(Exception $f){
				
			}

		
		}
		
		
	
	} while($score<=16);

}else{
	
	$I->wait(2);
}

if($score>=22){
	$ending=true;
}

$iteration=false;
ending:
if(!$ending){
	
	try{
		$I->wait(5);
		$I->click('#btnStand');
		
	
	}catch(Exception $i){

		try{ // checks if INSURANCE option is available
				global $iteration;
				$I->waitForElementVisible('#btnInsurYes',5);
				$I->click('#btnInsurNo');
				$iteration=true;
				$I->waitForElementVisible('#btnStand',5);
				$I->click('#btnStand');
				
		}catch(Exception $f){
							
		}
			
		try{  // checks if SPLIT option is available
				
				
				$I->waitForElementVisible('#btnSplitNo',5);
				$I->click('#btnSplitNo');
				$I->waitForElementVisible('#btnStand',5);
				$I->click('#btnStand');
								
		}catch(Exception $a){}
		
		try{  // checks if EVENS option is available
				
				
				$I->waitForElementVisible('#btnEvenMoneyNo',5);
				$I->click('#btnEvenMoneyNo');
				$I->waitForElementVisible('#btnStand',5);
				$I->click('#btnStand');
								
		}catch(Exception $e){}
		
			
		try{  // checks if Warning is displayed
			global $score;
			$I->waitForElementVisible('#w_container',5);
			$I->click('#w_btnDone');
			$I->wait(1);
			$score=$I->grabTextFrom('#boxValue_p2');
			
		}catch(Exception $f){}
			
			global $tireration;
			
			if(!$iteration){
			
				$iteration=true;
				goto ending;
				
			}
	}
}
		

finish:

$I->waitForElementVisible('#btnRepeatDeal',30);
$paid=$I->grabTextFrom('#bet_val');

$I->amGoingTo('Winning value: '.$paid);

if($paid!="£2.00" && $paid!="£2.50"){
	
	$won=false;
	$mess="Lost";
	
}else{
	
	$won=true;
	$mess="Won";
}

//Show Message with Lost or Win
$I->amGoingTo('YOU '.$mess);

//Finish the Game 
$I->wait(2);
$I->waitForElementVisible('#btn_back1',30);
$I->click('#btn_back1');

//Go to HomePage
$I->waitForElementVisible('.header-image',30);
$I->waitForElementVisible('.views-fluid-grid',30);
$I->waitForElementVisible('#AllSportsAnchor',30);


//TEMPORARY SOLUTION FOR KNOW LIVE ISSUE
//$I->click('#AllSportsAnchor'); 
$I->amOnPage('/home'); 


//Get Balance
$I->waitForElementVisible('.tradingbalance',30);
$endbalance=$I->grabTextFrom('.tradingbalance');
$endbalance= filter_var($endbalance, FILTER_SANITIZE_NUMBER_FLOAT); //get actual balance in Cents
$I->amGoingTo("Ending Balance is £".($endbalance/100));

$diff=$endbalance-$startbalance; // Difference between starting balance and end balance
$I->amGoingTo("Difference is £".($diff/100));

$paid= filter_var($paid, FILTER_SANITIZE_NUMBER_FLOAT); //get Paid amount in Cents
$paid = $paid-100;

if($won){
	
	if($diff==$paid){
		
		$I->greenText("You Won and Balance was updated correctly. Winnings are: £".($paid/100));
		
	}else{
		
		$I->redText("You Won, but Balance was not updated properly. Winnings are: £".($paid/100));
		$I->see("Failed in Balance Comparison");
	}
	
}else{
	
	if($diff<=-1){
		
		$I->greenText("You Lost and Starting Balance was greater than Ending one by £".($diff/100)*(-1));
		
	}else{
		
		if($diff==0){goto start;} //If difference is 0 it can be due to draw result so play again.
		
		$I->redText("You Lost, but Balance was not updated properly. Balance difference is £".($diff/100));
		$I->see("Failed in Balance Comparison");
	}
	
}

//LogOut

$I->amGoingTo("Log Out");
$I->click("Account",'.menu-nav-links.menu_table');

$I->waitForElementVisible('#logoutId');
$I->click("#logoutId");

$I->waitForElementVisible(".urlAftLogin");


?> 