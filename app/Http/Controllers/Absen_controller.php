<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Datatables;

use App\Models\Absen;
use App\Models\Absen_masuk;
use App\Models\Absen_pulang;
use App\Models\M_jam;
use App\User;
use App\Models\M_atasan;

class Absen_controller extends Controller
{
    public function index(){
    	$title = 'absensi';

    	return view('absen.index',compact('title'));
    }

    public function masuk(Request $request){
    	try {
            $user_ip = getenv('REMOTE_ADDR');
            $geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$user_ip"));
            $country = $geo["geoplugin_countryName"];
            $city = $geo["geoplugin_city"];
            // dd($city);

            $jam = M_jam::first();
            $jam_masuk = date('H:i',strtotime($jam->jam_masuk));
            $jam_pulang = date('H:i',strtotime($jam->jam_pulang));

    		$id = \Uuid::generate(4);

    		$a['id'] = $id;
    		$a['user'] = \Auth::user()->id;
    		$a['tanggal'] = date('Y-m-d');
    		$a['created_at'] = date('Y-m-d H:i:s');
    		$a['updated_at'] = date('Y-m-d H:i:s');
            // $a['location'] = $city;

    		// cek photo
    		$img = $request->image;
            $image_base = $img;
            if($img){
            	$b['photo'] = $image_base;
            }

            $jam_absen_masuk = date('Y-m-d H:i:s');

            if(date('H:i',strtotime($jam_absen_masuk)) > date('H:i',strtotime($jam_masuk))){
                // dd('telat');
                $diff = date_diff(date_create(date('H:i',strtotime($jam_masuk))),date_create(date('H:i',strtotime($jam_absen_masuk))));
                // $keterangan = "Telat $diff->h Jam $diff->i Menit";
                $keterangan = "TM";
                $b['keterangan'] = $keterangan;
                // dd($keterangan);
            }else{
                $b['keterangan'] = null;
            }

    		$b['id'] = \Uuid::generate(4);
    		$b['absen'] = $id;
    		$b['created_at'] = $jam_absen_masuk;
    		$b['updated_at'] = date('Y-m-d H:i:s');

    		// cek
    		$cek = Absen::where('user',\Auth::user()->id)->where('tanggal',date('Y-m-d'))->count();
    		if($cek > 0){
    			\Session::flash('gagal','kamu hari ini sudah pernah absen masuk');
    		}else{

    			\DB::transaction(function()use($a,$b){
    				Absen::insert($a);
    				Absen_masuk::insert($b);
    			});

    			\Session::flash('sukses','absen masuk berhasil');

    		}
    	} catch (\Exception $e) {
    		\Session::flash('gagal',$e->getMessage());
    	}

    	return redirect('absen');
    }

    public function pulang(Request $request){
        try {
            $jam = M_jam::first();
            $jam_masuk = date('H:i',strtotime($jam->jam_masuk));
            $jam_pulang = date('H:i',strtotime($jam->jam_pulang));

            // cek apakah sudah absen pulang
            $is_absen = Absen::where('user',\Auth::user()->id)->where('tanggal',date('Y-m-d'))->whereHas('pulangs')->count();

            if($is_absen > 0){
                \Session::flash('gagal','kamu hari ini sudah pernah absen pulang');

                return redirect('absen');
            }

            // cek apakah sudah absen masuk
            $is_masuk = Absen::where('user',\Auth::user()->id)->where('tanggal',date('Y-m-d'))->count();

            if($is_masuk == 0){
                \Session::flash('gagal','harap absen masuk terlebih dahulu');

                return redirect('absen');
            }

            // insert data
            $dt = Absen::where('user',\Auth::user()->id)->where('tanggal',date('Y-m-d'))->first();

            // cek photo
            $img = $request->image;
            $image_base = $img;
            if($img){
                $b['photo'] = $image_base;
            }

            $jam_absen_pulang = date('Y-m-d H:i:s');

            if(date('H:i',strtotime($jam_absen_pulang)) < date('H:i',strtotime($jam_pulang))){
                $diff = date_diff(date_create(date('H:i',strtotime($jam_absen_pulang))),date_create(date('H:i',strtotime($jam_pulang))));
                // dd($diff);
                // $keterangan = "Pulang lebih awal $diff->h Jam $diff->i Menit";
                $keterangan = 'CP';
                // dd($keterangan);
                $b['keterangan'] = $keterangan;
            }

            $user_ip = getenv('REMOTE_ADDR');
            $geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$user_ip"));
            $country = $geo["geoplugin_countryName"];
            $city = $geo["geoplugin_city"];

            $b['id'] = \Uuid::generate(4);
            $b['absen'] = $dt->id;
            $b['created_at'] = $jam_absen_pulang;
            $b['updated_at'] = date('Y-m-d H:i:s');
            // $a['location'] = $city;

            Absen_pulang::insert($b);

            \Session::flash('sukses','absen pulang berhasil');

            return redirect('absen');

        } catch (\Exception $e) {
            \Session::flash('gagal',$e->getMessage());

            return redirect('absen');
        }
    }

