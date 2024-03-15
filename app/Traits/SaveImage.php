<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

trait SaveImage{
    public function salesDoc($file,$extension)
    {
        $img = $file;
        $number = rand(1,999);
        $numb = $number / 7 ;
        // $extension      = $img->extension();
        $filenamenew    = date('Y-m-d')."_.".$numb."_.".$extension;
        $filenamepath   = 'uploads/'.$filenamenew;
        $filename       = $img->move(public_path('uploads/sales/'),$filenamenew);
        return $filenamepath;
    }
}
?>