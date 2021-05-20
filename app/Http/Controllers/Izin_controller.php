<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Absen;
use App\Models\Absen_masuk;
use App\Models\Absen_pulang;
use App\User;

class Izin_controller extends Controller
{
    public function index(){
    	$title = 'Izin tidak hadir';
        $user = User::orderBy('name','asc')->get();

    	return view('izin.index',compact('title','user'));
    }

    public function store(Request $request){
    	try {
            $cek = Absen::where('user',\Auth::user()->id)->where('tanggal',date('Y-m-d'))->count();
            if($cek > 0){
                Absen::where('user',\Auth::user()->id)->where('tanggal',date('Y-m-d'))->delete();
            }

            $user = $request->user;

    		$absen_id = \Uuid::generate(4);

    		$absen['id'] = $absen_id;
    		$absen['user'] = $user;
    		$absen['tanggal'] = date('Y-m-d',strtotime($request->tanggal));
    		$absen['keterangan'] = $request->alasan;
    		$absen['created_at'] = date('Y-m-d H:i:s');
    		$absen['updated_at'] = date('Y-m-d H:i:s');

    		$masuk['id'] = \Uuid::generate(4);
    		$masuk['absen'] = $absen_id;
    		$masuk['created_at'] = date('Y-m-d H:i:s');

    		$pulang['id'] = \Uuid::generate(4);
    		$pulang['absen'] = $absen_id;
    		$pulang['created_at'] = date('Y-m-d H:i:s');

    		\DB::transaction(function()use($absen,$masuk,$pulang){
                Absen::where('user',\Auth::user()->id)->where('tanggal',date('Y-m-d'))->whereNotNull('keterangan')->delete();

    			Absen::insert($absen);
	    		Absen_masuk::insert($masuk);
	    		Absen_pulang::insert($pulang);
    		});

    		\Session::flash('sukses','Data berhasil disimpan');

    	} catch (\Exception $e) {
    		\Session::flash('gagal',$e->getMessage());
    	}

    	return redirect()->back();
    }
}
