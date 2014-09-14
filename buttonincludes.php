<?php
// buttonincludes.php
//
// required common routines for glassy.php
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

require("config.php");

if (!isset($_SERVER['REQUEST_URI'])) {
  $_SERVER['REQUEST_URI'] = getenv('SCRIPT_NAME');
  if (isset($_SERVER['QUERY_STRING'])) $_SERVER['REQUEST_URI'] .= '?'.$_SERVER['QUERY_STRING'];
}

function const_array($constant) {
  $array = explode(",",$constant);
  return $array;
};

function unzip_file($zipfile, $extractfile) {
  if (function_exists('zip_open')) {
    $zip = zip_open($zipfile);
    if ($zip) {
      while ($zip_entry = zip_read($zip)) {
        if (zip_entry_name($zip_entry) == $extractfile && zip_entry_open($zip, $zip_entry, "r")) {
         $buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
         zip_entry_close($zip_entry);
        }
      }
      zip_close($zip);
    }
  } else {
    $buf = shell_exec("unzip -p ".$zipfile." ".$extractfile);
  }
  return $buf;
}

// function for retrieving information from a zip file
// returns the png image and button url from the readme inside
// returns true on success, false otherwise
function get_zip_data($basename, $imgselect, $imgext, $getimage, $getdata, &$imgtempfile, &$imagename, &$data)
{
  $ret = 0;
  $zipname = IMGPATH."/".$basename.".zip";

  if (file_exists($zipname))
  {
    // make sure we keep the file around if people are using it actively
    touch($zipname);

    if ($getimage)
    {
      $imagename = "button_$imgselect.$imgext";
      $buf = unzip_file($zipname, $imagename);
//      $buf = shell_exec("unzip -p ".$zipname." ".$imagename);
      $tmpfname = tempnam (IMGPATH, "");
      if ($file = fopen($tmpfname, "w+"))
      {
        fwrite($file, $buf);
        fclose($file);
        $imgtempfile = $tmpfname;
        $ret = filesize($tmpfname);
      }
    }
    elseif ($getdata)
    {
      $buf = unzip_file($zipname, READMENAME);
//      $buf = shell_exec("unzip -p ".$zipname." ".READMENAME);
     // for each $readmetags, get the associated value and put in an array
      $data = array();
      $readmetags = array(TAGBT, TAGPC, TAGGC, TAGW, TAGH, TAGCR, TAGTH,
      TAGTC, TAGBC, TAGFN, TAGRPC, TAGRGC, TAGRTC, TAGQ, TAGURL,TAGIL,TAGIH,
      TAGIN,TAGIF,TAGIFC,TAGIT,TAGITC);
      foreach($readmetags as $tag)
      {
        $tempstr = substr(strstr($buf, $tag.": "),strlen($tag)+2);
        $data[$tag] = substr($tempstr, 0, strpos($tempstr,"\n"));
      }
      $ret = 1;
    }
  }
  return $ret;
}

function get_zip_image($basename, $imgselect, $imgext, &$pngimg, &$imagename)
{
  $dummy = "";
  return get_zip_data($basename, $imgselect, $imgext, true, false, $pngimg, $imagename, $dummy);
}

function get_zip_url($basename, &$url)
{
  $dummy = "";
  $ret = get_zip_data($basename, $dummy, "", false, true, $dummypng, $dummy, $data);
  @unlink($dummypng);
  $url = $data[TAGURL];
  return $ret;
}


