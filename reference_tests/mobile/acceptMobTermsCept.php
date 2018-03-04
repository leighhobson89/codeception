<?php
require 'tests/acceptance/_bootstrap.php';

// @group general

$I = new AcceptanceTester($scenario);

$I->wantTo('Check Terms & Conditions PopUp on Mobile');

//Clear T&Cs flag before starting
$I->amOnUrl('http://sjauto/terms-reset/termsclear.php?environment='.$environment);
$I->waitForText('T&Cs Value Successfully Changed',30);

//Open homepage
$I->amGoingTo('Open website');
$I->amOnUrl($url);
$I->resizeWindow(411, 731); // Nexus5 size

$I->amGoingTo('Verify elements on Landing page');
$I->waitForElementVisible('.cta',30);

$I->amGoingTo('Open Login');
$I->click('.cta');
$I->waitForElement('.login-button-link',30);

//Login
$I->amGoingTo('Log in');
$I->fillField('.form-item-username input',$termsuser);
$I->fillField('.form-item-password input',$termsuserpass);
$I->click('.login-button-link');

$I->waitForElementVisible('.ui-simpledialog-container.ui-overlay-shadow',30); //Wait for T&C popup to appear

$I->amGoingTo('Verify popup');
$I->waitForElementVisible('a.terms-button div.aux-modal-continue-btn-color',30);
$I->seeElement('a.terms-button div.aux-modal-continue-btn-color');
$I->seeElement('div.aux-modal-deposit-btn');
$I->see('Our Terms and Conditions have changed, by clicking \'Accept\' you are agreeing to our new','.terms-and-cond_body');
$I->see('Terms and Conditions','.terms-button_text');
$I->see('Log out','a.terms-button div.aux-modal-continue-btn-color');
$I->see('Accept','div.aux-modal-deposit-btn');

$I->amGoingTo('Accept T&Cs');
$I->click('.btnOkTC.terms-button');
$I->waitForElementNotVisible('.ui-simpledialog-container.ui-overlay-shadow',30); // Wait for popup to disappear

$I->moblogout();

//Check pop up is not showing
$I->amGoingTo('Open Login');
$I->click('.urlAftLogin');
$I->waitForElement('.login-button-link',30);

$I->amGoingTo('Log in');
$I->fillField('.form-item-username input',$termsuser);
$I->fillField('.form-item-password input',$termsuserpass);
$I->click('.login-button-link');

$I->waitForElementVisible('.login-modal-continue-container',30); //Wait for popup to appear

$I->amGoingTo('Verify popup is not in there');
$I->waitForElementNotVisible('.ui-simpledialog-container.ui-overlay-shadow',30); // Wait for T&C popup not available
$I->dontSeeElement('a.terms-button div.aux-modal-continue-btn-color');
$I->dontSeeElement('div.aux-modal-deposit-btn');
$I->dontSee('Our Terms and Conditions have changed, by clicking \'Accept\' you are agreeing to our new','.terms-and-cond_body');
$I->dontSee('Terms and Conditions','.terms-button_text');

$I->click('.login-modal-continue-container');

$I->amGoingTo('Log out');
$I->moblogout();


$I->amGoingTo('Clear T&Cs Flag');
$I->amOnUrl('http://sjauto/terms-reset/termsclear.php?environment='.$environment);
$I->waitForText('T&Cs Value Successfully Changed',30);

//Check T&Cs Page Acceptance

//Top Accept Button
$I->amGoingTo('Open Homepage');
$I->amOnUrl($url);

$I->waitForElementVisible('.urlAftLogin ',30); //Wait for Login link loaded
$I->click('.urlAftLogin ');
$I->waitForElement('.login-button-link',30);

$I->amGoingTo('Log in');
$I->fillField('.form-item-username input',$termsuser);
$I->fillField('.form-item-password input',$termsuserpass);
$I->click('.login-button-link');

$I->waitForElementVisible('.ui-simpledialog-container.ui-overlay-shadow',30); //Wait for T&C popup to appear

$I->amGoingTo('Verify popup');
$I->waitForElementVisible('a.terms-button div.aux-modal-continue-btn-color',30);
$I->seeElement('a.terms-button div.aux-modal-continue-btn-color');
$I->seeElement('div.aux-modal-deposit-btn');
$I->see('Our Terms and Conditions have changed, by clicking \'Accept\' you are agreeing to our new','.terms-and-cond_body');
$I->see('Terms and Conditions','.terms-button_text');
$I->see('Log out','a.terms-button div.aux-modal-continue-btn-color');
$I->see('Accept','div.aux-modal-deposit-btn');

