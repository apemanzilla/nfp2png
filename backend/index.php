<?php
	//Constants
	$colors = array(
		
	);
	$newline = "<br />";
	//Debug flag
	$debug = $_GET["debug"] == "true";
	if($debug) {
		echo("Debug mode enabled" . $newline);
	}
	//Switch for different versions
	switch($_GET["parserversion"]) {
		case "1":
			$pasteid = $_GET["pasteid"];
			if (is_null($pasteid)) {
				die("Missing paste ID!");
			}
			if($debug) {
				echo("Paste ID: " . $pasteid . $newline);
			}
			$pasteurl = "http://pastebin.com/raw.php?i=" . $pasteid;
			if($debug) {
				echo("Paste URL: " . $pasteurl . $newline);
			}
			//Count rows and lines to determine image dimensions
			//Pixels on computers are approximately 12 x 18 when rendered in a browser
			$linecount = 0;
			$rowcount = 0;
			$handle = fopen($pasteurl,"r");
			while(!feof($handle)) {
				$line = fgets($handle);
				if(strlen($line) > $rowcount) {
					$rowcount = strlen($line);
				}
				$linecount++;
			}
			fclose($handle);
			//We now have the number of lines and characters
			$pixelwidth = 12;
			$pixelheight = 18;
			//Convert to use actual pixels
			$imgwidth = $pixelwidth * $rowcount;
			$imgheight = $pixelheight * $linecount;

			exit();
		case "debug":
			exit();
		default:
			die("Invalid parser version: " . $_GET["parserversion"]);
	}
?>