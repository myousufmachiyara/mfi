<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

use TCPDF;
use ZipArchive;
use Carbon\Carbon;
use App\Models\AC;
use App\Models\ac_att;
use App\Models\ac_group;
use App\Traits\SaveImage;
use App\Models\sub_head_of_acc;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UsersController extends Controller
{
    //
    public function index()
    {
        return view('users.users');
    }
}
