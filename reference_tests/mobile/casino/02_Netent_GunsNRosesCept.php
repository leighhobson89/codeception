<?php
require 'tests/acceptance/_bootstrap.php';

// @group general
// @group casino

$I = new AcceptanceTester($scenario);

$I->wantTo('Mobile Netent - GunsNRoses');

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
if($startbalance < 40){ //If balance is less than £0.4
	$I->redText("The balance is low. Please add money to the account");
	$I->see('Test failed due insufficient balance');
}

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


//$casinopage=1;
//findgame:
//try{	
		
//}catch(Exception $NoGameFound){
	
/*	$I->executeJS('document.querySelector(".stanjames-class:nth-of-type('.$casinopage.')").setAttribute("style","display: none;")'); //making Casino menu element hidden to make other tabs visible. Swiping should be implemented in teh future
	$casinopage++;
	$I->click('.stanjames-class:nth-of-type('.$casinopage.')'); //Activate next Casino tab
	$I->wait(1);
	$I->amGoingTo('Currently open Casino page: '.$casinopage);
	goto findgame;*/
//}


//$I->waitForElementVisible('#'.$tabID.'-content .views-row:nth-of-type(1) .casino-icon-high-resolution',30); //wait for the first game in the selected TAB

$I->amGoingTo("Launch Guns n Roses game");
$I->wait(3);
$I->click('#'.$tabID.' .views-fluid-grid li:nth-of-type('.$li.')'); // open game
$I->waitForElementVisible('#mobile-casino-dialog[style=""] .mobile-casino-real-money-button',30);
$I->click('#mobile-casino-dialog[style=""] .mobile-casino-real-money-button .ui-btn-text');

//Check the game is loaded

$I->waitForElementVisible('.interface-settingsButton_baseButton',60);

//Check for Bonus 

try{
		
	$I->waitForElementVisible('.interface-quickSettingsMenu_handle',2); //If spin button is present that means that bonus is finished and no need to click more
	$bonus=0;
	goto afterbonus;
				
}catch(Exception $NoSpinbutton){
	
}

$I->clickXY('#gameWrapper',100,100); //Get Full Screen
$I->wait(3);
$I->clickXY('#gameWrapper',600,500); //Click on Continue in Free Spins window
$I->wait(1);
$I->clickXY('#gameWrapper',600,530); //Click on Continue in Bonus round window
$I->wait(5);

$bonus=0;

bonus:
do{
	try{
		
		$I->waitForElementVisible('.interface-quickSettingsMenu_handle',1); //If spin button is present that means that bonus is finished and no need to click more
		$bonus=0;
		
		//Finish the game
		$I->click('#homeButton');
				
		//Go to HomePage
		$I->waitForElementVisible('.header-image',10);
		$I->click('.header-image');
			
		goto start;
		
	}catch(Exception $NoSpin){
		
		$bonus=1;
		$I->clickXY('#gameWrapper',400,290);
		$I->wait(0.5);
		$I->clickXY('#gameWrapper',630,290);
		$I->wait(0.5);
		$I->clickXY('#gameWrapper',860,290);
		$I->wait(0.5);
		$I->clickXY('#gameWrapper',520,410);
		$I->wait(0.5);
		$I->clickXY('#gameWrapper',750,410);
		$I->wait(0.5);
		$I->clickXY('#gameWrapper',400,550);
		$I->wait(0.5);
		$I->clickXY('#gameWrapper',630,550);
		$I->wait(0.5);
		$I->clickXY('#gameWrapper',860,550);
	}

}while($bonus!=0); //repeat loop until bonus is cleared


/*
do{
	for($y=200;$y<=450;$y=$y+20){ //Bonus Checker
		
		for($x=300;$x<=700;$x=$x+20){	//start spinning all screen with no mercy
			
			try{
				
				$I->waitForElementVisible('.interface-quickSettingsMenu_handle',0.2); //If spin button is present that means that bonus is finished and no need to click more
				$bonus=0;
				
				//Finish the game
				$I->click('#homeButton');
						
				//Go to HomePage
				$I->waitForElementVisible('.header-image',10);
				$I->click('.header-image');
					
				goto start;
				
			}catch(Exception $NoSpin){
				
				$bonus=1;
				$I->clickXY('#gameWrapper',$x,$y);
				$I->wait(0.1);
				$I->amGoingTo("(".$x.",".$y.")");
			
			}
		
		}
	}

}while($bonus!=0); //repeat loop until bonus is cleared
*/

