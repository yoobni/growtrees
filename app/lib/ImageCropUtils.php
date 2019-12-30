<?php

namespace App\Lib;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Storage;

class ImageCropUtils {

	private static function getImageResource($path, $mimetype) {
		
		$image = null;

		switch($mimetype) {
    	    case 'image/bmp': 
    	    {
				$image = imageCreateFromwBmp($path);
				break;
			}
	    	case 'image/gif': 
	    	{
				$image = imageCreateFromGif($path); 
				break;
			}
 	    	case 'image/jpeg': 
 	    	{
				$image = imageCreateFromJpeg($path); 
				break;
			}
    	    case 'image/png': 
    	    {
				$image = imageCreateFromPng($path); 
				break;
			}
  		}

  		return $image;
	}

	private static function saveImage($resource, $path, $mimetype) {
		switch($mimetype) {
            case 'image/bmp':
            {
                imagewBmp($resource, $path);
                break;
            }
            case 'image/gif':
            {
                imageGif($resource, $path);
                break;
            }
            case 'image/jpeg':
            {
                imageJpeg($resource, $path);
                break;
            }
            case 'image/png':
            {
                imagePng($resource, $path); 
                break;
            }
        }
	}
	private static function resizeImage($path, $mimetype, $newWidth = 250, $newHeight = 250) {

		list($orgWidth, $orgHeight) = getImageSize($path);

		$imageResized = imageCreateTrueColor($newWidth, $newHeight);
		$imageOrigin = self::getImageResource($path, $mimetype);

		imageCopyResampled($imageResized, $imageOrigin, 0, 0, 0, 0,
                        $newWidth, $newHeight, $orgWidth, $orgHeight);

		return $imageResized;
	}

	private static function cropImage($path, $mimetype, $imageOrigin, $x1, $y1, $orgSize) {
		$orgWidth = $orgHeight = $orgSize;
		$newWidth = $newHeight = 150;
		$imageCropped = imageCreateTrueColor($newWidth, $newHeight);

		imageCopyResampled($imageCropped, $imageOrigin, 0, 0, $x1, $y1,
				$newWidth, $newHeight, $orgWidth, $orgHeight);

		self::saveImage($imageCropped, $path, $mimetype);
	}

	public static function updateProfileImage(Request $request, User $user) {
		if(! $request->hasFile('profile_image')) {
	    	return;
		}
	
		$id = $user->id;
		$request->file('profile_image')->storeAs(
       		'public/profile_imgs/', $id.'.tmp'
        );
	
		$mimetype = $request->file('profile_image')->getMimeType();
		$PATH = public_path('storage/profile_imgs/');	
		$imageResized = self::resizeImage($PATH.$id.'.tmp', $mimetype);

		$x1 = $request->input('x1');
		$y1 = $request->input('y1');
		$size = $request->input('size');

		self::cropImage($PATH.$id, $mimetype, $imageResized, $x1, $y1, $size);

		Storage::delete('public/profile_imgs/'.$id.'.tmp');		
	}
}