<?php
require 'tests/acceptance/_bootstrap.php';


//Global Variables
global $diff,$iteration;
$fn = basename(__FILE__); //get a filename
$filename=explode(".",$fn); //get a filename without extension
$iteration=0;

// @group casino
$I = new AcceptanceTester($scenario);
$I->wantTo('Play Web Netent HTML5 game - StarBurst');

$I->amOnPage('/');
$I->maximizeWindow();

$I->amGoingTo('Verify elements on Landing page');
$I->waitForElementVisible('[title="Casino"]',30);

$I->amGoingTo('Check Casino');
$I->click('[title="Casino"]');

start:
$won=false;

if($iteration>=1){
	
	$I->waitForElementVisible('.casino a');
	$I->click('.casino a');
	
}

//temporary workaround to reload the casino page as it started failing a lot on production
//	try{
		$I->waitForElementVisible('.active[classname="casino"]', 30);
		$I->waitForElementVisible('.hometime',60);
//	}catch(Exception $pageNotLoaded){
//		$I->amGoingTo('Casino page not laoding. reloading the page.');
//		$I->reloadPage();
//		$I->waitForElementVisible('.active[classname="casino"]', 30);
//		$I->waitForElementVisible('.hometime',60);
//	}

if($iteration<=0){
	$I->weblogin($webgamestestuser,$webgamestestuserPass);
}

$serverid = $I->grabTextFrom('.hiddensrv'); // Get Server ID
$I->greenText('Server ID is: '.$serverid);

$I->waitForElementVisible('#slides .pagination li:nth-of-type(1) a',60);
$I->click('#slides .pagination li:nth-of-type(1) a');
$I->waitForElement('.slides_container img',30);
$I->waitForElementVisible('#searchGames input',30);

$funds=$I->grabTextFrom('span.balance_visible');
$funds= filter_var($funds, FILTER_SANITIZE_NUMBER_FLOAT); //get actual balance in Cents
$I->amGoingTo('Actual Funds => £'.$funds/100); //Shows Funds

//Find a game
$I->fillField('#searchGames input','Starburst');
$I->waitForElementVisible('.gamesFilteredDisplay [alt="Starburst"]',30);
$I->wait(1);
$I->click('.gamesFilteredDisplay [alt="Starburst"]');

$I->wait(2);

//change to game window
$I->executeInSelenium(function (\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver) {
     $handles=$webdriver->getWindowHandles();
     $last_window = end($handles);
     $webdriver->switchTo()->window($last_window);
});

/*
//change to game window
global $gamewindow,$curr_window;
$curr_window="hola";
$gamewindow="hola";

$I->wait(2);

$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver){ //Store current window id
	
	global $gamewindow;
	global $curr_window;
	$gamewindow= end($webdriver->getWindowHandles());
	$curr_window = $webdriver->getWindowHandle();
	$webdriver->switchTo()->window($gamewindow);
	
});	
*/

//Loading a game
$I->waitForElement('.gameiframe',30); // wait for 1st iframe
$I->wait(1);
$I->waitForElement('#iframegamecontainer',30);
$I->wait(1);
$I->switchToIFrame('iframegamecontainer');
$I->wait(1);

$I->waitForElement('#GameIframe',30);
$I->wait(1);
$I->switchToIFrame('GameIframe');
$I->wait(1);

$I->waitForElement('#gamecontainer',30);
$I->wait(1);
$I->switchToIFrame('gamecontainer');
$I->waitForElement('#gameFooter',60); // wait for game to load
$I->waitForElement('#canvasAnimationManager',30);


//Play the Game
//$I->wait(1);
//$I->clickXY('#canvasAnimationManager',470,478); //Click on continue
$I->wait(1);

// reduce stake to minimum - £0.1
	do{
		$I->clickXY('#canvasAnimationManager',625,483); //Click Minumum Coin Value
		$I->wait(0.5);
		$stake=$I->grabTextFrom('.inline.field.bet');
		$stake= filter_var($stake, FILTER_SANITIZE_NUMBER_FLOAT); //get current stake in Cents
		$I->amGoingTo('Current selected Stake ---> £'.($stake/100));
	} while($stake!=10);

$I->clickXY('#canvasAnimationManager',466,475); //Click on Spin
$I->wait(20);


