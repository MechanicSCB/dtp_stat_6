<?php


namespace App\Dev;


class PngCreator
{
    public function createCircle(int $diameter, string $colorHex, int|float $strokeWidth = null, string $strokeColor = null): void
    {
        $imgFolder = storage_path("app/public/icons/png");
        $filename = "circle-" . str_replace('#', '', $colorHex) . "-$diameter";

        // CREATE BIG CIRCLE PNG IMAGE
        $bigSize = 1000;
        $bigCircleImage = imagecreatetruecolor($bigSize, $bigSize);
        $bgColor = imagecolorallocatealpha($bigCircleImage, 0, 0, 0, 127);
        imagefill($bigCircleImage, 0, 0, $bgColor);
        [$r, $g, $b] = sscanf($colorHex, "#%02x%02x%02x");
        $padding = 50;
        $color = imagecolorallocatealpha($bigCircleImage, $r, $g, $b, 0);
        //imagefilter($bigCircleImage,   IMG_FILTER_EDGEDETECT);
        imagefilledarc($bigCircleImage, $bigSize / 2, $bigSize / 2, $bigSize - $padding, $bigSize - $padding, 0, 360, $color, IMG_ARC_EDGED);
        //imagefilter($bigCircleImage,   IMG_FILTER_GAUSSIAN_BLUR);
        imagefilter($bigCircleImage, IMG_FILTER_SMOOTH, 500);
        imagesavealpha($bigCircleImage, true);

        // RESIZE BIG TO FINAL DIAMETER
        $circleImage = imagecreatetruecolor($diameter, $diameter);
        imagealphablending($circleImage, false); //Отключаем режим сопряжения цветов
        imagesavealpha($circleImage, true); //Включаем сохранение альфа канала
        imagecopyresampled($circleImage, $bigCircleImage, 0, 0, 0, 0, $diameter, $diameter, $bigSize, $bigSize);

        //header("Content-type: image/png");
        //imagepng($circleImage);
        //die();

        imagepng($circleImage, "$imgFolder/s-$filename.png", 9); // save to storage folder

        df(tmr(@$this->start), 'done');
    }

    public function createCircleWithShadow(int $diameter, string $colorHex): void
    {
        $imgFolder = storage_path("app/public/icons/png/circle-shadow");
        $filename = "circle-" . str_replace('#', '', $colorHex) . "-$diameter";

        // CREATE BIG CIRCLE PNG IMAGE
        $bigImageSize = 2000;
        $bigCircleImage = imagecreatetruecolor($bigImageSize, $bigImageSize);
        $bgColor = imagecolorallocatealpha($bigCircleImage, 0, 0, 0, 127);
        imagefill($bigCircleImage, 0, 0, $bgColor);
        [$r, $g, $b] = sscanf($colorHex, "#%02x%02x%02x");
        $padding = 50;
        $color = imagecolorallocatealpha($bigCircleImage, $r, $g, $b, 0);
        $shadow = imagecolorallocatealpha($bigCircleImage, 0, 0, 0, 100);

        for ($i = 1; $i <= 30; $i += 5) {
            imagefilledarc($bigCircleImage, $bigImageSize / 2 + $i, $bigImageSize / 2 + $i, $bigImageSize / 2 + $i * 3, $bigImageSize / 2 + $i * 3, 0, 360, $shadow, IMG_ARC_EDGED);
        }
        //imagefilter($bigCircleImage,   IMG_FILTER_EDGEDETECT);
        imagefilledarc($bigCircleImage, $bigImageSize / 2, $bigImageSize / 2, $bigImageSize / 2, $bigImageSize / 2, 0, 360, $color, IMG_ARC_EDGED);
        //imagefilter($bigCircleImage,   IMG_FILTER_GAUSSIAN_BLUR);
        imagesavealpha($bigCircleImage, true);
        //imagefilter($bigCircleImage, IMG_FILTER_SMOOTH, 500);

        //header("Content-type: image/png");
        //imagepng($bigCircleImage);
        //die();

        // RESIZE BIG TO FINAL DIAMETER
        $newSize = $diameter * 2;
        $circleImage = imagecreatetruecolor($newSize, $newSize);
        imagealphablending($circleImage, false); //Отключаем режим сопряжения цветов
        imagesavealpha($circleImage, true); //Включаем сохранение альфа канала
        imagecopyresampled($circleImage, $bigCircleImage, 0, 0, 0, 0, $newSize, $newSize, $bigImageSize, $bigImageSize);

        //header("Content-type: image/png");
        //imagepng($circleImage);
        //die();


        imagepng($circleImage, "$imgFolder/$filename.png", 9); // save to storage folder

        //df(tmr(@$this->start), 'done');
    }

    public function copyResize(string $srcPath, int $newWidth, int $newHeight = null, string $storePath = null)
    {
        $newHeight ??= $newWidth;

        if (is_null($storePath)) {
            $storePath = explode('.', $srcPath);
            $storePath = "$storePath[0]({$newWidth}x$newHeight).$storePath[1]";
        }

        $srcImg = imagecreatefrompng($srcPath);
        $srcSize = getimagesize($srcPath);
        $srcWidth = $srcSize[0];
        $srcHeight = $srcSize[1];

        $newImg = imagecreatetruecolor($newWidth, $newHeight); //Создаем полноцветное изображение
        imagealphablending($newImg, false); //Отключаем режим сопряжения цветов
        imagesavealpha($newImg, true); //Включаем сохранение альфа канала

        //Ресайз
        imagecopyresampled($newImg, $srcImg, 0, 0, 0, 0, $newWidth, $newHeight, $srcWidth, $srcHeight);

        //Сохранение
        imagepng($newImg, $storePath);

        df(tmr(@$this->start), 777);

    }

    public function createPngFromSvg()
    {
        //        $usmap = '/path/to/blank/us-map.svg';
        //        $im = new Imagick();
        //        $svg = file_get_contents($usmap);
        //
        //        /*loop to color each state as needed, something like*/
        //        $idColorArray = array(
        //            "AL" => "339966"
        //            ,"AK" => "0099FF"
        //    ...
        //    ,"WI" => "FF4B00"
        //    ,"WY" => "A3609B"
        //);
        //
        //foreach($idColorArray as $state => $color){
        //    //Where $color is a RRGGBB hex value
        //    $svg = preg_replace(
        //        '/id="'.$state.'" style="fill:#([0-9a-f]{6})/'
        //        , 'id="'.$state.'" style="fill:#'.$color
        //        , $svg
        //    );
        //}
        //
        //$im->readImageBlob($svg);
        //
        ///*png settings*/
        //$im->setImageFormat("png24");
        //$im->resizeImage(720, 445, imagick::FILTER_LANCZOS, 1);  /*Optional, if you need to resize*/
        //
        ///*jpeg*/
        //$im->setImageFormat("jpeg");
        //$im->adaptiveResizeImage(720, 445); /*Optional, if you need to resize*/
        //
        //$im->writeImage('/path/to/colored/us-map.png');/*(or .jpg)*/
        //$im->clear();
        //$im->destroy();
        //        df(tmr(@$this->start), 888);
    }
}
