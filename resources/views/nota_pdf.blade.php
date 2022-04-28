<!DOCTYPE html>
<html>
<head>
	<title>Nota Transaksi Atma Jaya Rental</title>
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
		<h5>Nota Transaksi</h5>
        <h4>Atma Jaya Rental</h4>
	</center>
       
    <p><b>Nota Transaksi Sewa Mobil</b></p>
	<table class='table table-bordered'>   
		<thead>
			<tr>
                <th><center><b>Atma Rental</b></center></th>
				
			</tr>
		</thead>
		<tbody>
			@php $i=1 @endphp
			@foreach($data as $p)
			<tr>
                <td>{{$p->id_transaksi}}<br>
                {{$p->created_at}}
                <center>NOTA TRANSAKSI</center>
                <br>
                CUST = {{$p->nama_customer}}<br>
                CS = {{$p->nama_pegawai}}<br>
                DRV = {{$p->nama_driver}}<br>
                PRO = {{$p->kode_promo}}<br>
                <br>
                Tanggal Pinjam: {{$p->tgl_pinjam}}
                <br>
                Tanggal Kembali: {{$p->tgl_kembali}}
                <br>
                Tanggal Pengembalian: {{$p->tgl_selesai_pinjam}}
                <br>
                <br>
                Item - Satuan - Subtotal
                <br>
                {{$p->nama_mobil}} - {{$p->biaya_sewa}} - {{$p->total_biaya_pinjam}}
                <br>
                Driver {{$p->nama_driver}} - {{$p->biaya_sewa_driver}} - {{$p->total_sewa_driver}}
                <br>
                ====================================================== (+)
                <br>
                <br>
                Disc {{$p->jumlah_diskon}}<br>
                Denda {{$p->total_denda}}<br>
                Total {{$p->subtotal_all}}
                </td>
			</tr>
			@endforeach
		</tbody>
	</table>

</body>
</html>