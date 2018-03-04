<?php
require 'tests/acceptance/_bootstrap.php';

// @group general

$I = new AcceptanceTester($scenario);
$I->wantTo('Buy one Bingo ticket');

$I->amOnPage('/');
$I->maximizeWindow();

$I->amGoingTo('Verify elements on Landing page');
$I->waitForElementVisible('a.loginbtn',30); // Wait for Login button

$I->amGoingTo('Open Bingo page'); 
$I->click('[title="Bingo"]'); //click on Bingo tab

//temporary workaround to reload the bingo page as it started failing a lot on production
//	try{
		$I->waitForElement('#bingoiframe',30); //wait for Bingo iframe
//	}catch(Exception $pageNotLoaded){
//		$I->amGoingTo('Bingo page not laoding. Reloading the page.');
//		$I->reloadPage();
//		$I->waitForElement('#bingoiframe',30); //wait for Bingo iframe
//
//	}

$serverid = $I->grabTextFrom('.hiddensrv'); // Get Server ID
$I->greenText('Server ID is: '.$serverid);

$I->weblogin($userbingo,$passbingo);

$I->waitForElementVisible('span.balance_visible',30);

$funds=$I->grabTextFrom('span.balance_visible');
$funds=filter_var($funds, FILTER_SANITIZE_NUMBER_FLOAT); //get actual balance in Cents

$I->waitForElement('#bingoiframe',30); //wait for Bingo iframe
$I->switchToIFrame('bingoiframe'); // Swithching to Bingo iframe

$I->waitForElement('#lobbyArea',30); //wait for Bingo lobby
$I->waitForElement('#rightSide',30); //wait for Bingo right Panel
$I->waitForElement('#lobbyHeader',30); //wait for Bingo lobby header
$I->waitForElement('#gamesChooser',30); //wait for Bingo games chooser

$I->waitForElementVisible('.balanceDisplay .changed',30);

$fundsBingo=$I->grabTextFrom('.balanceDisplay .changed');
	
	if (strpos($fundsBingo, '.') !== false) {
		$fundsBingo=filter_var($fundsBingo, FILTER_SANITIZE_NUMBER_FLOAT); //get actual Bingo lobby balance in Cents
	}else{
		$fundsBingo=filter_var($fundsBingo, FILTER_SANITIZE_NUMBER_FLOAT); //get actual Bingo lobby balance in Cents
		$fundsBingo = $fundsBingo*100;
	}
	
$I->amGoingTo('Actual Funds => '.($funds/100).' and Actual Bingo Lobby funds => '.($fundsBingo/100));

$I->amGoingTo('Open Bingo game window');
$I->waitForElementVisible('.roomTile:nth-child(1) .atvImg-shine',30); //wait for Bingo lobby
$I->click('.roomTile:nth-child(1) .atvImg-shine');

$I->waitForElementVisible('#jackpotDisplay',30); //wait for Jackpot popup
$I->waitForElementNotVisible('#jackpotDisplay',30); //wait for Jackpot popup to disappear

$I->waitForElementVisible('#ticketControls',30); //wait for Bingo ticket control panel
$I->waitForElementVisible('#loyaltyMeter',30); //wait for Bingo loyalty Meter
$I->waitForElementVisible('#messageTab',30); //wait for Bingo chat
$I->waitForElementVisible('#scroller > div:nth-child(1)',30); //wait for fisrt Bingo ticket
$I->waitForElementVisible('.ticketPriceField',30); //wait for  Bingo ticket price

checkTimer: // to come back if the game round is about to start
$roundTimer=$I->grabTextFrom('#roundTimer');
$roundTimer=filter_var($roundTimer, FILTER_SANITIZE_NUMBER_FLOAT); //get round time in seconds

	if ($roundTimer > 15){
		$I->amGoingTo($roundTimer.' left until Bingo round start. Buying a ticket.');
	} else{
		$I->amGoingTo($roundTimer.' left until Bingo round start. Waiting for new round.');
		$I->wait(5);
		goto checkTimer;
	}

$I->amGoingTo('buy one Bingo ticket');

