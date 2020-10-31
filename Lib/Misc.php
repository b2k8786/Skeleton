<?php
namespace lib;

use lib\Dbase;
/**
 * Description of Misc
 *
 * @author cts
 */
class Misc extends Dbase
{
    static function printArray($array)
    {
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }
    static function randomString($length, $option = 'All')
    {
        $option = explode('-', $option);
        $string = [];

        if (in_array("All", $option))
        {
            $string = array_merge($string, range("A", "Z"), range("0", "9"), range("a", "z"));
        }
        if (in_array("A", $option))
        {
            $string = array_merge($string, range("A", "Z"));
        }
        if (in_array("a", $option))
        {
            $string = array_merge($string, range("a", "z"));
        }
        if (in_array("N", $option))
        {
            $string = array_merge($string, range("0", "9"));
        }
        $random = "";
        for ($i = 0; $i < $length; $i++)
        {
            $random .= $string[mt_rand(0, count($string) - 1)];
        }
        return $random;
    }
    static function sendOTP($mobileNumber)
    {
        $user = $this->query("SELECT  otp,otpValidTill from db_Users WHERE mobileNumber = '$mobileNumber' AND status !='INACTIVE'")->fetchAssocFirst();
        if ($this->affectedRows() > 0)
        {
            if (!empty($user['otp']) && (strtotime($user['otpValidTill']) < strtotime(date('Y-m-d H:i:s'))))
            {
                $query = "UPDATE db_Users
                            SET 
                                    otp = '" . $this->randomString(5) . "',
                                    otpValidTill='" . date('Y-m-d H:i:s', strtotime(date("Y-m-d H:i:s") . ' +1days')) . "',
                            WHERE 
                                    mobileNumber='$mobileNumber' ";

                $this->query($query)->run();
            }
        }
    }
    static function createAlbumThumb($albumPath)
    {
        $images = glob($albumPath . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
        $thumbImags = [];
        $i = 0;
        while ($i < 4)
        {
            $img_ = !empty($images[array_rand($images)]) ? $images[array_rand($images)] : null;
            if (!empty($img_) && !in_array($img_, $thumbImags))
            {
                $thumbImags[] = $img_;
                $i++;
            }
        }

        $lastX = 0;
        $lastY = 0;
        $blank = imagecreatetruecolor(400, 400);
        imagesavealpha($blank, true);
        $color = imagecolorallocatealpha($blank, 0, 0, 0, 127);
        imagefill($blank, 0, 0, $color);
        imagejpeg($blank, "$albumPath/thumb.jpeg");
        if (!empty($thumbImags))
        {
            $i = 0;
            while ($i < count($thumbImags))
            {
                $imageInfo = getimagesize($thumbImags[$i]);
                list($width, $height, $mime) = $imageInfo;

                if ($imageInfo['mime'] == 'image/png')
                    $current_image = imagecreatefrompng($thumbImags[$i]);
                if ($imageInfo['mime'] == 'image/jpg' || $imageInfo['mime'] == 'image/jpeg' || $imageInfo['mime'] == 'image/pjpeg')
                    $current_image = imagecreatefromjpeg($thumbImags[$i]);
                if ($imageInfo['mime'] == 'image/gif')
                    $current_image = imagecreatefromgif($thumbImags[$i]);

                $x = imageSX($current_image);
                $y = imageSY($current_image);
                $thumb_w = ($width / $height) * 200;
                $thumb_h = 200;
                imagecopyresampled($blank, $current_image, $lastX, $lastY, 0, 0, $thumb_w, $thumb_h, $width, $height);

                imagejpeg($blank, "$albumPath/thumb.jpg");
                if ($lastX == 200)
                {
                    $lastX = 0;
                    $lastY += 200;
                }
                else
                {
                    $lastX += 200;
                }
                $i++;
            }
            imagedestroy($current_image);
            imagedestroy($blank);
        }
    }
   static function cleanString($text)
    {
        $utf8 = array(
            '/[áàâãªä]/u' => 'a',
            '/[ÁÀÂÃÄ]/u' => 'A',
            '/[ÍÌÎÏ]/u' => 'I',
            '/[íìîï]/u' => 'i',
            '/[éèêë]/u' => 'e',
            '/[ÉÈÊË]/u' => 'E',
            '/[óòôõºö]/u' => 'o',
            '/[ÓÒÔÕÖ]/u' => 'O',
            '/[úùûü]/u' => 'u',
            '/[ÚÙÛÜ]/u' => 'U',
            '/ç/' => 'c',
            '/Ç/' => 'C',
            '/ñ/' => 'n',
            '/Ñ/' => 'N',
            '/–/' => '-', // UTF-8 hyphen to "normal" hyphen
            '/[’‘‹›‚]/u' => ' ', // Literally a single quote
            '/[“”«»„]/u' => ' ', // Double quote
            '/ /' => ' ', // nonbreaking space (equiv. to 0x160)
        );
        $string = preg_replace(array_keys($utf8), array_values($utf8), $text);
        return trim(str_replace("'","\'",$string));
    }
}