afterbonus:

try{ //Check is the game is resumed
	
	$I->waitForElementVisible('.interface-buttonDisable',2);
	$I->waitForElementNotVisible('.interface-buttonDisable',30);
	//Finish the game
	$I->click('#homeButton');
	//Go to HomePage
	$I->waitForElementVisible('.header-image',10);
	$I->click('.header-image');
	goto start;
	
}catch(Exception $a){
	
	try{ //Check if user has free Spins
	$I->waitForElementVisible('.dialogWindowSingleButton',2);
	$I->click('.dialogWindowSingleButton');

	$freespins=$I->grabTextFrom('.interface-spinButton_counter');
	
	for($n=1;$n<=$freespins;$n++){
	
		global $win;
		$I->waitForElementVisible('#spinButton',90);
		$I->click('#spinButton');
		$I->wait(5);
		$I->waitForElementVisible('#spinButton',90);
	}	
	
	$I->click('.dialogWindowSingleButton');
	
	//Finish the game
	$I->click('#homeButton');
		
	//Go to HomePage
	$I->waitForElementVisible('.header-image',10);
	$I->click('.header-image');
	goto start;
	
	}catch(Exception $a){
	
	$I->click('.interface-settingsButton_baseButton');
	
}
	
}

$I->waitForElementVisible('#betSettingsCoinValueSliderHandle',10);
$I->click('#betSettingsCoinValueSliderMinimum');
$I->wait(2);
$I->click('.interface-settingsButton_baseButton');
$I->waitForElementNotVisible('#betSettingsCoinValueSliderHandle',10);

//Do 2 Spins
$win=0;    // win per spin
$twin=0;  // total win
$spins=2; // number of spins


for($n=1;$n<=$spins;$n++){
	
	global $win;
	$I->waitForElementVisible('#spinButton',90);
	$I->click('#spinButton');
	$I->wait(5);
	
	try{
		
		$I->waitForElementVisible('#spinButton',60);
		$I->waitForElementVisible('.interface-quickSettingsMenu_handle',90);
		
		$win=$I->grabTextFrom('.win .text.value');
		$win= filter_var($win, FILTER_SANITIZE_NUMBER_FLOAT); //get actual winnings in Cents
		$twin=$twin+$win; //Update Total Winnings
	}catch(Exception $bonusHappened){
		
		$I->amGoingTo ('I won a BONUS round');
		$I->clickXY('#gameWrapper',600,500); //Click on Continue in Free Spins window
		$I->wait(1);
		$I->clickXY('#gameWrapper',600,530); //Click on Continue in Bonus round window
		$I->wait(3);
		$bonus=0;
		
		goto bonus;
	}
	
	
}


$I->amGoingTo ("Total Win £".($twin/100));
$won=true;
$spins=$n-1;

if(($twin/100)<(0.2*$spins)){   // User won less than he bet
	
	global $win;
	$win=(($spins*20)-$twin);
	$I->amGoingTo ("You lost a total amount of £".($win/100));
	$won=false;
	
}else{  						// User won more than he bet or returned the bets back
	
	global $win;
	$win=($twin-($spins*20));
	$I->amGoingTo("You won a total amount of £".($win/100));
	
}

//Finish the game
$I->click('#homeButton');


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


if($won){
	
	if($startbalance==$endbalance-$win){
		
		$I->greenText("You Won and Balance was updated correctly. Winnings are: £".($win/100));
		
	}else{
		
		$I->redText("You Won, but Balance was not updated properly. Winnings are: £".($win/100));
		$I->see("Failed in Balance Comparison");
	}
	
}else{
	
	if($endbalance==$startbalance-$win){
		
		$I->greenText("You Lost and Balance was updated correctly. You lost £".($win/100));
		
	}else{
		
		$I->redText("You Lost, but Balance was not updated properly. You lost £".($win/100));
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