    public function history(){
        $title = 'history absen';
        $yajra = url('absen/history/yajra');
        $awal = date('Y-m-d',strtotime('-1 days'));
        $akhir = date('Y-m-d');

        return view('history.index',compact('title','yajra','awal','akhir'));
    }

    public function pdf(Request $request){
        try {
            $awal = $request->awal;
            $akhir = $request->akhir;

            $title = "Laporan Absensi Periode $request->awal s/d $request->akhir";

            $tanggal1 = date('Y-m-d',strtotime($request->awal));
            $tanggal2 = date('Y-m-d',strtotime($request->akhir));
            $tanggals = [];
         
            while ($tanggal1 <= $tanggal2) {
                array_push($tanggals, $tanggal1);
         
                $tanggal1 = date('Y-m-d',strtotime('+1 days',strtotime($tanggal1)));
            }

            $total_tanggal = count($tanggals);
            // dd($tanggals);
            $karyawan = User::whereHas('absens')->orderBy('name','asc')->get();

            $atasan = M_atasan::first();
 
            $pdf = \PDF::loadview('history.pdf',compact('title','tanggals','karyawan','awal','akhir','total_tanggal','atasan'))->setPaper('a4', 'landscape');
            return $pdf->stream();
 
        } catch (\Exception $e) {
            \Session::flash('gagal',$e->getMessage().' ! '.$e->getLine());
        }
 
        return redirect()->back();
    }

    public function excel(Request $request){
        $awal = $request->awal;
        $akhir = $request->akhir;

        $title = "Laporan Absensi Periode $request->awal s/d $request->akhir";

        $tanggal1 = date('Y-m-d',strtotime($request->awal));
        $tanggal2 = date('Y-m-d',strtotime($request->akhir));
        $tanggals = [];
     
        while ($tanggal1 <= $tanggal2) {
            array_push($tanggals, $tanggal1);
     
            $tanggal1 = date('Y-m-d',strtotime('+1 days',strtotime($tanggal1)));
        }

        $total_tanggal = count($tanggals);
        // dd($tanggals);
        $karyawan = User::whereHas('absens')->orderBy('name','asc')->get();

        \Excel::create($title, function($excel) use($title,$tanggals,$karyawan,$awal,$akhir,$total_tanggal) {
            $excel->sheet('Sheetname', function($sheet) use($title,$tanggals,$karyawan,$awal,$akhir,$total_tanggal) {
 
                $sheet->loadView('history.excel')->with('tanggals',$tanggals)->with('karyawan',$karyawan)->with('title',$title)->with('total_tanggal',$total_tanggal);
 
            });
        })->export('xls');
    }

    public function periode(Request $request){
        $title = 'history absen';

        $awal = date('Y-m-d',strtotime($request->awal));
        $akhir = date('Y-m-d',strtotime($request->akhir));

        $yajra = url('absen/history/periode/yajra/'.$awal.'/'.$akhir);

        return view('history.index',compact('title','yajra','awal','akhir'));
    }

