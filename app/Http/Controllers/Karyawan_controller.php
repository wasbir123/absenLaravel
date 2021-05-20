<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Datatables;

use App\User;

class Karyawan_controller extends Controller
{
	public function index(){
		$title = 'list karyawan';
		$yajra = url('karyawan/yajra');

		return view('karyawan.index',compact('title','yajra'));
	}

	public function add(){
		$title = 'tambah karyawan';

		return view('karyawan.add',compact('title'));
	}

	public function store(Request $request){
		try {
			$a = $request->except(['_token','_method']);
			$a['created_at'] = date('Y-m-d H:i:s');
			$a['updated_at'] = date('Y-m-d H:i:s');
			$a['password'] = bcrypt('123');

			User::insert($a);

			\Session::flash('sukses','karyawan berhasil ditambah');
			return redirect('karyawan');
		} catch (\Exception $e) {
			\Session::flash('gagal',$e->getMessage());
			return redirect('karyawan/add');
		}
	}

	public function edit($id){
		$title = 'edit karyawan';
		$dt = User::where('id',$id)->first();

		return view('karyawan.edit',compact('title','dt'));
	}

	public function update(Request $request,$id){
		try {
			$a = $request->except(['_token','_method']);
			// $a['created_at'] = date('Y-m-d H:i:s');
			$a['updated_at'] = date('Y-m-d H:i:s');
			// $a['password'] = bcrypt('123');

			User::where('id',$id)->update($a);

			\Session::flash('sukses','karyawan berhasil di update');
			return redirect('karyawan');
		} catch (\Exception $e) {
			\Session::flash('gagal',$e->getMessage());
			return redirect('karyawan/'.$id);
		}
	}

	public function delete($id){
		try {
			User::where('id',$id)->delete();
			\Session::flash('sukses','data berhasil dihapus');
		} catch (\Exception $e) {
			\Session::flash('gagal','data sudah linked, tidak dapat dihapus');
		}

		return redirect('karyawan');
	}

	public function yajra(Request $request)
	{
		DB::statement(DB::raw('set @rownum=0'));
		$users = User::select([
			DB::raw('@rownum  := @rownum  + 1 AS rownum'),
			'id',
			'name',
			'email',
			'created_at',
			'updated_at'])->whereNull('level');
		$datatables = Datatables::of($users)->addColumn('action',function($e){
			$url = url('karyawan/'.$e->id);

			$dt = '<div style="width:60px"><a href="'.$url.'" class="btn btn-warning btn-xs btn-edit" id="edit"><i class="fa fa-pencil-square-o"></i></a> <button href="'.$url.'" class="btn btn-danger btn-hapus btn-xs" id="delete"><i class="fa fa-trash-o"></i></button></div>';
			return $dt;
		});

		if ($keyword = $request->get('search')['value']) {
			$datatables->filterColumn('rownum', 'whereRaw', '@rownum  + 1 like ?', ["%{$keyword}%"]);
		}

		return $datatables->make(true);
	}
}
