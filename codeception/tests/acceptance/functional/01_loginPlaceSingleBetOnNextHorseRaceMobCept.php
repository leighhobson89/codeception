<?php
require 'tests/acceptance/_bootstrap.php';
define ('SINGLE', "Single"); //used to check the bet receipt has this as its header if the bet is a normal bet
define ('EWSINGLE', "EW Single"); //used to check the bet receipt has this as its header if the bet is an each way bet
define ('OPENSTATUS', "OPEN");  //used to check the bet receipt has this as its status after it is placed
$ewToggle = 0; //must be defined

// @group general

//=====================Login to site and assert that initialization is complete=====================
$I = new AcceptanceTester($scenario);
$I->wantTo('Log in to Unibet Frankel site, place a bet on a horse race and check I have placed that bet');
$I->resizeWindow(1280, 1000); // iPhone 7 size
//$I->amOnPage('/hidden/racing/');
$I->amOnPage('/betting/beta-17121801/');


$I->amGoingTo('Login to the site.');
$I->waitForElementVisible('form[data-test-name="container-login-header"] input[name="username"]',30); // Wait for Username field
$I->waitForElementVisible('form[data-test-name="container-login-header"] input[name="password"]',30); // Wait for Password field
$I->waitForElementVisible('form[data-test-name="container-login-header"] button[data-test-name="btn-login"]',30); // Wait for Login Button

$I->waitForElementVisible('.fkrc-carousel',30); // Wait for Next Race Carousel
$I->waitForElementVisible('.fkrc-bet-slip-section',30); // Wait for Betslip
$I->waitForElementVisible('.fkrc-race-grid',30); // Wait for Race Grid

$I->fillField('form[data-test-name="container-login-header"] input[name="username"]',$username_01);
$I->fillField('form[data-test-name="container-login-header"] input[name="password"]',$password_01);
$I->click('form[data-test-name="container-login-header"] button[data-test-name="btn-login"]');
$I->waitForElementVisible('.deposit-button',30); // Wait for Deposit Button to confirm logged in
$I->waitForElementVisible('.fkrc-carousel__container--next-race',30); // Wait for Race Market to appear in carousel
//==================================================================================================

//================Temporary workaround for decimal odds problem until proper integration of odds format changer==========================
$I->click('.fkrc-menu-slider__odds-section .Dropdown-arrow');
$I->click('.fkrc-menu-slider__odds-section .Dropdown-option:last-child');
//=========================================================================================================================================

start: //return point if bet that was going to be placed somehow closes before it can be.
$I->amGoingTo('Add a horse racing market to the Betslip');

//===========Add Markets to Betslip=============================================================
// Check if there is a market available to add betslip


for ($x=1;$x<=4;$x++){
		
		$raceNumber=$x;
		$selectionsArray=$I->grabMultiple(".fkrc-carousel__container--next-race:nth-child($raceNumber) .fkrc-racing-event__table__list__item__fixed-odd-win");
		$selections=count($selectionsArray);
		
		if ($selections >0){
			$x=5;
			$I->comment("Race $raceNumber has $selections selections available.");
		}
		
	}
	
		if ($selections <= 0){
			$I->redText("No Selections available in any of the next races.");
			$I->see("FAILING TEST");
		}

$I->amGoingTo('Count the number of runners so we know if we can do an Each Way bet or not.');
$I->click(".fkrc-carousel__container--next-race:nth-child($raceNumber) .fkrc-race-item-detail");
$I->waitForElementVisible('.fkrc-racing-event', 30); // click in to race to count number of runners
$runnerCountArray=$I->grabMultiple(".//div[@class='fkrc-racing-event__table__list__item pure-u-1']");   //counts number of runners without including the scratched ones
		$numberOfRunners=count($runnerCountArray); 
		$I->comment("$numberOfRunners runners in race.");
$I->click(".fkrc-race-course__left-text"); //return to main page.
$I->waitForElementVisible('.fkrc-carousel__container--next-race',30); // Wait for Race Market to appear in carousel

$runnerSelection = rand(1, ($selections)); // Randomly selects a runner from the race
//===============================================================================================

