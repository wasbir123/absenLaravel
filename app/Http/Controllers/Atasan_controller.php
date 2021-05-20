<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\M_atasan;

class Atasan_controller extends Controller
{
    public function index(){
    	$title = 'Update Data';
    	$dt = M_atasan::first();

    	return view('other.index',compact('title','dt'));
    }

    public function update(Request $request){
    	try {
    		$a['daerah'] = $request->daerah;
    		$a['atasan'] = $request->atasan;
    		$a['kantor'] = $request->kantor;
    		$a['updated_at'] = date('Y-m-d H:i:s');

    		\DB::table('m_atasan')->update($a);

    		\Session::flash('sukses','Data berhasil disimpan');
    	} catch (\Exception $e) {
    		\Session::flash('gagal',$e->getMessage());
    	}

    	return redirect()->back();
    }
}
