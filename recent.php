<?php
// recent.php
//
// script builds a list of images and links corresponding
// to whatever is in the images directory
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

// includes
require("buttonincludes.php");

$startindex = isset($_REQUEST['start']) ? $_REQUEST['start'] : "0";
$buttonsperpage = isset($_REQUEST['buttonsperpage']) ? $_REQUEST['buttonsperpage'] : RECENTPERPAGE;
$startindex *= 1;
$buttonsperpage *= 1;

?>

<!DOCTYPE html>

<head>
<title>Recent Glassy Buttons</title>
<link rel="stylesheet" href="glassy.css" type="text/css" />
</head>

<body>

<table class="head" style="text-align:center;margin-left:auto;margin-right:auto;">
  <tr><td class="h1">
    recent buttons
  </td><td class="h3">
    click any button to edit one just like it
  </td></tr>
</table>

<table style="text-align:center;margin-left:auto;margin-right:auto;width:700px;">
  <tr><td><hr /></td></tr>
<?php

$index = 0;
$exhausted = true;
foreach(glob(IMGPATH."/*.zip") as $zipfile)
{
  if ($index >= $startindex+$buttonsperpage)
  {
    $exhausted = false;
    break;
  }

  if ($index >= $startindex)
  {
    $basen = basename($zipfile,".zip");
    get_zip_url($basen, $url);
    $url = $url."&amp;".USECACHE."=".$basen;
    $basep = IMGPATH."/".$basen;
    $imgbaseurl = "http://".BASEURL.$_SERVER['REQUEST_URI'];
    $imgbaseurl = substr($imgbaseurl,0,strpos($imgbaseurl,basename($_SERVER['SCRIPT_FILENAME'])))."image.php?name=";
    $baseid  = "glassy";
    $baseid .= str_replace(".", "x", $basen);

    echo "<tr><td>\n";
    echo "<script type='text/javascript'>\n";
    echo "/* <![CDATA[ */\n";
    echo "var ".$baseid."_1 = new Image ();\n";
    echo "var ".$baseid."_0 = new Image ();\n";
    echo $baseid."_1.src = '".$imgbaseurl.$basep."_1.png';\n";
    echo $baseid."_0.src = '".$imgbaseurl.$basep."_0.png';\n";
    echo "/* ]]> */\n";
    echo "</script>\n";
    echo "<a href='".$url."' onMouseover='document.getElementById(\"".$baseid."\").src=eval(\"".$baseid."_1.src\")' onMouseout='document.getElementById(\"".$baseid."\").src=eval(\"".$baseid."_0.src\")'><img src='".$imgbaseurl.$basep."_0.png' id='".$baseid."' alt='recent button design' /></a>\n";
    echo "</td></tr>\n";
  }
  $index++;
}

?>
  <tr><td><hr /></td></tr>
</table>

<table class="head" style="text-align:center;margin-left:auto;margin-right:auto;">
  <tr><td style="font-size:15pt;text-align:left;vertical-align:bottom;font-family:arial,sans-serif;">
<?php
$previndex = $startindex-$buttonsperpage;
if ($previndex < 0) $previndex = 0;
if ($startindex > 0) echo '<a href="'.$_SERVER['PHP_SELF'].'?start='.$previndex.'&amp;buttonsperpage='.$buttonsperpage.'">&lt;&lt; prev</a>'." . . \n";
if (!$exhausted) echo '<a href="'.$_SERVER['PHP_SELF'].'?start='.($startindex+$buttonsperpage).'&amp;buttonsperpage='.$buttonsperpage.'">next &gt;&gt;</a>'."\n";
?>
  </td><td class="h3">
      <a href="<?php echo HOMEPAGE; ?>">back to glassy buttons</a>
  </td></tr>
</table>

</body>