// create the readme file for a button
function glassy_readme(
              $filename,
              $text,
              $color,
              $grcolor,
              $width,
              $height,
              $corner,
              $texth,
              $textc,
              $bkcolor,
              $fontname,
              $quality,
              $rcolor,
              $rgrcolor,
              $rtextc,
              $image_locate,
              $image_height,
              $image_name,
              $image_foreground,
              $image_foregroundcolor,
              $image_transparent,
              $image_transparentcolor
              )
{
  $url = "http://".BASEURL.$_SERVER['REQUEST_URI']."?".
            BUTTON_TEXT."=".urlencode($text)."&amp;".
            COLOR."=".urlencode($color)."&amp;".
            GRCOLOR."=".urlencode($grcolor)."&amp;".
            WIDTH."=".urlencode($width)."&amp;".
            HEIGHT."=".urlencode($height)."&amp;".
            RADIUS."=".urlencode($corner)."&amp;".
            THEIGHT."=".urlencode($texth)."&amp;".
            TCOLOR."=".urlencode($textc)."&amp;".
            BKCOLOR."=".urlencode($bkcolor)."&amp;".
            FNAME."=".urlencode($fontname)."&amp;".
            RCOLOR."=".urlencode($rcolor)."&amp;".
            RGRCOLOR."=".urlencode($rgrcolor)."&amp;".
            RTCOLOR."=".urlencode($rtextc)."&amp;".
            IMAGELOCATE."=".urlencode($image_locate)."&amp;".
            IMAGEHEIGHT."=".urlencode($image_height)."&amp;".
            IMAGENAME."=".urlencode($image_name)."&amp;".
            IMAGEFORE."=".urlencode($image_foreground)."&amp;".
            IMAGEFORECOLOR."=".urlencode($image_foregroundcolor)."&amp;".
            IMAGETRAN."=".urlencode($image_transparent)."&amp;".
            IMAGETRANCOLOR."=".urlencode($image_transparentcolor)."&amp;".
            QUALITY."=".urlencode($quality).
            "&amp;fromhere=1";



  $file = fopen($filename,"w+");
  if ($file)
  {
    $data = array(
      TAGBT=>$text,
      TAGPC=>$color,
      TAGGC=>$grcolor,
      TAGW=>$width,
      TAGH=>$height,
      TAGCR=>$corner,
      TAGTH=>$texth,
      TAGTC=>$textc,
      TAGBC=>$bkcolor,
      TAGFN=>$fontname,
      TAGRPC=>$rcolor,
      TAGRGC=>$rgrcolor,
      TAGRTC=>$rtextc,
      TAGQ=>$quality,
      TAGIL=>$image_locate,
      TAGIH=>$image_height,
      TAGIN=>$image_name,
      TAGIF=>$image_foreground,
      TAGIFC=>$image_foregroundcolor,
      TAGIT=>$image_transparent,
      TAGITC=>$image_transparentcolor,
      TAGURL=>$url);

    foreach($data as $key=>$val)
    {
      fwrite($file, $key.": ".$val."\n");
    }

    fclose($file);
  }
}


