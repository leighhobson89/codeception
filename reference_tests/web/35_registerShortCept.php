<?php
require 'tests/acceptance/_bootstrap.php';

// @group general

$I = new AcceptanceTester($scenario);
$I->wantTo('Load Registration Page and Fill all Fields withour Submitting');

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


$I->amOnPage('/');
$I->maximizeWindow();

$I->amGoingTo('Verify elements on Landing page');
$I->waitForElementVisible('div.account h4:nth-child(3) a',30); // Wait for Registration button

$I->amGoingTo('Open Registration page');
$I->click('div.account h4:nth-child(3) a');

$I->waitForElementVisible('div.account_registration a.btn_orange.register_account_btn',30); //wait for Create My Account button to show

$serverid = $I->grabTextFrom('.hiddensrv'); // Get Server ID
$I->greenText('Server ID is: '.$serverid);

$cmpid=$I->grabAttributeFrom('.register_field[type="checkbox"]', 'id'); //Grabbing the component ID
$cmpid=str_replace(".IsTermsAndConditionsAgreed","",$cmpid);
$I->amGoingTo('The component ID is:'.$cmpid);

$I->amGoingTo('Fill all registartion fields');
$I->seeElement('#'.$cmpid.'\\2e Title');
$I->seeOptionIsSelected('#'.$cmpid.'\\2e Title','Please choose...');
$I->selectOption('#'.$cmpid.'\\2e Title','Mr.');
$I->seeOptionIsSelected('#'.$cmpid.'\\2e Title','Mr.');

$I->seeElement('#'.$cmpid.'\\2e FirstName');
$I->fillField('#'.$cmpid.'\\2e FirstName','QA name '.$tstmpletter);

$I->seeElement('#'.$cmpid.'\\2e LastName');
$I->fillField('#'.$cmpid.'\\2e LastName','QA surname '.$tstmpletter);

$I->seeElement('#'.$cmpid.'\\2e DateOfBirthDay');
$I->seeOptionIsSelected('#'.$cmpid.'\\2e DateOfBirthDay','Day...');
$I->selectOption('#'.$cmpid.'\\2e DateOfBirthDay','1');
$I->seeOptionIsSelected('#'.$cmpid.'\\2e DateOfBirthDay','1');

$I->seeElement('#'.$cmpid.'\\2e DateOfBirthMonth');
$I->seeOptionIsSelected('#'.$cmpid.'\\2e DateOfBirthMonth','Month...');
$I->selectOption('#'.$cmpid.'\\2e DateOfBirthMonth','January');
$I->seeOptionIsSelected('#'.$cmpid.'\\2e DateOfBirthMonth','January');

$I->seeElement('#'.$cmpid.'\\2e DateOfBirthYear');
$I->seeOptionIsSelected('#'.$cmpid.'\\2e DateOfBirthYear','Year...');
$I->selectOption('#'.$cmpid.'\\2e DateOfBirthYear','1990');
$I->seeOptionIsSelected('#'.$cmpid.'\\2e DateOfBirthYear','1990');

$I->seeElement('#'.$cmpid.'\\2e PrimaryEmail');
$I->fillField('#'.$cmpid.'\\2e PrimaryEmail','qaauto+'.$tstmp.'@stanjames.com');

$I->seeElement('#'.$cmpid.'\\2e IDMMCountry');
$I->seeOptionIsSelected('#'.$cmpid.'\\2e IDMMCountry','United Kingdom');

$I->seeElement('#'.$cmpid.'\\2e HomePhone');
$I->fillField('#'.$cmpid.'\\2e HomePhone',$tstmp);

//Postcode Finder
$I->seeElement('#'.$cmpid.'\\2e HouseNumber');
//$I->fillField('#'.$cmpid.'\\2e HouseNumber','1'); //Not filling house number in this test case, will select for mdropdown
$I->seeElement('#'.$cmpid.'\\2e PostCode');
$I->fillField('#'.$cmpid.'\\2e PostCode','WR2 6NJ');
$I->wait(1);
$I->seeElement('.post_code_btn');
$I->click('.post_code_btn');
$I->waitForElementVisible('.register_field_line_wrapper.center_register_field.setWidth_register_field',30);
$I->click('.register_field_line_wrapper.center_register_field.setWidth_register_field select option:nth-child('.rand(1,10).')');
$I->click('.confirm_adress_style');
$I->wait(3);
$I->seeElement('#'.$cmpid.'\\2e StreetAddress');
$address=$I->grabValueFrom('#'.$cmpid.'\\2e StreetAddress'); // storing Address
$I->seeElement('#'.$cmpid.'\\2e City');
$city=$I->grabValueFrom('#'.$cmpid.'\\2e City'); // storing City
$I->seeElement('#'.$cmpid.'\\2e CountyOrStateOrProvince');
$state=$I->grabValueFrom('#'.$cmpid.'\\2e CountyOrStateOrProvince'); // storing Province

