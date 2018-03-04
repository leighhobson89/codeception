<?php
require 'tests/acceptance/_bootstrap.php';
//Preparations for testing
//set_include_path('tests/_support/phpseclib');
//include_once('tests/_support/phpseclib/Net/SSH2.php');
//include_once('tests/_support/phpseclib/Net/SFTP.php');



//Global Variables
global $diff,$iteration;
$fn = basename(__FILE__); //get a filename
$filename=explode(".",$fn); //get a filename without extension
$iteration=0;

// @group casino-not-working
$I = new AcceptanceTester($scenario);
$I->wantTo('Play Web Netent - StarBurst');

$I->amOnPage('/');
$I->maximizeWindow();

$I->amGoingTo('Verify elements on Landing page');
$I->waitForElementVisible('[title="Casino"]',30);

$I->amGoingTo('Check Casino');
$I->click('[title="Casino"]');

start:

if($iteration>=1){
	
	$I->waitForElementVisible('.casino a');
	$I->click('.casino a');
	
}

$I->waitForElementVisible('.active[classname="casino"]', 30);
$I->waitForElementVisible('.hometime',60);

if($iteration<=0){
	$I->weblogin($webgamestestuser,$webgamestestuserPass);
}

$serverid = $I->grabTextFrom('.hiddensrv'); // Get Server ID
$I->greenText('Server ID is: '.$serverid);

$I->waitForElementVisible('#slides .pagination li:nth-of-type(1) a',60);
$I->click('#slides .pagination li:nth-of-type(1) a');
$I->waitForElement('.slides_container img',30);
$I->waitForElementVisible('.gameListing [title="Starburst"]',60);

$funds=$I->grabTextFrom('span.balance_visible');
$funds= filter_var($funds, FILTER_SANITIZE_NUMBER_FLOAT); //get actual balance in Cents
$I->amGoingTo('Actual Funds => £'.$funds/100); //Shows Funds

$I->wait(2);
$I->click('.gameListing [title="Starburst"]');

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

//Loading a game
$I->wait(30);


/*
$I->amGoingTo("Connecto to Host through SSH");
//connect to Remote machine
$ssh = new Net_SSH2($sshhost); //Madrid Windows 8 Machine
if (!$ssh->login($sshUser, $sshPass)) {
    exit('Login Failed');
}
$I->amGoingTo("Connecto to Copy File through SFTP");
//Copy executable to host machine

$sftp = new Net_SFTP($sshhost,22); //Madrid Windows 8 Machine
if (!$sftp->login($sshUser, $sshPass)) { //if you can't log on...
    exit('sftp Login Failed');
}
$sftp->put('starburst.au3','tests/acceptance/AutoIt/scripts/starburst.au3',NET_SFTP_LOCAL_FILE);

$I->amGoingTo("Connecto to Execute AutoIT through SSh");
//execute AutoIt script
$ssh->exec('C:\Selenium\AutoIT3\AutoIt3.exe C:\Selenium\AutoIT\starburst.au3');
*/

//Play the Game

$I->waitForElement('#iframegamecontainer',30);
$I->clickXY('#iframegamecontainer',401,529); //Click on continue
$I->wait(5);
$I->clickXY('#iframegamecontainer',555,565); //Click Minumum Coin Value
$I->wait(1);
$I->clickXY('#iframegamecontainer',401,534); //Click on Spin
$I->wait(15);


$I->amGoingTo("Continue in Codeception");
// Get the current time
date_default_timezone_set("Europe/London");
$date=date('d M Y H:i');
$date1=date('d M Y H:i',strtotime("+1 minute"));
$date2=date('d M Y H:i',strtotime("-1 minute"));

$I->wait(5);

//Change to main window
$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webDriver) { //Switch to the parent window
	
	global $curr_window;
	$webDriver->switchTo()->window($curr_window);
				
});

//Get Balance and winnings/loses
$I->click('.accountbalancerefresh');
$I->wait(3);
$I->click('.accountbalancerefresh');
 
$fundsafter=$I->grabTextFrom('span.balance_visible');
$fundsafter= filter_var($fundsafter, FILTER_SANITIZE_NUMBER_FLOAT); //get actual balance in Cents
$I->amGoingTo('Actual Funds => £'.$fundsafter/100); //Shows Funds
  
$stake=10; // Starburst Stake in cents

$diff=$funds-$fundsafter-$stake;

global $won;
$won=false;

//normalize amounts

$diff=number_format((float)($diff/100),2,'.','');
$stake=number_format((float)($stake/100),2,'.','');

