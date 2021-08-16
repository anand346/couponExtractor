<?php
echo chr(27).chr(91).'H'.chr(27).chr(91).'J'; //^[H^[J 
echo "
_______________________________________________________________________________________
|                                                  _                  _                |
|     ___ ___  _   _ _ __   ___  _ __     _____  _| |_ _ __ __ _  ___| |_ ___  _ __    | 
|    / __/ _ \| | | | '_ \ / _ \| '_ \   / _ \ \/ / __| '__/ _` |/ __| __/ _ \| '__|   |
|   | (_| (_) | |_| | |_) | (_) | | | | |  __/>  <| |_| | | (_| | (__| || (_) | |      |
|    \___\___/ \__,_| .__/ \___/|_| |_|  \___/_/\_\\__ |_|  \__,_|\___|\__\___/|_|      |
|                 |_|                                                                  |
|                                                                                      |
|    Author  :- Anand Raj                                                              |
|    Date    :- 02 August 2021                                                         |
|    Purpose :- To extract coupon code of udemy courses                                |
|______________________________________________________________________________________|

";
if(sizeof($argv) > 1){
    unset($argv[0]);
    if(sizeof($argv) > 1){
        echo "unexpected commands \n";exit();
    }else{
        if(is_numeric($argv[1])){
            if($argv[1] >= 1 && $argv[1] <= 5){
                $pagesToScrap = $argv[1];
            }else{
                echo "unexpected commands \n";exit();                            
            }
        }else{
            echo "unexpected commands \n";exit();            
        }
    }
}else{
    $pagesToScrap = 3;
}
echo "1. Paid courses only \n";
echo "2. Paid and free courses \n";
echo "Enter your choice : ";
$number = trim(fgets(STDIN));
if(is_numeric($number)){
    if($number == 1){
        echo "Extracting Paid Courses Coupon \n";
        $paidOrFreeNumber = $number;
    }else if($number == 2){
        echo "Extracting Paid and Free Courses Coupon \n";
        $paidOrFreeNumber = $number;
    }else{
        echo "command is not recognizable";exit();
    }
}else{
    echo "command is not recognizable";exit();
}
function getHtmlFromUrl($url){
    $agents = array(
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1',
        'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1.9) Gecko/20100508 SeaMonkey/2.0.4',
        'Mozilla/5.0 (Windows; U; MSIE 7.0; Windows NT 6.0; en-US)',
        'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_7; da-dk) AppleWebKit/533.21.1 (KHTML, like Gecko) Version/5.0.5 Safari/533.21.1'
     
    );
    $header = array();
    $header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
    $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
    $header[] = "Cache-Control: max-age=0";
    $header[] = "Connection: keep-alive";
    $header[] = "Keep-Alive: 300";
    $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
    $header[] = "Accept-Language: en-us,en;q=0.5";
    $header[] = "Pragma: ";
//assign to the curl request.
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_AUTOREFERER,true);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_USERAGENT,$agents[array_rand($agents)]);
    // curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $html = curl_exec($ch);
    curl_close($ch);
    return $html;
}
function getLinksWithReg($html,$tag = "a"){
    $dom = new DOMDocument();
    $lib_xml_previous_state = libxml_use_internal_errors(true);
    @$dom->loadHTML(mb_convert_encoding($html,'HTML-ENTITIES','UTF-8') ,LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    libxml_use_internal_errors($lib_xml_previous_state);
    $tagData = $dom->getElementsByTagName($tag);
    return $tagData;
}
function extractLink($links,$originalUrl){
    $hello =[];
    foreach ($links as  $value) {
        $generatedLink = $value->getAttribute("href");
        if(strpos($generatedLink,$originalUrl) !== false){
            $hello[] = $generatedLink;
            $hello = array_unique($hello);
        }
    }
    return $hello;
}
function extractSubLink($linkAnchor,$paidOrFreeNumber){
    $subLink = "";
    if($paidOrFreeNumber == 1){
        foreach ($linkAnchor as  $value) {
            $generatedLink = $value->getAttribute("href");
            if(strpos($generatedLink,"?couponCode=") !== false){
                $subLink = $generatedLink;
            }
        }
    }else if($paidOrFreeNumber == 2){
        foreach ($linkAnchor as  $value) {
            $generatedLink = $value->getAttribute("href");
            if(strpos($generatedLink,"www.udemy.com") !== false){
                $subLink = $generatedLink;
            }
        }
    }
        
    return $subLink;
}
function extractCoupon($pagesToScrap,$paidOrFreeNumber){
    $date = date("Y/m/d");
    $originalUrl = "https://studybullet.com/{$date}/";
    $linkLink = [];
    for ($i=1; $i <= $pagesToScrap ; $i++) {
        $url = "https://studybullet.com/{$date}/page/{$i}/";
        $html = getHtmlFromUrl($url);
        $anchors = getLinksWithReg($html,"a");
        $links = extractLink($anchors,$originalUrl);
        foreach ($links as $key => $value) {
            $linkHtml = getHtmlFromUrl($value);
            $linkAnchor = getLinksWithReg($linkHtml,"a");
            $result = extractSubLink($linkAnchor,$paidOrFreeNumber);
            if(!empty($result)){
                $linkLink[] = $result;
                $linkLink = array_unique($linkLink);
            }else{
                continue;
            }
        }
    }
    return $linkLink;
}
$allLinks = array();












function listFolderFiles($dir){
    $allImages = array();
    $ffs = scandir($dir);

    unset($ffs[array_search('.', $ffs, true)]);
    unset($ffs[array_search('..', $ffs, true)]);
    // print_r($ffs);
    // prevent empty ordered elements
    if (count($ffs) < 1)
        return;

    foreach($ffs as $ff){
        // echo "\n".$ff;
        if(!(is_dir($ff))){
            $allImages[] = $ff;         
        }
    }
    if(is_dir($dir."Sent")){
        $dir = $dir."Sent";
        listFolderFiles($dir);
    }
    return $allImages;
}

// $dir = __DIR__;
$images  = array();
$dir = "/storage/emulated/0/WhatsApp/Media/WhatsApp Images/";
$images = listFolderFiles($dir);
print_r($images);exit();

// echo $dir."\n";exit();
// for($j = 1;true; $j++){
//     if(!(is_dir($j))){
//         mkdir($j);
//     }
// }









$allLinks = extractCoupon($pagesToScrap,$paidOrFreeNumber);
print_r($allLinks);

?>