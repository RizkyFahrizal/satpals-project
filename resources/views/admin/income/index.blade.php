{{-- DEPRECATED: This view is no longer used. Use /admin/financial instead --}}
@extends('layouts.admin')

@section('title', 'Index Pemasukan - Deprecated')

@section('content')
<div class="alert alert-warning">
    <h2>Halaman ini sudah tidak digunakan</h2>
    <p>Silakan gunakan <a href="{{ route('admin.financial.index') }}">Kelola Keuangan</a> untuk mengelola semua transaksi</p>
</div>
@endsection
