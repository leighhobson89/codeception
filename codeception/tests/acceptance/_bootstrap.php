<?php
// Here you can initialize variables that will be available to your tests

$env = $_SERVER['argv'];
$environment = $env[4];

// STAGE VARIABLES
if(strpos($environment, "qa")!==false){

$username_01 = ('qaleigh1981');
$password_01 = ('Burnleyfc81');

}

// PRODUCTION VARIABLES
if((strpos($environment, "prod")!==false)){

$username_01 = ('');
$password_01 = ('');

}






/*

// STAGE VARIABLES
if(strpos($environment, "stage")!==false){
$url="http://www.stagestanjames.com";

$username_01 = ('petras34');
$password_01 = ('Password');
$username_02 = ('danieltest3');
$password_02 = ('123456');

$cardUser = ('qadanieltest1');
$cardUserPass=('111111');
$cardCVV=('610');

$nocardUser = ('qadanieltest2');
$nocardUserPass=('111111');
$paypaluser=('qauat9paypal');
$paypaluserpass=('Password123');
$paypalemail=('automation@stanjames.com');
$paypalsitepass=('Password123');
$paypalsitetitle=('Pay with a PayPal account - PayPal');
$nettelleruser=('qanettellertest');
$nettelleruserpass=('111111');
$skrilluser=('');
$skrilluserpass=('');
$skrillemail=('coneill@stanjames.com');
$skrillsitepass=('QaTest101');
$skrillsitetitle=('Payment Gateway');
$termsuser=('qatermsauto');
$termsuserpass=('111111');
$gamestestuser = ('');
$gamestestuserPass = ('');
$webgamestestuser = ('');
$webgamestestuserPass = ('');
$activitytestuser = ('');
$activitytestPass = ('');
$excludedtestuser = ('qaselfexclusion'); // Self Excluded user - all products
$excludedtestPass = ('111111');
$logintestuser = ('qalaimonas100');
$logintestuserPass = ('111111');
$userbingo = (''); /// Bingo test account
$passbingo = ('');


}

// PRODUCTION VARIABLES
if((strpos($environment, "hl")!==false) or (strpos($environment, "prod")!==false)){

//login details
$username_01 = ('qaautomation1'); // account used to place bets: 01_loginCept, 03_BetCept, 04_5betsCept
$password_01 = ('111111');
$username_02 = ('qaautomation2'); // account to use for forgot password: 05_forgotPassCept
$password_02 = ('111111');
$username_03 = ('qaautomation3'); // account to use for forgot password: 06_updatePassCept
$password_03 = ('111111');
$password_02 = ('111111');
$username_04 = ('qaautomation4'); // account to use for MobBetCept
$password_04 = ('111111');

$cardUser = ('qalaimonas1'); // account for Card deposit
$cardUserPass=('999999');
$cardCVV=('610');

$paypaluser=('qapptest2');
$paypaluserpass=('111111');
$paypalemail=('lguiga@stanjames.com');
$paypalsitepass=('Password123');
$paypalsitetitle=('Pay with a PayPal account');
$nettelleruser=('qanettellertest');
$nettelleruserpass=('Password123');
$skrilluser=('qambtest');
$skrilluserpass=('Password123');
$skrillemail=('coneill@stanjames.com');
$skrillsitepass=('QaTest101');
$skrillsitetitle=('Payment Gateway');
$nocardUser = ('qadanieltest3');
$nocardUserPass=('111111');
$termsuser=('qatermsauto');
$termsuserpass=('111111');
$gamestestuser = ('qagamestester'); // Mobile casino games user
$gamestestuserPass = ('111111');
$logintestuser = ('qalogintest'); // User to test log in and balance
$logintestuserPass = ('111111');

//$webgamestestuser = ('qawebautocasino'); // Web casino games user
$webgamestestuser = ('qawebautocasin2');
$webgamestestuserPass = ('111111');
$activitytestuser = ('qaactivitytest'); // Activity Alert user with 10 seconds setting
$activitytestPass = ('111111');
$excludedtestuser = ('qaselfexclusion'); // Self Excluded user - all products
$excludedtestPass = ('111111');
$userbingo = ('qabingotest'); /// Bingo test account
$passbingo = ('111111');
$usermobbingo = ('qamobbingotest'); /// Bingo test account
$passmobbingobingo = ('111111');


*/




?> 