<?php
//Preparations for testing
require 'tests/acceptance/_bootstrap.php';
//set_include_path('tests/_support/phpseclib');
//include_once('tests/_support/phpseclib/Net/SSH2.php');
//include_once('tests/_support/phpseclib/Net/SFTP.php');


//Global Variables
global $diff,$iteration;
$fn = basename(__FILE__); //get a filename
$filename=explode(".",$fn); //get a filename without extension
$iteration=0;

// @group NOT_casino
$I = new AcceptanceTester($scenario);
$I->wantTo('Play Web ChartWell - Rainbow Riches');

$I->amOnPage('/');
$I->maximizeWindow();

$I->amGoingTo('Verify elements on Landing page');
$I->waitForElementVisible('[title="Casino"]',30);

$I->amGoingTo('Check Casino');
$I->click('[title="Casino"]');
start:
//As Landing page is diferent than rest of the web we need to control Iteration before calling Casino.
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

if($iteration<=0){ //Login only in first iteration
	$I->weblogin($webgamestestuser,$webgamestestuserPass);
}

$serverid = $I->grabTextFrom('.hiddensrv'); // Get Server ID
$I->greenText('Server ID is: '.$serverid);

$I->waitForElementVisible('#slides .pagination li:nth-of-type(1) a',60);
$I->click('#slides .pagination li:nth-of-type(1) a');
$I->waitForElement('.slides_container img',30);
$I->waitForElementVisible('#searchGames input',30);

$I->click('.accountbalancerefresh');
$I->wait(3);
$funds=$I->grabTextFrom('span.balance_visible');
$funds= filter_var($funds, FILTER_SANITIZE_NUMBER_FLOAT); //get actual balance in Cents
$I->amGoingTo('Actual Funds => £'.$funds/100); //Shows Funds

//Find a game
$I->fillField('#searchGames input','Rainbow Riches');
$I->waitForElementVisible('.gamesFilteredDisplay [alt="Rainbow Riches"]',30);
$I->wait(1);
$I->click('.gamesFilteredDisplay [alt="Rainbow Riches"]');

$I->wait(5);

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

$I->wait(5);

$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver){ //Store current window id
	
	global $gamewindow;
	global $curr_window;
	$gamewindow= end($webdriver->getWindowHandles());
	$curr_window = $webdriver->getWindowHandle();
	$webdriver->switchTo()->window($gamewindow);
	
});	
*/


//Loading a game
$I->wait(60);

/*
//connect to Remote machine
	$ssh = new Net_SSH2($sshhost); //Madrid Windows 8 Machine
	if (!$ssh->login($sshUser, $sshPass)) {
		exit('Login Failed');
	}

	//Copy executable to host machine

	$sftp = new Net_SFTP($sshhost,22); //Madrid Windows 8 Machine
	if (!$sftp->login($sshUser, $sshPass)) { //if you can't log on...
		exit('sftp Login Failed');
	}


$sftp->put('rriches.au3','tests/acceptance/AutoIt/scripts/rriches.au3',NET_SFTP_LOCAL_FILE);


//execute AutoIt script

$ssh->exec('C:\Selenium\AutoIT3\AutoIt3.exe C:\Selenium\AutoIT\rriches.au3');
*/


//Play the Game

$I->waitForElement('#iframegamecontainer',30);
$I->clickXY('#iframegamecontainer',383,347); //Click on OK
$I->wait(5);

//Look for water well

for($y=125;$y<=365;$y=$y+120){

	for($x=205;$x<=685;$x=$x+120){
		$I->clickXY('#iframegamecontainer',$x,$y); //Click on Element
//		$I->clickXY('#iframegamecontainer',555,565); //Click on Element
		$I->wait(0.1);
	}

}

if($iteration>=1){ //if it is 2nd or 3rd iteration, try to click couple of times on spin button, to get rid of Road to Riches
	
	for($a=0;$a<=5;$a++){
		$I->clickXY('#iframegamecontainer',724,493); //Click on Spin
		$I->wait(5);
	}


}


$I->clickXY('#iframegamecontainer',724,493); //Click on Spin
$I->wait(15);

// Get the current time
date_default_timezone_set("Europe/London");
$date=date('d M Y H:i');
$date1=date('d M Y H:i',strtotime("+1 minute"));
$date2=date('d M Y H:i',strtotime("-1 minute"));
$date3=date('d M Y H:i',strtotime("-2 minute"));

$I->wait(5);

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

//Get Balance and winnings/loses
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
  
