<?php
// config.php
//
// This file was created in 2011 by R Virtue for the Buttonmill project
//
// To the extent possible under law, Eric Dennison has waived all copyright 
// and related or neighboring rights to Buttonmill. This work is published 
// from: United States. See https://launchpad.net/buttonmill for more
// information or to participate in this project.
//
// You may use this code for any purpose, commercial or personal without 
// permission and without attribution to the original author(s)

//
// The BASEURL is detected from the host server name - e.g., www.mydomain.com or localhost
// The following ImageMagick executable file pathnames can then be assigned based on that
// BASEURL auto-detection and SHOULD be modified as required for your localhost development
// server (if any) and online production servers.  Other config items (e.g. limits) may also
// be moved to this section if their differentiation per server is necessary or desired.
//
define('BASEURL',(!empty($_SERVER['HTTP_HOST'])) ? strtolower($_SERVER['HTTP_HOST']) : ((!empty($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : getenv('SERVER_NAME')));
if (BASEURL == 'localhost' || BASEURL == '127.0.0.1') {
  define('IMCONVERT',"C:\Inetapps\IMagick\convert.exe"); // full pathname for localhost server
  define('IMMOGRIFY',"C:\Inetapps\IMagick\mogrify.exe"); // full pathname for localhost server
} else if (BASEURL == 'something.else.com') {
  define('IMCONVERT',"/usr/local/bin/convert"); // full pathname for specified online server
  define('IMMOGRIFY',"/usr/local/bin/mogrify"); // full pathname for specified online server
} else {
  define('IMCONVERT',"/usr/bin/convert"); // full pathname for default online server
  define('IMMOGRIFY',"/usr/bin/mogrify"); // full pathname for default online server
}
//
// The following limitation and specification items may be modified according to preferences.
//
define('RECENTPERPAGE',10); // number of recent button images to be shown per page
define('MINTEMPFILEQTY',100); // trigger level for auto-deletion of files created in IMGPATH folder
define('THUMBSIZE',16); // size (in pixels) of thumbnail images for ImageMagick convert actions
define('MAXUSRIMGSIZE',100000); // maximum size (in bytes) of user image uploads
define('MAXUSRFONTSIZE',500000); // maximum size (in bytes) of user font uploads
//
// The following path items should seldom need modification unless folder/file name defaults are changed.
// If they are changed, be sure to delete any existing DEFAULTZIP file. It is re-created automatically.
//
define('HOMEPAGE',"glassy.php"); // base filename of Glassy Buttons home page (change to index.php for autoloading) 
define('RECENTPAGE',"recent.php"); // base filename of Glassy Buttons recent buttons page (assumes home folder)
define('READMENAME',"readme.txt"); // name of the readme text file to include in ZIP download package
define('DEFAULTZIP',"defaultglassy.zip"); // name of the default buttons ZIP file (auto-created in IMGPATH folder)
define('IMGPATH',"./temp"); // path (relative or absolute) for creating ZIPped packages of new glassy button images
define('STOCKIMGPATH',"./stockimg"); // path (relative or absolute) for publicly available button image files
define('STOCKIMGTHUMBPATH',STOCKIMGPATH."/thumbs"); // path (relative or absolute) for button image thumbs
define('USRIMGPATH',"./tempimg"); // path (relative or absolute) for user uploading of button image files
define('USRIMGTHUMBPATH',USRIMGPATH."/thumbs"); // path (relative or absolute) for uploaded of button image thumbs
define('FONTPATH',"/usr/share/fonts/truetype/dejavu"); // path (relative or absolute) for publicly available fonts
define('USRFONTPATH',"./fonts/userfonts"); // path (relative or absolute) for user uploading of TrueType fonts
define('LOGGINGPATH',"./logs"); // path (relative or absolute) for Glassy Buttons logs
//
// Items defined below are used internally and should NOT be changed unless they are fully understood.
//
// file upload
define('USERFONT',"userfont");
define('USERIMG',"userimage");
// interface text
define('BUTTON_TEXT',"button_text");
define('COLOR',"color");
define('GRCOLOR',"grcolor");
define('WIDTH',"width");
define('HEIGHT',"height");
define('RADIUS',"radius");
define('THEIGHT',"theight");
define('TCOLOR',"tcolor");
define('BKCOLOR',"bkcolor");
define('STYLE',"style");
define('FNAME',"fname");
define('QUALITY',"quality");
define('USECACHE',"usecache");
// define tags in the readme.txt
define('TAGBT',"button text");
define('TAGPC',"primary color");
define('TAGGC',"gradient color");
define('TAGW',"width (pixels)");
define('TAGH',"height (pixels)");
define('TAGCR',"corner radius (pixels)");
define('TAGTH',"text height (points)");
define('TAGTC',"text color");
define('TAGBC',"background color");
define('TAGFN',"font name");
define('TAGRPC',"rollover primary color");
define('TAGRGC',"rollover gradient color");
define('TAGRTC',"rollover text color");
define('TAGQ',"quality");
define('TAGIL',"image location");
define('TAGIH',"image height (pixels)");
define('TAGIN',"image name");
define('TAGIF',"image foreground color determination");
define('TAGIFC',"image foreground color");
define('TAGIT',"image transparent color determination");
define('TAGITC',"image transparent color");
define('TAGURL',"url");
// image attributes
define('IMAGELOCATE',"imglocate");  // none, left, right, background
define('IMAGEHEIGHT',"imgheight");  // pixels height in final image
define('IMAGENAME',"imgname");      // name.ext of image
define('IMAGEFORE',"imgfore");      // auto, none, custom (image rollover color)
define('IMAGEFORECOLOR',"imgforecolor");  // color code for rollover color on image
define('IMAGETRAN',"imgtran");      // auto, none, custom (image transparency color)
define('IMAGETRANCOLOR',"imgtrancolor"); // color code for transparent color on image
define('USRIMGFILE',"userimgfile");
define('USRIMGNAME',"userimgname");
// rollover attributes
define('RCOLOR',"rcolor");
define('RGRCOLOR',"rgrcolor");
define('RTCOLOR',"rtcolor");
// font attributes
define('USRFONTFILE',"userfontfile");
define('USRFONTNAME',"userfontname");
define('EXCLUDEFONTS',"StarMath,StarBats");

?>