/*
//Manual Address Method

$I->seeElement('#'.$cmpid.'\\2e HouseNumber');
$I->fillField('#'.$cmpid.'\\2e HouseNumber','7');
$I->seeElement('#'.$cmpid.'\\2e PostCode');
$I->fillField('#'.$cmpid.'\\2e PostCode','m333aj');
$I->seeElement('.post_code_manually.greenhighlighttext.cursor_pointer');
$I->click('.post_code_manually.greenhighlighttext.cursor_pointer');
$I->seeElement('#'.$cmpid.'\\2e StreetAddress');
$I->fillField('#'.$cmpid.'\\2e StreetAddress','Dummy Address');
$I->seeElement('#'.$cmpid.'\\2e City');
$I->fillField('#'.$cmpid.'\\2e City','Dummy City');
$I->seeElement('#'.$cmpid.'\\2e CountyOrStateOrProvince');
$I->fillField('#'.$cmpid.'\\2e CountyOrStateOrProvince','Dummy State');
*/

$I->seeElement('#'.$cmpid.'\\2e UserName');
$I->fillField('#'.$cmpid.'\\2e UserName','qaaut'.$tstmp);

$I->seeElement('#'.$cmpid.'\\2e Password');
$I->fillField('#'.$cmpid.'\\2e Password','111111');

$I->seeElement('#'.$cmpid.'\\2e Password2');
$I->fillField('#'.$cmpid.'\\2e Password2','111111');

$I->seeElement('#'.$cmpid.'\\2e IDMMCurrency');
$I->seeOptionIsSelected('#'.$cmpid.'\\2e IDMMCurrency','UK sterling');

$I->seeElement('#'.$cmpid.'\\2e IDDCSecurityQuestion');
$I->seeOptionIsSelected('#'.$cmpid.'\\2e IDDCSecurityQuestion','Please choose a security question');
$I->selectOption('#'.$cmpid.'\\2e IDDCSecurityQuestion','Where were you born?');
$I->seeOptionIsSelected('#'.$cmpid.'\\2e IDDCSecurityQuestion','Where were you born?');

$I->seeElement('#'.$cmpid.'\\2e SecurityAnswer');
$I->fillField('#'.$cmpid.'\\2e SecurityAnswer','QA Gibraltar');

$I->seeElement('#'.$cmpid.'\\2e AffiliateCode');

//Deposit Limit 
//Text
$I->see("Deposit Limits",".account_registration:nth-of-type(3) .register_field_line_wrapper:nth-of-type(8) div p strong");
$I->see("We strongly support responsible gaming. The excitement of betting should not work against you.",".account_registration:nth-of-type(3) .register_field_line_wrapper:nth-of-type(8) div:nth-of-type(1) p:nth-of-type(2)");
$I->see("We want all of our clients to bet with responsibility and within their means.",".account_registration:nth-of-type(3) .register_field_line_wrapper:nth-of-type(8) div:nth-of-type(1) p:nth-of-type(2)");
$I->see('If you require a deposit limit, please select from the options below. If you do not require a limit,',".account_registration:nth-of-type(3) .register_field_line_wrapper:nth-of-type(8) div:nth-of-type(1) p:nth-of-type(3)");
$I->see('please select "No limit".',".account_registration:nth-of-type(3) .register_field_line_wrapper:nth-of-type(8) div:nth-of-type(1) p:nth-of-type(3)");
//DropDown

$I->seeElement('#'.$cmpid.'\\2e DepositLimit');
$I->seeOptionIsSelected('#'.$cmpid.'\\2e DepositLimit','Select');
$I->selectOption('#'.$cmpid.'\\2e DepositLimit','No Limit'); //Select No Limit to complete registration
$I->seeOptionIsSelected('#'.$cmpid.'\\2e DepositLimit','No Limit');

$I->seeElement('#'.$cmpid.'\\2e DepositLimitAmount');

//Check Option Over 18 and Terms Agreed
$I->seeElement('#'.$cmpid.'\\2e IsTermsAndConditionsAgreed'); 
$I->checkOption('#'.$cmpid.'\\2e IsTermsAndConditionsAgreed');

$I->see('Terms and Conditions','label#lblIsTermsAndConditionsAgreed.register_tc_accept a.greenhighlighttext:nth-child(2)');
$I->see('Privacy Policy','label#lblIsTermsAndConditionsAgreed.register_tc_accept a.greenhighlighttext:nth-child(3)');

$I->amGoingTo('Verify Capcha is available');
$I->seeElement('[title="recaptcha widget"]'); //Captcha iframe is available

//$I->executeJS('document.querySelector("#reg_captcha_div iframe").setAttribute("name","captchaFrame")'); //Settting temporary name attribute for Captcha iFrame
//$I->switchToIFrame("captchaFrame"); // Swithching to Captcha iframe
//$I->seeElement('div.recaptcha-checkbox-checkmark');
//$I->switchToIFrame(); // Swithching back to parent iframe

//$I->executeJS('document.getElementById("g-recaptcha-response").style.display = "block"');
//$I->fillField('.g-recaptcha-response','5765617265746865626573747161');

$I->amGoingTo('Submit the registration');
$I->seeElement('a.btn_orange.register_account_btn');



?>

