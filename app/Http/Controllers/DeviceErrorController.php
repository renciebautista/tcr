<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\DeviceError;

class DeviceErrorController extends Controller
{
    public function index(Request $request){
    	$devices = DeviceError::orderBy('updated_at','desc')->get();
    	return view('deviceerror.index', compact('devices'));
    }

    public function getfile($filename){
        $myfile = storage_path().'/uploads/traces/'.$filename;

        if (!\File::exists($myfile))
        {
            echo "File not exists.";
        }else{
            return \Response::download($myfile, $filename);
        }
    }
}
