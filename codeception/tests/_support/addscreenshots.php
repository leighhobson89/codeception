<?php
		//new update here
	
		//Set the globals
		
		global $filename;
		global $test;
		global $result;
		global $lines;
		
		$env = $_SERVER['argv']; //Get an array of all command line arguments
		
		if(isset($env[1])){ //If argument 1 is set then set the path for report, we can expect is set in production
			$path=$env[1]; //path for report
			$sspath="output/"; //path for screenshots
		}else{
			$path="tests/_output/"; //if it is not set then the path to dev environment
			$sspath="";
		}
		
		//$lines=array("","");
		
		//$test=array("");
		
	if (file_exists("failedtests.txt")) {
		$file = fopen("failedtests.txt", "r"); //Read the file created during the TestCase with report name and failed tests
		$x=0;
		while(!feof($file)){
			
			
			global $test;
						
			$line = fgets($file);
			
			if($x<=0){$report=$line;} //first line will be always report name
			//	echo($report);
			if($line!="" && $x>=1){ //Following lines are failed test names
				
				preg_match('#(.*?)\:#', $line, $tests[$x]); //Get only the part in line into brackets "()"
			//	echo($tests[$x][0]);
			//	echo($tests[$x][1]);
				
				if (strpos($tests[$x][1], '/') !== false) {
					$parts=explode("/", $tests[$x][1]); //This preg_match will create an array with 2 elements, one including brackets "(xxxxxx)" and other one without "xxxxxx"
					$test[$x-1]=$parts[sizeof($parts)-1]; //Get the last element in array, the one without brackets.
			//		echo($test[$x-1]);
				}else{
					$test[$x-1]=$tests[$x][1];
			//		echo($test[$x-1]);

				}
							
			}
			
			$x++;
		}
		fclose($file);
		unlink("failedtests.txt");
		
		if($report!=""){
		
			
					
			$filename=glob("tests/_output/*.png"); //Get all png files from output folder in an array
			
					
			$y=0;
			$z=0;
			
			while($y<=(sizeof($filename))-1){ //check all png files 
				
				$j=0;
				while($j<=(sizeof($test))-1){ //looking for a coincidence with test name
					
					global $filename;
					global $test;
					global $result;
					
					if($test[$j]!="" && strpos($filename[$y],$test[$j])!=false){
					
						$filename[$y]=str_replace("tests/_output/","",$filename[$y]); //if any coincidence remove path
					//	echo($filename[$y]);
						$result[$j]=$test[$j];
						
						//and create a line of code that will replace the one in actual report file 
						$code[$j]='<tr> 
									<td class="stepName ">
									<a href="'.$sspath.$filename[$y].'" target="_blank" style="margin-right: 10px;"><img src="'.$sspath.$filename[$y].'" width="20%"></a>
									<a href="'.$sspath.'2'.$filename[$y].'" target="_blank" style="margin-right: 10px;"><img src="'.$sspath.'2'.$filename[$y].'" width="20%" onerror="this.style.display=\'none\'"></a></td>
									</tr>
									<tr>
									<td class="stepName ">
									<div style="font-weight: bold; color: blue; background-color: yellow; font-size: large;">Step By Step Recording: <a href="output/records" target="_blank">output/records</a></div>
									</td>
									</tr>';

					//	echo($code[$j]);
					}
					
					$j++;
				}
				$y++;
			}
			
			
			
			$report=trim($report); //as report was in file maybe in can come with
			

			$parts=explode("/", $report); //Explode the string in each "/"
			
			if(sizeof($parts)>=1){
				$report=$parts[sizeof($parts)-1]; //Get the last part as it is the report name.
			}
			
			$file0= $path.$report; //set the path for the file 
			
		if (file_exists($file0)) {
		//	echo "File Exists";
			$content=file_get_contents($file0);
			$file = fopen($file0, "r+");
			$flag=0;
			
			
			$y=0;
			
			while(!feof($file)){ //Read all the content of report file 
				
				
				global $test;
				global $result;
				
				$line = fgets($file);
				
				for($x=0;$x<=(sizeof($result))-1;$x++){ //Look for a coincidence of each failed test in each line 
					
				//	echo("BLABLABLA".$result[$x]);
					
					if($result[$x]!="" && strpos(strtolower($line),strtolower($result[$x]))!=false) {
					
						$flag=1; //If we found any coincidence then set the flag for replacing
						
					}
				//	echo ($line);
				//	echo ("FLAG:".$flag);
					if(strpos($line,"stepContainer")!=false && $flag>=1) { //Find The line we want 
				
				//		echo ($line);
				//		echo ('</br>');
				//		echo ($line.$code[$y]);
				//		echo ('</br>');
				//		echo ($content);
				
						$content=str_replace($line,$line.$code[$y],$content); //and replace with the one with screenshot
						$flag=0;
						$y++;
						
					}
				
				}
				
				
			}
				
				
			fclose($file); //Close the file
			
			file_put_contents($file0,$content); //Put the final content to the report file
			
			if(isset($env[2])){ //If WorkSpace is provided as second argument in command line then copy final html to there
				
				$wsPath=$env[2];
				copy($file0,$wsPath.$report);
			
			}
		}
		}
	}
	
?>