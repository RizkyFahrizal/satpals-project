@extends('layouts.admin')

@section('title', 'Detail Pemasukan - Admin')
@section('header', 'Detail Pemasukan')
@section('breadcrumb', 'Detail Pemasukan')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-6">
        <!-- Header -->
        <div class="mb-8 pb-6 border-b border-gray-200">
            <div class="flex flex-col lg:flex-row justify-between items-start gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ $income->title }}</h1>
                    <p class="text-gray-600 text-sm mt-1">Dibuat oleh: {{ $income->creator->name }}</p>
                </div>
                <span class="badge badge-lg badge-success">
                    Rp {{ number_format($income->nominal, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-2 gap-6 mb-8">
            <div>
                <p class="text-sm text-gray-600">Sumber Pemasukan</p>
                <p class="text-lg font-semibold text-gray-800">{{ $income->source ?? 'Tidak ada' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Tanggal Dibuat</p>
                <p class="text-lg font-semibold text-gray-800">{{ $income->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>

        <!-- Description -->
        @if($income->description)
            <div class="mb-8 pb-6 border-b border-gray-200">
                <p class="text-sm text-gray-600 mb-2">Deskripsi</p>
                <p class="text-gray-800 leading-relaxed whitespace-pre-wrap">{{ $income->description }}</p>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.income.edit', $income) }}" class="btn btn-sm btn-outline">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </a>
            <form action="{{ route('admin.income.destroy', $income) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-ghost text-error">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus
                </button>
            </form>
        </div>

        <!-- Back Button -->
        <div class="mt-6">
            <a href="{{ route('admin.income.index') }}" class="btn btn-outline">Kembali</a>
        </div>
    </div>
</div>
@endsection
