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
        $filenamenew    = date('Y-m-d')."_.".$numb."_.".$extension;
        $filenamepath   = 'uploads/'.$filenamenew;
        $filename       = $img->move(public_path('uploads/sales/'),$filenamenew);
        return $filenamepath;
    }
    
    public function coaDoc($file,$extension)
    {
        $img = $file;
        $number = rand(1,999);
        $numb = $number / 7 ;
        $filenamenew    = date('Y-m-d')."_.".$numb."_.".$extension;
        $filenamepath   = 'uploads/coa/'.$filenamenew;
        $filename       = $img->move(public_path('uploads/coa/'),$filenamenew);
        return $filenamepath;
    }

    public function jv1Doc($file,$extension)
    {
        $img = $file;
        $number = rand(1,999);
        $numb = $number / 7 ;
        $filenamenew    = date('Y-m-d')."_.".$numb."_.".$extension;
        $filenamepath   = 'uploads/jv1/'.$filenamenew;
        $filename       = $img->move(public_path('uploads/jv1/'),$filenamenew);
        return $filenamepath;
    }

    public function jv2Doc($file,$extension)
    {
        $img = $file;
        $number = rand(1,999);
        $numb = $number / 7 ;
        $filenamenew    = date('Y-m-d')."_.".$numb."_.".$extension;
        $filenamepath   = 'uploads/jv2/'.$filenamenew;
        $filename       = $img->move(public_path('uploads/jv2/'),$filenamenew);
        return $filenamepath;
    }

    public function pur1Doc($file,$extension)
    {
        $img = $file;
        $number = rand(1,999);
        $numb = $number / 7 ;
        $filenamenew    = date('Y-m-d')."_.".$numb."_.".$extension;
        $filenamepath   = 'uploads/pur1/'.$filenamenew;
        $filename       = $img->move(public_path('uploads/pur1/'),$filenamenew);
        return $filenamepath;
    }
}
?>