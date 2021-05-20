<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Beranda_controller extends Controller
{
    public function index(){
    	$title = 'beranda';

    	return view('beranda.index',compact('title'));
    }
}