// function for generating the main and rollover button images
function generate_images(
          $quality,
          $width_image,
          $height_image,
          &$actwoa,
          &$acthoa,
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
          $defaultzipexists
          )
{
  // produce a temporary image file
  $deletetemppath = $defaultzipexists ? tempnam(IMGPATH, "") : IMGPATH."/".basename(DEFAULTZIP,".zip");
  $tmpimgpath = $deletetemppath."d";
  mkdir($tmpimgpath, 0700);
  $imgpath = $tmpimgpath."/button_0.png";
  $gifimgpath = $tmpimgpath."/button_0.gif";
  $jpgimgpath = $tmpimgpath."/button_0.jpg";
  $rimgpath = $tmpimgpath."/button_1.png";
  $rgifimgpath = $tmpimgpath."/button_1.gif";
  $rjpgimgpath = $tmpimgpath."/button_1.jpg";
  $readmepath = $tmpimgpath."/".READMENAME;

  $font_name = $fonts->GetFontNameFromIndex($font_style-1);

  // generate the readme text
  glassy_readme($readmepath,
              $button_text,
              $color,
              $grcolor,
              $width_image,
              $height_image,
              $corner_radius,
              $text_height,
              $text_color,
              $back_color,
              $font_name,
              $quality,
              $rcolor,
              $rgrcolor,
              $rtext_color,
              $image_locate,
              $image_height,
              $image_name,
              $image_foreground,
              $image_foregroundcolor,
              $image_transparent,
              $image_transparentcolor
              );

  $zippath = $deletetemppath;
  @unlink($deletetemppath);

  $qualityfactor = $quality;

  $font_path = $fonts->GetFontPathFromIndex($font_style-1);


  $im = MakeGradientFilledButtonPolygon(
                            $qualityfactor,
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
                            $image_locate,
                            $image_height,
                            $image_name,
                            $image_foreground,
                            $image_foregroundcolor,
                            $image_transparent,
                            $image_transparentcolor,
                            $font_path);


//  Header ('Content-type: image/png');
  @rename($im, $imgpath);
  @unlink($im);
  chmod($imgpath, 0644);

  @exec(IMCONVERT." $imgpath -quality 100 $jpgimgpath");
  chmod($jpgimgpath, 0644);


// create the rollover image
  $rim = MakeGradientFilledButtonPolygon(
                            $qualityfactor,
                            $width_image,
                            $height_image,
                            $actwoa,
                            $acthoa,
                            $corner_radius,
                            $rcolor,
                            $rgrcolor,
                            $button_text,
                            $text_height,
                            $rtext_color,
                            $back_color,
                            $image_locate,
                            $image_height,
                            $image_name,
                            $image_foreground,
                            $image_foregroundcolor,
                            $image_transparent,
                            $image_transparentcolor,
                            $font_path);

//  Header ('Content-type: image/png');
  @rename($rim, $rimgpath);
  @unlink($rim);
  chmod($rimgpath, 0644);

  @exec(IMCONVERT." $rimgpath -quality 100 $rjpgimgpath");
  chmod($rjpgimgpath, 0644);



  // create a zipped version
  if (file_exists($imgpath) &&
      file_exists($jpgimgpath) &&
      file_exists($rimgpath) &&
      file_exists($rjpgimgpath))
  {
   $zip = new ZipArchive();

   if ($zip->open($zippath.".zip", ZIPARCHIVE::CREATE)!==TRUE) {
     exit("cannot open <$zf>\n");
   }
   $zip->addFile($imgpath,basename($imgpath));
   $zip->addFile($jpgimgpath,basename($jpgimgpath));
   $zip->addFile($rimgpath,basename($rimgpath));
   $zip->addFile($rjpgimgpath,basename($rjpgimgpath));
   $zip->addFile($readmepath,basename($readmepath));
   $zip->close();
  }


  @unlink($imgpath);
  @unlink($jpgimgpath);
  @unlink($rimgpath);
  @unlink($rjpgimgpath);
  @unlink($readmepath);
  @rmdir($tmpimgpath);




  $ourfilesize = @filesize($zippath.".zip");
  // now check to see if the new file is identical to any existing files
  $dirlist = glob(IMGPATH."/*.zip");
  foreach($dirlist as $file)
  {
    // for every file not equal to the one we just made
    if (basename($zippath.".zip") != basename($file))
    {
      // if it's the same size as another file
      if (@filesize($file) == $ourfilesize)
      {
        // inspect the urls of each zip package
        get_zip_url(basename($file,".zip"), $url1);
        get_zip_url(basename($zippath), $url2);
        if (strcmp($url1,$url2) == 0)
        {
          // no difference found, use this file instead
          @unlink($zippath.".zip");
          $zippath = IMGPATH."/".basename($file,".zip");
          break;
        }
      }
   }
  }


  // set new permissions
  chmod($zippath.".zip", 0644);

  // tell the caller where to look for the new file!
  return IMGPATH."/".basename($zippath);
}


// class for encapsulating available fonts
class cFont
{
  var $path = NULL;
  var $fontlist;
  var $fontqty = 0;

  function cFont($fontspath)
  {
    $excludefonts = const_array(EXCLUDEFONTS);
    $index = 0;
    // check for a user font
    if (!empty($_SESSION[USRFONTNAME]))
    {
      // refresh the time tag
      touch($_SESSION[USRFONTFILE]);
      $this->fontlist[$index++] = array( $_SESSION[USRFONTFILE],
        $_SESSION[USRFONTNAME],
        "",
        "",
        $_SESSION[USRFONTFILE]);
    }


    // hunt down the system fonts
    // make a list of the various systemn fonts available
    foreach(glob($fontspath."/*.ttf") as $fontfile)
    {
        $this->fontlist[$index++] = array($fontfile, basename($fontfile,".ttf"), "", "r", $fontfile);
    }
    // now add some of our own?
    foreach(glob("./fonts/*.ttf") as $fontfile)
    {
      $this->fontlist[$index++] = array($fontfile, substr(str_replace("_"," ",basename($fontfile,".ttf")),0,32),"", "r", $fontfile);
    }
    $this->fontqty = $index;
 }

  function GetFontItemLabel($index)
  {
    return $this->fontlist[$index][1];
  }


  function GetFontQty()
  {
    return $this->fontqty;
  }


  function GetFontPathFromIndex($index)
  {
    return $this->fontlist[$index][4];
  }

  function GetFontIndexFromName($name)
  {
    foreach($this->fontlist as $index => $line)
    {
      if ($line[1] == $name) return $index;
    }
    return -1;
  }

  function GetFontNameFromIndex($index)
  {
    return $this->fontlist[$index][1];
  }

}