$stake=20; // Stake in cents

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
  
//Check for Statement records
$I->amGoingTo('Possible Time when the game was played: '.$date.' ------------ '.$date2.' -------------- '.$date3.' ------------ '.$date1); 

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
			
				$I->see($date3,'.statement-history tr:nth-of-type(1) td:nth-of-type(1)');
		
			}catch(Exception $date3wrong){
			
				try{
				
					$I->see($date1,'.statement-history tr:nth-of-type(1) td:nth-of-type(1)');
			
				}catch(Exception $date1wrong){
						
					global $iteration;
					
					if($iteration >= 1){
						
						$I->redText("Failed: Date/time is not correct. Date= ".$datetime);
						$I->makeScreenshot('2'.$filename[0].'.fail');
						
						//If failed then focus on game window to take screenshot
						$I->executeInSelenium(function (\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver) {
							 $handles=$webdriver->getWindowHandles();
							 $last_window = end($handles);
							 $webdriver->switchTo()->window($last_window);
						});
						
						$I->see("Failed");
						
						
					}else{
						
						$iteration = ($iteration+1);
						$I->redText($iteration.' failed - Game was not played. Starting Iteration '.($iteration+1));
						goto start;
						
					}
				}
			}
		}
		
	}
	
	try{
		$I->see('CGS','.statement-history tr:nth-of-type(1) td:nth-of-type(3)');
	}catch(Exception $noEntry){
		
		$I->makeScreenshot('2'.$filename[0].'.fail');

		//If failed then focus on game window to take screenshot
		$I->executeInSelenium(function (\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver) {
			 $handles=$webdriver->getWindowHandles();
			 $last_window = end($handles);
			 $webdriver->switchTo()->window($last_window);
		});
		
		$I->see("Failed");
	}
	
	
	/*
	if(strcmp($trans, "CGS")!== 0){
		
		$I->redText("Failed: Description is not correct. Description= ".$trans);
		$I->makeScreenshot('2'.$filename[0].'.fail');
		
		//If failed then focus on game window to take screenshot
		$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webDriver) { //Switch to the parent window

			global $gamewindow;
			$webDriver->switchTo()->window($gamewindow);
		
		});
		
		$I->see("Failed");
		
	}
	*/
	
	/*
	if(strcmp($debit,"£".$stake)!==0){
		
		$I->redText("Failed: Debit is not correct. Debit should be £".$stake." And it is showing ".$debit);
		$I->makeScreenshot('2'.$filename[0].'.fail');
		
		//If failed then focus on game window to take screenshot
		$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webDriver) { //Switch to the parent window

			global $gamewindow;
			$webDriver->switchTo()->window($gamewindow);
		
		});
		
		$I->see("Failed");
		
	}
	*/
	/*
	if(strcmp($credit,"£0.00")!==0){
		
		$I->redText("Failed: Debit is not correct. credit= ".$credit);
		$I->makeScreenshot('2'.$filename[0].'.fail');
		
		//If failed then focus on game window to take screenshot
		$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webDriver) { //Switch to the parent window

			global $gamewindow;
			$webDriver->switchTo()->window($gamewindow);
		
		});
		
		$I->see("Failed");
		
	}
	*/
	
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
			
				$I->see($date3,'.statement-history tr:nth-of-type(2) td:nth-of-type(1)');
		
			}catch(Exception $date3wrong){
			
				try{
				
					$I->see($date1,'.statement-history tr:nth-of-type(2) td:nth-of-type(1)');
			
				}catch(Exception $date1wrong){
						
					global $iteration;
					
					if($iteration>=1){
						
						$I->redText("Failed: Date/time is not correct. Date= ".$datetime);
						$I->makeScreenshot('2'.$filename[0].'.fail');
						
						//If failed then focus on game window to take screenshot
						$I->executeInSelenium(function (\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver) {
							 $handles=$webdriver->getWindowHandles();
							 $last_window = end($handles);
							 $webdriver->switchTo()->window($last_window);
						});
						
						$I->see("Failed");
						
						
					}else{
						
						$iteration = ($iteration+1);
						$I->redText($iteration.' failed - Game was not played. Starting Iteration '.($iteration+1));
						goto start;
						
					}
					
				}
			}
		}
		
	}
	
	try{
		$I->see('CGS','.statement-history tr:nth-of-type(2) td:nth-of-type(3)');
	}catch(Exception $noEntry){
		
		$I->makeScreenshot('2'.$filename[0].'.fail');

		//If failed then focus on game window to take screenshot
		$I->executeInSelenium(function (\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver) {
			 $handles=$webdriver->getWindowHandles();
			 $last_window = end($handles);
			 $webdriver->switchTo()->window($last_window);
		});		
		
		$I->see("Failed");
	}
	
	/*
	if(strcmp($trans, "CGS")!== 0){
		
		$I->redText("Failed in ".$step." line: Description is not correct. Description= ".$trans);
		$I->makeScreenshot('2'.$filename[0].'.fail');
		
		//If failed then focus on game window to take screenshot
		$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webDriver) { //Switch to the parent window

			global $gamewindow;
			$webDriver->switchTo()->window($gamewindow);
		
		});
		
		$I->see("Failed");
		
	}
	*/
	/*
	if(strcmp($debit,"£".$stake)!==0){
		
		$I->redText("Failed in ".$step." line: Debit is not correct. Debit= ".$debit);
		$I->makeScreenshot('2'.$filename[0].'.fail');
		
		//If failed then focus on game window to take screenshot
		$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webDriver) { //Switch to the parent window

			global $gamewindow;
			$webDriver->switchTo()->window($gamewindow);
		
		});
		
		$I->see("Failed");
		
	}
	*/
	
	/*
	if(strcmp($credit,"£0.00")!==0){
		
		$I->redText("Failed in ".$step." line: Debit is not correct. credit= ".$credit);
		$I->makeScreenshot('2'.$filename[0].'.fail');
		
		//If failed then focus on game window to take screenshot
		$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webDriver) { //Switch to the parent window

			global $gamewindow;
			$webDriver->switchTo()->window($gamewindow);
		
		});
		
		$I->see("Failed");
		
	}
	*/
	
	
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
			
				$I->see($date3,'.statement-history tr:nth-of-type(1) td:nth-of-type(1)');
		
			}catch(Exception $date3wrong){
			
				try{
				
					$I->see($date1,'.statement-history tr:nth-of-type(1) td:nth-of-type(1)');
			
				}catch(Exception $date1wrong){
						
					$I->redText("Failed: Date/time is not correct. Date= ".$datetime);
					$I->makeScreenshot('2'.$filename[0].'.fail');
					
					//If failed then focus on game window to take screenshot
					$I->executeInSelenium(function (\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver) {
						 $handles=$webdriver->getWindowHandles();
						 $last_window = end($handles);
						 $webdriver->switchTo()->window($last_window);
					});
					
					$I->see("Failed");	
				}
			}
		}
		
	}
	
	try{
		$I->see('CGS','.statement-history tr:nth-of-type(1) td:nth-of-type(3)');
	}catch(Exception $noEntry){
		
		$I->makeScreenshot('2'.$filename[0].'.fail');

		//If failed then focus on game window to take screenshot
		$I->executeInSelenium(function (\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver) {
			 $handles=$webdriver->getWindowHandles();
			 $last_window = end($handles);
			 $webdriver->switchTo()->window($last_window);
		});		
		
		$I->see("Failed");
	}
	
	/*
	if(strcmp($trans, "CGS Return tx")!== 0){
		
		$I->redText("Failed in ".$step." line: Description is not correct. Description= ".$trans);
		$I->makeScreenshot('2'.$filename[0].'.fail');
		
		//If failed then focus on game window to take screenshot
		$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webDriver) { //Switch to the parent window

			global $gamewindow;
			$webDriver->switchTo()->window($gamewindow);
		
		});
		
		$I->see("Failed");
		
	}
	*/
	
	/*
	if(strcmp($debit,"£0.00")!==0){
		
		$I->redText("Failed in ".$step." line: Debit is not correct. Debit= ".$debit);
		$I->makeScreenshot('2'.$filename[0].'.fail');
		
		//If failed then focus on game window to take screenshot
		$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webDriver) { //Switch to the parent window

			global $gamewindow;
			$webDriver->switchTo()->window($gamewindow);
		
		});
		
		$I->see("Failed");
		
	}

	*/
	
	/*
	if(strcmp($credit,"£".($diff ))!==0){
		
		$I->redText("Failed in ".$step." line: credit is not correct. Difference= ".$diff." and credit says= ".$credit);
		$I->makeScreenshot('2'.$filename[0].'.fail');
		
		//If failed then focus on game window to take screenshot
		$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webDriver) { //Switch to the parent window

			global $gamewindow;
			$webDriver->switchTo()->window($gamewindow);
		
		});
		
		$I->see("Failed");
		
	}
	*/
}

$I->weblogout();

?> 