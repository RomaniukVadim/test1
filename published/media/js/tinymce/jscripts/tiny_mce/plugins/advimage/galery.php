<?php
session_start(); 
error_reporting(E_PARSE); 
/*
   tiny-image-manager
   Image manager for TinyMCE
   Mithat Konar, mkonar[AT]dogus.edu.tr, 2009-03-13
   Derived from
   ROBIT BT plugins for tinyMCE editor by  Tibor Fogler   foglert@robitbt.hu | www.robitbt.hu, 2008.04.20
   licence: GNU/GPL
   Requires: PHP4 and gd.lib extension

   The above attributions must remain intact in any modification or redistribution.

   Changes from Tibor Fogler's version: 
   * Cleaner HTML output
   * Improved appearance
   * Security improvements (from http://github.com/pkoch/simple_image_browser-uploader_for_tinymce/tree/master)
      TODO: determine if this security improvement creates write problems on some systems.
   * Adapted to work in in a CMS context using a relative location (see config section)

   Known limitations/issues
      * Only works with three-character extensions: jpg, png, and gif (upper and lower case) ... in other words, foo_bar.JPG is ok but foo_bar.JPEG is not.
      * Only works with files of the form <filename>.<ext> ... in other words, "foo_bar.png" is ok but "foo.bar.png" is not.
    
   Install: 
   1. Edit this file's config sections below.
   2. Copy this file into tiny_mce/plugins/advimage folder.
   3. Replace image.htm in tiny_mce/plugins/advimage folder.
   4. Copy audio.jpg, video.jpg into tiny_mce/plugins/advimage folder.

*/

global $GDok,$IMGFOLDER,$IMGURL,$AUDIOICON,$VIDEOICON;
$GDok = TRUE;

 
// ------------ Configurations for CMS integration ------------ //
//$IMGFOLDER = '../../../../../../pages/uploads/images';	// relative path to image folder from the point of view of this file
//$IMGURL = 'pages/uploads/images';						// path  to image folder from the point of view of CMS root ('prefix' passed to CMS) 


$IMGFOLDER = $_SESSION['tinymce']['imgfolder']; // relative path to image folder from the point of view of this file
$IMGURL = $_SESSION['tinymce']['imgurl'];  
 
// ------------------------------------------------------------- //