// class for creating a gd color object
class cColor
{
  var $red;
  var $green;
  var $blue;
  var $transparent;
  var $allocatedcolor;

  // accepts a "ffffee" type color or "blue", etc..
  function cColor($color_in)
  {
    // default color .. black
    $this->red = 0;
    $this->green = 0;
    $this->blue = 0;
    $this->transparent = FALSE;
    switch ($color_in)
    {
      case "black" :
        break;
      case "red" :
        $this->red = 254;
        break;
      case "green" :
        $this->green = 254;
        break;
      case "blue" :
        $this->blue = 254;
        break;
      case "transparent" :
      case "clear" :
        $this->red = 69;
        $this->green = 69;
        $this->blue = 69;
        $this->transparent = TRUE;
        break;
      case "white" :
        $this->red = 255;
        $this->green = 255;
        $this->blue = 255;
        break;
      default :
        // expect a hex color here
        $safe_color_in = ltrim($color_in,"# ");
        $this->red = hexdec(substr($safe_color_in, 0, 2));
        $this->green = hexdec(substr($safe_color_in, 2, 2));
        $this->blue = hexdec(substr($safe_color_in, 4, 2));
        if ($this->red == 69 && $this->green == 69 && $this->blue == 69)
        {
          $this->red = 68;
          $this->green = 68;
          $this->blue = 68;
        }
        break;
    }

  }

  // allocate a color to an image
  function &Allocate($image)
  {
    $this->allocatedcolor = ImageColorAllocate($image, $this->red, $this->green, $this->blue);
    return $this->allocatedcolor;
  }

  // return the hexval string for this color
  function ColorHexVal()
  {
    $hexval =  dechex($this->red*0x10000 + $this->green*0x100 + $this->blue);
    while (strlen($hexval) < 6)
    {
      $hexval = "0".$hexval;
    }
    return $hexval;
  }

  // return a new color, equal to something between $this and another color object
  // at percent= 0, returns equal to $this, at 100, returns equal to $targetcolor
  function GradientColor($targetcolor, $percent)
  {
    $newred = floor($this->red + ($targetcolor->red - $this->red)*$percent/100);
    $newgreen = floor($this->green + ($targetcolor->green - $this->green)*$percent/100);
    $newblue = floor($this->blue + ($targetcolor->blue - $this->blue)*$percent/100);
    $hexval =  dechex($newred*0x10000 + $newgreen*0x100 + $newblue);
    while (strlen($hexval) < 6)
    {
      $hexval = "0".$hexval;
    }
    return new cColor($hexval);
  }

  // MakeTransparent
  // if the color is transparent, it will set the color as transparent
  // in the image
  function MakeTransparent($image)
  {
    if ($this->transparent)
    {
      ImageColorTransparent($image, $this->allocatedcolor);
    }
  }
}

// function to do arcs with alpha blending (broken currently)
function MyImageArc($image, $xc, $yc, $xdia, $ydia, $astart, $aend, $color)
//function MyImageArc($image, $xc, $yc, $xdia, $ydia, $xstart, $xend, $color)
{

  $xfact = pow($xdia/2,2);
  $yfact = pow($ydia/2,2);

  $xstart = $xc;
  $xend = $xc;
  if ($astart == 0) $xstart = $xc + $xdia/2;
  if ($astart == 180) $xstart = $xc - $xdia/2;
  if ($aend == 0) $xend = $xc + $xdia/2;
  if ($aend == 180) $xend = $xc - $xdia/2;

  if ($xend < $xstart)
  {
    $temp = $xend;
    $xend = $xstart;
    $xstart = $temp;
  }

  for ($xstep = $xstart; $xstep <= $xend; $xstep++)
  {
    $ypos = $yc - sqrt((1-(pow($xstep-$xc,2)/$xfact))*$yfact);
    imagesetpixel($image, $xstep, $ypos, $color);
  }
}


