<?php

function hashSubDB($filename){
	$size = filesize($filename);
	$inicio = file_get_contents($filename, false, null, 0, 64 * 1024);
	$fim = file_get_contents($filename, false, null, $size - (64 * 1024), 64 * 1024);
	$data = $inicio . $fim;
	$hash = md5($data);
	return $hash;
}

function generateFile($fileNameToGenerate, $content) {
	$file = fopen($fileNameToGenerate, "a");
	fwrite($file,$content);
	fclose($file);
}

if (isset($argv)) {
    $argument = $argv[1];
}
else {
    $argument = $_GET['argument1'];
}

$extentions = array(".avi",".mp4",".mkv",".mpg",".mpeg",".mov",".rm",".vob",".wmv",".flv",".3gp");
$fileName = str_replace($extentions, "", $argument);
if(strlen($argument) != strlen($fileName)) {
		$fileHash = hashSubDB($argument);
		$context = stream_context_create(array(
								'http' => array(
									'header'  => "User-Agent:SubDB/1.0"
								)
							));
		$subTitles = @file_get_contents("http://api.thesubdb.com/?action=download&hash=".$fileHash."&language=en",0,$context);
		if(FALSE === $subTitles) {
			$fileNameToGenerate = $fileName.".txt";
			$content = "Sorry no subtitles found for the file you have requested for.";
		} else {
			$fileNameToGenerate = $fileName.".srt";
			$content = $subTitles;
		}
		generateFile($fileNameToGenerate, $content);
}
?>