//===========Read in Runner and Race Information==============
$raceLocation = $I->grabTextFrom(".fkrc-carousel__container--next-race:nth-child($raceNumber) .fkrc-race-item-detail__row2__race-course-name");
$raceTime = $I->grabTextFrom(".fkrc-carousel__container--next-race:nth-child($raceNumber) .fkrc-race-item-detail__row2__race-time");
$runnerName = $I->grabTextFrom(".fkrc-carousel__container--next-race:nth-child($raceNumber) .fkrc-carousel__race-list__item:nth-child($runnerSelection) .fkrc-carousel__race-list__item__runner-details__horse-name");
$runnerOdds = $I->grabTextFrom(".fkrc-carousel__container--next-race:nth-child($raceNumber) .fkrc-carousel__race-list__item:nth-child($runnerSelection) .fkrc-racing-event__table__list__item__fixed-odd-win:first-child");
$runnerOdds = (float)$runnerOdds;

if ($numberOfRunners >= 5){ //Checks if enough runners to even consider an Each Way bet
	$ewToggle = rand(0,1); //If there are enough runners, decide yes or no whether or not to do an Each Way bet.
}
//============================================================

// Debug Variable Information
$I->comment("Location: $raceLocation , Time: $raceTime , Horse Name: $runnerName , Odds: $runnerOdds , E/W?: $ewToggle");

//=============Add selection to betslip=============
$I->click(".fkrc-carousel__container--next-race:nth-child($raceNumber) .fkrc-carousel__race-list__item:nth-child($runnerSelection) .fkrc-racing-event__table__list__item__fixed-odd-win:first-child"); // Add Market to Betslip
$I->amGoingTo('Verify that the competitor chosen is the one we see on the Betslip, and that all other information is shown correctly.');
//==================================================

//=============Assert that correct details have been added and show on betslip==================
$I->see($raceLocation, '.fkrc-bet-item__segment1__race-course'); // Verifies that correct race location is added to the betslip
$I->see($raceTime, '.fkrc-bet-item__segment1__time'); // Verifies that correct race time is added to the betslip
$I->see($runnerName, '.fkrc-bet-item__segment2__selection-detail'); // Verifies that correct horse selection is added to the betslip
$I->see($runnerOdds, '.fkrc-bet-item__segment3');// Verifies that correct odds are added to the betslip
if ($numberOfRunners < 5) {
	$I->dontSeeElement(".fkrc-checkbox"); //checks that checkbox didn't appear after selection added to betslip if number of runners is less than 5 for that selection.
}
//==============================================================================================

// ============Enter Stake and MAYBE tick EW box (if EW is even available)============
$I->amGoingTo('Enter the Stake, and if the random number variable decides it will be an each way bet, I will check the EW box too.');
$I->fillField('.fkrc-bet-item__segment3__text-input', '0.1'); // Enters a stake of 10p
$stakedAmount = $I->grabValueFrom('.fkrc-bet-item__segment3__text-input');
$I->waitForText('Place Bet', 30, '.fkrc-bet-slip__payout__place-bet--yellow'); // Sometimes can temporarily display "Log in to Place a Bet" when betslip first opened, so this step forces it to wait for the text to update before continuing.
$receiptHeader = SINGLE; //initialises bet receipt header variable
if ($ewToggle == 1) {
	$I->comment("This WILL be an Each Way Bet.");
	$receiptHeader = EWSINGLE; //initialises bet receipt header variable
	$I->clickWithLeftButton(['css' => '.fkrc-checkbox'], 0, 10);
	$I->see($runnerOdds, '.fkrc-bet-item__segment3');// Verifies that correct odds are added to the betslip (do this last minute in case the odds change)
	$ewTerms = $I->grabTextFrom('.fkrc-bet-item__segment4__each-way__value');
	$I->see($ewTerms, '.fkrc-bet-item__segment4__each-way__value'); // Check here if EW terms are appearing on betslip, to double confirm the checkbox is ticked.
	$ewTerms2 = substr($ewTerms, 6, 5); //grabs info about places that EW applies to, e.g. 1,2,3.
	$ewTerms = substr($ewTerms, 0, 3);	//grabs info about amount paid for EW bet, e.g. 1/5.
	$I->comment("The Each Way Terms Are: $ewTerms");
}
//=====================================================================================

//==================Calculate Potential Payout=================
calculationPotentialPayout:

