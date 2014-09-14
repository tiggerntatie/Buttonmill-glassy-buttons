<?php
// imagemenu.php
//
// thumbnail image picker
// for use with glassybutton.php
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


// begin our session
session_start();

// includes
require("buttonincludes.php");

$table_width = 4;

$imagelist = array(); // root name
$imagelistfull = array(); // full name
$imagecount = 0;



function ProcessImageName($imagename)
{
  global $imagelist, $imagelistfull;
  global $imagecount;
  GetImagePaths($imagename, $fullpath, $thumbpath);
  $size = GlassyImageSize($fullpath, $imagename);
  if ($size[0] && $size[1] && (($size[2] == 99) || ($size[2] < 4)))
  {
    $fileroot = explode('.',basename($imagename));
    EnsureThumbnail($imagename,  $size, THUMBSIZE);
    if (strcmp($fileroot[0],"null"))
    {
      $imagelist[$imagecount] = $imagename;
      $imagelistfull[$imagecount] = $thumbpath;
      $imagecount++;
    }
  }
}


// check for custom
if (isset($_SESSION[USRIMGNAME]))
{
  ProcessImageName($_SESSION[USRIMGNAME]);
}

foreach(glob(STOCKIMGPATH."/*.*") as $filename)
{
  ProcessImageName(basename($filename));
}


?>


<html>
<head>
  <title>Button Image Picker</title>
  <style>
    .bd { border : 1px inset InactiveBorder; }
    .s  { width:150 }
    img {border-width : 0; text-align : center;}
  </style>

<script  type="text/javascript"> <!--
function select(filename,thumbname) {
  opener.document.forms['buttonform'].elements['imgname'].value = filename;
  opener.document.images['imagesrc'].src = thumbname;
  window.close();
}
// --></script>

</head>
<body leftmargin="5" topmargin="5" marginheight="5" marginwidth="5">
<table cellpadding=0 cellspacing=2 border=0 width=150>

<?php
$thumbqty = count($imagelist);
$rowqty = floor(($thumbqty-1)/$table_width)+1;
$i = 0;
for ($r=0; $r<$rowqty; $r++)
{
  print "<tr>";
  for ($c=0; $c<$table_width; $c++)
  {
    if (isset($imagelist[$i]))
    {
      GetImagePaths($imagelist[$i], $fullpath, $thumbpath);
      print "<td><a href='javascript:select(\"".$imagelist[$i]."\",\"".$thumbpath."\")'><img src='".$thumbpath."'></a></td>\r\n";
    }
    else
    {
      print "<td></td>\r\n";
    }

    $i++;
  }
  print "</tr>\r\n";
}
?>

</table>
</body>
</html>