    public function yajra_periode(Request $request,$awal,$akhir)
    {
        if(\Auth::user()->level == null){
            $data_absen = Absen::select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id',
            'user',
            'tanggal',
            'created_at',
            'updated_at'])->with([
                'users'
            ])->where('user',\Auth::user()->id)
            ->whereBetween('tanggal',[$awal,$akhir])
            ->orderBy('created_at','desc');
        }else{
            $data_absen = Absen::select([
                DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                'id',
                'user',
                'tanggal',
                'created_at',
                'updated_at'])->with([
                'users'
                ])
                ->whereBetween('tanggal',[$awal,$akhir])
                ->orderBy('created_at','desc');
        }

        DB::statement(DB::raw('set @rownum=0'));
        $users = $data_absen;

        $datatables = Datatables::of($users)->addColumn('details_url', function($user) {
            return url('absen/history/yajra/' . $user->id);
        })->editColumn('tanggal',function($e){
            return date('d M Y',strtotime($e->tanggal));
        });

        if ($keyword = $request->get('search')['value']) {
            $datatables->filterColumn('rownum', 'whereRaw', '@rownum  + 1 like ?', ["%{$keyword}%"]);
        }

        return $datatables->make(true);
    }

    public function yajra(Request $request)
    {
        if(\Auth::user()->level == null){
            $data_absen = Absen::select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id',
            'user',
            'tanggal',
            'created_at',
            'updated_at'])->with([
                'users'
            ])->where('user',\Auth::user()->id)->orderBy('created_at','desc');
        }else{
            $data_absen = Absen::select([
                DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                'id',
                'user',
                'tanggal',
                'created_at',
                'updated_at'])->with([
                'users'
                ])->orderBy('created_at','desc');
        }

        DB::statement(DB::raw('set @rownum=0'));
        $users = $data_absen;

        $datatables = Datatables::of($users)->addColumn('details_url', function($user) {
            return url('absen/history/yajra/' . $user->id);
        })->editColumn('tanggal',function($e){
            return date('d M Y',strtotime($e->tanggal));
        });

        if ($keyword = $request->get('search')['value']) {
            $datatables->filterColumn('rownum', 'whereRaw', '@rownum  + 1 like ?', ["%{$keyword}%"]);
        }

        return $datatables->make(true);
    }

    public function yajra_detail(Request $request,$id)
    {
        DB::statement(DB::raw('set @rownum=0'));
        $users = Absen::select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'absen.id',
            'absen.user',
            'absen.tanggal',
            'absen.keterangan',
            'absen.created_at',
            'absen.updated_at'
            ])->with([
                'masuks',
                'pulangs',
                'users'
            ])->where('absen.id',$id);
        $datatables = Datatables::of($users)->editColumn('masuks.created_at',function($e){
            if($e->keterangan != null){
                return $e->keterangan;
            }

            $dt = $e->masuks->created_at;
            $dt = date('H:i',strtotime($dt));

            return $dt;
        })->addColumn('photo_masuk',function($e){
            $dt = $e->masuks->photo;
            $dt = '<img src="'.$dt.'" style="width:100px;">';

            return $dt;
        })->editColumn('pulangs.created_at',function($e)use($id){
            $cek = Absen_pulang::where('absen',$id)->count();
            if($e->keterangan != null){
                return $e->keterangan;
            }

            if($cek > 0){
                $dt = $e->pulangs->created_at;
                $dt = date('H:i',strtotime($dt));

            }else{
                $dt = null;
            }

            return $dt;
        })->addColumn('photo_pulang',function($e)use($id){
            $cek = Absen_pulang::where('absen',$id)->count();

            if($cek > 0){
                $dt = $e->pulangs->photo;
                $dt = '<img src="'.$dt.'" style="width:100px;">';
            }else{
                $dt = null;
            }

            return $dt;
        })->addColumn('total',function($e)use($id){
            $cek = Absen_pulang::where('absen',$id)->count();
            if($e->keterangan != null){
                return $e->keterangan;
            }

            if($cek > 0){
                $masuk = date('H:i',strtotime($e->masuks->created_at));
                $pulang = date('H:i',strtotime($e->pulangs->created_at));

                $dt = date_diff(date_create($masuk),date_create($pulang));

                $dt = $dt->h.' jam '.$dt->i.' menit';
            }else{
                $dt = 0;
            }

            return $dt;
        })->rawColumns(['photo_masuk','photo_pulang']);

        if ($keyword = $request->get('search')['value']) {
            $datatables->filterColumn('rownum', 'whereRaw', '@rownum  + 1 like ?', ["%{$keyword}%"]);
        }

        return $datatables->make(true);
    }
}
