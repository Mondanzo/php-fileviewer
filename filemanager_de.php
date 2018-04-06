<?php

//CONFIG
$dir = "D:";				// Pfad zum Haupt-Verzeichnis
$thisfile = "filemanager_de.php";		// Name dieser Datei
$lock = "123456";


//CODE - NICHTS VERÄNDERN
//
//oder ändere doch was, wenn du weißt, was du tust.

$loginform = "<p><form action=\"\" method=\"GET\">Passwort: </input><input type=\"password\" name=\"lock\"></input>";
$rootdir = $dir;
$rdlen = strlen($rootdir);
if(isset($_GET['link'])){
	$loginform = $loginform."<input type=\"hidden\" value=\"".$_GET['link']."\" name=\"link\">";
	if(isset($_GET['playaudio'])){
		$loginform = $loginform."<input type=\"hidden\" name=\"playaudio\">";
	}else if(isset($_GET['viewimg'])){
		$loginform = $loginform."<input type=\"hidden\" name=\"viewimg\">";
	}else if(isset($_GET['viewpdf'])){
		$loginform = $loginform."<input type=\"hidden\" name=\"viewpdf\">";
	}else if(isset($_GET['viewvideo'])){
		$loginform = $loginform."<input type=\"hidden\" name=\"viewvideo\">";
	}else if(isset($_GET['viewtext'])){
		$loginform = $loginform."<input type=\"hidden\" name=\"viewtext\">";
	}
	if(isset($_GET['dir'])){
		$loginform = $loginform."<input type=\"hidden\" value=\"". $_GET['dir'] ."\"name=\"dir\">";
	}
	$loginform = $loginform."</form><p>";
}
if(isset($lock)){
	if(isset($_GET[$lock])){
		$key = $lock;
	}else if(isset($_GET['lock'])){
		$key = $_GET['lock'];
	}else{
		echo($loginform); 
		die("Unautorisiert.");
	}
}

