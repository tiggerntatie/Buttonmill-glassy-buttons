<?php
// glassy.php
//
// glassy button composition form
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

// #!/usr/local/bin/php-4.3.1
// dl("local/gdfm431.so");  // pick our own gd here!

// begin our session
session_start();

// play with fire here!  careful!
set_time_limit(60);

// includes
require("buttonincludes.php");

include("help.php");

$favimgext = "png";
$isiebrowser = false;


$button_text = isset($_REQUEST[BUTTON_TEXT]) ? stripslashes($_REQUEST[BUTTON_TEXT]) : "";
$color = isset($_REQUEST[COLOR]) ? $_REQUEST[COLOR] : "";
$grcolor = isset($_REQUEST[GRCOLOR]) ? $_REQUEST[GRCOLOR] : "";
$width_image = isset($_REQUEST[WIDTH]) ? $_REQUEST[WIDTH] : "";
$height_image = isset($_REQUEST[HEIGHT]) ? $_REQUEST[HEIGHT] : "";
$corner_radius = isset($_REQUEST[RADIUS]) ? $_REQUEST[RADIUS] : "";
$text_height = isset($_REQUEST[THEIGHT]) ? $_REQUEST[THEIGHT] : "";
$text_color = isset($_REQUEST[TCOLOR]) ? $_REQUEST[TCOLOR] : "";
$back_color = isset($_REQUEST[BKCOLOR]) ? $_REQUEST[BKCOLOR] : "";
$font_style = isset($_REQUEST[STYLE]) ? $_REQUEST[STYLE] : "";
$font_name = isset($_REQUEST[FNAME]) ? $_REQUEST[FNAME] : "";
$quality = isset($_REQUEST[QUALITY]) ? $_REQUEST[QUALITY] : "";
$cachename = isset($_REQUEST[USECACHE]) ? $_REQUEST[USECACHE] : "";
// rollover attributes
$rcolor = isset($_REQUEST[RCOLOR]) ? $_REQUEST[RCOLOR] : "";
$rgrcolor = isset($_REQUEST[RGRCOLOR]) ? $_REQUEST[RGRCOLOR] : "";
$rtext_color = isset($_REQUEST[RTCOLOR]) ? $_REQUEST[RTCOLOR] : "";
// image attributes
$image_locate = isset($_REQUEST[IMAGELOCATE]) ? $_REQUEST[IMAGELOCATE] : "";
$image_height = isset($_REQUEST[IMAGEHEIGHT]) ? $_REQUEST[IMAGEHEIGHT] : "";
$image_name = isset($_REQUEST[IMAGENAME]) ? $_REQUEST[IMAGENAME] : "";
$image_foreground = isset($_REQUEST[IMAGEFORE]) ? $_REQUEST[IMAGEFORE] : "";
$image_foregroundcolor = isset($_REQUEST[IMAGEFORECOLOR]) ? $_REQUEST[IMAGEFORECOLOR] : "";
$image_transparent = isset($_REQUEST[IMAGETRAN]) ? $_REQUEST[IMAGETRAN] : "";
$image_transparentcolor = isset($_REQUEST[IMAGETRANCOLOR]) ? $_REQUEST[IMAGETRANCOLOR] : "";


// whether to render or use cached image
$fromhere = isset($_REQUEST['fromhere']) ? $_REQUEST['fromhere'] : "";

if (empty($fromhere)) $fromhere = FALSE; else $fromhere = TRUE;

