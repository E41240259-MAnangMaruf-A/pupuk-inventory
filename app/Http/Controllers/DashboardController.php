<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function adminDesa()
    {
        return view('dashboard.admin-desa');
    }

    public function adminKoperasi()
    {
        return view('dashboard.admin-koperasi');
    }

    public function kasirKoperasi()
    {
        return view('dashboard.kasir-koperasi');
    }

    public function kepalaDesa()
    {
        return view('dashboard.kepala-desa');
    }
}
