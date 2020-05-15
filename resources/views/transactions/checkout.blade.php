@extends('layouts.default')

@section('title', 'Checkout')

@section('default-content')
@page_title(['title' => 'Checkout'])@endpage_title
<div class="row">
    @if(!$transaction->receipt)
    <div class="col-12">
        <h4>Petunjuk</h4>
        <p>Untuk pembayaran, transfer ke nomor rekening yang tertera.<br>Jika pembayaran telah berhasil dilakukan, mohon untuk mengunggah foto bukti pembayaran pada menu di bawah ini. Bukti pembayaran kemudian akan dicek oleh admin Kanaya Kebaya untuk dikonfirmasi.</p>
    </div>
    <div class="col-12">
        <h5>Rekening Bank</h5>
        <p>(BCA) 82204430201</p>
        <h5>Atas Nama</h5>
        <p>NILA KRESNAWATI</p>
        <h5>Total Pembayaran</h5>
        <p>Rp {{number_format($transaction->products()->sum('price'))}}</p>
    </div>
    <div class="col-12">
        <form action="{{route('checkout-receipt')}}" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="image">Unggah Bukti Pembayaran</label>
                <input type="file" class="form-control" id="image" name="image">
            </div>
            <input class="btn btn-success btn-block" type="submit" name="submit" value="Unggah">
            @csrf
        </form>
    </div>
    @elseif($transaction->status < 2)
    <div class="col-12">
        <h4>Status</h4>
        <h5>Pembayaran anda sedang diproses. Silakan untuk menunggu beberapa saat.</h5>
    </div>
    @else
    <div class="col-12">
        <h5>Status</h5>
        <p>{{$transaction->notes}}</p>
    </div>
    <div class="col-12">
        <h5>Peminjaman</h5>
        <p>{{date('d F Y', strtotime($transaction->start_date))}}</p>
    </div>
    <div class="col-12">
        <h5>Pengembalian</h5>
        <p>{{date('d F Y', strtotime($transaction->end_date))}}</p>
    </div>
    @endif
</div>
@endsection