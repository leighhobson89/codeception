<?php
require 'tests/acceptance/_bootstrap.php';

// @group general

//Creating variables with timestamp for User registration data (to ensure the data is unique)
$tstmp = time();
$tstmpstr= strval($tstmp);
settype($tstmpstr, "string");
$tstmpletter=NULL;
$long= strlen($tstmpstr);
$x=0;

for ($x=0; $x<=($long)-1; $x++) {
  
  $tstmpletter=$tstmpletter.num_to_letter(substr($tstmpstr,$x,1));
    
} 
// End of variables preparation


$I = new AcceptanceTester($scenario);

$I->wantTo('Check that registartion form is fine, NOT completing the registration');

//Open homepage
$I->amGoingTo('Open website');
$I->amOnPage('/');
$I->resizeWindow(411, 731); // Nexus5 size

$I->amGoingTo('Verify elements on Landing page');
$I->waitForElementVisible('.cta',30);

$I->amGoingTo('Open Registration page');
$I->click('#registerButton');
$I->waitForElement('#reg-button',30);

$I->amGoingTo('Fill all registartion fields');
$I->seeElement('#edit-title');
$I->seeOptionIsSelected('#edit-title','Mr');
$I->selectOption('#edit-title','Mrs');
$I->seeOptionIsSelected('#edit-title','Mrs');

$I->seeElement('#edit-first-name');
$I->fillField('#edit-first-name','QA name '.$tstmpletter);

$I->seeElement('#edit-last-name');
$I->fillField('#edit-last-name','QA surname '.$tstmpletter);

$I->seeElement('#edit-dob-day');
$I->selectOption('#edit-dob-day','1');
$I->seeOptionIsSelected('#edit-dob-day','1');

$I->seeElement('#edit-dob-month');
$I->selectOption('#edit-dob-month','Jan');
$I->seeOptionIsSelected('#edit-dob-month','Jan');

$I->seeElement('#edit-dob-year');
$I->selectOption('#edit-dob-year','1980');
$I->seeOptionIsSelected('#edit-dob-year','1980');

$I->seeElement('#edit-country');
$I->seeOptionIsSelected('#edit-country','United Kingdom');

$I->seeElement('#edit-housenum');
$I->fillField('#edit-housenum','1');

$I->seeElement('#edit-postcode');
$I->fillField('#edit-postcode','WR2 6NJ');

$I->click('#findPostCodeId'); // Find a postcode
$I->waitForElementVisible('#edit-address');
$I->waitForElementVisible('#edit-town');
$I->seeInField('#edit-address','1 The Cottages, Moseley Road, Hallow');
$I->seeInField('#edit-town','Worcester');

$I->seeElement('#edit-email');
$I->fillField('#edit-email','qaauto+'.$tstmp.'@stanjames.com');

$I->seeElement('#edit-phone');
$I->fillField('#edit-phone',$tstmp);

$I->seeElement('#edit-username');
$I->fillField('#edit-username','qaaut'.$tstmp);

$I->seeElement('#edit-password');
$I->fillField('#edit-password','111111');

$I->seeElement('#edit-passconfirm');
$I->fillField('#edit-passconfirm','111111');

$I->seeElement('#edit-currency');
$I->seeOptionIsSelected('#edit-currency','UK Sterling');

$I->seeElement('#edit-security-question');
$I->seeOptionIsSelected('#edit-security-question','Memorable Text');
$I->selectOption('#edit-security-question','Where were you born?');
$I->seeOptionIsSelected('#edit-security-question','Where were you born?');

$I->seeElement('#edit-security-question-answer');
$I->fillField('#edit-security-question-answer','QA Gibraltar');

$I->seeElement('#edit-promotional-code');

//Deposit Limit 
$I->see('Deposit Limit','#deposit-limit-title');
$I->see('We strongly support responsible gaming. The excitement of betting should not work against you. We want all of our clients to bet with responsibility and within their means.','#deposit-limit-paragraph1');
$I->see('If you require a deposit limit, please select from the options below. If you do not require a limit, please select "No limit".','#deposit-limit-paragraph2');

$I->seeElement('#edit-limit-deposit');
$I->seeOptionIsSelected('#edit-limit-deposit','Select');
$I->selectOption('#edit-limit-deposit','No Limit');
$I->seeOptionIsSelected('#edit-limit-deposit','No Limit');

$I->seeElement('#edit-limit-amount');

$I->seeElement('.reg-tandc-check-box');
$I->checkOption('.reg-tandc-check-box');

$I->see('I am over 18 years of age and have read and accepted the','.tnctext1');
$I->see('Terms & Conditions','.tnclink1 a');
$I->see('Privacy Policy','.tnclink2 a');

$I->seeElement('#reg-button');

$I->amGoingTo('Submit the registration');

?>