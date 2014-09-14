<?php
// help.php
//
// glassy button help popup generator
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


$helpbodystart = "
<!DOCTYPE html>
<head>
<title>Glassy Button Help</title>
<link rel=stylesheet href='glassy.css' type='text/css' />
</head>
<body>
<table><tr><td>
";

$helpbodyend = "
</td></tr></table>
</body>
</html>
";

$helpitem = isset($_REQUEST['item']) ? $_REQUEST['item']  : "";

$colorhelp = "
  <p class='helpitem'>Define the color using the web-style hexadecimal format for
  specifying the red, green and blue components. For example, a pure red
  color is <b>FF0000</b>. Do not use the # character.</p>
  <p class='helpitem'>Click the color chooser icon to bring up a convenient color selection tool.</p>
  <p class='helpitem'>The form also recognizes a few colors in text form: black, white, red, green
  and blue. To use one of these colors just type the name of the color in the edit
  box.</p>
";

$helpitems['fonts'] = "
  <p class='helpitem'>Select a TrueType font from your computer to upload to our server. The
  font you select will only be kept for about an hour and will not be available
  for anyone but you to use.</p>
  <p class='helpitem'>Please be sure that you have the right to use whatever font you upload.</p>
  <p class='helpitem'>The glassy buttons web site is not responsible for ensuring that you have
  the right to use your uploaded font.</p>
";

$helpitems['images'] = "
  <p class='helpitem'>Select an image (jpg, gif, png, svg) from your computer to upload to our server.
  The image you select will only be kept for about an hour and will not be available
  for anyone but you to use.</p>
  <p class='helpitem'>For best results, use images with the following characteristics:
  <ul><li class='helpitem'>Use a picture that is several times larger than the final image
  on the button.
  <li class='helpitem'>Avoid using <em>true color</em> images. Images with a small number of colors
  will work best (for example, 256 colors or fewer).</li>
  <li class='helpitem'>Don't use anti-aliasing to generate your image. The button generator
  will automatically anti-alias your image when it generates the final button,
  provided the original image is sufficiently large.</li>
  <li class='helpitem'>Minimal SVG support. Because of bitmap rendering issues, all SVG images
  will be converted to <em>two</em> colors when rendering. Use the slowest rendering
  modes for best results with SVG images.</li>
  </ul></p>
  <p class='helpitem'>Please be sure that you have the right to use whatever image you upload.</p>
  <p class='helpitem'>The glassy buttons web site is not responsible for ensuring that you have
  the right to use your uploaded image.</p>
";

$helpitems['text'] = "
  <p class='helpitem'>Enter the text to appear on your button. If entering multiple lines of text,
  be sure to make the button size large enough. Do NOT press 'Enter' after the
  end-of-text line.</p>
";

$helpitems['radius'] = "
  <p class='helpitem'>Enter the corner radius of the button in pixels. Perfectly round buttons
  can be made by entering a corner radius that is more than half the button
  height and width values.</p>
";

$helpitems['width'] = "
  <p class='helpitem'>Enter the overall width of your button in pixels. If the value you enter
  is less than twice the corner radius then the radius takes priority and
  the top of your button will be perfectly round.</p>
";

$helpitems['height'] = "
  <p class='helpitem'>Enter the overall height of your button in pixels. If the value you enter
  is less than twice the corner radius then the radius takes priority and
  the ends of your button will be perfectly round.</p>
";

$helpitems['textheight'] = "
  <p class='helpitem'>Enter the overall height of your button text in pixels. If the size is
  too large to fit on the button then it will not be shrunk to fit.</p>
";

$helpitems['textcolor'] = "
  <p class='helpitem'>Enter the color of your button text.</p>
".$colorhelp;

$helpitems['color'] = "
  <p class='helpitem'>Enter the primary color for the button. This color will appear at the
  center of the button in its normal (not rollover) state.</p>
".$colorhelp;