$I->amGoingTo('Open T&Cs page');
$I->click('.terms-button_text a');
$I->waitForElementNotVisible('.ui-simpledialog-container.ui-overlay-shadow',30); // Wait for popup to disappear
$I->waitForElementVisible('.content-block.responsible-gaming-page',30); //Wait for T&C page content is loaded

$I->amGoingTo('Ensure both Accept buttons are available in T&C page');
$I->waitForElementVisible('.tandc-container:nth-of-type(2) .buttonTextTC',30);
$I->seeElement('.tandc-container:nth-of-type(2) .buttonTextTC');
$I->see('Please read and accept our new Terms and Conditions.','.tandc-container:nth-of-type(2) .acceptTextTC');
$I->see('Accept','.tandc-container:nth-of-type(2) .buttonTextTC');
//$I->executeJS('document.querySelector(".tandc-container:nth-of-type(4) .buttonTextTC").scrollIntoView(false)');
$I->scrollTo('.tandc-container:nth-of-type(4) .buttonTextTC');

$I->waitForElementVisible('.tandc-container:nth-of-type(4) .buttonTextTC',30);
$I->seeElement('.tandc-container:nth-of-type(4) .buttonTextTC');
$I->see('Please read and accept our new Terms and Conditions.','.tandc-container:nth-of-type(4) .acceptTextTC');
$I->see('Accept','.tandc-container:nth-of-type(4) .buttonTextTC');

$I->amGoingTo('Accept T&Cs with up button');
//$I->executeJS('document.querySelector(".tandc-container:nth-of-type(2) .buttonTextTC").scrollIntoView(false)');
$I->scrollTo('.tandc-container:nth-of-type(2) .buttonTextTC',0,100);

$I->click('.tandc-container:nth-of-type(2) .buttonTextTC');
$I->waitForElement('.swipe',30); //Homepage is loaded
$I->waitForElementNotVisible('.tandc-container:nth-of-type(2) .buttonTextTC',30);

//Check Accept buttons not visible
$I->amGoingTo('Open T&Cs page and ensure that there is no Accept buttons');
$I->click('.aTermsCond.ui-link');
$I->waitForText('STAN JAMES TERMS AND CONDITIONS',30,'.content-block.responsible-gaming-page');
$I->dontSeeElement('.tandc-container:nth-of-type(2) .buttonTextTC');
//$I->executeJS('document.querySelector(".back-top-top-bnt-txt").scrollIntoView(false)');
$I->scrollTo('.back-top-top-bnt-txt');
$I->wait(1);
$I->dontSeeElement('.tandc-container:nth-of-type(4) .buttonTextTC');
$I->wait(1);
//$I->executeJS('document.querySelector(".header-image").scrollIntoView(false)');
$I->scrollTo('.header-image');

$I->amGoingTo('Log out');
$I->moblogout();

//Check pop up is not showing
$I->amGoingTo('Log in');
$I->fillField('.form-item-username input',$termsuser);
$I->fillField('.form-item-password input',$termsuserpass);
$I->click('.login-button-link');

$I->waitForElementVisible('.login-modal-continue-container',30); //Wait for popup to appear

$I->amGoingTo('Verify popup is not in there');
$I->waitForElementNotVisible('.ui-simpledialog-container.ui-overlay-shadow',30); // Wait for T&C popup not available
$I->dontSeeElement('a.terms-button div.aux-modal-continue-btn-color');
$I->dontSeeElement('div.aux-modal-deposit-btn');
$I->dontSee('Our Terms and Conditions have changed, by clicking \'Accept\' you are agreeing to our new','.terms-and-cond_body');
$I->dontSee('Terms and Conditions','.terms-button_text');

$I->click('.login-modal-continue-container');

$I->amGoingTo('Log out');
$I->moblogout();

$I->amGoingTo('Clear T&Cs Flag');
$I->amOnUrl('http://sjauto/terms-reset/termsclear.php?environment='.$environment);
$I->waitForText('T&Cs Value Successfully Changed',30);

//Bottom Accept Button
$I->amGoingTo('Open Homepage');
$I->amOnUrl($url);

$I->waitForElementVisible('.urlAftLogin ',30); //Wait for Login link loaded
$I->click('.urlAftLogin ');
$I->waitForElement('.login-button-link',30);

$I->amGoingTo('Log in');
$I->fillField('.form-item-username input',$termsuser);
$I->fillField('.form-item-password input',$termsuserpass);
$I->click('.login-button-link');