if(isset($_GET['link'])) {
$var_1 = $_GET['link'];
$file = $dir."/".$_GET['dir']."/". $var_1;


$file = str_replace("..","/",$file);

		if(isset($_GET['playaudio'])){
			echo '<audio src="'.$thisfile.'?dir='.$_GET['dir'].'&lock='.$key.'&link='.$_GET['link'].'" autostart="true" loop="false" autoplay="autoplay" controls ></audio>';
			die();	
		}else if(isset($_GET['viewimg'])){
			echo '<img src="'.$thisfile.'?dir='.$_GET['dir'].'&lock='.$key.'&link='.$_GET['link'].'" ></img>';
			die();	 
		} else if(isset($_GET['viewpdf'])){
			header('Content-Type: application/pdf');
			readfile($file);
			die();
		} else if(isset($_GET['viewvideo'])){
			echo '<video src="'.$thisfile.'?dir='.$_GET['dir'].'&lock='.$key.'&link='.$_GET['link'].'" controls>Oups. Dein Browser unterstützt leider den HTML5-Videoplayer nicht...</video>';
			die();	 			
		}else if(isset($_GET['viewtext'])){
			echo '<pre>';
			include($rootdir.'/'.$_GET['dir'].'/'.$_GET['link']);
			echo '</pre>';
			die();	 			
		}
	
	if((!isset($_GET[$lock])) && ($_GET["lock"] != $lock) ){echo($loginform); die("Unautorisiert.");}
    if (file_exists($file)) {
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'.basename($file).'"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file));
		ob_clean();
		flush();
		readfile($file);
		exit;
    }else{
		echo "Error.";	
    }
} else if(isset($_GET['list'])){
	$loginform = $loginform."<input type=\"hidden\" name=\"list\">";
	if(isset($_GET['dir'])){
        $dir = $dir."/".$_GET['dir'];
		$dir = str_replace("..","/",$dir);
		$dir = str_replace('//', '/', $dir);
    }
	$loginform = $loginform."</form><p>";
    echo "Aktuelles Verzeichnis: ".$dir."<p>";
	if((!isset($_GET[$lock])) && ($_GET["lock"] != $lock) ){echo($loginform); die("Unautorisiert.");}
    $dh  = opendir($dir);
	if(is_dir($dir) && isset($dh)){
		while (false !== ($filename = readdir($dh))) {
			$files[] = $filename;
		}
	}else{
		echo "Das Verzeichnis konnte nicht gefunden werden. D:";
	}
    $files = array_diff($files, ["..", "."]);
    sort($files);
	$fn = '';
	for($i = 0+strlen($rootdir); $i <= strlen($dir); $i++){
		$fn = $fn.$dir[$i];
	}
	echo '<a href="'.$thisfile.'?list&dir&lock='.$key.'">[/]</a><br>';
	echo '<a href="'.$thisfile.'?list&dir='.getLink($dir, $fn, $rootdir).'&lock='.$key.'">[..]</a><br>';
    foreach($files as $filename){
		$size = filesize($dir.'/'.$filename);
		$units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
		$power = $size > 0 ? floor(log($size, 1024)) : 0;
		$finfsiz = number_format($size / pow(1024, $power), 2, '.', ',').' '.$units[$power];
		$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
		if(is_dir($dir."/".$filename)) {
			echo '<a href="'.$thisfile.'?list&dir='.$fn.'/'.$filename.'&lock='.$key.'">'.$filename, '</a> - '.$finfsiz.' [DIR]<br>';
		}else if($ext == "mp3"){
			echo '<a href="'.$thisfile.'?dir='.$_GET['dir'].'&link='.$filename.'&lock='.$key.'">'.$filename, '</a> - '.$finfsiz.' - <a href="'.$thisfile.'?dir='.$_GET['dir'].'&playaudio&link='.$filename.'&lock='.$key.'">[MP3 wiedergeben]</a><br>';
		} else if($ext == "png" || $ext == "jpg" || $ext == "jpeg"  || $ext == "ico" || $ext == "gif" || $ext == "svg"){
			echo '<a href="'.$thisfile.'?dir='.$_GET['dir'].'&link='.$filename.'&lock='.$key.'">'.$filename, '</a> - '.$finfsiz.' - <a href="'.$thisfile.'?dir='.$_GET['dir'].'&viewimg&link='.$filename.'&lock='.$key.'">[Bild ansehen]</a><br>';
		} else if($ext == "pdf"){
			echo '<a href="'.$thisfile.'?dir='.$_GET['dir'].'&link='.$filename.'&lock='.$key.'">'.$filename, '</a> - '.$finfsiz.' - <a href="'.$thisfile.'?dir='.$_GET['dir'].'&viewpdf&link='.$filename.'&lock='.$key.'">[PDF ansehen]</a><br>';
		} else if($ext == "mp4" || $ext == "webm" || $ext == "flv" || $ext == "wmv" || $ext == "mov"|| $ext == "avi" || $ext == "mpg" || $ext == "mpeg" || $ext == "m3u8" || $ext == "m4v"){
			echo '<a href="'.$thisfile.'?dir='.$_GET['dir'].'&link='.$filename.'&lock='.$key.'">'.$filename, '</a> - '.$finfsiz.' - <a href="'.$thisfile.'?dir='.$_GET['dir'].'&viewvideo&link='.$filename.'&lock='.$key.'">[Video ansehen]</a><br>';
		} else if($ext == "ini" || $ext == "txt" || $ext == "log" || $ext == "java" || $ext == "php"|| $ext == "htm" || $ext == "html" || $ext == "htaccess" || $ext == "cmd" || $ext == "json" || $ext == "sh" || $ext == "bat" || $ext == "sh"){
			echo '<a href="'.$thisfile.'?dir='.$_GET['dir'].'&link='.$filename.'&lock='.$key.'">'.$filename, '</a> - '.$finfsiz.' - <a href="'.$thisfile.'?dir='.$_GET['dir'].'&viewtext&link='.$filename.'&lock='.$key.'">['.strtoupper($ext).' als Text anzeigen]</a><br>';
		} else {
			echo '<a href="'.$thisfile.'?dir='.$_GET['dir'].'&link='.$filename.'&lock='.$key.'">'.$filename.'</a> - '.$finfsiz.' - ['.strtoupper($ext).']<br>'; 
		}
    }
}

function getLink ($stack, $fn, $rootdir){
	$stack = str_replace($rootdir, '', $stack);
    $arr = array_filter(explode('/',$stack));
    $out = array('/'.implode('/',$arr).'/');
    while((array_pop($arr) and !empty($arr))){
        $out[] = '/'.implode('/',$arr).'/';
    };
	$res = $out[1];
	return $res;
}

?>