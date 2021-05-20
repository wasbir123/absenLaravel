<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class Profile_controller extends Controller
{
    public function index(){
    	$title = 'Edit Profile';
    	$dt = User::where('id',\Auth::user()->id)->first();

    	return view('profile.index',compact('title','dt'));
    }

    public function update(Request $request){
    	try {
    		User::where('id',\Auth::user()->id)->update([
    			'name'=>$request->name,
    			'email'=>$request->email,
    			'password'=>bcrypt($request->password)
    		]);

    		\Session::flash('sukses','Data berhasil diupdate');
    	} catch (\Exception $e) {
    		\Session::flash('gagal',$e->getMessage());
    	}
    	return redirect()->back();
    }
}
