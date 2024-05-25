@extends('layouts.master')

@section('title')
    Dashboard
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Dashboard</li>
@endsection

@section('content')
<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-teal">
            <div class="inner">
                <h3>{{ $kategori }}</h3>

               <p style="font-size: larger; font-weight: bold; margin: 0;">Total Kategori</p>
            </div>
            <style>
                .icon i.fa {
                    font-size: 60px; /* Ubah ukuran ikon sesuai kebutuhan */
                }
            </style>
            
            <div class="icon">
                <i class="fa fa-cube"></i>
            </div>
            <a href="{{ route('kategori.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col --><!-- visit "codeastro" for more projects! -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>{{ $produk }}</h3>

               <p style="font-size: larger; font-weight: bold; margin: 0;">Total Produk</p>
            </div>
            <div class="icon">
                <i class="fa fa-cubes"></i>
            </div>
            <a href="{{ route('produk.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
         <div class="small-box bg-yellow">
            <div class="inner">
                <h3>{{ $member }}</h3>

               <p style="font-size: larger; font-weight: bold; margin: 0;">Total Member</p>
            </div>
            <div class="icon">
                <i class="fa fa-id-card"></i>
            </div>
            <a href="{{ route('member.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
        </div> 
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-blue">
            <div class="inner">
                <h3>{{ $supplier }}</h3>

                <p style="font-size: larger; font-weight: bold; margin: 0;">Total Supplier</p>
            </div>
            <div class="icon">
                <i class="fa fa-truck"></i>
            </div>
            <a href="{{ route('supplier.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
</div>
<!-- /.row -->

<h4 id="laporan-bulan" style="margin-top: 0;">Laporan Bulan</h4>
<div class="row">
<div class="col-lg-4 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3>Rp {{ number_format($penjualan, 0, ',', '.') }}</h3>

                <p style="font-size: larger; font-weight: bold; margin: 0;">Penjualan</p>
                <p style="margin: 0;">( Total Pendapatan )</p>
    
            </div>
            <div class="icon">
                <i class="fa fa-dollar"></i>
            </div>
            <a href="{{ route('penjualan.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->

    <div class="col-lg-4 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-orange">
            <div class="inner">
                <h3>Rp {{ number_format($pengeluaran, 0, ',', '.') }}</h3>

                <p style="font-size: larger; font-weight: bold; margin: 0;">Pengeluaran</p>
                 <p style="margin: 0;">( Total Biaya Oprasional )</p>
            </div>
            <div class="icon">
                <i class="fa fa-dollar"></i>
            </div>
            <a href="{{ route('pengeluaran.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    
    <div class="col-lg-4 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3>Rp {{ number_format($pembelian, 0, ',', '.') }}</h3>

                <p style="font-size: larger; font-weight: bold; margin: 0;">Pembelian</p>
                 <p style="margin: 0;">( Total Biaya Restock )</p>
            </div>
            <div class="icon">
                <i class="fa fa-dollar"></i>
            </div>
            <a href="{{ route('pembelian.index') }}" class="small-box-footer">Lihat <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->

   
</div>
<!-- Main row -->
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Grafik Pendapatan {{ tanggal_indonesia($tanggal_awal, false) }} - {{ tanggal_indonesia($tanggal_akhir, false) }}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="chart">
                            <!-- Sales Chart Canvas -->
                            <canvas id="salesChart" style="height: 280px;"></canvas>
                        </div>
                        <!-- /.chart-responsive -->
                    </div>
                </div>
                <!-- /.row -->
            </div>
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row (main row) -->
@endsection

@push('scripts')
<!-- ChartJS -->
<script src="{{ asset('AdminLTE-2/bower_components/chart.js/Chart.js') }}"></script>
<script>
$(function() {
    // Get context with jQuery - using jQuery's .get() method.
    var salesChartCanvas = $('#salesChart').get(0).getContext('2d');
    // This will get the first returned node in the jQuery collection.
    var salesChart = new Chart(salesChartCanvas);

    var salesChartData = {
        labels: {{ json_encode($data_tanggal) }},
        datasets: [
            {
                label: 'Pendapatan',
                fillColor           : 'rgba(60,141,188,0.9)',
                strokeColor         : 'rgba(60,141,188,0.8)',
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(60,141,188,1)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',
                data: {{ json_encode($data_pendapatan) }}
            }
        ]
    };

    var salesChartOptions = {
        pointDot : false,
        responsive : true
    };

    salesChart.Line(salesChartData, salesChartOptions);
});
</script>
<script>
    // Mendapatkan tanggal saat ini
    var tanggalSekarang = new Date();

    // Mendapatkan bulan saat ini
    var bulanSekarang = tanggalSekarang.toLocaleString('default', { month: 'long' });

    // Menampilkan bulan saat ini di dalam elemen dengan id "laporan-bulan"
    document.getElementById('laporan-bulan').innerHTML = 'Laporan Bulan ' + bulanSekarang;
</script>
@endpush