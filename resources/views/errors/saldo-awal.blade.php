@extends('layouts.main')

@section('contents')
<div class="error-page">
    {{-- <h2 class="headline text-warning"> 422</h2> --}}

    <div class="error-content">
        <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Saldo awal belum diisi</h3>

        <p>
           Segera lakukan input saldo awal untuk menggunakan aplikasi ini, pergi ke halaman <a href="{{ route('saldo.main') }}">Saldo</a>
        </p>

    </div>
    <!-- /.error-content -->
</div>
@endsection
