<!DOCTYPE html>
<html>
<head>
	<title>Laporan Top Transaksi by Driver AJR</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
	<style type="text/css">
		table tr td,
		table tr th{
			font-size: 9pt;
		}
	</style>
	<center>
		<h5>Laporan Top Transaksi by Driver AJR</h4>
	</center>

	<table class='table table-bordered'>
		<thead>
			<tr>
				<th>No</th>
				<th>ID Driver</th>
				<th>Nama Driver</th>
				<th>Jumlah Transaksi</th>
			</tr>
		</thead>
		<tbody>
			@php $i=1 @endphp
			@foreach($data as $p)
			<tr>
				<td>{{ $i++ }}</td>
				<td>{{$p->id_driver}}</td>
				<td>{{$p->nama_driver}}</td>
				<td>{{$p->jumlah_transaksi}}</td>
			</tr>
			@endforeach
		</tbody>
	</table>

</body>
</html>