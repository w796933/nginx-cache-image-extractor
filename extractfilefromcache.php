<?php 

error_reporting(0);

$listcachefile = 'listcachefile.txt';
$contents_listcachefile = file($listcachefile);

foreach($contents_listcachefile as $line) {

    $arr_element = split(" ",$line);
    //print_r($arr_element);
    $cachefile = $arr_element[1];
    $outputfile = urldecode(strtok(str_replace('fptshop.com.vn/', '', $arr_element[4]),'?'));
    $outputfile = iconv("UTF-8", "CP1258//TRANSLIT", $outputfile); //reserve filename unicode characters in Windows

    echo "[+] Cache File: ".$cachefile."\n";
    echo "[+] Output file: ".$outputfile."\n";

    extractfile($cachefile,$outputfile);

    echo "-----------------\n";

}

//---FUNCTION---

/*
Desc: extract nginx cache file to real file
$cachefile : full path to nginx cache file
$outputfile : full path to extracted file
*/
function extractfile($cachefile, $outputfile){

$magicbytes = 'ffd8ff'; //magic bytes of image file

//---Read file in binary and covert to hex---
$hex_content = '';
$handle = @fopen($cachefile, "rb");
if ($handle) {
    while (!feof($handle)) {
        $hex_content .= bin2hex(fread ($handle , 1 ));
    }
    fclose($handle);
}

$startpos = strpos($hex_content, $magicbytes);

$image_data_hex = substr($hex_content, $startpos);

//---Convert Hex to Bin and Write to file---

$image_data = hex2bin($image_data_hex);

@mkdir(dirname($outputfile), 0777, true); //make directories from path

@unlink('tmp.img');
$fp = @fopen('tmp.img', 'wb'); //binary safe open file handle for writing binary
fwrite($fp, $image_data);
fclose($fp);

$sizeoftmp = filesize('tmp.img');
if($sizeoftmp >= 1000){
	if(file_exists($outputfile)){
		$sizeofexisted = filesize($outputfile);
		if($sizeoftmp > $sizeofexisted){
			rename('tmp.img', $outputfile);
			echo "[!!] REPLACED WITH BIGGER FILE, TMP: $sizeoftmp > EXISTED: $sizeofexisted\n";
			//echo "DEBUG Cache File: $cachefile \n";
			//echo "DEBUG Output File: $outputfile \n";
		}else{
			echo "/!\ File Existed is bigger/equal, EXISTED: $sizeofexisted >= TMP: $sizeoftmp \n";

		}
	}else{
		rename('tmp.img', $outputfile);
		echo "[>>] Created File \n";
	}
}else{
	echo "/!\ FILE TOO SMALL, it is corrupted, TMP: $sizeoftmp \n";
	@unlink('tmp.img');
}

}

?>