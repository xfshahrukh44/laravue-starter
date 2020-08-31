<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;

class BannerController extends Controller
{
    public function __construct()
    {
        
    }
    
    public function get_banner(Request $request)
    {
        return Banner::find($request->id);
    }
}
