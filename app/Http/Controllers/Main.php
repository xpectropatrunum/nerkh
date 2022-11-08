<?php

namespace App\Http\Controllers;

use App\Helpers\Shamsi;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Main extends Controller
{
    function index(){
        try {
            $data = Http::timeout(4)->get("http://localhost:8000/api/price")->object();
        } catch (\Exception $e) {
            $data = Cache::get("prep_data", (object)[]);
        }
        $data =  collect($data) ->toArray();
        $time = (new Shamsi)->jdate(date("Y/m/d H:i"));
        return view("welcome", compact('data', 'time'));
    }
}