// recreate this default argument list:
// http://temp.modwest.com/netdenizen.com/buttonmill/glassybutton.php?button_text=Classy%20Glassy%20Buttons!&theight=12&color=99eeff&grcolor=1020ff&width=260&height=24&radius=15&tcolor=blue&bkcolor=white&quality=1&style=bolditalic
//if (empty($button_text)) $button_text = "Classy Glassy Buttons!";
if (empty($color)) $color = "3366ff";
if (empty($grcolor)) $grcolor = "330099";
if (empty($width_image)) $width_image = "260";
if (empty($height_image)) $height_image = "24";
if (empty($corner_radius)) $corner_radius = "15";
if (empty($text_height)) $text_height = "12";
if (empty($text_color)) $text_color = "000000";
if (empty($back_color)) $back_color = "white";
if (empty($font_style)) $font_style = "8";
if (empty($quality)) $quality = "3";
// rollover
if (empty($rcolor)) $rcolor = "66ffff";
if (empty($rgrcolor)) $rgrcolor = "6666ff";
if (empty($rtext_color)) $rtext_color = "000000";
// image
if (empty($image_locate)) $image_locate = "none";  // none
if (empty($image_height)) $image_height = $text_height;
if (empty($image_name)) $image_name = "";     // empty name
if (empty($image_foreground)) $image_foreground = "auto";  // auto
if (empty($image_foregroundcolor)) $image_foregroundcolor = "000000";  // black
if (empty($image_transparent)) $image_transparent = "auto";  // auto
if (empty($image_transparentcolor)) $image_transparentcolor = "ffffff";


//////
// Set Up Fonts

// handle uploaded font, if any
// make sure a user font directory exists
@mkdir(USRFONTPATH, 0700);
@chmod(USRFONTPATH, 0700);

if (!empty($_FILES[USERFONT]['tmp_name']) && $_FILES[USERFONT]['size'] < MAXUSRFONTSIZE)
{
  $font = tempnam(USRFONTPATH, "");
  move_uploaded_file($_FILES[USERFONT]['tmp_name'], $font);
  $_SESSION[USRFONTFILE] = $font;
  $_SESSION[USRFONTNAME] = $_FILES[USERFONT]['name'];
  $font_style = 1;
}

// instantiate the fonts class .. first font is user font, if any
$fonts = new cFont(FONTPATH);

$fontqty = $fonts->GetFontQty();



// allow user to specify the font name.. this is a safe way to specify fonts in case
// the list of available fonts changes
if (empty($font_name))
{
  // generate $font_name from index at least
  $font_name = $fonts->GetFontNameFromIndex($font_style-1);
}
else
{
  $font_style = $fonts->GetFontIndexFromName($font_name)+1;
}

//////
// Set Up Images

// handle uploaded image, if any
// make sure a user image directory exists
@mkdir(USRIMGPATH, 0700);
@chmod(USRIMGPATH, 0700);
@mkdir(USRIMGTHUMBPATH, 0755);
@chmod(USRIMGTHUMBPATH, 0755);

if (!empty($_FILES[USERIMG]['tmp_name']) && $_FILES[USERIMG]['size'] < MAXUSRIMGSIZE)
{
  $imagename = tempnam(USRIMGPATH, "");
  move_uploaded_file($_FILES[USERIMG]['tmp_name'], $imagename);
  $_SESSION[USRIMGFILE] = $imagename;
  $_SESSION[USRIMGNAME] = $_FILES[USERIMG]['name'];
  $image_name = $_SESSION[USRIMGNAME];
  // create a thumbnail
  $size = GlassyImageSize($imagename, $image_name);
  if ($size[0] && $size[1] && (($size[2] == 99) || ($size[2] < 4)))
  {
    $fileroot = explode('.',basename($imagename));
    EnsureThumbnail($image_name,$size,THUMBSIZE);
  }
}





// fix up strings that should be lower case
$color = strtolower($color);
$grcolor = strtolower($grcolor);
$text_color = strtolower($text_color);
$back_color = strtolower($back_color);
$font_style = strtolower($font_style);
$rcolor = strtolower($rcolor);
$rgrcolor = strtolower($rgrcolor);
$rtext_color = strtolower($rtext_color);
$image_foregroundcolor = strtolower($image_foregroundcolor);
$image_transparentcolor = strtolower($image_transparentcolor);

$html_button_text = str_replace('"', '&quot;', $button_text);

// create a local background color object so we can provide the proper hue
$ourbkcolor = new cColor($back_color);

// start out with a default zip name
$zippath = IMGPATH."/".basename(DEFAULTZIP,".zip");
// check to see if a default zip file exists
$defaultzip =  file_exists(IMGPATH."/".DEFAULTZIP);