// ------------ Other configuration -------------------- //
// language setting   en
$PAGETITLE = 'tiny image manager';
$LARGEIMG = 'View';
$DELETEIMG = 'Delete';
$INSERTIMG = 'Select';
$UPLOADIMG = 'Upload';
$HELPSTR = '&nbsp;';
//$HELPSTR = '...click thumbnail to select...';
$BROWSEHEADER = 'Available images';
$UPLOADHEADER = 'Upload new image';
$UPLOADFAIL = 'Upload failed!';
$FILEEXISTS = 'There is already a file with the name:';
$SELECTFILE = 'You must first select a file to upload.';
$CONFIRMDELETE = 'Really delete';
$NOTANIMAGE = 'The file is not an image:';
// probably unneeded, but it's already in the code...
$VIDEOICON = 'video.jpg';
$AUDIOICON = 'audio.jpg';
// ------------------------------------------------- //

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>
<?php print "$PAGETITLE" ?>
</title>
<style type="text/css">
/*<![CDATA[*/
<!--
  body {padding:5px 20px;background-color: white;}
  h1 {font-family: verdana,arial,helvetica,geneva,sans-serif; font-size:13px;color:#2B6FB6;
      padding:40px 0 0px 0; border-bottom:#000 1px solid;}
  h1.first {padding:0 0 0px 0;}
  a {color: #33a; text-decoration: none; font-weight: bold}
  a:hover {text-decoration: underline}
  .caution {color:#900;}
  #imgbrowse{width:420px;}
  #imgpload{width:420px;}
  #imgalt {border:1px solid #ddd; color:#00f; margin:2px 0; padding:1px; width:415px; font:11px Sans-serif;}
  #footer {margin-top:20px;width:420px;text-align:center;color:#aaa; font-family: verdana,arial,helvetica,geneva,sans-serif;font-size:9px;}
-->
/*]]>*/
</style>
</head>

<body>
<?php

function make_thumb($img_name,$filename,$new_w,$new_h) {
  $fsize = filesize($img_name);
  if (!$fsize) {
    return;
  }
  if ($fsize > 100000) {
    return;
  }
///  TODO: integrate upper and lower case testing
  //get image extension.
  $ext=strToLower(getExtension($img_name));
  //creates the new image using the appropriate function from gd library
  if(!strcmp("jpg",$ext) || !strcmp("jpeg",$ext))
    $src_img=ImageCreateFromJPEG($img_name);
  if(!strcmp("gif",$ext))
    $src_img=ImageCreateFromGIF($img_name);
  if(!strcmp("png",$ext))
    $src_img=ImageCreateFromPng($img_name);
  if (isset($src_img)) {
    if ($src_img != '') {
      //gets the dimmensions of the image
      $old_x=imageSX($src_img);
      $old_y=imageSY($src_img);
      if (($old_x > new_w) | ($old_y > new_h)) {
	      $ratio1=$old_x/$new_w;
	      $ratio2=$old_y/$new_h;
	      if($ratio1>$ratio2) {
	        $thumb_w=$new_w;
	        $thumb_h=$old_y/$ratio1;
	      } else {
	        $thumb_h=$new_h;
	        $thumb_w=$old_x/$ratio2;
	      }
	      // we create a new image with the new dimmensions
	      if (($old_x < 1000) and ($old_y < 1000)) {
	         $dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);
	         // resize the big image to the new created one
	         imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
	         // output the created image to the file. Now we will have the thumbnail into the
			 // file named by $filename
	         if(!strcmp("png",$ext))
	           imagepng($dst_img,$filename);
	         else if(!strcmp("gif",$ext))
	           imagegif($dst_img,$filename);
	         else
	           imagejpeg($dst_img,$filename);
	      }
	  }
      //destroys source and destination images.
      imagedestroy($dst_img);
      imagedestroy($src_img);
    }
  }
}

// get size of image
function getimgsize($filename,&$x,&$y) {
  $x = -1;
  $y = -1;
  $result = FALSE;
  $imginfo = GetImageSize($filename);
  if (count($imginfo) > 2) {
    $x = $imginfo[0];
    $y = $imginfo[1];
    $result = TRUE;
  }
  return $result;
}

// read directory list,
// echo table with thumbmail images
// (generate thumbmail if not exists)
// onclick=parent.imgselect(i);
function maketable($dirname) {
// has mfk changes for using local URLs
// TODO: only works for three char extensions, e.g. .JPEG
  global $GDok,$IMGFOLDER,$IMGURL,$VIDEOICON,$AUDIOICON;
  $handle = opendir($dirname);
  $file_lista[]=array();
  while ($file = readdir($handle)) {
     if (($file != '.') && ($file != '..')) {
	$file_lista[]=$file;
     }
  };
  closedir($handle);
  $kepdb= -1;
  $coldb = 0;
  print '<table border="0" cellspacing="0" cellpadding="0"><tr>'."\n";
  if (count($file_lista) > 0) {
  	for ( $a=0; $a<sizeof($file_lista); $a++) {
          $fnev = $dirname."/".$file_lista[$a];
          if (! is_dir($fnev)) {
          if (((substr($fnev,-4)==".jpg") || (substr($fnev,-4)==".gif") ||
	            (substr($fnev,-4)==".mpg") || (substr($fnev,-4)==".MPG") ||
	            (substr($fnev,-4)==".mpeg") || (substr($fnev,-4)==".MPEG") ||
	            (substr($fnev,-4)==".avi") || (substr($fnev,-4)==".AVI") ||
	            (substr($fnev,-4)==".wmv") || (substr($fnev,-4)==".WMV") ||
	            (substr($fnev,-4)==".mov") || (substr($fnev,-4)==".MOV") ||
	            (substr($fnev,-4)==".png") || (substr($fnev,-4)==".PNG") ||
	            (substr($fnev,-4)==".mp3") || (substr($fnev,-4)==".MP3") ||
	            (substr($fnev,-4)==".JPG") || (substr($fnev,-4)==".GIF")) &&
                ($file_lista[$a] != 'index.gif') &&
                ($file_lista[$a] != 'index.gif') &&
                (!strpos($file_lista[$a],'_t.'))) {
                $kepdb++;
                $coldb++;
         	    $picname=substr($file_lista[$a], 0, -4);
                $belyeg = $dirname.'/'.$picname.'_t'.substr($file_lista[$a],-4);
                //$belyegurl = $IMGURL.'/'.rawurlencode($picname).'_t'.substr($file_lista[$a],-4);
                $belyegurl = $IMGFOLDER.'/'.rawurlencode($picname).'_t'.substr($file_lista[$a],-4);
	            if ((substr($fnev,-4)==".mpg") || (substr($fnev,-4)==".MPG") ||
	                (substr($fnev,-4)==".avi") || (substr($fnev,-4)==".AVI") ||
	                (substr($fnev,-4)==".wmv") || (substr($fnev,-4)==".WMV") ||
	                (substr($fnev,-4)==".mov") || (substr($fnev,-4)==".MOV")) {
                   $belyeg = './'.$VIDEOICON;
                   $belyegurl = './'.$VIDEOICON;
                }
                if ((substr($fnev,-4)==".mp3") || (substr($fnev,-4)==".MP3")) {
                     $belyeg = './'.$AUDIOICON;
                     $belyegurl = './'.$AUDIOICON;
                }
                // ha m�g nincsb�lyegk�p akkor l�trehozni
                if (!file_exists($belyeg)) {
                   if ($GDok) make_thumb($fnev,$belyeg,100,100);
	            };
				if (! file_exists($belyeg)) {
                   $belyeg = $dirname.'/'.$file_lista[$a];
				   //$belyegurl = $IMGURL.'/'.rawurlencode($file_lista[$a]);
				   $belyegurl = $IMGFOLDER.'/'.rawurlencode($file_lista[$a]);
                }
                $x = -1;
                $y = -1;
                getimgsize($belyeg,$x,$y);
                print '<td width="110" height="110" onclick="parent.selectimg('.$kepdb.')" '.
                      'align="center" valign="center" style="padding:5px; cursor:pointer;">'."\n";
                if ($x < 0) {
				  print '<img src="'.$belyegurl.'" alt="'.$file_lista[$a].'" width="100" height="100" id="'.$kepdb.'" />';
				} else if ($x > $y) {
				  print '<img src="'.$belyegurl.'" alt="'.$file_lista[$a].'" width="100" id="'.$kepdb.'" />';
				} else {
				  print '<img src="'.$belyegurl.'" alt="'.$file_lista[$a].'" height="100" id="'.$kepdb.'" />';
				}
                print '</td>'."\n";
				if ($coldb == 3) {
				  print '</tr><tr>'."\n";
				  $coldb = 0;
				}
  	      }
       }
     }
   }
   print '</tr></table>'."\n";
} //function maketable

// This function returns the extension of the file passed in $str.
// TODO: only works for three-letter extensions
function getExtension($str) {
  $i = strrpos($str,".");
  if (!$i) { return ""; }
  $l = strlen($str) - $i;
  $ext = substr($str,$i+1,$l);
  return $ext;
}

// Determines whether  filename is an image (based on its extension)
function isImage($filename) {
	$ext = getExtension($filename);
	$ext = strtolower($ext);
	$rv = !strcmp("jpg",$ext) || !strcmp("jpeg",$ext) || !strcmp("png",$ext)  || !strcmp("gif",$ext);
	return $rv;
}

// ----------------
// main program
// ----------------
if (!extension_loaded('gd')) {
   if (!dl('gd.so')) {
       $GDok = FALSE;
   }
}
if (isset($_GET['dirname'])) $dirname = $_GET['dirname']; else $dirname = $IMGFOLDER;
if (isset($_GET['act'])) $act = $_GET['act']; else $act = $list;
if (isset($_POST['act'])) $act = $_POST['act'];
if (isset($_POST['fname'])) $fname = $_POST['fname']; else $fname = '';
if ($act == 'upload') {
  // do file upload
    $name = $_FILES['upload']['name'];
	
	if (!empty($name) && !isImage($name)) {
		$fileExt = getExtension($name);
		echo "<script type='text/javascript'>alert('$UPLOADFAIL \\n\\n$NOTANIMAGE $name');</script>";
	}
    else if (file_exists("$dirname/$name"))  {
			 // echo "<p>"._EXIST." $dirname/$name </p>";
			 if (empty($name))
				echo "<script type='text/javascript'>alert('$UPLOADFAIL \\n\\n$SELECTFILE');</script>";
			 else
				echo "<script type='text/javascript'>alert('$UPLOADFAIL \\n\\n$FILEEXISTS $name');</script>";
	}
	else {
        if (!is_dir($dirname)) {
			mkdir($dirname,0644);
		};
		if (is_uploaded_file($_FILES['upload']['tmp_name'])) {
           if (move_uploaded_file($_FILES['upload']['tmp_name'],"$dirname/$name" ))
              chmod("$dirname/$name",0644);
        };
        if (!file_exists("$dirname/$name")) {
          echo "<p>"._UPLOADERROR." $dirname/$name</p>\n";
        };
       	$picname=substr($name, 0, -4);
    };
}
if ($act == 'delete') {
  // delete  main file
  $file_name = $_POST['fname'];
  unlink($IMGFOLDER.'/'.$file_name);
  // delete  thumbnail: insert '_t' before extension, then unlink
  $thumb_ext = getExtension($file_name);
  // TODO: this should really be a "do once" and "start from right" operation
  $thumb_name = str_replace('.'.$thumb_ext, '_t.'.$thumb_ext, $file_name);
  unlink($IMGFOLDER.'/'.$thumb_name);
}
if ($act == 'list') {
  // generate table
  maketable($dirname);
  print '</body></html>';
  exit();
}
// draw image manager window
print "<form name=\"imgupload\" method=\"post\" action=\"./galery.php?dirname=$dirname\" enctype=\"multipart/form-data\">\n";
print "<div id=\"imgbrowse\">"; 
print "<h1 class=\"first\">$BROWSEHEADER</h1>\n";
print "<div>\n";
print '<iframe id="frm1" name="frm1" src="./galery.php?act=list" scrolling="auto" width="415px" frameborder="1" ></iframe>'."\n";
//print "<form name=\"imgupload\" method=\"post\" action=\"./galery.php?dirname=$dirname\" enctype=\"multipart/form-data\">\n";
print "<p id=\"imgalt\">$HELPSTR</p>\n";
print "<button class=\"btn\" type=\"button\" onclick=\"insertimg();\">$INSERTIMG</button>\n";
print "<button class=\"btn\" type=\"button\" onclick=\"viewimg();\">$LARGEIMG</button>\n";
print "<button class=\"btn caution\" type=\"button\" onclick=\"deleteimg('$CONFIRMDELETE');\">$DELETEIMG</button>\n";
print "</div>"; 
print "</div>";
print "<div id=\"imgpload\">"; 
print "<h1>$UPLOADHEADER</h1>\n";
print "<div>";
print "<input class=\"btn\" type=\"hidden\" name=\"act\" value=\"upload\">";
print "<input class=\"btn\" type=\"file\" size=40 name=\"upload\">";
print "<input class=\"btn\" type=\"hidden\" name=\"fname\" value=\"\">\n";
print "<button class=\"btn\" type=\"button\" onclick=\"uploadimg();\">$UPLOADIMG</button>\n";
print "</div>";
print "</div>"; 
print "</form>\n";
?>

<script type="text/javascript" language="JavaScript">
function selectimg(i) {
  doc = frames['frm1'].document;
  if (selected >= 0) {
    img = doc.getElementById(selected);
    img.parentNode.style.background = 'white';
  }
  selected = i;
  img = doc.getElementById(i);
  img.parentNode.style.background = 'blue';
  document.getElementById('imgalt').innerHTML = img.alt;
}
function deleteimg(delMsg) {
  if (selected >= 0) {
    doc = frames['frm1'].document;
	img = doc.getElementById(selected);
	if (confirm(delMsg + " '" + img.alt + "'?")) {
		document.forms.imgupload.act.value='delete';
		document.forms.imgupload.fname.value=img.alt;
		document.forms.imgupload.submit();
	}
  }
}
function uploadimg() {
  document.forms.imgupload.act.value='upload';
  document.forms.imgupload.submit();
}
function insertimg() {
// has mfk changes
  if (selected >= 0) {
    doc = frames['frm1'].document;
    img = doc.getElementById(selected);
    opener.document.forms[0].src.value = '<?php echo $IMGURL ?>/'+img.alt;
//    opener.document.forms[0].src.value = '<?php echo $IMGFOLDER ?>/'+img.alt;
    window.close();
  }
}
function viewimg() {
// has mfk changes
  if (selected >= 0) {
    var pWidth = 600;
	var pHeight = 450;
    var pTop = 20;  
	var pLeft = 20;
    doc = frames['frm1'].document;
    img = doc.getElementById(selected);
//    fnev = '<?php echo $IMGURL ?>/'+img.alt;
    fnev = '<?php echo $IMGFOLDER ?>/'+img.alt;
    window.open(fnev,'','left=' +pLeft+ ',top=' +pTop+ ',width=' +pWidth+ ',height=' +pHeight+ ',resizable=yes,scrollbars=yes');
  }
}
// js main program
selected = -1;
</script>
<div id="footer">
<p>tiny-image-manager v0.8</p>
</div>
</body>
</html>