$I->waitForElementVisible('.ui-simpledialog-container.ui-overlay-shadow',30); //Wait for T&C popup to appear

$I->amGoingTo('Verify popup');
$I->waitForElementVisible('a.terms-button div.aux-modal-continue-btn-color',30);
$I->seeElement('a.terms-button div.aux-modal-continue-btn-color');
$I->seeElement('div.aux-modal-deposit-btn');
$I->see('Our Terms and Conditions have changed, by clicking \'Accept\' you are agreeing to our new','.terms-and-cond_body');
$I->see('Terms and Conditions','.terms-button_text');
$I->see('Log out','a.terms-button div.aux-modal-continue-btn-color');
$I->see('Accept','div.aux-modal-deposit-btn');

$I->amGoingTo('Open T&Cs page');
$I->click('.terms-button_text a');
$I->waitForElementNotVisible('.ui-simpledialog-container.ui-overlay-shadow',30); // Wait for popup to disappear
$I->waitForElementVisible('.content-block.responsible-gaming-page',30); //Wait for T&C page content is loaded

$I->amGoingTo('Ensure both Accept buttons are available in T&C page');
$I->waitForElementVisible('.tandc-container:nth-of-type(2) .buttonTextTC',30);
$I->seeElement('.tandc-container:nth-of-type(2) .buttonTextTC');
$I->see('Please read and accept our new Terms and Conditions.','.tandc-container:nth-of-type(2) .acceptTextTC');
$I->see('Accept','.tandc-container:nth-of-type(2) .buttonTextTC');
//$I->executeJS('document.querySelector(".tandc-container:nth-of-type(4) .buttonTextTC").scrollIntoView(false)');
$I->scrollTo('.tandc-container:nth-of-type(4) .buttonTextTC');
$I->waitForElementVisible('.tandc-container:nth-of-type(4) .buttonTextTC',30);
$I->seeElement('.tandc-container:nth-of-type(4) .buttonTextTC');
$I->see('Please read and accept our new Terms and Conditions.','.tandc-container:nth-of-type(4) .acceptTextTC');
$I->see('Accept','.tandc-container:nth-of-type(4) .buttonTextTC');

$I->amGoingTo('Accept T&Cs with bottom button');
$I->click('.tandc-container:nth-of-type(4) .buttonTextTC');
$I->waitForElement('.swipe',30); //Homepage is loaded
$I->waitForElementNotVisible('.tandc-container:nth-of-type(2) .buttonTextTC',30);

//Check Accept buttons not visible
$I->amGoingTo('Open T&Cs page and ensure that there is no Accept buttons');
$I->click('.aTermsCond.ui-link');
$I->waitForText('STAN JAMES TERMS AND CONDITIONS',30,'.content-block.responsible-gaming-page');
$I->dontSeeElement('.tandc-container:nth-of-type(2) .buttonTextTC');
//$I->executeJS('document.querySelector(".back-top-top-bnt-txt").scrollIntoView(false)');
$I->scrollTo('.back-top-top-bnt-txt');
$I->wait(1);
$I->dontSeeElement('.tandc-container:nth-of-type(4) .buttonTextTC');
$I->wait(1);
$I->executeJS('document.querySelector(".header-image").scrollIntoView(false)');
$I->scrollTo('.header-image');

$I->amGoingTo('Log out');
$I->moblogout();

//Check pop up is not showing
$I->amGoingTo('Log in');
$I->fillField('.form-item-username input',$termsuser);
$I->fillField('.form-item-password input',$termsuserpass);
$I->click('.login-button-link');

$I->waitForElementVisible('.login-modal-continue-container',30); //Wait for popup to appear

$I->amGoingTo('Verify popup is not in there');
$I->waitForElementNotVisible('.ui-simpledialog-container.ui-overlay-shadow',30); // Wait for T&C popup not available
$I->dontSeeElement('a.terms-button div.aux-modal-continue-btn-color');
$I->dontSeeElement('div.aux-modal-deposit-btn');
$I->dontSee('Our Terms and Conditions have changed, by clicking \'Accept\' you are agreeing to our new','.terms-and-cond_body');
$I->dontSee('Terms and Conditions','.terms-button_text');

$I->click('.login-modal-continue-container');

$I->amGoingTo('Log out');
$I->moblogout();

$I->amGoingTo('Clear T&Cs Flag');
$I->amOnUrl('http://sjauto/terms-reset/termsclear.php?environment='.$environment);
$I->waitForText('T&Cs Value Successfully Changed',30);
