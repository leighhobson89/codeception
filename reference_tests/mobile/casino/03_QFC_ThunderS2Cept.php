<?php
require 'tests/acceptance/_bootstrap.php';

$fn = basename(__FILE__); //get a filename
$filename=explode(".",$fn); //get a filename without extension

// @group general
// @group casino

$I = new AcceptanceTester($scenario);

$I->wantTo('Mobile QFC game - Thunderstuck 2');

$I->amOnPage('/');
$I->resizeWindow(1024,768);

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
if($startbalance < 60){ //If balance is less than £0.6
	$I->redText("The balance is low. Please add money to the account");
	$I->see('Test failed due insufficient balance');
}

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
//$casinopage=1;
findgame:
try{	
	$li=0; //Variable to 0 to start from the 1st game

		$tabID=$I->grabAttributeFrom('.tab-content.active','id'); //grab the currently selected Casino TAB ID
		$I->amGoingTo('Currently active Casino TAB ID: '.$tabID);
		$I->waitForElementVisible('#'.$tabID.' .views-row:nth-of-type(1) .casino-icon-high-resolution',30); //wait for the first game in the selected TAB
		
		do{
			$li++; //increase li before grabbing text to start from 1st 
			$gamename=$I->grabTextFrom('#'.$tabID.' .views-fluid-grid li:nth-of-type('.$li.') .game-name');
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

$previous=false; //variable for resumed game
$ending=false;

//Check Tutorial is on place
/*
try{

	$I->waitForElementVisible('#tutorial-tutorialScreen',45);
	$I->wait(2);
	$I->click('#tutorial-tutorialScreen');
	
}catch(Exception $e){
	
}

*/


//Click on Dialog, if it is not available then the game was resumed or error happened
try{
	
	$I->waitForElementVisible('#acDialog',45);
	$I->click('#acDialog');

}catch(Exception $dialog){
	
	try{
		$I->waitForElementVisible('li[style*="inherit"] #txtSpin',2); // if it was a Big win screen, tutorial is not showing, but user can play normally
		$I->click('#btnSpin');
		$previous=true;
		goto restart; //restart the game play
		
	}catch(Exception $noBigWin){
		$I->amGoingTo('BigWin screen was not available');
	}
	
	bonus:
	//check for continue Free Spins
	try{ 
		
		$I->amGoingTo('Continue Bonus round');
		try {
			$I->waitForElementVisible('#txtContinueFS',2); // check if Continue Free Spins button is available
		}catch(Exception $NoContinueButton){
			$I->waitForElementVisible('#freespin-counter',2); // check if the free spins counter field is available
		}
				
		$previous=true;
		$freespins = $I->grabTextFrom('#freespin-counter'); // Get amount of free spins
		
		try{
			$I->click('#txtContinueFS');
		}catch(Exception $NoContinueFSButton){
		}
	
		do {
			$I->wait(3);
			$freespins = $I->grabTextFrom('#freespin-counter'); // Get amount of free spins left
			$freespins = (int)$freespins;
			$I->amGoingTo('Still spinning free spins. '.$freespins.' free spins left.');
	
		}while($freespins != 0);
		
		$previous=true;
		goto restart; //restart the game play
				
	}catch(Exception $continuefs){
	
		$I->amGoingTo('NO Free spins to CONTINUE');
	}

	//check if game is resumed with free spins
	try{

		$I->waitForElementVisible('#btnStartFS',2);
		$previous=true;
		$freespins = $I->grabTextFrom('#freespin-counter'); // Get amount of free spins
		$I->click('#btnStartFS');
		
		do {
			$I->wait(3);
			$freespins = $I->grabTextFrom('#freespin-counter'); // Get amount of free spins left
			$freespins = (int)$freespins;
			$I->amGoingTo('Still spinning free spins. '.$freespins.' free spins left.');
			$I->wait(1);
			
		}while($freespins != 0);
		
	}catch(Exception $bonus){
		
		$I->amGoingTo('NO Free spins to start');
		
	}
	
/*	try {
		
		$I->waitForElement('.content.reelset-1',2); // Wait for Big Win box
		$I->seeElement('.content.reelset-1'); // click on Big Win area
		$I->waitForElement('.content.reelset-0',20); // Wait for Big Win box to disappear
		
	}catch(Exception $NoContinueButton){
		$I->amGoingTo('NO BIG Winning Screen');
	}
*/
	
	try{
		$I->waitForElementVisible('#bonus',2);

		//If it is visible then check which bonus is the one available
		//Valkirie
		$style=$I->grabAttributeFrom('.valk.bonusItem', 'style');
		if($style="opacity:1;"){
			
			$previous=true;
			$I->click('#valk .bttn');
			goto bonus;
		}
		//Loki
		$style=$I->grabAttributeFrom('.loki.bonusItem', 'style');
		if($style="opacity:1;"){
			
			$previous=true;
			$I->click('#loki .bttn');
			goto bonus;
		}
		//Odin
		$style=$I->grabAttributeFrom('.odin.bonusItem', 'style');
		if($style="opacity:1;"){
			
			$previous=true;
			$I->click('#odin .bttn');
			goto bonus;
		}
		//Thor
		$style=$I->grabAttributeFrom('.thor.bonusItem', 'style');
		if($style="opacity:1;"){
			
			$previous=true;
			$I->click('#thor .bttn');
			goto bonus;
		}
	}catch(Exception $bonusSelection){
		
		$I->amGoingTo('NO Bonus selection is available');
		
	}
	
	try{
		$I->waitForElementVisible('.dialog-outer-container',2);
		$I->amGoingTo('ERROR dialog. Game is not working.');
		$I->see("TEST FAILED AS GAME DOESN'T WORK");
	}catch(Exception $bonusSelection){
		$I->amGoingTo('ERROR dialog is not present');
	}
	
	$I->waitForElementVisible('#txtSpin',2); // wait for spin button
	$previous=true;
}
	
restart:

if($previous){ //If game was resumed start from scratch
	
	//Finish the game
	$I->amGoingTo('Restarting the game and will play another round');
	$I->wait(2);
	$I->moveBack();
	//Go to HomePage
	$I->waitForElementVisible('.header-image',30);
	$I->click('.header-image');
	goto start;
		
}

//Spin 2 Times

$win=0;    // win per spin
$twin=0;  // total win
$spins=2; // number of spins

$I->amGoingTo("Play the Game");
$I->wait(3);

for($n=1;$n<=$spins;$n++){

	try { //will try to spin and if something fails then will check for Bonus Round

		$I->waitForElementVisible('li[style*="inherit"] #txtSpin',60);
		$I->waitForElement('li[style*="hidden"] #txtStop',30);
		$I->wait(1);
		
		$I->click('#btnSpin'); // Click Spin button

//Temporar fix for thunderstruck game while tets are executed slowly		
	$I->wait(5);
	//	$I->waitForElementVisible('.autoplay.disabled #txtSpin',30); // Stop button is visible
		
	//	$I->waitForElementVisible('li[style*="inherit"] #txtStop',60);
	//	$I->waitForElement('li[style*="hidden"] #txtSpin',30);
	//	$I->dontSeeElement('li[style*="inherit"] #txtSpin');
		
		$I->wait(3);
		$I->waitForElementVisible('li[style*="inherit"] #txtSpin',30);
		$I->waitForElementNotVisible('.autoplay.disabled #txtSpin',30);
		
	//	$I->waitForElement('li[style*="hidden"] #txtStop',30);
	//	$I->dontSeeElement('li[style*="hidden"] #txtSpin');

	}catch(Exception $spin){
		
		$I->redText('Something happened during the play. Going to check available bonus rounds.');
		$I->makeScreenshot('2'.$filename[0].'.fail');
		goto bonus;
		
		/*
			try{
				
				$I->waitForElementVisible('#bonus',2);
				//If it is visible then check which bonus is the one available
				//Valkirie
				$style=$I->grabAttributeFrom('.valk.bonusItem', 'style');
				if($style="opacity:1;"){
					
					$I->click('#valk .bttn');
					
				}
				//Loki
				$style=$I->grabAttributeFrom('.loki.bonusItem', 'style');
				if($style="opacity:1;"){
					
					$I->click('#loki .bttn');
					
				}
				//Odin
				$style=$I->grabAttributeFrom('.odin.bonusItem', 'style');
				if($style="opacity:1;"){
					
					$I->click('#odin .bttn');
					
				}
				//Thor
				$style=$I->grabAttributeFrom('.thor.bonusItem', 'style');
				if($style="opacity:1;"){
					
					$I->click('#thor .bttn');
					
				}
				
				$I->waitForElementVisible('#txtSpin',180);
				
				
			}catch(Exception $bonus){
				
				
			}
		*/
		
	}
	
	
	$I->wait(1);
	
//Start a loop that will get the won amount until it stops changing
	do{
	
		$win=$I->grabTextFrom('#txtWinVal');
//		$I->amGoingTo ("Win 1 ".$win);
		$win= filter_var($win, FILTER_SANITIZE_NUMBER_FLOAT); //get actual winnings in Cents
//		$I->amGoingTo ("Win 1 after sanitize ".$win);
		
		// if win amount is empty
		if (!$win) {
			$win = 0;			
		}
		
		$I->wait(1);
		$win2=$I->grabTextFrom('#txtWinVal');
//		$I->amGoingTo ("Win 2 ".$win2);
		$win2= filter_var($win2, FILTER_SANITIZE_NUMBER_FLOAT); //get actual winnings in Cents
//		$I->amGoingTo ("Win 2 after sanitize ".$win2);
	
		// if win amount is empty
		if (!$win2) {
			$win2 = 0;			
		}
	
	}while($win!=$win2);
	
	$twin=$twin+$win; //Update Total Winnings
	
}

$I->amGoingTo ("Total Win £".($twin/100));

$won=true;
$spins=$n-1;

if(($twin/100)<(0.3*$spins)){   // User won less than he bet	
	
	global $win;
	$win=(($spins*30)-$twin);
	$I->amGoingTo ("You lost a total amount of £".($win/100));
	$won=false;
	
}else{							// User won more than he bet or returned the bets back
	
	global $win;
	$win=($twin-($spins*30));
	$I->amGoingTo("You won a total amount of £".($win/100));
	
}

//Finish the game
$I->moveBack();


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