<?php

use Illuminate\Support\Facades\Auth;


use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	return redirect('beranda');
});

Route::group(['middleware' => 'auth'], function () {

	Route::get('beranda', function () {
		return redirect('absen');
	});

	// absensi
	Route::get('absen', 'Absen_controller@index');
	Route::post('store/masuk', 'Absen_controller@masuk');
	Route::post('store/pulang', 'Absen_controller@pulang');

	// history absen
	Route::get('absen/history', 'Absen_controller@history');
	Route::get('absen/history/yajra', 'Absen_controller@yajra');
	Route::get('absen/history/yajra/{id}', 'Absen_controller@yajra_detail');

	Route::get('absen/history/periode', 'Absen_controller@periode');
	Route::get('absen/history/periode/yajra/{awal}/{akhir}', 'Absen_controller@yajra_periode');

	Route::post('absen/excel', 'Absen_controller@excel');
	Route::post('absen/pdf', 'Absen_controller@pdf');

	// master karyawan
	Route::get('karyawan', 'Karyawan_controller@index');
	Route::get('karyawan/yajra', 'Karyawan_controller@yajra');

	Route::get('karyawan/add', 'Karyawan_controller@add');
	Route::post('karyawan/add', 'Karyawan_controller@store');

	Route::get('karyawan/{id}', 'Karyawan_controller@edit');
	Route::put('karyawan/{id}', 'Karyawan_controller@update');

	Route::delete('karyawan/{id}', 'Karyawan_controller@delete');

	// master jam
	Route::get('jam', 'Jam_controller@index');
	Route::put('jam', 'Jam_controller@update');

	Route::get('izin', 'Izin_controller@index');
	Route::post('izin', 'Izin_controller@store');

	// profile
	Route::get('profile', 'Profile_controller@index');
	Route::put('profile', 'Profile_controller@update');

	// m_atasan
	Route::get('atasan', 'Atasan_controller@index');
	Route::put('atasan', 'Atasan_controller@update');
});

Auth::routes();

Route::get('/home', function () {
	return redirect('beranda');
});

Route::get('keluar', function () {
	Auth::logout();
	return redirect('login');
});