if ($ewToggle == 1) {
	$I->comment("This WILL be an Each Way Bet.");
	$operatorOneEw = substr($ewTerms, 0, 1); //Pulls out top half of fraction
	$operatorTwoEw = substr($ewTerms, 2, 1); //Pulls out bottoms half of fraction
	$ewDecimal = $operatorOneEw/$operatorTwoEw; // Writes decimal value of fraction for EW Calculation for Potential Payout
	$potentialPayout = $stakedAmount * $runnerOdds + ($ewDecimal * ($runnerOdds-1) * $stakedAmount) + $stakedAmount; //Potential Payout calculation.
	$fraction = substr($potentialPayout - floor($potentialPayout), 2, 2); //calculates the decimal places to 2 decimal places.
    $potentialPayout = floor($potentialPayout). '.' .$fraction;
	$potentialPayout = number_format($potentialPayout, 2, '.', ''); //ensures it adds an extra zero for comparison with webpage if number was a multiple of 10.  i.e. 0.3 would become 0.30 for asserts (if required)
}
else {
	$ewDecimal = 0;
	$I->comment("This WILL NOT be an Each Way Bet.");
	$potentialPayout = ($stakedAmount * $runnerOdds) + $stakedAmount;
	$potentialPayout = $stakedAmount * $runnerOdds + ($ewDecimal * ($runnerOdds-1) * $stakedAmount);// + $stakedAmount; //Potential Payout calculation.
	$fraction = substr($potentialPayout - floor($potentialPayout), 2, 2); //calculates the decimal places to 2 decimal places.
    $potentialPayout = floor($potentialPayout). '.' .$fraction;
	$potentialPayout = number_format($potentialPayout, 2, '.', ''); //ensures it adds an extra zero for comparison with webpage if number was a multiple of 10.  i.e. 0.3 would become 0.30 for asserts (if required)
}
//=============================================================

//===================If necessary, update odds if they change during execution of test, and set a flag for the Accept New Offer button==============================
$updatedRunnerOdds = $I->grabTextFrom(".fkrc-bet-item__segment3__odds-value");
if ($runnerOdds != $updatedRunnerOdds) {
	$runnerOdds = $updatedRunnerOdds;
	$I->redText("Odds changed to $runnerOdds, but this has been taken in to account for the remainder of the test.");
	$I->click('.fkrc-bet-slip__payout__accept-changes__accept-btn');
	goto calculationPotentialPayout;
}
//==================================================================================================================================================================
//======================Betslip Calculation Assertions=====================
$I->amGoingTo('Check that betslip calculations are correct.');
if ($ewToggle == 1) {
	$stakedAmount = $stakedAmount * 2; //will be used for Total Stake assertion further down.
}
$stakedAmount = number_format($stakedAmount, 2, '.', ''); //ensures stake is converted to two digit format for comparison.
$I->comment("Potential Payout is $potentialPayout.");
$I->see($potentialPayout, '.fkrc-bet-slip__payout__potential-payout__value'); // checks calculation matches betslip calculation
$I->see($potentialPayout, '.fkrc-bet-item__segment4__payout'); // checks payout for individual bet matches calculation
$I->see($stakedAmount, '.fkrc-bet-slip__payout__total-stake__value'); // checks total stake is correct even if adjusted for EW bet
$I->greenText("Betslip calculations are CORRECT!");
//=========================================================================

//=============Ensure bet hasn't closed while we have been preparing to place it================
if (count($I->grabMultiple(".fkrc-bet-slip__error-review")) > 0) {
	goto start; //restart the test if the bet had somehow closed between adding the race to the betslip and placing the bet.
}
//==============================================================================================

//======================Place Bet==============================
$I->amGoingTo('Place the bet.');
$I->waitForText('Place Bet', 30, '.fkrc-bet-slip__payout__place-bet--yellow');
$I->click('.fkrc-bet-slip__payout__place-bet--yellow'); // Clicks the "place bet" button
$I->waitForElementVisible('.fkrc-bet-receipt-ticket__body',30); //wait for bet receipt to show
//=============================================================

//======================Assert that bet receipt contains correct information=======================
$I->amGoingTo('Check all the information on the betslip receipt.');
$I->see($receiptHeader, '.fkrc-bet-receipt-ticket__header'); //Receipt Header
$I->see($raceTime, '.fkrc-bet-receipt-ticket__event-time'); //Time
$I->see($raceLocation, '.fkrc-bet-receipt-ticket__event-course'); //Race Course
$I->see($runnerName, '.fkrc-bet-receipt-ticket__runner'); //Runner
$I->see($runnerOdds, '.fkrc-bet-receipt-ticket__bet-details'); //Odds
if ($ewToggle == 1) {
	$I->see($ewTerms, '.fkrc-bet-receipt-ticket__bet-details'); //Amount paid for EW bet, e.g. 1/5
	$I->see($ewTerms2, '.fkrc-bet-receipt-ticket__bet-details'); //Places that EW applies to, e.g. 1,2,3
}
$I->see($stakedAmount, '.fkrc-bet-receipt-ticket__stake'); //Stake including EW if applicable
//$I->see($potentialPayout, '.fkrc-bet-receipt-ticket__payout'); // Payout on Win -- WILL CAUSE TEST TO FAIL UNTIL JIRA TICKET KRP-283 IS FIXED
$I->see(OPENSTATUS, '.fkrc-bet-receipt-ticket__status'); //Checks bet shows as OPEN
//==================================================================================================