// now, to go about rendering the button image!
// only bother rendering if we're referred here from ourself.
// people coming in from outside shouldn't have to wait for anything!
// if the url includes a cache identifier, use it
if (!empty($cachename))
{
  $zippath = IMGPATH."/".$cachename;
}
elseif ($fromhere || !$defaultzip)
{
  $zippath = generate_images(
          $quality,
          $width_image,
          $height_image,
          $actwoa,
          $acthoa,
          $corner_radius,
          $color,
          $grcolor,
          $button_text,
          $text_height,
          $text_color,
          $back_color,
          $font_style,
          $rcolor,
          $rgrcolor,
          $rtext_color,
          $image_locate,
          $image_height,
          $image_name,
          $image_foreground,
          $image_foregroundcolor,
          $image_transparent,
          $image_transparentcolor,
          $fonts,
          $defaultzip
          );
  $neww = $actwoa;
  $newh = $acthoa;
}
elseif (!$fromhere)
{
  $zippath = IMGPATH."/".basename(DEFAULTZIP,".zip");
  $dummy = "";
  // retrieve default image details from default.zip
  if (get_zip_data(basename($zippath), $dummy, $dummy, false, true, $dummy, $dummy, $data))
  {
    $button_text = $data[TAGBT];
    $color = $data[TAGPC];
    $grcolor = $data[TAGGC];
    $width_image = $data[TAGW];
    $height_image = $data[TAGH];
    $corner_radius = $data[TAGCR];
    $text_height = $data[TAGTH];
    $text_color = $data[TAGTC];
    $back_color = $data[TAGBC];
    $font_name = $data[TAGFN];
    $rcolor = $data[TAGRPC];
    $rgrcolor = $data[TAGRGC];
    $rtext_color = $data[TAGRTC];
    $quality = $data[TAGQ];
    $image_locate = $data[TAGIL];
    $image_height = $data[TAGIH];
    $image_name = $data[TAGIN];
    $image_foreground = $data[TAGIF];
    $image_foregroundcolor = $data[TAGIFC];
    $image_transparent = $data[TAGIT];
    $image_transparentcolor = $data[TAGITC];
    $font_style = $fonts->GetFontIndexFromName($font_name)+1;
  }
}