$helpitems['backcolor'] = "
  <p class='helpitem'>Enter the color of the web page in which the button will appear. For
  example: If you will use the button on a white web page, then enter
  <em>white</em> here. Or to specify a <em>transparent</em> background
  (suitable for placing images over an image background) enter <em>clear</em>
  here. Otherwise enter the hex color code, or use the color picker.</p>
".$colorhelp;

$helpitems['rtextcolor'] = "
  <p class='helpitem'>Enter the text color for the button's <em>rollover</em> state.</p>
".$colorhelp;

$helpitems['edgecolor'] = "
  <p class='helpitem'>Enter the color of the outer edge of the button. If this color is the
  same as the primary color then the button will have a flat appearance. If
  the color is different, then the button will have a color that varies
  smoothly from the center to the outer edge. Use this difference to give
  the appearance of a refractive (glassy) button body.</p>
".$colorhelp;

$helpitems['rcolor'] = "
  <p class='helpitem'>Enter the primary color for the button when it is in the <em>rollover</em>
  state.</p>
".$colorhelp;

$helpitems['redgecolor'] = "
  <p class='helpitem'>Enter the color of the outer edge of the button when it is in the <em>
  rollover</em> state.</p>
".$colorhelp;

$helpitems['image'] = "
  <p class='helpitem'>You can specify any image to appear on the face of your button. Click
  the icon next to the edit box to choose one of the stock images using the
  Glassy Buttons image picker. If you have uploaded your own image then a
  thumbnail of your custom image will appear in the image picker too.</p>
";

$helpitems['imageheight'] = "
  <p class='helpitem'>If you are using an image on your button, select the desired height of
  your image in pixels. For best results the source image should be several
  times larger than the final rendered height.</p>
";

$helpitems['imagelocate'] = "
  <p class='helpitem'>You can use this option to decide whether and how to place an image
  on the face of your button. There are several options:
  <ul><li class='helpitem'><b>none</b> no image will be added to the button.</li>
  <li class='helpitem'><b>left</b> the image will be placed to the left of your button text.</li>
  <li class='helpitem'><b>right</b> the image will be placed to the right of your button text.</li>
  <li class='helpitem'><b>background</b> the image will be centered and placed behind the
  button text.</li></ul></p>
";

$helpitems['imageforeground'] = "
  <p class='helpitem'>If you have selected an image for your button, you can also specify
  a color <em>present in the image</em> that will be changed to match
  the text color for the button normal and rollover states. This is specified
  as one of several options:
  <ul><li class='helpitem'><b>auto</b> the color most closely matching black will be forced
  to match the text color.</li>
  <li class='helpitem'><b>none</b> no color will be forced to match the text color.</li>
  <li class='helpitem'><b>custom</b> the color most closely matching a color you define will
  be forced to match the text color.</li></ul></p>
";

$helpitems['imageforegroundcolor'] = "
  <p class='helpitem'>Enter a custom color in your image that will be changed to match the
  button text color. Note that <em>foreground type</em> must be set to
  <b>custom</b> for this field to have any effect.</p>
".$colorhelp;

$helpitems['imagetransparent'] = "
  <p class='helpitem'>If you have selected an image for your button, you can also specify
  a color <em>present in the image</em> that will be made transparent
  when the image is overlayed on the button top. This is specified
  as one of several options:
  <ul><li class='helpitem'><b>auto</b> the color most closely matching white will become
  transparent in the final image.</li>
  <li class='helpitem'><b>none</b> no color will become transparent.</li>
  <li class='helpitem'><b>custom</b> the color most closely matching a color you define will
  become transparent in the final image.</li></ul></p>
";

$helpitems['imagetransparentcolor'] = "
  <p class='helpitem'>Enter a custom color in your image that will become transparent before
  the image is overlayed on the button top. Note that <em>transparency</em>
  must be set to <b>custom</b> for this field to have any effect.</p>
".$colorhelp;


?>