//Start a loop that will get the won amount until it stops changing
	do{
		$winning=$I->grabTextFrom('.inline.field.win');
		$winning= filter_var($winning, FILTER_SANITIZE_NUMBER_FLOAT); //get actual winnings in Cents
		$I->wait(3);
		$winning2=$I->grabTextFrom('.inline.field.win');
		$winning2= filter_var($winning2, FILTER_SANITIZE_NUMBER_FLOAT); //get actual winnings in Cents
	
	}while($winning != $winning2);


	if($winning != 0){
		$won=true;
		$I->greenText('User WON ---> £'.($winning/100));
	} else {
		$won=false;
		$I->greenText('User LOST');
	}

$I->wait(1);

//Make Gamewindow screenshot
$I->makeScreenshot('2'.$filename[0].'.fail');

//Change back to main window
$I->switchToWindow();


/*
//Change to main window
$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webDriver) { //Switch to the parent window
	
	global $curr_window;
	$webDriver->switchTo()->window($curr_window);
				
});
*/


// Get the current time later to check teh Statement
date_default_timezone_set("Europe/London");
$date=date('d M Y H:i');
$date1=date('d M Y H:i',strtotime("+1 minute"));
$date2=date('d M Y H:i',strtotime("-1 minute"));
$date3=date('d M Y H:i',strtotime("-2 minute"));	

// Ensure balance is updated correctly
$I->waitForElementVisible('.accountbalancerefresh',30);
$I->click('.accountbalancerefresh');
$I->wait(3);
$I->click('.accountbalancerefresh');
 
$fundsafter=$I->grabTextFrom('span.balance_visible');
$fundsafter= filter_var($fundsafter, FILTER_SANITIZE_NUMBER_FLOAT); //get actual balance in Cents 
 
	if( $fundsafter==$funds ){
		$I->wait(5);
		$I->click('.accountbalancerefresh');
		$I->wait(1);
		$fundsafter=$I->grabTextFrom('span.balance_visible');
		$fundsafter= filter_var($fundsafter, FILTER_SANITIZE_NUMBER_FLOAT); //get actual balance in Cents 
	} 
  
$I->amGoingTo('Actual Funds => £'.$fundsafter/100); //Shows Funds

	if($won!=true){ // if user lost
		$diff=$funds-$fundsafter; //difference between initial balance and balance after game play
		if ($diff==$stake){
			$I->amGoingTo('User balance was updated correctly');
		}else{
			global $iteration;
			if($iteration>=1){
				$I->redText('User balance was NOT updated correctly');
				$I->makeScreenshot('2'.$filename[0].'.fail');
				
				//If failed then focus on game window to take screenshot
				$I->executeInSelenium(function (\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver) {
					 $handles=$webdriver->getWindowHandles();
					 $last_window = end($handles);
					 $webdriver->switchTo()->window($last_window);
				});
				
			$I->see("Failed");
			
			}else{
			
				$I->redText('Failed: User balance was NOT updated correctly. Starting Iteration 2');
				$iteration=1;
				goto start;
			}
		}
	}else{ //if user won
	
		$diff=($funds-$stake-$fundsafter)*(-1); //calculating winning from initial funds deducting stake and deducting funds after
		
		if ($diff==$winning){
			$I->amGoingTo('User balance was updated correctly');
		}else{
			global $iteration;
			if($iteration>=1){
				$I->redText('User balance was NOT updated correctly');
				$I->makeScreenshot('2'.$filename[0].'.fail');
				
				//If failed then focus on game window to take screenshot
				$I->executeInSelenium(function (\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver) {
					 $handles=$webdriver->getWindowHandles();
					 $last_window = end($handles);
					 $webdriver->switchTo()->window($last_window);
				});
				
			$I->see("Failed");
			
			}else{
			
				$I->redText('Failed: User balance was NOT updated correctly. Starting Iteration 2');
				$iteration=1;
				goto start;
			}
		}
	}

//Navigate to Statement
$I->wait(2);
$I->click('[title="Display account details"]');
$I->waitForElementVisible('[title="Statement"]',2);
$I->click('[title="Statement"]');
$I->waitForElementVisible('.statement-history',30);

