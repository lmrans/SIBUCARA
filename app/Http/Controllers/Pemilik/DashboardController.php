<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        abort_unless($request->user()->isPemilik(), 403);

        return view('dashboard.pemilik.index');
    }
}
