<?php


namespace App\Services\Interfaces;


interface ImageServiceInterface
{
    public function saveImage($image, $image_name, $path, $crop = false, $width = null, $height = null);
    public function deleteImage($image_path);

    public function saveUserAvatar($user, $file);
}
