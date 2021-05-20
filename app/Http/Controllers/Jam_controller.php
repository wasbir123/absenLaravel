<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\M_jam;

class Jam_controller extends Controller
{
    public function index(){
    	$title = 'Manage Jam';
    	$dt = M_jam::first();

    	return view('jam.index',compact('title','dt'));
    }

    public function update(Request $request){
    	try {
    		\DB::table('m_jam')->update([
    			'id'=>\Uuid::generate(4),
    			'jam_masuk'=>date('Y-m-d H:i',strtotime($request->jam_masuk)),
    			'jam_pulang'=>date('Y-m-d H:i',strtotime($request->jam_pulang))
    		]);

    		\Session::flash('sukses','Data berhasil diupdate');
    	} catch (\Exception $e) {
    		\Session::flash('gagal',$e->getMessage());
    	}

    	return redirect('jam');
    }
}