$ticketPrice=$I->grabTextFrom('.ticketPriceField');
$roomName=$I->grabTextFrom('#roomInfo .header');
$I->click('.plusButton');
$I->wait(1);
//$I->see('1','.stepperInput');
$I->see($ticketPrice,'#ticketBuyButton');
$I->click('#ticketBuyButton');
$I->waitForText('Purchased',10,'#earlierTicketPurchaseNotice');
$I->wait(3);

$I->amGoingTo('Check Bingo balance');
$fundsBingoAfter=$I->grabTextFrom('.balanceDisplay .changed');


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

// Get the current time
date_default_timezone_set('Europe/London');
$date=date('d M Y H:i');
$date1=date('d M Y H:i',strtotime('+1 minute'));
$date2=date('d M Y H:i',strtotime('-1 minute'));
$date3=date('d M Y H:i',strtotime('-2 minute'));	
	
	
//Navigate to Statement
$I->amGoingTo('Navigate to Statement');
$I->switchToIFrame(); // Swithching out of iframe
$I->wait(2);
$I->waitForElementVisible('[title="Display account details"]',30);
$I->click('[title="Display account details"]');
$I->waitForElementVisible('[title="Statement"]',2);
$I->click('[title="Statement"]');
$I->waitForElementVisible('.statement-history',30);

$I->amGoingTo('Check Main balance');
$I->waitForElementVisible('span.balance_visible',30);
$I->click('.accountbalancerefresh');
$I->wait(1);
$I->click('.accountbalancerefresh');
$fundsAfter=$I->grabTextFrom('span.balance_visible');
$fundsAfter=filter_var($fundsAfter, FILTER_SANITIZE_NUMBER_FLOAT); //get actual balance in Cents
$I->amGoingTo('Main Funds => £'.$fundsAfter/100); //Shows Main Funds

$diff2 = $funds - $fundsAfter; // balance difference before purchase and after

	if ($diff2 == $ticketPriceSanitized){
		$I->greenText('Main Balance was updated correctly');
	} else{
		$I->redText('Main Balance was NOT updated correctly');
		$I->see('TERMINATE TEST DUE FAILURE');
	}
	
//click on Debit/Credit
	try{

		$I->waitForElementVisible('[name="TransactionTypeRadio"][value="101"]',2);
		$I->click('[name="TransactionTypeRadio"][value="101"]');
		$I->waitForText('Balance history',30,'.tab_content.accountcontent h4');
		$I->waitForElementVisible('.statement-history tr:nth-of-type(1) td:nth-of-type(2)',30);
	} catch(Exception $emptyStatement){
		$I->redText('Statement does not have any records');
		$I->makeScreenshot('2'.$filename[0].'.fail');
		
		$I->see('TERMINATE, STATEMENT EMPTY');
	}	

//Check for Statement records
$I->amGoingTo('Possible Time when Bingo was played: '.$date.' ------------ '.$date2.' -------------- '.$date3.' ------------ '.$date1); 	

$datetime=$I->grabTextFrom('.statement-history tr:nth-of-type(1) td:nth-of-type(1)');
$trans=$I->grabTextFrom('.statement-history tr:nth-of-type(1) td:nth-of-type(3)');
$debit=$I->grabTextFrom('.statement-history tr:nth-of-type(1) td:nth-of-type(4)');
$credit=$I->grabTextFrom('.statement-history tr:nth-of-type(1) td:nth-of-type(5)');
$I->amGoingTo('First record data:'.$datetime.' - '.$trans.' - '.$debit.' - '.$credit);
	
// check Date	
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
						
							$I->redText('Failed: Date/time in Statement is not correct. Date = '.$datetime);
							$I->see('Failed'); // fail test
						}
					}
				}
			}

// check Description		
	try{
		$I->see('Relax Bingo Stake','.statement-history tr:nth-of-type(1) td:nth-of-type(3)');
	}catch(Exception $noEntry){
		$I->redText('Transaction deskription is not correct');
		$I->see('Failed'); // fail test
	}

// check Debit amount		
	if(strcmp($debit,$ticketPrice)!==0){
		
		$I->redText('Failed: Debit is not correct. Debit should be £'.$ticketPrice.' And it is showing '.$debit);
		$I->see('Failed'); // fail test
		}
	
$I->weblogout();

$I->amGoingTo('RESULTS');
$I->greenText('Bingo ticket was bought for '.$ticketPrice.' in '.$roomName.' room.');

?>