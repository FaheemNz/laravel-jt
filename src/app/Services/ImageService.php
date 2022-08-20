<?php


namespace App\Services;


use App\Lib\Helper;
use App\Services\Interfaces\ImageServiceInterface;
use App\Utills\Constants\FilePaths;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;


class ImageService implements ImageServiceInterface
{
    public function deleteImage($image_path)
    {
        $is_default_image = Str::contains($image_path, '/defaults/');

        if (!$is_default_image) {
            File::delete($image_path);
        }

    }

    public function saveImage($image, $image_name, $path, $crop = false, $width = null, $height = null)
    {
        if (!file_exists(public_path($path))) {
            if (!mkdir($concurrentDirectory = public_path($path), 666, true) && !is_dir($concurrentDirectory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }
        }

        $pub_path = public_path($path . $image_name);
        $loc = $path . $image_name;

        if ($crop) {
            if ($height != null && $width != null) {
                Image::make($image)->crop($height, $width)->save($pub_path, 60);
            } else {
                $height = Image::make($image)->height();
                Image::make($image)->crop($height, $height)->save($pub_path, 60);
            }
        } else {
            $img = Image::make($image->getRealPath());
            $img = $this->resizeImage($img, 1000);
            $img->save($pub_path,60);

//            Image::make($image)->save($pub_path, 60);
        }

        return $loc;
    }

    private function resizeImage($image, $requiredSize) {
        $width = $image->width();
        $height = $image->height();

        // Check if image resize is required or not
        if ($requiredSize >= $width && $requiredSize >= $height) return $image;

        $newWidth;
        $newHeight;

        $aspectRatio = $width/$height;
        if ($aspectRatio >= 1.0) {
            $newWidth = $requiredSize;
            $newHeight = $requiredSize / $aspectRatio;
        } else {
            $newWidth = $requiredSize * $aspectRatio;
            $newHeight = $requiredSize;
        }


        $image->resize($newWidth, $newHeight);
        return $image;
    }

//    private function createImagesFolder($folder)
//    {
//        $imagesFolder = public_path($folder);
//        if (!File::exists($imagesFolder)) {
//            File::makeDirectory($imagesFolder, 0777, true, true);
//        }
//    }

    public function saveUserAvatar($user, $file)
    {
        if($user->image){
            $image = $user->image;
            $this->deleteImage(public_path(FilePaths::ADMIN_PROFILE_IMAGE_DIRECTORY ."/".$image->name));
            $image->delete();
        }

        $file_name        =     $file->getClientOriginalName();
        $file_ext         =     $file->getClientOriginalExtension();
        $file_unique_name =     Helper::getUniqueImageName($file_name);

        $file->move(public_path(FilePaths::ADMIN_PROFILE_IMAGE_DIRECTORY), $file_unique_name);

        $image = \App\Image::create([
            'original_name' => $file_name,
            'name'          => $file_unique_name,
            'uploaded_by'   => auth()->user()->id
        ]);

        $user->update([
            'image_id' => $image->id
        ]);

        $image['name'] = asset(FilePaths::ADMIN_PROFILE_IMAGE_DIRECTORY) . '/' . $image['name'];

        return $image;
    }
}
