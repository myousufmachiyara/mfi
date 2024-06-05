<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\AC;
use App\Models\lager;
use App\Models\lager0;
use App\Models\jv2_att;
use App\Traits\SaveImage;
use TCPDF;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class JV2Controller extends Controller
{
    use SaveImage;

    public function index()
    {
        $jv2 = lager::where('lager.status', 1)
                ->join('ac', 'ac.ac_code', '=', 'lager.account_cod')
                ->select('lager.*', 
                'ac.ac_name as account_name')
                ->get();
        $acc = AC::where('status', 1)->get();
        return view('vouchers.jv2',compact('jv2','acc'));
    }

    public function create(Request $request)
    {
        return view('vouchers.jv2-new');
    }
}
