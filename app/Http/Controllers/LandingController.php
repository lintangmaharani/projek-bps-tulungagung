<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kuota;
use App\Models\CmsContent;

class LandingController extends Controller
{
    public function index()
    {
        // Mengambil kuota yang statusnya 'buka'
        $kuotas = Kuota::where('status', 'buka')
            ->orderBy('tgl_mulai', 'asc')
            ->take(3) // Batasi maksimal 3 periode yang tampil di beranda agar tetap rapi
            ->get(); 

        return view('landing', compact('kuotas'));

        // Mengambil semua data CMS dan mengubahnya menjadi array asosiatif key => value
        $contents = CmsContent::all()->pluck('value', 'key');
        
        // Sesuaikan 'landing' dengan nama file blade landing page Anda (misal: landing.blade.php)
        return view('landing', compact('contents'));
    }
}