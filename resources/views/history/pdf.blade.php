<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
 
    <link rel="stylesheet" href="{{asset('adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
 
   
   
</head>
<body>
   
   <div class="container">
   	
   	<center>
   	<h3>LAPORAN ABSENSI : {{ date('d M Y',strtotime($awal)) }} s/d {{ date('d M Y',strtotime($akhir)) }}</h3>
   </center>
   <p>
   	<b><i>{{ $atasan->kantor }}</i></b>
   </p>
	<table class="{{ ($total_tanggal <= 25) ? 'table' : '' }}" border="1">
		<thead>
			<tr>
				<th>No</th>
				<th>Nama</th>
				@foreach($tanggals as $tg)
				<th>{{ date('d',strtotime($tg)) }}</th>
				@endforeach
			</tr>
		</thead>
		<tbody>
			@foreach($karyawan as $e=>$kr)
			<tr>
				<td>{{ $e+1 }}</td>
				<td>{{ $kr->name }}</td>

				@foreach($tanggals as $tg)
				<?php
					$absen = \App\Models\Absen::whereDate('tanggal',$tg)->where('user',$kr->id)->first();
				?>
				@if(\App\Models\Absen::where('tanggal',$tg)->where('user',$kr->id)->count() > 0)

					<td>
						@if($absen->keterangan != null)
						<p>{{ $absen->keterangan }}</p>
						@else
							<p>
								{{ date('H:i',strtotime($absen->masuks->created_at)) }}
								<img src="{{ $absen->masuks->photo }}" style="width: 100px;">
							</p>

							@if(isset($absen->pulangs->created_at))
							<p>
								{{ date('H:i',strtotime($absen->pulangs->created_at)) }}
								<img src="{{ $absen->pulangs->photo }}" style="width: 100px;">
							</p>

							<?php
								$jam_masuk = date('H:i',strtotime($absen->masuks->created_at));
								$jam_pulang = date('H:i',strtotime($absen->pulangs->created_at));

								$selisih = date_diff(date_create($jam_masuk),date_create($jam_pulang));
							?>

							<p>
								@if($absen->masuks->keterangan == null)
								HNM
								@else
								{{ $absen->masuks->keterangan }}
								@endif
							</p>
							<p>
								@if($absen->pulangs->keterangan == null)
								HNP
								@else
								{{ $absen->pulangs->keterangan }}
								@endif
							</p>
							@else
							
							@endif
						@endif
					</td>
				@else
				<td>
					<p>TK</p>
				</td>
				@endif
				@endforeach
			</tr>
			@endforeach
		</tbody>
	</table>
	<hr>
	<div class="row">
		<div class="col-xs-4">
			<table class="">
				<tbody>
					<tr>
						<td>HNM:</td>
						<td>Hadir Normal Masuk</td>
					</tr>
					<tr>
						<td>HNP:</td>
						<td>Hadir Normal Pulang</td>
					</tr>
					<tr>
						<td>TM:</td>
						<td>Terlambat Masuk</td>
					</tr>
					<tr>
						<td>CP:</td>
						<td>Cepat Pulang</td>
					</tr>
					<tr>
						<td>I:</td>
						<td>Izin</td>
					</tr>
					<tr>
						<td>C:</td>
						<td>Cuti</td>
					</tr>
					<tr>
						<td>S:</td>
						<td>Sakit</td>
					</tr>
					<tr>
						<td>DL:</td>
						<td>Dinas Lapangan</td>
					</tr>
					<tr>
						<td>TK:</td>
						<td>Tanpa Keterangan</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="col-xs-4 col-xs-offset-4">
			<table>
				<tbody>
					<tr>
						<td>
							<center>
								{{ $atasan->daerah }}, {{ date('d-m-Y') }}
							</center>
						</td>
					</tr>
					<tr>
						<td>
							<center>
								Atasan
							</center>
						</td>
					</tr>
					<tr>
						<td></td>
					</tr>
					<tr>
						<td></td>
					</tr>
					<tr>
						<td>
							<center>
								<br>
								<br>
								<br>
								---------------
								<p>
									{{ $atasan->atasan }}
								</p>
							</center>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

   </div>

</body>
</html>