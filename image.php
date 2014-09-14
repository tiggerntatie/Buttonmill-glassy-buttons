<?php
// image.php
//
// script accesses image files in zip containers
// for use with glassy.php
//
// This file was created for the Buttonmill project by Eric Dennison in 2005
//
// To the extent possible under law, Eric Dennison has waived all copyright 
// and related or neighboring rights to Buttonmill. This work is published 
// from: United States. See https://launchpad.net/buttonmill for more
// information or to participate in this project.
//
// You may use this code for any purpose, commercial or personal without 
// permission and without attribution to the original author(s)
//

// the syntax for getting the image file is:
// image.php?name=xyz_0.png
// _0 or _1 is appended to "button" when seeking the file in the zip

$fname = $_REQUEST["name"];
$base = null;

$basefname = basename($fname);
$imgselect = @substr($basefname,strrpos($basefname, ".")-1,1);
$imgext = @substr($basefname,strrpos($basefname, ".")+1,3);
$base = basename($fname, "_".$imgselect.".".$imgext);

$zipname = @substr($fname,0,strpos($fname, "_".$imgselect.".".$imgext)).".zip";

if ($imgselect == "" || $imgext == "" || !file_exists($zipname))
{
  header("HTTP/1.0 404 Not Found");
  exit;
}

// everything looks promising, proceed with includes, etc.
// includes
require("buttonincludes.php");

$image = null;
$imagename = null;
$size = 0;



if (!file_exists(LOGGINGPATH))
{
  @mkdir(LOGGINGPATH, 0700);
  @chmod(LOGGINGPATH, 0700);
}
$logfilepath = LOGGINGPATH."/image.php.log";
$f = @fopen($logfilepath,"a");
// fwrite($f,date('r')." ".$_REQUEST["name"]." ".$_SERVER["HTTP_REFERER"]."\r\n");
@fclose($f);


// retrieve a single button image
$size = get_zip_image($base, $imgselect, $imgext, $image, $imagename);

if ($size)
{
  header("Content-Disposition: image; filename=".$imagename);
  header("Cache-Control: max-age=36000");
  header("Accept-Ranges: bytes");
// with composite image, we don't know the size without making a temp image file (bah!)
//  header("Content-Length: ".$size);
  header ("Content-type: image/".$imgext);
  readfile($image);
  @unlink($image);
}
else
{
  header("HTTP/1.0 404 Not Found");
  exit;
}


?>
