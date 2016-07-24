<?php
/**
 * Created by PhpStorm.
 * User: antony
 * Date: 7/3/16
 * Time: 7:25 PM
 */
namespace Judge\Services;

class UploadImageService
{
    public function __construct()
    {
    }

    public function uploadImage($dir="images/")
    {
        // Check if image file is a actual image or fake image
        if ( isset($_POST["submit"]) && isset($_FILES['image']) && !empty($_FILES['image']['name']) ) {

            $errorMessage = "";
            $target_dir = $dir;
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            $uploadOk = 1;
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

            $check = getimagesize($_FILES["image"]["tmp_name"]);

            if ($check !== false) {
//                $errorMessage = "File " . basename($_FILES["image"]["name"]) . " is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                $errorMessage = "File " . basename($_FILES["image"]["name"]) . " is not an image.\n";
                $uploadOk = 0;
            }


            // Check if file already exists
            if (file_exists($target_file)) {
                $errorMessage = "File already exists.\n";
                $uploadOk = 0;
            }

            // Check file size
            if ($_FILES["image"]["size"] > 5000000) {
                $errorMessage = "Sorry, your file is too large. Max size: 5MB.\n";
                $uploadOk = 0;
            }

            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "JPG" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "PNG"
            ) {
                $errorMessage = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.\n";
                $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
//            echo "Sorry, your file was not uploaded.";

                // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
//                echo "The file " . basename($_FILES["image"]["name"]) . " has been uploaded.";
                } else {
                    $errorMessage = "Sorry, there was an error uploading your file.";
                }
            }

            return $errorMessage;

        } else {
            return $errorMessage = "You didn't select a file.";
        }
    }
    
}