// function for creating a button-shaped polygon with a transparent background
function MakeFilledButtonPolygon($width, $height, &$actwidth, &$actheight, $radius, $color, $bkcolor = "clear")
{
  $w = $width;
  $h = $height;
  $r = $radius;

  // validate the dimensions .. radius trumps height and width
  if ($r > ($h/2))
  {
    $h = $r*2;
  }
  if ($r > ($w/2))
  {
    $w = $r*2;

  }

  $actwidth = $w;
  $actheight = $h;

  // size of the straight side sections
  $w_str = $w - 2*$r;
  $h_str = $h - 2*$r;
  if ($w_str < 0) $w_str = 0;
  if ($h_str < 0) $h_str = 0;

  // create a color object to work with
  $color = new cColor($color);
  $bkcolor = new cColor($bkcolor);
  // make some adjustments for
  // and an image
  $im = ImageCreateTrueColor($w, $h);
  $col = $color->Allocate($im);
  $bk = $bkcolor->Allocate($im);
  imagefilledrectangle($im, 0, 0, $w-1, $h-1,$bk);  // this may be substituted for imagefill
  $bkcolor->MakeTransparent($im);
  ImageFilledRectangle($im, 0, $r, $w_str + 2*$r, $r + $h_str, $col);
  ImageFilledRectangle($im, $r, 0, $r + $w_str, $h_str + 2*$r, $col);
  ImageFilledArc($im, $r, $r, $r*2, $r*2, 180, 270, $col, IMG_ARC_PIE);
  ImageFilledArc($im, $r, $r+$h_str-1, $r*2, $r*2, 90, 180, $col, IMG_ARC_PIE);
  ImageFilledArc($im, $r+$w_str, $r, $r*2, $r*2, 270, 0, $col, IMG_ARC_PIE);
  ImageFilledArc($im, $r+$w_str, $r+$h_str-1, $r*2, $r*2, 0, 90, $col, IMG_ARC_PIE);
  // return the image
  return $im;
}

