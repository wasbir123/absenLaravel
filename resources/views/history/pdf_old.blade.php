<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

	<table class="table">
					<thead>
						<tr>
							<th colspan="4">{{ $title }}</th>
						</tr>
						<tr>
							<th rowspan="2">Karyawan</th>
							@foreach($tanggals as $tg)
							<th colspan="4">
								<center>{{ date('d',strtotime($tg)) }}</center>
							</th>
							@endforeach
						</tr>
						<tr>
							@foreach($tanggals as $e=>$tg)
							<th>Masuk</th>
							<th>Keluar</th>
							<th>Total Jam</th>
							<th>Keterangan</th>
							@endforeach
						</tr>
					</thead>
					<tbody>
						@foreach($karyawan as $kr)
						<tr>
							<td>{{ $kr->name }}</td>

							@foreach($tanggals as $tg)
							<?php
								$absen = \App\Models\Absen::whereDate('tanggal',$tg)->where('user',$kr->id)->first();
							?>
							@if(\App\Models\Absen::where('tanggal',$tg)->where('user',$kr->id)->count() > 0)

								<td>{{ date('H:i',strtotime($absen->masuks->created_at)) }}</td>

								@if(isset($absen->pulangs->created_at))

									<td>{{ date('H:i',strtotime($absen->pulangs->created_at)) }}</td>

								<?php
									$jam_masuk = date('H:i',strtotime($absen->masuks->created_at));
									$jam_pulang = date('H:i',strtotime($absen->pulangs->created_at));

									$selisih = date_diff(date_create($jam_masuk),date_create($jam_pulang));
								?>

								<td>{{ $selisih->h }} Jam {{ $selisih->i }} Menit</td>
								<td>{{ $absen->masuks->keterangan }} - {{ $absen->pulangs->keterangan }}</td>
							@else
							<td></td>
							<td></td>
							<td></td>
							@endif
							@else
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							@endif
							@endforeach
						</tr>
						@endforeach
					</tbody>
				</table>

</body>
</html>