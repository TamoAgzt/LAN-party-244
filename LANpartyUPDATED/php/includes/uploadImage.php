<?php
function ImageChecker(){
        // Get file info 
        $fileName = basename($_FILES["file"]["name"]); 
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION); 
         
        // Allow certain file formats 
        $allowTypes = array('png'); 
        if(in_array($fileType, $allowTypes)){ 
            $image = $_FILES["file"]['tmp_name']; 
            $imgContent = addslashes(file_get_contents($image)); 
         
            return $imgContent;
        }
    }