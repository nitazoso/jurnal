@extends('layouts.piket')

@section('title', 'Detail Dispen - Jurnify')
@section('page-title', 'Detail Dispen')

@section('content')
<div class="mx-auto max-w-5xl space-y-5 px-4 py-6">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div><p class="text-sm text-slate-500">Rincian permohonan dispensasi</p><h1 class="text-2xl font-bold text-slate-800">Detail Dispen</h1></div>
        <a href="{{ route('piket.dispen.index') }}" class="rounded-lg bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700">Kembali</a>
    </div>
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        @include('piket.dispen.partials.detail-content')
    </section>
</div>
@endsection
