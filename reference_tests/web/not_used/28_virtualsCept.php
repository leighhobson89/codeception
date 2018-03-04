<?php
//Preparations for testing
//shell_exec('RunDll32.exe InetCpl.cpl,ClearMyTracksByProcess 2'); // Clear cookies and cache
require 'tests/acceptance/_bootstrap.php';

// @group general

$I = new AcceptanceTester($scenario);
$I->wantTo('Check Virtuals Odds');

$I->amOnPage('/UK/802/virtual#idfosporttype=BRVIRFOOT');
$I->maximizeWindow();

//FOOTBALL
//Check Iframe elements
$serverid = $I->grabTextFrom('.hiddensrv'); // Get Server ID
$I->greenText('Server ID is: '.$serverid);

$virtualiframe=$I->grabAttributeFrom('.virtual_display iframe', 'id'); //Get Iframe ID
$I->switchToIFrame($virtualiframe);
		
$I->seeElement('#ticker #srlive_eventticker');
$I->seeElement('#video');
$I->seeElement('#onOff');
//$I->seeElement('#srlive_eventticker_match'); This is not available when the match ends
$I->seeElement('#srlive_matchlist');
$I->seeElement('#srlive_table');
$I->seeElement('#timeline');
$I->seeElement('#matchday_box');
			
$I->switchToIFrame();		
			
//Iframe end

$I->executeJS('document.querySelector(".virtual_display iframe").scrollIntoView(true)'); //Scroll to Bet Options Buttons for screenshot
	
$I->see('3 WAY','.virtualtabs ul li:nth-child(1)'); //3 Way
$I->see('HANDICAP','.virtualtabs ul li:nth-child(2)'); //Handicap
$I->see('UNDER/OVER','.virtualtabs ul li:nth-child(3)'); //Under/Over
$I->see('CORRECT SCORE','.virtualtabs ul li:nth-child(4)'); //Correct Score
		
if(strpos($environment,'uat9')===false){ //if environment is uat9 then bypass virtuals checking
	//Check all Bet Options are in there

	global $oddOpacity,$iteration;

	$iteration=0;
	football: //Loop start for football

	 //('.virtualtabs div:nth-of-type(1)', 'style.opacity');
	
	$I->waitForElementVisible('div.virtualtabs .footballmatchhighlights tbody.master:nth-child(2) a.odds_text_link',120); //Wait for the prices to show
	
	$oddOpacity=$I->grabAttributeFrom('.virtualtabs div:nth-of-type(1)', 'style');
	
	while($oddOpacity!="opacity: 1;"){ //Check div opacity is 1 
		
		$I->wait(5); //This loop will be checking div opacity every 5 seconds
		$oddOpacity=$I->grabAttributeFrom('.virtualtabs div:nth-of-type(1)', 'style');
		$I->amGoingTo("The prices are greyed out. Opacity -> ".$oddOpacity);
	}
	
	$I->executeJS('var highestTimeoutId = setTimeout(";");for (var i = 0 ; i < highestTimeoutId ; i++) {clearTimeout(i);}'); //Stop Auto Refresh
	$I->executeJS('document.querySelector("div.virtualtabs .footballmatchhighlights tbody.master:nth-child(9) a.odds_text_link").scrollIntoView(false)'); //Scroll into the last element to make all the races visible on the screen

	for($x=2;$x<=9;$x++){ //Loop for the Rows
		for($y=2;$y<=6;$y+=2){ //Loop for the columns
			for($z=1;$z<=3;$z++){ //Loop for the prices in columns
				//$I->wait(0.33);
				try{
					$I->waitForElementVisible('div:nth-child(1) .footballmatchhighlights .master:nth-of-type('.$x.') .no_border_right.no_border_left:nth-child('.$y.') td:nth-child('.$z.')',30);
					//$I->seeElement('.footballmatchhighlights .master:nth-of-type('.$x.') .no_border_right.no_border_left:nth-child('.$y.') td:nth-child('.$z.')'); //bet offer
				}catch(Exception $E){
					
					if($iteration<=0){
						
						global $iteration;
						$I->redText("First Iteration failed (Probably the prices were changing at that time), lets try again!");
						$iteration=1;
						goto football;
					}else{	
						$I->redText("Second Iteration Failed");
						$I->see("Failed");
					}
				
				}
			}
		}
	}	
}
		
//Tennis
$I->executeJS('document.querySelector(".virtual_navigation li:nth-child(2) a").scrollIntoView(false)');// Scroll to top to make Tennis tab visible
$I->click('.virtual_navigation li:nth-child(2) a');
$I->waitForElementVisible('div.virtualtabs .footballmatchhighlights tbody.master:nth-child(2) a.odds_text_link',120);
$I->executeJS('var highestTimeoutId = setTimeout(";");for (var i = 0 ; i < highestTimeoutId ; i++) {clearTimeout(i);}'); //Stop Auto Refresh

//Check IFrame Elements
$virtualiframe=$I->grabAttributeFrom('.virtual_display iframe', 'id'); //Get Iframe ID 

$I->switchToIFrame($virtualiframe);
$I->seeElement('.titlebar'); //titlebar
$I->seeElement('.tennisball');
$I->seeElement('#match_wrapper');
$I->seeElement('#video_container');
$I->seeElement('#clock');
$I->seeElement('#upcoming');
$I->seeElement('#place_bets');
		
$I->switchToIFrame();
		
//IFrame End
		
$I->executeJS('document.querySelector(".virtual_display iframe").scrollIntoView(true)'); //Scroll to Bet Options Buttons for screenshot
$I->see('WINNER','.virtualtabs ul li:nth-child(1) a'); //Winner
$I->see('ODD/EVEN - UNDER/OVER','.virtualtabs ul li:nth-child(2) a'); //ODD/EVEN
$I->see('CORRECT SCORE','.virtualtabs ul li:nth-child(3) a'); //Correct Score
		
if(strpos($environment,'uat9')===false){ //if environment is uat9 then bypass virtuals checking

loop:
$I->waitForElementVisible('div.virtualtabs .footballmatchhighlights tbody.master:nth-child(2) a.odds_text_link',120); //Wait for prices to appear
$gamesNo=sizeof($I->elementsArray('div.virtualtabs .footballmatchhighlights .odds_text_link'));	//Get number of games

$I->executeJS('document.querySelector("div.virtualtabs .footballmatchhighlights tbody.master:nth-child('.($gamesNo+1).') a.odds_text_link").scrollIntoView(false)'); //Scroll into the last element to make all teh races visible on the screen
$iteration=0;



	for($x=2;$x<=$gamesNo+1;$x++){ //Loop for the Rows
		for($y=2;$y<=6;$y+=2){ //Loop for the Columns
			for($z=1;$z<=2;$z++){ //Loop for the prices inside Columns
				//$I->wait(0.33);
				try{
					$I->waitForElementVisible('div:nth-child(1) .footballmatchhighlights .master:nth-of-type('.$x.') .no_border_right.no_border_left:nth-child('.$y.') td:nth-child('.$z.')',30);
				}catch(Exception $E){
					if ($iteration<=0){
						$I->redText("First Iteration Failed, let's do second");
						$iteration=1;
						goto loop;
					}else{
						
						$I->redText("Test Failed");
						$I->seeElement("Failed");
					}
				}
				//$I->seeElement('.footballmatchhighlights .master:nth-of-type('.$x.') .no_border_right.no_border_left:nth-child('.$y.') td:nth-child('.$z.')'); //bet offer
			}
		}
	}
}

		
		
		


?>