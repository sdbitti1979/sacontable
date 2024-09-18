<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportesController extends Controller
{
    public function reportes(Request $request)
    {
        $vista = view('reportes.reportes');
        
        return $vista;
    }
}
