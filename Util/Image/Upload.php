<?php
/**
 * Here're your description about this file and its function
 *
 * @version			$Id: KernelUpload.php Jul 27, 2010 2:07:54 PM$
 * @category		App_Util_Upload
 * @package    App.Platform
 * @subpackage		App_Util_Upload
 * @license			http://xgoon.com
 * @copyright		Copyright (c) 2005-2011 XGOON MEDIA
 * @author			toan@xgoon.com (toan)
 * @file			Grid.php
 *
 *
 */

class App_Util_Upload extends JFile
{

    var $Rule = null;

    function setExtensions($ext_array = array())
    {
        $this->Rule ['ext'] = $ext_array;
    }

    //max and min kb
    function setSize($max, $min)
    {
        $this->Rule ['size'] = array (
            'min' => $min, 
            'max' => $max );
    }

    function executeRule($src)
    {

        $ext = strtolower ( $this->getExt ( $src ['name'] ) );
        if (array_search ( $ext, $this->Rule ['ext'] ) === false)
        {
            return false;
        }
        if ($src ['size'] < ($this->Rule ['size'] ['min'] * 1024) || $src ['size'] > ($this->Rule ['size'] ['max'] * 1024))
        {
            return false;
        }

        return true;
    }

    function upload($src, $dest)
    {
        if ($this->executeRule ( $src ))
        {

            return parent::upload ( $src ['tmp_name'], $dest );
        }
        return false;
    }

    function uploadImage($files, $filePath, $widthImage)
    {
        //the new width of the resized image, in pixels.
        $img_thumb_width = $widthImage;

        //the image -> variables
        $file_type = $files ['type'];
        $file_name = $files ['name'];
        $file_size = $files ['size'];
        $file_tmp = $files ['tmp_name'];

        //check if you have selected a file.
        if (! is_uploaded_file ( $file_tmp ))
        {
            return false; //exit the script and don't process the rest of it!
        }
        //the new width variable
        $ThumbWidth = $img_thumb_width;

        /////////////////////////////////
        // CREATE THE THUMBNAIL //
        ////////////////////////////////
        if ($file_size)
        {
            if ($file_type == "image/pjpeg" || $file_type == "image/jpeg")
            {
                $new_img = imagecreatefromjpeg ( $file_tmp );
            }
            elseif ($file_type == "image/x-png" || $file_type == "image/png")
            {
                $new_img = imagecreatefrompng ( $file_tmp );
            }
            elseif ($file_type == "image/gif")
            {
                $new_img = imagecreatefromgif ( $file_tmp );
            }

            //list the width and height and keep the height ratio.
            list ( $width, $height ) = getimagesize ( $file_tmp );

            //calculate the image ratio
            $imgratio = $width / $height;
            if ($imgratio > 1)
            {
                $newwidth = $ThumbWidth;
                $newheight = $ThumbWidth / $imgratio;
            }
            else
            {
                //				$newheight = $ThumbWidth;
                //				$newwidth = $ThumbWidth*$imgratio;
                $newwidth = $ThumbWidth;
                $newheight = $ThumbWidth / $imgratio;
            }

            //function for resize image.
            if (function_exists ( imagecreatetruecolor ))
            {
                $resized_img = imagecreatetruecolor ( $newwidth, $newheight );
            }
            else
            {
                return false;
            }
            //the resizing is going on here!
            imagecopyresized ( $resized_img, $new_img, 0, 0, 0, 0, $newwidth, $newheight, $width, $height );
            //finally, save the image
            ImageJpeg ( $resized_img, "$filePath" );
            ImageDestroy ( $resized_img );
            ImageDestroy ( $new_img );
            return true;
        }

    }
}

?>