// get the image dimensions
if ($imagesize = get_zip_image(basename($zippath), "0", $favimgext, $pngimg, $imagename)) {
  $imgsize = getimagesize($pngimg);
  $neww = $imgsize[0];
  $newh = $imgsize[1];
  @unlink($pngimg);
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Glassy Buttons</title>
<link rel="stylesheet" href="glassy.css" type="text/css" />

<script type="text/javascript">
  /* <![CDATA[ */
var helpitem = new Array();
<?php
print "helpstart=\"".str_replace("</", "<\\/", strtr($helpbodystart,"\r\n","  "))."\";\r\n";
print "helpend=\"".str_replace("</", "<\\/", strtr($helpbodyend,"\r\n","  "))."\";\r\n";
foreach ($helpitems as $name => $text)
{
  print "helpitem['".$name."']=\"".str_replace("</", "<\\/", strtr($text,"\r\n","  "))."\";\r\n";
}
?>

function popUp(message) {
day = new Date();
id = day.getTime();
eval("page"+id+"=window.open('','"+id+"','toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=300,height=300');");
eval("page"+id+".document.write(helpstart+helpitem['"+message+"']+helpend);");
eval("page"+id+".document.close();");
}

function imagePicker() {
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open('imagemenu.php', '" + id + "', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=1,width=150,height=150');");
}

  /* ]]> */
</script>


<!-- Link script file to the HTML document in the header -->
<script  type="text/javascript" src="picker.js"></script>
<script  type="text/javascript">
/* <![CDATA[ */
var cacheonimg = new Image ();
var cacheoffimg = new Image ();
cacheonimg.src = <?php echo "'image.php?name=".$zippath."_1.".$favimgext."'" ?>;
cacheoffimg.src = <?php echo "'image.php?name=".$zippath."_0.".$favimgext."'" ?>;
/* ]]> */
</script>

</head>

<body>

<table class="head" style="text-align:center;margin-left:auto;margin-right:auto;">
  <tr><td class="h1">
    glassy buttons
  </td><td class="h3">
    free online glass button generator
  </td></tr>
  <tr><td colspan="2"><hr /></td></tr>
</table>

<table style="text-align:center;margin-left:auto;margin-right:auto;"><tr><td>

<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="buttonform" enctype="multipart/form-data">
<table class="head">
  <tr><td>


<!--  Display the desired button here! -->
  <table class="form">
    <tr>
      <td class="margin">
<!-- // -->
      </td>
      <td class="<?php
      $isclear = false;
      if (strcmp(strtoupper($back_color),"CLEAR") != 0)
      {
        echo 'preview';
      }
      else
      {
        echo 'clearpreview';
        $isclear = true;
      }
      ?>">
<?php echo '<a href="" onMouseover="document.getElementById(\'buttonimg\').src=eval(\'cacheonimg.src\')" onMouseout="document.getElementById(\'buttonimg\').src=eval(\'cacheoffimg.src\')"><img src="image.php?name='.$zippath."_0.".$favimgext.'" id="buttonimg" width="'.$neww.'" height="'.$newh.'" alt="'.$html_button_text.'" /></a>'."\n";?>
<?php echo '<br /><br /><a href="'.$zippath.'.zip">download zipped images (png, jpeg, readme)</a>'."\n";?>
<?php if ($isiebrowser && $isclear) echo '<br /><b>warning!</b> Internet Explorer does not handle transparency well.\n'?>
      </td>
      <td class="margin">

<!-- hidden input identifies calls from ourself -->
<input class="hidden" type="hidden" name="fromhere" value="1" />
rendering speed<br />
<select name="quality">
<?php
  $qualdesc = Array(1=>'zippy', 2=>'fast', 3=>'sluggish', 4=>'wicked slow');
  for ($index = 1; $index <= 4; $index++)
  {
    echo '<option';
    if ($index == $quality)
    {
      echo ' selected';
    }
    echo ' value="'.strval($index).'">'.$qualdesc[$index].'</option>'."\n";
  }
?>
</select>
<p><input class="submit" type="submit" value="Reload" name="submit" tabindex="12" /></p>
<p><em><b><a href="<?php echo RECENTPAGE; ?>">recent buttons</a></b></em></p>
      </td>
    </tr>
  </table>
  </td></tr>

  <tr><td>
  <hr />
  </td></tr>

  <tr><td>

<!--  Show the menu options here -->

    <table class="form">
      <!-- row 1 -->
      <tr>
      <!-- column 1 -->
        <td class="formdesc">
button text
        </td>
        <td class="formedit">
<textarea name="button_text" tabindex="1"><?php echo $html_button_text; ?></textarea>
<a href="javascript:popUp('text')"><img src='question.png' alt='information about this item' /></a>
        </td>
        <td class="sminfo">
      <!-- column 2 -->
        </td>
        <td class="formdesc">
radius (pixels)
        </td>
        <td class="formedit">
<input class="text" type="text" name="radius" tabindex="2" maxlength="3" value="<?php echo $corner_radius; ?>" />
<a href="javascript:popUp('radius')"><img src='question.png' alt='information about this item' /></a>
        </td>
        <td class="sminfo">
<!-- corner radius, pixels -->
        </td>
      </tr>
      <!-- row 2 -->
      <tr>
      <!-- column 1 -->
        <td class="formdesc">
width (pixels)
        </td>
        <td class="formedit">
<input class="text" type="text" name="width" tabindex="3" maxlength="3" value="<?php echo $width_image; ?>" />
<a href="javascript:popUp('width')"><img src='question.png' alt='information about this item' /></a>
        </td>
        <td class="sminfo">
      <!-- column 2 -->
        </td>
        <td class="formdesc">
height (pixels)
        </td>
        <td class="formedit">
<input class="text" type="text" name="height" tabindex="4" maxlength="3" value="<?php echo $height_image; ?>" />
<a href="javascript:popUp('height')"><img src='question.png' alt='information about this item' /></a>
        </td>
        <td class="sminfo">
        </td>
      </tr>
      <!-- row 3 -->
      <tr>
      <!-- column 1 -->
        <td class="formdesc">
text height (pixels)
        </td>
        <td class="formedit">
<input class="text" type="text" name="theight" tabindex="5" maxlength="3" value="<?php echo $text_height; ?>" />
<a href="javascript:popUp('textheight')"><img src='question.png' alt='information about this item' /></a>
        </td>
       <td class="sminfo">
      <!-- column 2 -->
        <td class="formdesc">
text style
        </td>
        <td class="formedit">
<select name="style" tabindex="6">
<?php
  for ($index = 0; $index < $fontqty; $index++)
  {
    echo '<option';
    if ($index+1 == $font_style)
    {
      echo ' selected';
    }
    echo ' value="'.strval($index+1).'">'.$fonts->GetFontItemLabel($index).'</option>'."\n";
  }
  ?>
</select>
        </td>
        <td class="sminfo">
        </td>
      </tr>
      <!-- row 4 -->
      <tr>
      <!-- column 1 -->
        <td class="formdesc">
text color
        </td>
        <td class="formedit">
<input class="color" type="text" name="tcolor" tabindex="7" maxlength="6" value="<?php echo $text_color; ?>" />
<a href="javascript:TCP.popup(document.forms['buttonform'].elements['tcolor'])"><img src="palette.png" width="16" height="16" alt="Click here to pick the color" /></a>
<a href="javascript:popUp('textcolor')"><img src='question.png' alt='information about this item' /></a>
        </td>
        <td class="sminfo">
        </td>
      <!-- column 2 -->
        <td class="formdesc">
upload font
        </td>
        <td class="formedit">
<input class="file" type="file" name="userfont" tabindex="8" />
<a href="javascript:popUp('fonts')"><img src='question.png' alt='information about this item' /></a>
        </td>
        <td class="sminfo">
        </td>
      </tr>
      <!-- row 5 -->
      <tr>
      <!-- column 1 -->
        <td class="formdesc">
color
        </td>
        <td class="formedit">
<input class="color" type="text" name="color" tabindex="9" maxlength="7" value="<?php echo $color; ?>" />
<a href="javascript:TCP.popup(document.forms['buttonform'].elements['color'])"><img src="palette.png" width="16" height="16"  alt="Click here to pick the color" /></a>
<a href="javascript:popUp('color')"><img src='question.png' alt='information about this item' /></a>
        </td>
        <td class="sminfo">
<!-- color code -->
        </td>
      <!-- column 2 -->
        <td class="formdesc">
background color
        </td>
        <td class="formedit">
<input class="color" type="text" name="bkcolor" tabindex="10" maxlength="6" value="<?php echo $back_color; ?>" />
<a href="javascript:TCP.popup(document.forms['buttonform'].elements['bkcolor'])"><img src="palette.png" width="16" height="16" alt="Click here to pick the color" /></a>
<a href="javascript:popUp('backcolor')"><img src='question.png' alt='information about this item' /></a>
<input class="button" type="button" tabindex="99" value="transparent" onclick="javascript:document.forms['buttonform'].elements['bkcolor'].value='clear'" />
        </td>
        <td class="sminfo">
        </td>
      </tr>
      <!-- row 6 -->
      <tr>
      <!-- column 1 -->
        <td class="formdesc">
rollover text color
        </td>
        <td class="formedit">
<input class="color" type="text" name="rtcolor" tabindex="11" maxlength="6" value="<?php echo $rtext_color; ?>" />
<a href="javascript:TCP.popup(document.forms['buttonform'].elements['rtcolor'])"><img src="palette.png" width="16" height="16" alt="Click here to pick the color" /></a>
<a href="javascript:popUp('rtextcolor')"><img src='question.png' alt='information about this item' /></a>
        </td>
        <td class="sminfo">
        </td>
      <!-- column 2 -->
        <td class="formdesc">
edge color
        </td>
        <td class="formedit">
<input class="color" type="text" name="grcolor" tabindex="12" maxlength="6" value="<?php echo $grcolor; ?>" />
<a href="javascript:TCP.popup(document.forms['buttonform'].elements['grcolor'])"><img src="palette.png" width="16" height="16" alt="Click here to pick the color" /></a>
<a href="javascript:popUp('edgecolor')"><img src='question.png' alt='information about this item' /></a>
        </td>
        <td class="sminfo">
<!-- button edge color -->
        </td>
      </tr>
      <!-- row 7 -->
      <tr>
      <!-- column 1 -->
        <td class="formdesc">
rollover color
        </td>
        <td class="formedit">
<input class="color" type="text" name="rcolor" tabindex="13" maxlength="7" value="<?php echo $rcolor; ?>" />
<a href="javascript:TCP.popup(document.forms['buttonform'].elements['rcolor'])"><img src="palette.png" width="16" height="16"  alt="Click here to pick the color" /></a>
<a href="javascript:popUp('rcolor')"><img src='question.png' alt='information about this item' /></a>
        </td>
        <td class="sminfo">
        </td>
      <!-- column 2 -->
        <td class="formdesc">
rollover edge color
        </td>
        <td class="formedit">
<input class="color" type="text" name="rgrcolor" tabindex="14" maxlength="6" value="<?php echo $rgrcolor; ?>" />
<a href="javascript:TCP.popup(document.forms['buttonform'].elements['rgrcolor'])"><img src="palette.png" width="16" height="16" alt="Click here to pick the color" /></a>
<a href="javascript:popUp('redgecolor')"><img src='question.png' alt='information about this item' /></a>
        </td>
        <td class="sminfo">
        </td>
      </tr>
      <!-- row 8 -->
      <tr><td colspan="6">
<hr />
      </td></tr>
      <!-- row 9 -->
      <tr>
      <!-- column 1 -->
        <td class="formdesc">
button image
        </td>
        <td class="formedit">
<input class="text" type="text" name="imgname" tabindex="15" value="<?php echo $image_name; ?>" />
<?php
GetImagePaths($image_name, $fullpath, $thumbpath);
?>
<a href="javascript:imagePicker()"><img alt="select a background button image" id="imagesrc" src="<?php echo $thumbpath; ?>"></a>
<a href="javascript:popUp('image')"><img src='question.png' alt='information about this item' /></a>
        </td>
        <td class="sminfo">
        </td>
      <!-- column 2 -->
        <td class="formdesc">
upload image
        </td>
        <td class="formedit">
<input class=file type="file" name="userimage" tabindex="16" />
<a href="javascript:popUp('images')"><img src='question.png' alt='information about this item' /></a>
        </td>
        <td class="sminfo">
        </td>
      </tr>
      <!-- row 10 -->
      <tr>
      <!-- column 1 -->
        <td class="formdesc">
image height (pixels)
        </td>
        <td class="formedit">
<input class="text" type="text" name="imgheight" tabindex="17" maxlength="3" value="<?php echo $image_height; ?>" />
<a href="javascript:popUp('imageheight')"><img src='question.png' alt='information about this item' /></a>
        </td>
        <td class="sminfo">
<!-- pixels -->
        </td>
      <!-- column 2 -->
        <td class="formdesc">
image position
        </td>
        <td class="formedit">
<select name="imglocate" tabindex="18">
<?php
  $posdesc = Array(1=>'none', 2=>'left', 3=>'right', 4=>'background');
  for ($index = 1; $index <= 4; $index++)
  {
    echo '<option';
    if (strcmp($posdesc[$index],$image_locate) == 0)
    {
      echo ' selected';
    }
    echo '>'.$posdesc[$index].'</option>'."\n";
  }
?>
</select>
<a href="javascript:popUp('imagelocate')"><img src='question.png' alt='information about this item' /></a>
        </td>
        <td class="sminfo">
        </td>
      </tr>
      <!-- row 11 -->
      <tr>
      <!-- column 1 -->
        <td class="formdesc">
foreground type
        </td>
        <td class="formedit">
<select name="imgfore" tabindex="19">
<?php
  $imgfdesc = Array(1=>'auto', 2=>'none', 3=>'custom');
  for ($index = 1; $index <= 3; $index++)
  {
    echo '<option';
    if (strcmp($imgfdesc[$index],$image_foreground) == 0)
    {
      echo ' selected';
    }
    echo '>'.$imgfdesc[$index].'</option>'."\n";
  }
?>
</select>
 <a href="javascript:popUp('imageforeground')"><img src='question.png' alt='information about this item' /></a>
        </td>
        <td class="sminfo">
        </td>
      <!-- column 2 -->
        <td class="formdesc">
foreground color
        </td>
        <td class="formedit">
<input class="color" type="text" name="imgforecolor" tabindex="20" maxlength="6" value="<?php echo $image_foregroundcolor; ?>" />
<a href="javascript:TCP.popup(document.forms['buttonform'].elements['imgforecolor'])"><img src="palette.png" width="16" height="16" alt="Click here to pick the color" /></a>
<a href="javascript:popUp('imageforegroundcolor')"><img src='question.png' alt='information about this item' /></a>
        </td>
        <td class="sminfo">
        </td>
      </tr>
      <!-- row 12 -->
      <tr>
      <!-- column 1 -->
        <td class="formdesc">
transparency
        </td>
        <td class="formedit">
<select name="imgtran" tabindex="21">
<?php
  $imgtdesc = Array(1=>'auto', 2=>'none', 3=>'custom');
  for ($index = 1; $index <= 3; $index++)
  {
    echo '<option';
    if (strcmp($imgtdesc[$index],$image_transparent) == 0)
    {
      echo ' selected';
    }
    echo '>'.$imgtdesc[$index].'</option>'."\n";
  }
?>
</select>
<a href="javascript:popUp('imagetransparent')"><img src='question.png' alt='information about this item' /></a>
        </td>
        <td class="sminfo">
        </td>
      <!-- column 2 -->
        <td class="formdesc">
transparent color
        </td>
        <td class="formedit">
<input class="color" type="text" name="imgtrancolor" tabindex="22" maxlength="6" value="<?php echo $image_transparentcolor; ?>" />
<a href="javascript:TCP.popup(document.forms['buttonform'].elements['imgtrancolor'])"><img src="palette.png" width="16" height="16" alt="Click here to pick the color" /></a>
<a href="javascript:popUp('imagetransparentcolor')"><img src='question.png' alt='information about this item' /></a>
        </td>
        <td class="sminfo">
        </td>
      </tr>
<!-- end of rows -->

    </table>
  </td></tr>
  <tr><td><hr /></td></tr>
  <tr><td>

<p class="foot">
  <a rel="license"
     href="http://creativecommons.org/publicdomain/zero/1.0/">
    <img src="http://i.creativecommons.org/p/zero/1.0/88x31.png" style="border-style: none;" alt="CC0" />
  </a>
  <br />
  This site is generated dynamically using software produced by the Buttonmill project. To the extent possible under law,
  <a 
     href="https://launchpad.net/buttonmill">
    <span >Eric Dennison</span></a>
  has waived all copyright and related or neighboring rights to
  <span >Buttonmill</span>.
This work is published from:
 United States.
</p>
  </td></tr>

</table>
</form>

</td></tr></table>

</body>

</html>

<?php
// clean up the font directory
$dir = @opendir(USRFONTPATH);
while ($file = @readdir($dir))
{
  if ($file != "." && $file != "..")
  {
    $file = USRFONTPATH."/".$file;
    if (time() > @filectime($file) + 60*60) // 1 hour
    {
      @unlink($file);
    }
  }
}

// clean up the user image directory
$dir = @opendir(USRIMGPATH);
while ($file = @readdir($dir))
{
  if ($file != "." && $file != "..")
  {
    $killfile = USRIMGPATH."/".$file;
    if (time() > @filectime($killfile) + 60*60) // 1 hour
    {
      $base = explode(".",basename($file));
      $thumb = USRIMGTHUMBPATH."/".$base[0].".png";
      @unlink($killfile);
      @unlink($thumb);
    }
  }
}

// clean up the image directory
$dir = @opendir(IMGPATH);
$dircount = 0;
// only delete if more than 100 files in the image path
while ($file = @readdir($dir)) $dircount++;
if ($dircount > MINTEMPFILEQTY)
{
  rewinddir($dir);
  // and don't leave less than 101 files there
  while (($file = @readdir($dir)) && ($dircount > MINTEMPFILEQTY))
  {
    if ($file != "." && $file != "..")
    {
      $file = IMGPATH."/".$file;
      if (time() > @filectime($file) + 60*60) // 1 hour
      {
        if (is_dir($file))
        {
          $handle = opendir($file);
          while($filename = readdir($handle)) {
            if ($filename != "." && $filename != "..") {
              @unlink($file."/".$filename);
            }
          }
          closedir($handle);
          @rmdir($file);
        }
        else
        {
          @unlink($file);
        }
      }
    }
    $dircount--;
  }
}

?>

