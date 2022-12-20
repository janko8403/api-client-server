<?php

/**
 * Created by PhpStorm.
 * User: mdomarecki
 * Date: 01.06.17
 * Time: 23:55
 */

namespace Hr\Image;

class Image
{
    private $originalFile;

    public function resize($maxVal, $targetFile, $fileName, $quality = 80)
    {
        if (empty($maxVal) || empty($targetFile) || empty($fileName)) {
            return false;
        }

        $this->originalFile = $fileName;

        $src = imagecreatefromjpeg($this->originalFile);
        $exif = @exif_read_data($this->originalFile);

        if (!empty($exif['Orientation'])) {
            switch ($exif['Orientation']) {
                case 3:
                    $src = imagerotate($src, 180, 0);
                    break;

                case 6:
                    $src = imagerotate($src, -90, 0);
                    break;

                case 8:
                    $src = imagerotate($src, 90, 0);
                    break;
            }
        }

        $width = $height = $maxVal;
        $widthOrg = imagesx($src);
        $heightOrg = imagesy($src);
        $ratioOrg = $widthOrg / $heightOrg;

        if ($width / $height > $ratioOrg) {
            $width = $height * $ratioOrg;
        } else {
            $height = $width / $ratioOrg;
        }

        $tmp = imagecreatetruecolor($width, $height);
        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $width, $height, $widthOrg, $heightOrg);


        if (file_exists($targetFile)) {
            unlink($targetFile);
        }
        imagejpeg($tmp, $targetFile, $quality);

        unlink($this->originalFile);
    }


    public function getGpsData($long, $lati, $longHemi, $latiHemi): array
    {
        if (!is_array($long) || !is_array($lati)) {
            return [-1, -1];
        }

        $latitude = $this->getGps($lati, $latiHemi);
        $longitude = $this->getGps($long, $longHemi);

        return [$longitude, $latitude];
    }

    private function getGps($exifCoord, $hemi)
    {

        $degrees = count($exifCoord) > 0 ? $this->gps2Num($exifCoord[0]) : 0;
        $minutes = count($exifCoord) > 1 ? $this->gps2Num($exifCoord[1]) : 0;
        $seconds = count($exifCoord) > 2 ? $this->gps2Num($exifCoord[2]) : 0;

        $flip = ($hemi == 'W' or $hemi == 'S') ? -1 : 1;

        return $flip * ($degrees + $minutes / 60 + $seconds / 3600);

    }

    private function gps2Num($coordPart)
    {
        $parts = explode('/', $coordPart);

        if (count($parts) <= 0)
            return 0;

        if (count($parts) == 1)
            return $parts[0];

        return floatval($parts[0]) / floatval($parts[1]);
    }
}