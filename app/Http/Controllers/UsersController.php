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
        $acc = AC::join('sub_head_of_acc as shoa', 'shoa.id', '=', 'ac.AccountType')
               ->leftjoin('ac_group as ag', 'ag.group_cod', '=', 'ac.group_cod')
               ->select('ac.*' , 'ag.group_name', 'shoa.sub')
               ->get();
        $sub_head_of_acc = sub_head_of_acc::where('status', 1)->get();
        $ac_group = ac_group::where('status', 1)->get();

        return view('users.users',compact('acc','sub_head_of_acc','ac_group'));
    }
}