// function for creating a button-shaped polygon with a transparent background
// if infinite is set TRUE, radius is ignored, and the gradient has no horizontal
// component
function MakeGradientFilledButtonPolygon(
                            $qualityfactor,   // 1 for draft.. 4 for good anti-aliasing
                            $width_temp,
                            $height_temp,
                            &$actwoa,
                            &$acthoa,
                            $radius_temp,
                            $color_inner,
                            $color_outer,
                            $text,
                            $text_height_temp,
                            $text_color = "black",
                            $bkcolor = "white",
                            $image_locate,
                            $image_height_temp,
                            $image_name,
                            $image_foreground,
                            $image_foregroundcolor,
                            $image_transparent,
                            $image_transparentcolor,
                            $font_path = "")
{
  $width = $qualityfactor*$width_temp;
  $height = $qualityfactor*$height_temp;
  $radius = $qualityfactor*$radius_temp;
  $text_height = $qualityfactor*$text_height_temp;
  $image_height = $qualityfactor*$image_height_temp;

  $fontname = $font_path;
  if ($fontname == "") $fontname = FONT;

  $baseimage = MakeFilledButtonPolygon($width, $height, $actwoa, $acthoa, $radius, $color_outer, $bkcolor);
  $ccolor_outer = new cColor($color_outer);
  $ccolor_inner = new cColor($color_inner);
  if ($radius > 0)
  {
    /* construct a gradient fill for this button */
    for ( $step = 0, $newr = $radius, $newh = $height, $neww = $width;
        $newr > 0;
        $step++, $newr--, $newh -= 2, $neww -= 2)
    {
      $newcolor = $ccolor_outer->GradientColor($ccolor_inner, 100*sqrt(($radius-$newr)/$radius));
      $overlayimage = MakeFilledButtonPolygon($neww, $newh, $actw, $acth, $newr, $newcolor->ColorHexVal());
      imagecopymerge($baseimage, $overlayimage, $step, ($acthoa-$acth)/1.5, 0, 0, $actw, $acth,100);
//      imagecopymerge($baseimage, $overlayimage, $step, ($acthoa-$acth)/2, 0, 0, $actw, $acth, 100);
      ImageDestroy($overlayimage);
    }


  }

  $ctcolor = new cColor($text_color);
  $blk = $ctcolor->Allocate($baseimage);


  imagealphablending ($baseimage, TRUE );


  // TEXT OVERLAY

  // find out the size of the text at that font size

  $txtlns = explode("\n", $text);
  $linecount = count($txtlns);
  $i = $linecount;
  foreach ($txtlns as $txtln) {
    $bbox = imagettfbbox ($text_height, 0, $fontname, $txtln);
    $left_text = $bbox[0];
    $right_text = $bbox[2];
    $text_w = $right_text - $left_text;
    $text_x = $actwoa/2.0 - $text_w/2.0;
    if ($left_text < 0) $text_x += abs($left_text);    // add factor for left overhang
    $text_y = $acthoa/2 + $text_height/2*$linecount + $text_height/6*($linecount-1) - $text_height*($i-1) - $text_height/3*($i-1);
    $textlines[] = array('text' => $txtln, 'x' => $text_x, 'y' => $text_y, 'w' => $text_w);
    $widths[] = $text_w;
    $i--;
  }
  unset($txtln);
  $maxwidth = max($widths);

  // IMAGE OVERLAY
  $foreground_custom = strcmp($image_foreground,"custom") == 0;
  $foreground_auto = strcmp($image_foreground,"auto") == 0;
  $foreground_none = strcmp($image_foreground,"none") == 0;
  $transparent_custom = strcmp($image_transparent,"custom") == 0;
  $transparent_auto = strcmp($image_transparent,"auto") == 0;
  $transparent_none = strcmp($image_transparent,"none") == 0;

  $cicolor = new cColor("black");
  if ($foreground_custom)
  {
    $cicolor = new cColor($image_foregroundcolor);
  }
  $citcolor = new cColor("white");
  if ($transparent_custom)
  {
    $citcolor = new cColor($image_transparentcolor);
  }

  GetImagePaths($image_name, $img_path, $thumbpath);

//  $img_size = @getimagesize($img_path);
  $img_size = GlassyImageSize($img_path, $image_name);
  $img_overlay = "invalid";
  switch ($img_size[2])
  {
    case IMAGETYPE_GIF:
      $img_overlay = imagecreatefromgif($img_path);
      break;
    case IMAGETYPE_JPEG:
      $img_overlay = imagecreatefromjpeg($img_path);
      break;
    case IMAGETYPE_PNG:
      $img_overlay = imagecreatefrompng($img_path);
      break;
    case 99:  // double-secret code for SVG
      $destfile = tempnam(IMGPATH,"");
      @rename($destfile,$destfile.".png");
      $destfile = $destfile.".png";
      // we limit svg conversions to TWO colors only because there is no way to disable
      // antialiasing and antialiased images look like hell when we do color conversions
      // at a later step..  so, SVG images are relatively useless at this time
      $execarg = IMCONVERT." -resize 10000"."x$image_height -colors 2 $img_path $destfile";
      @exec($execarg);
      $img_overlay = imagecreatefrompng($destfile);
      @unlink($destfile);
      $img_size[0] = imagesx($img_overlay);
      $img_size[1] = imagesy($img_overlay);
      break;
    default:
      break;
  }

  // image and text!

  $img_newh = 0;
  $img_neww = 0;
  $img_x = 0;
  $img_y = 0;
  if ($img_overlay !== "invalid")
  {
    $img_newh = $image_height;
    $img_neww = $img_newh*$img_size[0]/$img_size[1];
    // palettize the user image if not already
    imagetruecolortopalette ( $img_overlay, FALSE, 256 );
    // find the "foreground" color index, if configured to do so
    if (!$foreground_none)
    {
      $foregroundindex = imagecolorclosest( $img_overlay, $cicolor->red, $cicolor->green, $cicolor->blue );
      // and set it to the text color
      if ($foregroundindex > -1)
      {
        imagecolorset ( $img_overlay, $foregroundindex , $ctcolor->red, $ctcolor->green, $ctcolor->blue );
      }
    }
    // find the transparent "background" color index, if configured to do so
    if (!$transparent_none)
    {
      $backgroundindex = imagecolorclosest($img_overlay, $citcolor->red, $citcolor->green, $citcolor->blue);
      if ($backgroundindex > -1)
      {
        imagecolortransparent ( $img_overlay, $backgroundindex );
      }
    }
    $img_y = $acthoa/2 - $img_newh/2;
    $img_x = $actwoa/2 - $img_neww/2;
  }

  // make adjustments for relative positions
    if (strcmp($image_locate, "left") == 0)
    {
      $img_x -= $maxwidth/2+5;
      foreach ($textlines as &$textline) {
        $textline['x'] += $img_neww/2;
      }
      unset($textline);
    }
    else if (strcmp($image_locate, "right") == 0)
    {
      $img_x += $maxwidth/2+5;
      foreach ($textlines as &$textline) {
        $textline['x'] -= $img_neww/2;
      }
      unset($textline);
    }

  if ($img_overlay !== "invalid" && strcmp($image_locate, "none") != 0)
  {
    imagecopyresampled ($baseimage, $img_overlay, $img_x, $img_y, 0, 0, $img_neww, $img_newh, $img_size[0], $img_size[1]);
  }

  @imagedestroy($img_overlay);

  foreach ($textlines as $textline) {
    ImageTTFText ($baseimage, $text_height, 0, $textline['x'], $textline['y'], $blk, $fontname, $textline['text']);
  }
  unset($textline);


  if ($radius > 0)
  {
    $radiusstart = floor($radius/1.2);
    $radiuslimit = floor($radius/2.2);
    $outersteps = $radiusstart - $radiuslimit;
    $blendfactor = 100;
    if ($outersteps)
    {
      $blendfactor = 100* sqrt(1/$outersteps);
    }
    // construct a hilight for this button
//    $chilitecolor = imagecolorresolvealpha ( $baseimage, 255, 255, 255, 80);

    for ( $newr = $radiusstart; $newr >= $radiuslimit; $newr--)
    {
      $chilitecolor = imagecolorresolvealpha($baseimage, 255,255,255, 100-floor(100*($newr-$radiuslimit)/($radiusstart-$radiuslimit)));
      MyImageArc($baseimage, $radius-1, $radius, 2*$radiusstart, 2*$newr, 180, 270, $chilitecolor);
      if ($actwoa > 2*$radius)
      {
        ImageLine($baseimage, $radius, $radius-$newr, $actwoa - $radius - 1, $radius-$newr, $chilitecolor);
      }
      MyImageArc($baseimage, $actwoa-$radius, $radius, 2*$radiusstart, 2*$newr, 270, 0, $chilitecolor);
    }
  }


  $imagetemp = tempnam(IMGPATH, "");
  ImagePNG($baseimage,$imagetemp);
  imagedestroy($baseimage);
  // experimental, fill the corners with transparent color
  if (strcmp(strtoupper($bkcolor),"CLEAR") == 0)
  {
    $execarg = IMMOGRIFY." -transparent #454545 $imagetemp";
    @exec($execarg);
  }

  if ($qualityfactor > 1)
  {
    $neww = $actwoa/$qualityfactor;
    $newh = $acthoa/$qualityfactor;
    $execarg = IMMOGRIFY." -resize ".$neww."x".$newh." $imagetemp";
    @exec($execarg);
  }
  return $imagetemp;
}


