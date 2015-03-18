<?php

class App_Util_Util {

    public static function alias($str) {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ|À|Á|Ạ|Ả|Ã|Â|A|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", "a", $str);
        $str = preg_replace("/(B)/", "b", $str);
        $str = preg_replace("/(C)/", "c", $str);
        $str = preg_replace("/(đ|D|Đ)/", "d", $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ|È|É|Ẹ|E|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", "e", $str);
        $str = preg_replace("/(F)/", "f", $str);
        $str = preg_replace("/(G)/", "g", $str);
        $str = preg_replace("/(H)/", "h", $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ|Ì|Í|Ị|Ỉ|Ĩ)/", "i", $str);
        $str = preg_replace("/(J)/", "j", $str);
        $str = preg_replace("/(K)/", "k", $str);
        $str = preg_replace("/(L)/", "l", $str);
        $str = preg_replace("/(M)/", "m", $str);
        $str = preg_replace("/(N)/", "n", $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ|Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|O|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", "o", $str);
        $str = preg_replace("/(P)/", "p", $str);
        $str = preg_replace("/(Q)/", "q", $str);
        $str = preg_replace("/(R)/", "r", $str);
        $str = preg_replace("/(S)/", "s", $str);
        $str = preg_replace("/(T)/", "t", $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ|Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", "u", $str);
        $str = preg_replace("/(V)/", "v", $str);
        $str = preg_replace("/(W)/", "w", $str);
        $str = preg_replace("/(X)/", "x", $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ|Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", "y", $str);
        $str = preg_replace("/(Z)/", "z", $str);
        $str = preg_replace("/(!|@|%|\^|\*|\(|\)|\+|\=|<|>|\?|\/|,|\.|\:|\;|\'|\"|\“|\”|\&|\#|\[|\]|~|$|_)/", "", $str);
        $str = str_replace("&*#39;", "", $str);
        $str = str_replace(" ", "-", $str);
        return $str;
    }


    public static function wordLimit($str, $limit = 100, $strip_tags = true, $end_char = ' &#8230;') {
        if (trim($str) == '') {
            return $str;
        }

        if ($strip_tags) {
            $str = trim(preg_replace('#<[^>]+>#', ' ', $str));
        }
        $words = explode(' ', $str);
        $words = array_filter($words);
        $string = '';
        if (count($words) > $limit) {
            $i = 0;
            foreach ($words as $word) {
                if ($i < $limit) {
                    $string.=$word . ' ';
                    $i++;
                } else {
                    break;
                }
            }
        } else {
            $string = $str;
        }

        $string = self::removeSpace($string);

        return rtrim($string) . $end_char;
    }

    public static function characterLimit($str, $limit = 150, $strip_tags = true, $end_char = ' &#8230;', $enc = 'utf-8') {
        if (trim($str) == '') {
            return $str;
        }

        if ($strip_tags) {
            $str = strip_tags($str);
        }

        if (strlen($str) > $limit) {
            if (function_exists("mb_substr")) {
                $str = mb_substr($str, 0, $limit, $enc);
            } else {
                $str = substr($str, 0, $limit);
            }
            return rtrim($str) . $end_char;
        } else {
            return $str;
        }
    }

    public static function random($length = 8, $possible = "0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSXTUVYW") {
        // start with a blank string
        $string = "";

        // set up a counter
        $i = 0;

        // add random characters to $string until $length is reached
        while ($i < $length) {

            // pick a random character from the possible ones
            $char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);

            // we don't want this character if it's already in the string
            if (!strstr($string, $char)) {
                $string .= $char;
                $i++;
            }
        }

        // done!
        return $string;
    }

    public static function Timer($timestamp) {
        $etime = time() - $timestamp;
        if ($etime < 1) {
            return 'bây giờ';
        }
        $a = array(365 * 24 * 60 * 60 => 'year',
            30 * 24 * 60 * 60 => 'month',
            7 * 24 * 60 * 60 => 'week',
            24 * 60 * 60 => 'day',
            60 * 60 => 'hour',
            60 => 'minute',
            1 => 'second'
        );
        $a_plural = array('year' => 'năm',
            'month' => 'tháng',
            'week' => 'tuần',
            'day' => 'ngày',
            'hour' => 'giờ',
            'minute' => 'phút',
            'second' => 'giây'
        );

        foreach ($a as $secs => $str) {
            $d = $etime / $secs;
            if ($d >= 1) {
                $r = round($d);
                return $r . ' ' . $a_plural[$str] . ' ' . 'trước';
            }
        }
    }

    public static function resizeIMG($src_file, $dest_file, $width, $height, $prop, $quality) {
        $imagetype = array(1 => 'GIF', 2 => 'JPG', 3 => 'PNG');
        $imginfo = getimagesize($src_file);
        if ($imginfo == null) {
            $error = 'COM_IPROPERTY_NO_FILE_FOUND';
            return $error;
        }

        $imginfo[2] = $imagetype[$imginfo[2]];

        // GD can only handle JPG & PNG images
        if ($imginfo[2] != 'JPG' && $imginfo[2] != 'GIF' && $imginfo[2] != 'PNG') {
            $error = "GDERROR1";
            return $error;
        }

        // source height/width
        $srcWidth = $imginfo[0];
        $srcHeight = $imginfo[1];

        if ($prop == 1) {
            if (!$width):
                return 'COM_IPROPERTY_IMAGE_DIMENSIONS_INVALID'; // can't create image with 0 width and constrain proportions
            endif;
            // if prop, maintain proportions
            $haveratio = $srcWidth / $srcHeight;
            if ($haveratio == 1) { // it's square
                $destWidth = $width;
                $destHeight = $width;
            } else { // it's horizontal or vertical
                $destWidth = $width;
                $destHeight = round($width / $haveratio);
            }
        } else {
            // we don't care about the ratio, we're building to their specs
            if (!$height || !$width):
                return 'COM_IPROPERTY_IMAGE_DIMENSIONS_INVALID'; // can't create image with 0 width or height
            endif;

            $destWidth = (int) ($width);
            $destHeight = (int) ($height);
        }

        if (!function_exists('imagecreatefromjpeg')) {
            return 'GDERROR2';
        }

        if ($imginfo[2] == 'JPG') {
            $src_img = imagecreatefromjpeg($src_file);
        } else if ($imginfo[2] == 'GIF') {
            $src_img = imagecreatefromgif($src_file);
        } else if ($imginfo[2] == 'PNG') {
            $src_img = imagecreatefrompng($src_file);
        }

        if (!$src_img):
            return JText::_('GDERROR3');
        endif;

        if (function_exists("imagecreatetruecolor")) {
            $dst_img = imagecreatetruecolor($destWidth, $destHeight);
        } else {
            $dst_img = imagecreate($destWidth, $destHeight);
        }

        if (function_exists("imagecopyresampled")) {
            imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, (int) $destWidth, (int) $destHeight, $srcWidth, $srcHeight);
        } else {
            imagecopyresized($dst_img, $src_img, 0, 0, 0, 0, (int) $destWidth, (int) $destHeight, $srcWidth, $srcHeight);
        }
//
//        if (!$is_thmb && $settings->watermark) {
//            /* drop shadow watermark thanks to hkingman */
//            $wmstr = $settings->watermark_text;
//            $wmstr = "(c)" . date("Y") . " " . $wmstr;
//            $ftcolor2 = imagecolorallocate($dst_img, 239, 239, 239);
//            $ftcolor = imagecolorallocate($dst_img, 15, 15, 15);
//            // imagestring ($dst_img, 2,10, $destHeight-20, $wmstr, $ftcolor);
//            imagestring($dst_img, 2, 11, $destHeight - 20, $wmstr, $ftcolor);
//            imagestring($dst_img, 2, 10, $destHeight - 21, $wmstr, $ftcolor2);
//        }
        imagejpeg($dst_img, $dest_file, $quality);
        imagedestroy($src_img);
        imagedestroy($dst_img);

        // Set mode of uploaded picture
        chmod($dest_file, octdec('644'));

        // We check that the image is valid
        $imginfo = getimagesize($dest_file);
        if ($imginfo == null) {
            return 'COM_IPROPERTY_IMAGE_INFO_NOT_RETURNED';
        } else {
            // return true;
        }
    }

}
