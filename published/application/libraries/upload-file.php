<?php
//$uploaddir = './uploads/'; 
$uploaddir = "../".$_REQUEST['directory'];
$new_fname = uniqid().'_'.basename($_FILES['uploadfile']['name']); 
$file = $uploaddir.$new_fname; 

//Create folder name based on bo_table
@mkdir($uploaddir, 0707, true);
@chmod($uploaddir, 0707, true);
 
if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file)) { 
  echo "success|||".$_REQUEST['directory'].$new_fname; 
} else {
	echo "error";
}

?>