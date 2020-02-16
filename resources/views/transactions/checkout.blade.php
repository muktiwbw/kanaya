@extends('layouts.default')

@section('title', 'Checkout')

@section('default-content')
<div class="row">
    <div class="col-12">
        <h1><i class="fa fa-credit-card" aria-hidden="true"></i> Checkout</h1>
        <hr>
    </div>
    @if($transaction->status >= 2)
    <div class="col-12">
        <h5>Status</h5>
        <p>{{explode('|', $transaction->notes)[1]}}</p>
    </div>
    <div class="col-12">
        <h5>Pengambilan</h5>
        <p>{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', explode('|', $transaction->notes)[0])->isoFormat('D MMMM Y')}}</p>
    </div>
    <div class="col-12">
        <h5>Pengembalian</h5>
        <p>{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', explode('|', $transaction->notes)[0])->add(2, 'day')->isoFormat('D MMMM Y')}}</p>
    </div>
    @endif
    @if(!$transaction->receipt)
    <div class="col-12">
        <h4>Petunjuk</h4>
        <p>Untuk pembayaran, transfer ke nomor rekening yang tertera.<br>Jika pembayaran telah berhasil dilakukan, mohon untuk mengunggah foto bukti pembayaran pada menu di bawah ini. Bukti pembayaran kemudian akan dicek oleh admin Kanaya Kebaya untuk dikonfirmasi.</p>
    </div>
    <div class="col-12">
        <h5>Rekening Bank</h5>
        <p>(BCA) 909898789098</p>
        <h5>Atas Nama</h5>
        <p>Kanaya Kebaya</p>
        <h5>Total Pembayaran</h5>
        <p>Rp {{number_format($transaction->transactionDetails->sum('total'))}}</p>
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
        <h5>Pembayaran anda sedang diproses</h5>
    </div>
    @endif
</div>
@endsection