// given an image file name (ex. picture.jpg) this returns
// relative path to the full size image, and the thumbnail
function GetImagePaths($image_name, &$fullpath, &$thumbpath)
{
  $thumbpath = false;
  if (!empty($image_name)) {
    if (isset($_SESSION[USRIMGNAME]) && strcmp($_SESSION[USRIMGNAME],$image_name) == 0)
    {
      // this is the user's custom image
      $basethumbpath = explode('.',basename($_SESSION[USRIMGFILE]));
      $thumbpath = USRIMGTHUMBPATH."/".$basethumbpath[0].".png";
      $fullpath = $_SESSION[USRIMGFILE];
      touch($fullpath);
    }
    else
    {
      $basethumbpath = explode('.',basename($image_name));
      $thumbpath = STOCKIMGTHUMBPATH."/".$basethumbpath[0].".png";
      $fullpath = STOCKIMGPATH."/".$image_name;
      if (!file_exists($fullpath)) $thumbpath = false;
    }
  }
  if (!$thumbpath)
  {
    $thumbpath = STOCKIMGTHUMBPATH."/null.png";
    $fullpath = "";
  }
}

function EnsureThumbnail($name,$size,$thumbdim)
{
  $image = "invalid";
  GetImagePaths(basename($name), $fullpath, $thumbpath);
  if (!file_exists($thumbpath) || (@filectime($name)>@filectime($thumbpath)))
  {
    $execarg = IMCONVERT." -resize $thumbdim"."x$thumbdim $fullpath $thumbpath";
    @exec($execarg);
  }
}

// wrapper for imagesize that also identifies SVG files
function &GlassyImageSize($imagepath, $filename)
{
  $size = array();
  $filebits = explode(".",basename($filename));
  if (isset($filebits[1]) && strcmp(strtolower($filebits[1]),"svg") == 0)
  {
    $size[0] = 1;
    $size[1] = 1;
    $size[2] = 99;
  }
  else
  {
    $size = @getimagesize($imagepath);
  }
  return $size;
}





?>
