<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;


	public function clickXY($elem, $xx, $yy)
    {
        global $element,$x,$y;
		$I=$this;
        $element=$elem;
		$x=$xx;
		$y=$yy;
		$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver){ 
			
			global $x,$y,$element;
			
			$E = $webdriver->findElement(WebDriverBy::cssselector($element));
			$action = new WebDriverActions($webdriver);
			//$action->moveToElement($E,$x,$y)->click()->release()->build()->perform();
			$action->moveToElement($E,$x,$y)->click()->perform();
		});
		
			
    }
	
	public function weblogout(){
	
		$I=$this;
		$I->amGoingTo('Log out');
		$I->click('[title="LOGOUT"]');
	
	}

	public function weblogoutFull(){
	
		$I=$this;
		$I->amGoingTo('Log out');
		$I->click('[title="LOGOUT"]');
		$I->waitForElementVisible('[name="username"]',60);
		$I->dontSeeElement('a span.balance_visible');
	
	}
	
	public function moblogout(){
	
		$I=$this;
		$I->amGoingTo('Log out');
		$I->click("Account",'.menu-nav-links.menu_table');
		$I->waitForElementVisible('#logoutId',30);
		$I->click("#logoutId");
		$I->waitForElementVisible(".urlAftLogin",30);
	
	}
	
	public function weblogin($username,$password){
	
		$I=$this;
		$I->amGoingTo('Log in');
		$I->fillField('[name="username"]',$username);
		$I->fillField('[name="password"]',$password);
		$I->click('#xLogin.loginbtn');
		$I->waitForElementVisible('.accountname a',30);
		$I->wait(1);
		$I->amGoingTo('Verify username');
		$I->click('.accountname a');
		$I->see($username,'.accountnameselection li:nth-child(2)'); //username
		$I->click('.accountname a');
		
	}	
	
	public function elementsArray($elem){ // $elem is a CSS selector
	
		global $E,$element;
		$I=$this;
		$element=$elem;
		
		$I->amGoingTo('Collect all <'.$elem.'> elements.');
		$I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver){  //Store all paragraphs in an array
	
		global $E,$element;
				
		$E = $webdriver->findElements(WebDriverBy::cssselector($element));
		return $E;
		
	});
		
		return $E;
	}	
	public function redText($text){
 
		$I=$this;
		$I->amGoingTo('<font color="red">'.$text.'</font>');  
  
	}
 
	public function greenText($text){
 
		$I=$this;
		$I->amGoingTo('<font color="green">'.$text.'</font>');  
  
	}
}

//PURE PHP FUNCTIONS

	
	if(!function_exists ( "compare" )){
		function compare($a, $b) //File comparison script
		{
			// Check if filesize is different
			if(filesize($a) !== filesize($b))
				return false;

			// Check if content is different
			$ah = fopen($a, 'rb');
			$bh = fopen($b, 'rb');

			$result = true;
			while(!feof($ah))
				{
					if(fread($ah, 8192) != fread($bh, 8192))
					{	
						$result = false;
						break;
					}
				}

			fclose($ah);
			fclose($bh);

			return $result;
		}
	}
	
	if(!function_exists ( "checkFormat" )){
		function checkFormat($string) {
    
			if (preg_match('/([0-9]+[\/][0-9])/',$string)) {
				
				$priceType="FRACTION";

			} elseif (preg_match('/^([-+]+[0-9.])/',$string)) {
			
				$priceType="AMERICAN";

			} else {
			
				$priceType="DECIMAL";
			}

			return $priceType;
		}
	}
	if(!function_exists ( "num_to_letter" )){
		
		function num_to_letter($num,$uppercase = FALSE)
			{
			
				$letter = 	chr($num+ 97);
				return 		($uppercase ? strtoupper($letter) : $letter); 
				
			}
	}
	
?>