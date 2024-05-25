<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Member;
use App\Models\Pembelian;
use App\Models\Pengeluaran;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\Supplier;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
{
    $kategori = Kategori::count();
    $produk = Produk::count();
    $supplier = Supplier::count();
    $member = Member::count();

    // Mendapatkan tanggal awal dan akhir bulan ini
    $tanggal_awal = date('Y-m-01');
    $tanggal_akhir = date('Y-m-t');

    // Menghitung penjualan, pengeluaran, dan pembelian untuk bulan ini
    $penjualan = Penjualan::whereBetween('created_at', [$tanggal_awal, $tanggal_akhir])->sum('diterima');
    $pengeluaran = Pengeluaran::whereBetween('created_at', [$tanggal_awal, $tanggal_akhir])->sum('nominal');
    $pembelian = Pembelian::whereBetween('created_at', [$tanggal_awal, $tanggal_akhir])->sum('bayar');

    $data_tanggal = array();
    $data_pendapatan = array();

    // Mengisi data tanggal dan pendapatan
    while (strtotime($tanggal_awal) <= strtotime($tanggal_akhir)) {
        $data_tanggal[] = (int) substr($tanggal_awal, 8, 2);

        $total_penjualan = Penjualan::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('bayar');
        $total_pembelian = Pembelian::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('bayar');
        $total_pengeluaran = Pengeluaran::where('created_at', 'LIKE', "%$tanggal_awal%")->sum('nominal');

        $pendapatan = $total_penjualan - $total_pembelian - $total_pengeluaran;
        $data_pendapatan[] += $pendapatan;

        $tanggal_awal = date('Y-m-d', strtotime("+1 day", strtotime($tanggal_awal)));
    }

    // Kembalikan tampilan berdasarkan level pengguna
    if (auth()->user()->level == 1) {
        return view('admin.dashboard', compact('kategori', 'produk', 'supplier', 'member', 'penjualan', 'pengeluaran', 'pembelian', 'tanggal_awal', 'tanggal_akhir', 'data_tanggal', 'data_pendapatan'));
    } else {
        return view('kasir.dashboard');
    }
}
}