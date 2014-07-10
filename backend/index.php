<?php
	//Switch for parser versions
	switch($_GET["parserversion"]) {
		case "1":
			$pasteid = $_GET["pasteid"];
			if (is_null($pasteid)) {
				die("Missing paste ID!");
			}
			$pasteurl = "http://pastebin.com/raw.php?i=" . $pasteid;
			//Count rows and lines to determine image dimensions
			//Pixels on computers are approximately 12 x 18 when rendered in a browser
			$linecount = 0;
			$rowcount = 0;
			$handle = fopen($pasteurl,"r");
			while(@feof($handle)) {
				$line = fgets($handle);
				echo($line . "\n");
				$linecount++;
			}
			fclose($handle);

			exit();
		default:
			die("Invalid parser version: " . $_GET["parserversion"]);
	}
?>