//=====================Assert that Open Bets shows correct information (possibly not necessary as uses same class names as receipt tab)==============================
$I->amGoingTo('Check all the information on Open Bets on the betslip concerning the bet just placed.');
$I->click('.fkrc-bet-slip .fkrc-bet-slip__header__bets-type__slip-btn:nth-child(2)'); // Clicks the "Open Bets" tab on the betslip.
$I->see($receiptHeader, '.fkrc-bet-receipt-ticket__header'); //Receipt Header
$I->see($raceTime, '.fkrc-bet-receipt-ticket__event-time'); //Time
$I->see($raceLocation, '.fkrc-bet-receipt-ticket__event-course'); //Race Course
$I->see($runnerName, '.fkrc-bet-receipt-ticket__runner'); //Runner
$I->see($runnerOdds, '.fkrc-bet-receipt-ticket__bet-details'); //Odds
if ($ewToggle == 1) {
	$I->see($ewTerms, '.fkrc-bet-receipt-ticket__bet-details'); //Amount paid for EW bet, e.g. 1/5
	$I->see($ewTerms2, '.fkrc-bet-receipt-ticket__bet-details'); //Places that EW applies to, e.g. 1,2,3
}
$I->see($stakedAmount, '.fkrc-bet-receipt-ticket__stake'); //Stake including EW if applicable
//$I->see($potentialPayout, '.fkrc-bet-receipt-ticket__payout'); // Payout on Win -- WILL CAUSE TEST TO FAIL UNTIL JIRA TICKET KRP-283 IS FIXED
$I->see(OPENSTATUS, '.fkrc-bet-receipt-ticket__status'); //Checks bet shows as OPEN
//====================================================================================================================================================================

//======================Assert that My Racing Bets (Open) shows the correct information===============================================================================
$I->amGoingTo('Check all the information in My Racing Bets (Open) concerning the bet just placed.');
$I->click('.fkrc-menu-item__icon__my-bets'); //Click My Racing Bets in the side menu.
$I->see($receiptHeader, '.main-content .fkrc-bet-receipt-separator:first-child .fkrc-bet-receipt-ticket__header'); //Receipt Header
$I->see($raceTime, '.main-content .fkrc-bet-receipt-separator:first-child .fkrc-bet-receipt-ticket__event-time'); //Time
$I->see($raceLocation, '.main-content .fkrc-bet-receipt-separator:first-child .fkrc-bet-receipt-ticket__event-course'); //Race Course
$I->see($runnerName, '.main-content .fkrc-bet-receipt-separator:first-child .fkrc-bet-receipt-ticket__runner'); //Runner
$I->see($runnerOdds, '.main-content .fkrc-bet-receipt-separator:first-child .fkrc-bet-receipt-ticket__bet-details'); //Odds
if ($ewToggle == 1) {
	$I->see($ewTerms, '.main-content .fkrc-bet-receipt-separator:first-child .fkrc-bet-receipt-ticket__bet-details'); //Amount paid for EW bet, e.g. 1/5
	$I->see($ewTerms2, '.main-content .fkrc-bet-receipt-separator:first-child .fkrc-bet-receipt-ticket__bet-details'); //Places that EW applies to, e.g. 1,2,3
}
$I->see($stakedAmount, '.main-content .fkrc-bet-receipt-separator:first-child .fkrc-bet-receipt-ticket__stake'); //Stake including EW if applicable
//$I->see($potentialPayout, '.fkrc-bet-receipt-ticket__payout'); // Payout on Win -- WILL CAUSE TEST TO FAIL UNTIL JIRA TICKET KRP-283 IS FIXED
$I->see(OPENSTATUS, '.main-content .fkrc-bet-receipt-separator:first-child .fkrc-bet-receipt-ticket__status'); //Checks bet shows as OPEN
//=====================================================================================================================================================================

?>