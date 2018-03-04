<?php
namespace Helper;
// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Acceptance extends \Codeception\Module
{
	public function _beforeSuite($I) {
			
	  $env = $_SERVER['argv'];
	  $report=$env[6];
	  file_put_contents('failedtests.txt', $report."\r\n");  
  
 }
  
	 public function _failed($test,$fail) {
			
	  file_put_contents('failedtests.txt', $test->toString()."\r\n" , FILE_APPEND);
	  
		}
}
