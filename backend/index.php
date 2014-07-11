<?php
	//Constants
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
				$line = rtrim(fgets($handle)," ");
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
			//Set MIME type so it is seen as an image
			header('Content-Type: image/png');
			$img = imagecreatetruecolor($imgwidth,$imgheight);
			/* RGBA colors for each character 
			 * [ ] = 255, 255, 255, 0 - Transparent
			 * [0] = 0, 0, 0, 1 - Black
			 * [1] = 204, 76, 76, 1 - Red
			 * [2] = 87, 166, 78, 1 - Green
			 * [3] = 127, 102, 76, 1 - Brown
			 * [4] = 37, 49, 146, 1 - Blue
			 * [5] = 178, 102, 229, 1 - Purple
			 * [6] = 76, 153, 178, 1 - Cyan
			 * [7] = 153, 153, 153, 1 - Light Gray
			 * [8] = 76, 76, 76, 1 - Gray
			 * [9] = 242, 178, 204, 1 - Pink
			 * [a] = 127, 204, 25, 1 - Lime
			 * [b] = 222, 222, 108, 1 - Yellow
			 * [c] = 153, 178, 242, 1 - Light Blue
			 * [d] = 229, 127, 216, 1 - Magenta
			 * [e] = 242, 178, 51, 1 - Orange
			 * [f] = 240, 240, 240, 1 - White
			 */
			//Construct colors array
			$colors = array(
				//										Red		Green	Blue	Alpha
				" " => imagecolorallocatealpha($img,	255,	255,	255,	127),
				"0" => imagecolorallocatealpha($img,	0,		0,		0,		0),
				"1" => imagecolorallocatealpha($img,	204,	76,		76,		0),
				"2" => imagecolorallocatealpha($img,	87,		166,	78,		0),
				"3" => imagecolorallocatealpha($img,	127,	102,	76,		0),
				"4" => imagecolorallocatealpha($img,	37,		49,		146,	0),
				"5" => imagecolorallocatealpha($img,	178,	102,	229,	0),
				"6" => imagecolorallocatealpha($img,	76,		153,	178,	0),
				"7" => imagecolorallocatealpha($img,	153,	153,	153,	0),
				"8" => imagecolorallocatealpha($img,	76,		76,		76,		0),
				"9" => imagecolorallocatealpha($img,	242,	178,	204,	0),
				"a" => imagecolorallocatealpha($img,	127,	204,	25,		0),
				"b" => imagecolorallocatealpha($img,	222,	222,	108,	0),
				"c" => imagecolorallocatealpha($img,	153,	178,	242,	0),
				"d" => imagecolorallocatealpha($img,	229,	127,	216,	0),
				"e" => imagecolorallocatealpha($img,	242,	178,	51,		0),
				"f" => imagecolorallocatealpha($img,	240,	240,	240,	0),
			);
			//Fill image with color " " (transparent)
			imagefill($img,0,0,$colors[" "]);
			//Read data from paste and render image
			$handle = fopen($pasteurl,"r");
			for($i = 1;$i <= $linecount; $i++){
				$line = rtrim(fgets($handle)," ");
				for($j = 1; $j <= strlen($line);$j++){
					if($line{$j-1} != " "){
						imagefilledrectangle ( $img,
							($j - 1) * $pixelwidth,
							($i - 1) * $pixelheight,
							$j * $pixelwidth,
							$i * $pixelheight,
							$colors[$line{$j - 1}]
						 );
					}
				}
			}
			//Make background appear transparent
			imagecolortransparent($img,$colors[" "]);
			//Send image to client and remove it from memory
			imagepng($img);
			imagedestroy($img);
			exit();
		case "debug":
			exit();
		default:
			die("Invalid parser version: " . $_GET["parserversion"]);
	}
?>
