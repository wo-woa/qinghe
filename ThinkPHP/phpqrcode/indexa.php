<?php    

    $imgdir = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
    $temp = '/phpqrcode/temp/';
    include "qrlib.php"; 
    if (!file_exists($imgdir))
        mkdir($imgdir);
    
    $str='aaa';
        $filename = $imgdir.'img_'.md5($str).'.png';
        QRcode::png($str, $filename, 'M', 50, 1); //png(内容，文件名，'L/Q/M/H'质量，1-50相素，1-2边框) 
        
    
        echo(','.$temp.basename($filename)); 
        
    //display generated file
    echo '<img src="'.$temp.basename($filename).'" /><hr/>';  
  