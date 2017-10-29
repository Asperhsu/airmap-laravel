<?php

namespace App\Service;

class IconMaker
{
    public static function make(string $color = "", string $text = "", int $size = 250, float $opacity = 1)
    {
        $imgPadding     = 2;
        $borderWidth    = 10;
        $borderColor    = "#FFFFFF";
        $circleRadius   = $size;
        $fillColor      = $color;
        $fontColor      = static::getContrastYIQ($fillColor);
        $fontPath       = storage_path().'/consola.ttf';
        $fontSize       = 100;
        $smallFontSize  = 80;

        $img = imagecreatetruecolor($circleRadius+$imgPadding, $circleRadius+$imgPadding);
        $imgCenter = ($circleRadius+$imgPadding)/2;

        //transparent background
        imagesavealpha($img, true);
        $transparentColor = imagecolorallocatealpha($img, 0, 0, 0, 127);
        imagefill($img, 0, 0, $transparentColor);

        //draw border
        $imgBorderColor = static::hexColorAllocate($img, $borderColor, $opacity);
        // imagefilledellipse($img, $imgCenter, $imgCenter, $circleRadius, $circleRadius, $imgBorderColor); 
        for($i=0; $i<=$borderWidth; $i++){
            imagearc($img, $imgCenter, $imgCenter, $circleRadius-$i, $circleRadius-$i,  0, 360, $imgBorderColor);
        }

        //draw inner circle
        if(strlen($fillColor)){
            $imgCircleColor = static::hexColorAllocate($img, $fillColor, $opacity);
            imagefilledellipse($img, $imgCenter, $imgCenter, $circleRadius-$borderWidth, $circleRadius-$borderWidth, $imgCircleColor);
        }

        //text
        $xOffset = -15; $yOffset = 0;
        switch(strlen($text)){
            case '1': $xOffset=$circleRadius/10; break;
            case '2': break;
            case '3': $xOffset=-($circleRadius/6); $fontSize=$fontSize/1.2; break;
            default: $xOffset=-($circleRadius/3); $fontSize=$fontSize/1.2; break;
        }
        $textPosition = ($circleRadius + $imgPadding - $fontSize - 5) / 2;
        $imgTextColor = static::hexColorAllocate($img, $fontColor, 1);
        imagettftext($img, $fontSize, 0, $textPosition+$xOffset, $textPosition+$fontSize+$yOffset, $imgTextColor, $fontPath, $text);

        ob_start();
        $rendered_buffer = imagepng($img);
        $buffer = ob_get_contents();
        imagedestroy($img);
        ob_end_clean();

        return $buffer;
    }

    public static function getContrastYIQ(string $hexColor)
    {
        $hex = ltrim($hexColor,'#');
        $r = hexdec(substr($hex,0,2));
        $g = hexdec(substr($hex,2,2));
        $b = hexdec(substr($hex,4,2));
        $yiq = (($r*299)+($g*587)+($b*114))/1000;
        return ($yiq >= 128) ? 'black' : 'white';
    }

    public static function getContrast50(string $hexColor)
    {
        return (hexdec($hexColor) > 0xffffff/2) ? 'black':'white';
    }

    public static function hexColorAllocate($im, string $hexColor, float $opacity=1)
    {
        $hex = ltrim($hexColor,'#');
        $a = hexdec(substr($hex,0,2));
        $b = hexdec(substr($hex,2,2));
        $c = hexdec(substr($hex,4,2));
    
        $alpha = (1-$opacity) * 127;
        return imagecolorallocatealpha($im, $a, $b, $c, $alpha); 
    }

    public static function color(float $pm25)
    {
        $colors = [
            11 => '#9CFF9C',
            23 => '#31FF00',
            35 => '#31CF00',
            41 => '#FFFF00',
            47 => '#FFCF00',
            53 => '#FF9A00',
            58 => '#FF6464',
            64 => '#FF0000',
            70 => '#990000',
            71 => '#CE30FF',
        ];

        $lastColorMaxValue = last(array_keys($colors));
        foreach ($colors as $maxValue => $color) {
            if( $pm25 <= $maxValue ){
                return $color;
            }

            //level greater lastone level
            if( $pm25 >= $lastColorMaxValue){
                return $colors[$lastColorMaxValue];
            }
        }
    }
}