if($diff>=0){
	
	$I->greenText("You lost a total of £".($diff+$stake)); //stake should be added as normally if nothing won diff will be 0 
	
}else{
	
	$diff=number_format((float)($diff*(-1)),2,'.','');
	$I->greenText("You won a total of £".$diff);
	$won=true;
}


//Navigate to Statement
$I->click('[title="Display account details"]');
$I->waitForElementVisible('[title="Statement"]',2);
$I->click('[title="Statement"]');
$I->waitForElementVisible('.statement-history',30);

//click on Debit/Credit
$I->waitForElementVisible('[name="TransactionTypeRadio"][value="101"]',2);
$I->click('[name="TransactionTypeRadio"][value="101"]');
$I->waitForText('Balance history',30,'.tab_content.accountcontent h4');
$I->waitForElementVisible('.statement-history tr:nth-of-type(1) td:nth-of-type(2)',30);


  
//Check for Statement records
$I->amGoingTo($date." ------------ ".$date1." -------------- ".$date2);

if(!$won){ //If not won first record will be a debit one and no credit
	
	$datetime=$I->grabTextFrom('.statement-history tr:nth-of-type(1) td:nth-of-type(1)');
	$trans=$I->grabTextFrom('.statement-history tr:nth-of-type(1) td:nth-of-type(3)');
	$debit=$I->grabTextFrom('.statement-history tr:nth-of-type(1) td:nth-of-type(4)');
	$credit=$I->grabTextFrom('.statement-history tr:nth-of-type(1) td:nth-of-type(5)');
	$I->amGoingTo('First record data:'.$datetime.' - '.$trans.' - '.$debit.' - '.$credit);
	
	try{
		
		$I->see($date,'.statement-history tr:nth-of-type(1) td:nth-of-type(1)');
		
	}catch(Exception $datewrong){
		
		try{
			
			$I->see($date2,'.statement-history tr:nth-of-type(1) td:nth-of-type(1)');
		
		}catch(Exception $date2wrong){
			
			try{
			
			$I->see($date1,'.statement-history tr:nth-of-type(1) td:nth-of-type(1)');
		
			}catch(Exception $date1wrong){
					
				global $iteration;
				
				if($iteration>=1){
					
					$I->redText("Failed: Date/time is not correct. Date= ".$datetime);
					$I->makeScreenshot('2'.$filename[0].'.fail');
					//If failed then focus on game window to take screenshot
					$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webDriver) { //Switch to the parent window
	
					global $gamewindow;
					$webDriver->switchTo()->window($gamewindow);
				
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
	
	if(strcmp($trans, "Net Entertainment Stake tx")!== 0){
		
		$I->redText("Failed: Description is not correct. Description= ".$trans);
		$I->makeScreenshot('2'.$filename[0].'.fail');
		
		$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webDriver) { //Switch to the parent window

			global $gamewindow;
			$webDriver->switchTo()->window($gamewindow);
		
		});
		
		$I->see("Failed");
		
	}
	
	if(strcmp($debit,"£".$stake)!==0){
		
		$I->redText("Failed: Debit is not correct. Debit= ".$debit);
		$I->makeScreenshot('2'.$filename[0].'.fail');
		
		$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webDriver) { //Switch to the parent window

			global $gamewindow;
			$webDriver->switchTo()->window($gamewindow);
		
		});
		
		$I->see("Failed");
		
	}
	
	if(strcmp($credit,"£0.00")!==0){
		
		$I->redText("Failed: Debit is not correct. credit= ".$credit);
		$I->makeScreenshot('2'.$filename[0].'.fail');
		
		$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webDriver) { //Switch to the parent window

			global $gamewindow;
			$webDriver->switchTo()->window($gamewindow);
		
		});
		
		$I->see("Failed");
		
	}
	
}else{ //if won then we will need to check 2 last movements, one for debit and other for credit 
	
	global $diff;
	
	//Check Debit
	$step="Debit";
	$datetime=$I->grabTextFrom('.statement-history tr:nth-of-type(2) td:nth-of-type(1)');
	$trans=$I->grabTextFrom('.statement-history tr:nth-of-type(2) td:nth-of-type(3)');
	$debit=$I->grabTextFrom('.statement-history tr:nth-of-type(2) td:nth-of-type(4)');
	$credit=$I->grabTextFrom('.statement-history tr:nth-of-type(2) td:nth-of-type(5)');
	$I->amGoingTo('First record data:'.$datetime.' - '.$trans.' - '.$debit.' - '.$credit);
	
	try{
		
		$I->see($date,'.statement-history tr:nth-of-type(2) td:nth-of-type(1)');
		
	}catch(Exception $datewrong){
		
		try{
			
			$I->see($date2,'.statement-history tr:nth-of-type(2) td:nth-of-type(1)');
		
		}catch(Exception $date2wrong){
			
			try{
			
			$I->see($date1,'.statement-history tr:nth-of-type(2) td:nth-of-type(1)');
		
			}catch(Exception $date1wrong){
					
				global $iteration;
				
				if($iteration>=1){
					
					$I->redText("Failed: Date/time is not correct. Date= ".$datetime);
					$I->makeScreenshot('2'.$filename[0].'.fail');
					
					//If failed then focus on game window to take screenshot
					$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webDriver) { //Switch to the parent window
	
					global $gamewindow;
					$webDriver->switchTo()->window($gamewindow);
				
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
	
	if(strcmp($trans, "Net Entertainment Stake tx")!== 0){
		
		$I->redText("Failed in ".$step." line: Description is not correct. Description= ".$trans);
		$I->makeScreenshot('2'.$filename[0].'.fail');
		
		$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webDriver) { //Switch to the parent window

			global $gamewindow;
			$webDriver->switchTo()->window($gamewindow);
		
		});
		
		$I->see("Failed");
		
	}
	
	if(strcmp($debit,"£".$stake)!==0){
		
		$I->redText("Failed in ".$step." line: Debit is not correct. Debit= ".$debit);
		$I->makeScreenshot('2'.$filename[0].'.fail');
		
		$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webDriver) { //Switch to the parent window

			global $gamewindow;
			$webDriver->switchTo()->window($gamewindow);
		
		});
		
		$I->see("Failed");
		
	}
	
	if(strcmp($credit,"£0.00")!==0){
		
		$I->redText("Failed in ".$step." line: Debit is not correct. credit= ".$credit);
		$I->makeScreenshot('2'.$filename[0].'.fail');
		
		$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webDriver) { //Switch to the parent window

			global $gamewindow;
			$webDriver->switchTo()->window($gamewindow);
		
		});
		
		$I->see("Failed");
		
	}
	
	//Check Credit
	$step="Credit";
	$datetime=$I->grabTextFrom('.statement-history tr:nth-of-type(1) td:nth-of-type(1)');
	$trans=$I->grabTextFrom('.statement-history tr:nth-of-type(1) td:nth-of-type(3)');
	$debit=$I->grabTextFrom('.statement-history tr:nth-of-type(1) td:nth-of-type(4)');
	$credit=$I->grabTextFrom('.statement-history tr:nth-of-type(1) td:nth-of-type(5)');
	$I->amGoingTo('First record data:'.$datetime.' - '.$trans.' - '.$debit.' - '.$credit);
	
	try{
		
		$I->see($date,'.statement-history tr:nth-of-type(1) td:nth-of-type(1)');
		
	}catch(Exception $datewrong){
		
		try{
			
			$I->see($date2,'.statement-history tr:nth-of-type(1) td:nth-of-type(1)');
		
		}catch(Exception $date2wrong){
			
			try{
			
			$I->see($date1,'.statement-history tr:nth-of-type(1) td:nth-of-type(1)');
		
			}catch(Exception $date1wrong){
					
				$I->redText("Failed: Date/time is not correct. Date= ".$datetime);
				$I->makeScreenshot('2'.$filename[0].'.fail');
				
				$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webDriver) { //Switch to the parent window

					global $gamewindow;
					$webDriver->switchTo()->window($gamewindow);
				
				});
				
				$I->see("Failed");	
			}
			
		}
		
	}
	
	if(strcmp($trans, "Net Entertainment Casino Return tx")!== 0){
		
		$I->redText("Failed in ".$step." line: Description is not correct. Description= ".$trans);
		$I->makeScreenshot('2'.$filename[0].'.fail');
		
		$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webDriver) { //Switch to the parent window

			global $gamewindow;
			$webDriver->switchTo()->window($gamewindow);
		
		});
		
		$I->see("Failed");
		
	}
	
	if(strcmp($debit,"£0.00")!==0){
		
		$I->redText("Failed in ".$step." line: Debit is not correct. Debit= ".$debit);
		$I->makeScreenshot('2'.$filename[0].'.fail');
		
		$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webDriver) { //Switch to the parent window

			global $gamewindow;
			$webDriver->switchTo()->window($gamewindow);
		
		});
		
		$I->see("Failed");
		
	}

	if(strcmp($credit,"£".($diff ))!==0){
		
		$I->redText("Failed in ".$step." line: credit is not correct. Difference= ".$diff." and credit says= ".$credit);
		$I->makeScreenshot('2'.$filename[0].'.fail');
		
		$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webDriver) { //Switch to the parent window

			global $gamewindow;
			$webDriver->switchTo()->window($gamewindow);
		
		});
		
		$I->see("Failed");
		
	}
	
}

$I->weblogout();

?> 