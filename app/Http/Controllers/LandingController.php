<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kuota;
use App\Models\CmsContent;

class LandingController extends Controller
{
    public function index()
    {
        // 1. Ambil kuota yang statusnya 'buka'
        $kuotas = Kuota::where('status', 'buka')
            ->orderBy('tgl_mulai', 'asc')
            ->take(3)
            ->get(); 

        // 2. Ambil semua data CMS
        $contents = CmsContent::all()->pluck('value', 'key');
        
        // 3. Kirim kedua variabel secara bersamaan ke view 'landing'
        return view('landing', compact('kuotas', 'contents'));
    }
}