//click on Debit/Credit
	try{

		$I->waitForElementVisible('[name="TransactionTypeRadio"][value="101"]',2);
		$I->click('[name="TransactionTypeRadio"][value="101"]');
		$I->waitForText('Balance history',30,'.tab_content.accountcontent h4');
		$I->waitForElementVisible('.statement-history tr:nth-of-type(1) td:nth-of-type(2)',30);
	} catch(Exception $emptyStatement){
		$I->redText('Statement does not have any records');
		$I->makeScreenshot('2'.$filename[0].'.fail');
		
		//If failed then focus on game window to take screenshot
		$I->executeInSelenium(function (\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver) {
			 $handles=$webdriver->getWindowHandles();
			 $last_window = end($handles);
			 $webdriver->switchTo()->window($last_window);
		});
		
		$I->see("Failed");
	}

//normalize amounts to match format dispalyed in statement
$stake=number_format((float)($stake/100),2,'.','');
$winning=number_format((float)($winning/100),2,'.','');

//Check for Statement records
$I->amGoingTo('Possible Time when the game was played: '.$date.' ------------ '.$date2.' -------------- '.$date3.' ------------ '.$date1); 

	if(!$won){ //If user lost first record will be a debit one and no credit
		
		$datetime=$I->grabTextFrom('.statement-history tr:nth-of-type(1) td:nth-of-type(1)');
		$trans=$I->grabTextFrom('.statement-history tr:nth-of-type(1) td:nth-of-type(3)');
		$debit=$I->grabTextFrom('.statement-history tr:nth-of-type(1) td:nth-of-type(4)');
		$credit=$I->grabTextFrom('.statement-history tr:nth-of-type(1) td:nth-of-type(5)');
		$I->amGoingTo('First Statement record data: '.$datetime.' - '.$trans.' - '.$debit.' - '.$credit);

		try{
			
			$I->see($date,'.statement-history tr:nth-of-type(1) td:nth-of-type(1)');
			
		}catch(Exception $datewrong){
			
			try{
				
				$I->see($date2,'.statement-history tr:nth-of-type(1) td:nth-of-type(1)');
		
			}catch(Exception $date2wrong){
				
				try{
				
					$I->see($date3,'.statement-history tr:nth-of-type(1) td:nth-of-type(1)');
		
				}catch(Exception $date3wrong){
				
					try{
						
						$I->see($date1,'.statement-history tr:nth-of-type(1) td:nth-of-type(1)');
			
					}catch(Exception $date1wrong){
						
						global $iteration;
						if($iteration>=1){
							$I->redText('Failed: Date/time is not correct. Actual Date in statement = '.$datetime);
							$I->makeScreenshot('2'.$filename[0].'.fail');
							
							//If failed then focus on game window to take screenshot
							$I->executeInSelenium(function (\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver) {
								 $handles=$webdriver->getWindowHandles();
								 $last_window = end($handles);
								 $webdriver->switchTo()->window($last_window);
							});
							
						$I->see("Failed");
						
						}else{
						
							$I->redText("Failed: Game was not played. Starting Iteration 2");
							$iteration=1;
							goto start;
						
						}
					}
				}
			}
		}
		
		if(strcmp($trans, "Net Entertainment Stake tx")!== 0){
		
			$I->redText("Failed: Description is not correct. Actual Description in statement = ".$trans);
			$I->makeScreenshot('2'.$filename[0].'.fail');
			
			//If failed then focus on game window to take screenshot
			$I->executeInSelenium(function (\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver) {
				 $handles=$webdriver->getWindowHandles();
				 $last_window = end($handles);
				 $webdriver->switchTo()->window($last_window);
			});
		
			$I->see("Failed");
		}else{
			$I->amGoingTo('Transaction Description is correct.');
		}
	
		if(strcmp($debit,'£'.$stake)!==0){
		
			$I->redText('Failed: Debit value is not correct. Actual Debit (Stake) in statement = '.$debit);
			$I->makeScreenshot('2'.$filename[0].'.fail');
			
			//If failed then focus on game window to take screenshot
			$I->executeInSelenium(function (\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver) {
				 $handles=$webdriver->getWindowHandles();
				 $last_window = end($handles);
				 $webdriver->switchTo()->window($last_window);
			});
		
			$I->see("Failed");
		
		}else{
			$I->amGoingTo('Debit (Stake) value is correct.');
		}

		if(strcmp($credit,"£0.00")!==0){
		
			$I->redText("Failed: Credit is not correct. Actual Credit (winning) in statement = ".$credit);
			$I->makeScreenshot('2'.$filename[0].'.fail');
			
			//If failed then focus on game window to take screenshot
			$I->executeInSelenium(function (\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver) {
				 $handles=$webdriver->getWindowHandles();
				 $last_window = end($handles);
				 $webdriver->switchTo()->window($last_window);
			});
			
			$I->see("Failed");
		
		}else{
			$I->amGoingTo('Credit (Winning) value is correct.');
		}
		
	}else{ //if won then we will need to check 2 last records, one for debit and other for credit 
		
		//Check Debit
		$step='Debit';
		$datetime=$I->grabTextFrom('.statement-history tr:nth-of-type(2) td:nth-of-type(1)');
		$trans=$I->grabTextFrom('.statement-history tr:nth-of-type(2) td:nth-of-type(3)');
		$debit=$I->grabTextFrom('.statement-history tr:nth-of-type(2) td:nth-of-type(4)');
		$credit=$I->grabTextFrom('.statement-history tr:nth-of-type(2) td:nth-of-type(5)');
		$I->amGoingTo('Second Statement record data: '.$datetime.' - '.$trans.' - '.$debit.' - '.$credit);
		
		try{
			
			$I->see($date,'.statement-history tr:nth-of-type(2) td:nth-of-type(1)');
			
		} catch(Exception $datewrong){
			
			try{
				
				$I->see($date2,'.statement-history tr:nth-of-type(2) td:nth-of-type(1)');
		
			}catch(Exception $date2wrong){
				
				try{
				
					$I->see($date3,'.statement-history tr:nth-of-type(2) td:nth-of-type(1)');
		
				}catch(Exception $date3wrong){
					
					try{
						
						$I->see($date1,'.statement-history tr:nth-of-type(2) td:nth-of-type(1)');
			
					}catch(Exception $date1wrong){
						
						global $iteration;
						if($iteration>=1){
							$I->redText('Failed: Date/time is not correct. Actual Date in statement = '.$datetime);
							$I->makeScreenshot('2'.$filename[0].'.fail');
							
							//If failed then focus on game window to take screenshot
							$I->executeInSelenium(function (\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver) {
								 $handles=$webdriver->getWindowHandles();
								 $last_window = end($handles);
								 $webdriver->switchTo()->window($last_window);
							});
							
						$I->see("Failed");
						
						}else{
						
							$I->redText("Failed: Game was not played. Starting Iteration 2");
							$iteration=1;
							goto start;
						
						}
					}
				}
			}
		}	
		
		if(strcmp($trans, "Net Entertainment Stake tx")!== 0){
		
			$I->redText("Failed: Description is not correct. Actual Description in statement = ".$trans);
			$I->makeScreenshot('2'.$filename[0].'.fail');
			
			//If failed then focus on game window to take screenshot
			$I->executeInSelenium(function (\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver) {
				 $handles=$webdriver->getWindowHandles();
				 $last_window = end($handles);
				 $webdriver->switchTo()->window($last_window);
			});
		
			$I->see("Failed");
			
		}else{
			$I->amGoingTo('Transaction Description is correct.');
		}
		
		if(strcmp($debit,"£".$stake)!==0){
		
			$I->redText('Failed: Debit value is not correct. Actual Debit (Stake) in statement = '.$debit);
			$I->makeScreenshot('2'.$filename[0].'.fail');
			
			//If failed then focus on game window to take screenshot
			$I->executeInSelenium(function (\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver) {
				 $handles=$webdriver->getWindowHandles();
				 $last_window = end($handles);
				 $webdriver->switchTo()->window($last_window);
			});
		
			$I->see("Failed");
			
		}else{
			$I->amGoingTo('Debit (Stake) value is correct.');
		}

		if(strcmp($credit,"£0.00")!==0){
		
			$I->redText("Failed: Credit is not correct. Actual Credit (winning) in statement = ".$credit);
			$I->makeScreenshot('2'.$filename[0].'.fail');
			
			//If failed then focus on game window to take screenshot
			$I->executeInSelenium(function (\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver) {
				 $handles=$webdriver->getWindowHandles();
				 $last_window = end($handles);
				 $webdriver->switchTo()->window($last_window);
			});
			
			$I->see("Failed");
		
		}else{
			$I->amGoingTo('Credit (Winning) value is correct.');
		}	
		
		//Check Credit
		$step="Credit";
		$datetime=$I->grabTextFrom('.statement-history tr:nth-of-type(1) td:nth-of-type(1)');
		$trans=$I->grabTextFrom('.statement-history tr:nth-of-type(1) td:nth-of-type(3)');
		$debit=$I->grabTextFrom('.statement-history tr:nth-of-type(1) td:nth-of-type(4)');
		$credit=$I->grabTextFrom('.statement-history tr:nth-of-type(1) td:nth-of-type(5)');
		$I->amGoingTo('First Statement record data: '.$datetime.' - '.$trans.' - '.$debit.' - '.$credit);	
		
		try{
			
			$I->see($date,'.statement-history tr:nth-of-type(1) td:nth-of-type(1)');
			
		}catch(Exception $datewrong){
			
			try{
				
				$I->see($date2,'.statement-history tr:nth-of-type(1) td:nth-of-type(1)');
		
			}catch(Exception $date2wrong){
				
				try{
				
					$I->see($date3,'.statement-history tr:nth-of-type(1) td:nth-of-type(1)');
		
				}catch(Exception $date3wrong){
					
					try{
						
						$I->see($date1,'.statement-history tr:nth-of-type(1) td:nth-of-type(1)');
			
					}catch(Exception $date1wrong){
						
						global $iteration;
						if($iteration>=1){
							$I->redText('Failed: Date/time is not correct. Actual Date in statement = '.$datetime);
							$I->makeScreenshot('2'.$filename[0].'.fail');
							
							//If failed then focus on game window to take screenshot
							$I->executeInSelenium(function (\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver) {
								 $handles=$webdriver->getWindowHandles();
								 $last_window = end($handles);
								 $webdriver->switchTo()->window($last_window);
							});
							
						$I->see("Failed");
						
						}else{
						
							$I->redText("Failed: Game was not played. Starting Iteration 2");
							$iteration=1;
							goto start;
						
						}
					}
				}
			}
		}			

		if(strcmp($trans, "Net Entertainment Casino Return tx")!== 0){
			
			$I->redText("Failed: Description is not correct. Actual Description in statement = ".$trans);
			$I->makeScreenshot('2'.$filename[0].'.fail');
			
			//If failed then focus on game window to take screenshot
			$I->executeInSelenium(function (\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver) {
				 $handles=$webdriver->getWindowHandles();
				 $last_window = end($handles);
				 $webdriver->switchTo()->window($last_window);
			});
			
			$I->see("Failed");
			
		}else{
			$I->amGoingTo('Transaction Description is correct.');
		}	
		
		if(strcmp($debit,"£0.00")!==0){
			
			$I->redText('Failed: Debit value is not correct. Actual Debit (Stake) in statement = '.$debit);
			$I->makeScreenshot('2'.$filename[0].'.fail');
			
			//If failed then focus on game window to take screenshot
			$I->executeInSelenium(function (\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver) {
				 $handles=$webdriver->getWindowHandles();
				 $last_window = end($handles);
				 $webdriver->switchTo()->window($last_window);
			});
			
			$I->see("Failed");
		
		}else{
			$I->amGoingTo('Debit (Stake) value is correct.');
		}	
		
		if(strcmp($credit,"£".($winning))!==0){
			
			$I->redText("Failed in ".$step." line: credit is not correct. Winning = ".$winning." and statement says that credit is = ".$credit);
			$I->makeScreenshot('2'.$filename[0].'.fail');
			
			//If failed then focus on game window to take screenshot
			$I->executeInSelenium(function (\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver) {
				 $handles=$webdriver->getWindowHandles();
				 $last_window = end($handles);
				 $webdriver->switchTo()->window($last_window);
			});
			
			$I->see("Failed");
			
		}else{
			$I->amGoingTo('Credit (Winning) value is correct.');
		}		
	}

$I->weblogout();	
		
?> 