<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard(){
       //echo "pepe" ;
       return view('dashboard');
    }
}
