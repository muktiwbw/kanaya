@extends('layouts.default')

@section('title', 'Cart')

@section('extra-script')
<script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
<link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('default-content')
<!-- Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmationModal">Konfirmasi Peminjaman</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-4">
                <strong>Kode transaksi:</strong>
            </div>
            <div class="col-8 text-right">
                <span id="trx-id"></span>
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <strong>Tanggal pinjam:</strong>
            </div>
            <div class="col-8 text-right">
                <span id="trx-start-date"></span> - <span id="trx-end-date"></span>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <strong>Item:</strong>
            </div>
        </div>
        <div class="row" id="trx-item-list"></div>
        <div class="row">
            <div class="col-12">
                <hr>
            </div>
            <div class="col-6">
                <strong>Total:</strong>
            </div>
            <div class="col-6 text-right">
                Rp <span id="trx-total"></span>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <p class="text-danger">Mohon dicek kembali. Pesanan tidak akan dapat diubah kembali setelah mengklik tombol <strong>Lanjutkan.</strong></p>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
        <a href="{{route('cart-checkout')}}" type="button" class="btn btn-primary">Lanjutkan</a>
      </div>
    </div>
  </div>
</div>

<!-- Content -->
<div class="row">
    <div class="col-12">
        <h1><i class="fa fa-shopping-cart" aria-hidden="true"></i> My Cart</h1>
        <hr>
    </div>
</div>
@if($transaction && $transaction->transactionDetails()->count() > 0)
<div class="row pb-2">
    <div class="col-6">
        <form action="#" method="post"> 
            <div class="form-group">
                <label for="notes">Waktu Mulai</label>
                <input id="date-start" width="100%" value="{{$transaction->start_date ? date('m/d/Y', strtotime($transaction->start_date)) : ''}}"/>
            </div>
        </form>
    </div>
    <div class="col-6">
        <form action="#" method="post"> 
            <div class="form-group">
                <label for="notes">Waktu Selesai</label>
                <input id="date-end" width="100%" value="{{$transaction->end_date ? date('m/d/Y', strtotime($transaction->end_date)) : ''}}"/>
            </div>
        </form>
    </div>
</div>
<div class="row">
    <div class="col-12">
        @foreach($transaction->transactionDetails as $item)
        <div class="card mb-4 item-card" item-index="{{$loop->index}}" item-id="{{$item->id}}">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <h5>{{$item->product->name}}</h5>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-3 col-sm-6 col-6">
                                @if($item->product->images()->first())
                                <img class="mw-100" src="{{asset('img/'.$item->product->images()->first()->path)}}" alt="{{$item->product->name}}">
                                @endif
                            </div>
                            <div class="col-md-9 col-sm-6 col-6 text-right">
                                <div class="row text-left">
                                    <div class="col-12">
                                        <form action="#">
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Ukuran</label>
                                                <div class="col-sm-10">
                                                    <select class="form-control onchange-forms" item-field="size" item-index="{{$loop->index}}" item-id="{{$item->id}}">
                                                        <option @if($item->size == 'XS') selected @endif>XS</option>
                                                        <option @if($item->size == 'S') selected @endif>S</option>
                                                        <option @if($item->size == 'M') selected @endif>M</option>
                                                        <option @if($item->size == 'L') selected @endif>L</option>
                                                        <option @if($item->size == 'XL') selected @endif>XL</option>
                                                        <option @if($item->size == 'XXL') selected @endif>XXL</option>
                                                        <option @if($item->size == 'XXXL') selected @endif>XXXL</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-12">
                                        <form action="#" method="post"> 
                                            <div class="form-group row">
                                                <label for="notes" class="col-sm-2 col-form-label">Note</label>
                                                <div class="col-sm-10">
                                                    <textarea name="notes" class="form-control onchange-forms" id="notes" item-field="notes" item-index="{{$loop->index}}" item-id="{{$item->id}}">{{$item->notes}}</textarea>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 pt-2 pb-2">
                                        <div class="float-right">
                                            <form action="#" class="form-inline" method="post">
                                                <span class="mr-3">{{$item->product->available}} available</span>
                                                <input class="form-control mr-sm-2 onchange-forms" type="number" name="new_quantity" value="{{$item->quantity}}" min="0" max="{{$item->product->available}}" item-field="quantity" item-index="{{$loop->index}}" item-id="{{$item->id}}">
                                                <button type="button" class="btn btn-danger remove-item" item-id="{{$item->id}}"><i class="fa fa-trash-o" aria-hidden="true"></i> Remove</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <span item-quantity="{{$item->id}}">{{$item->quantity}}</span> x Rp <span item-price="{{$item->id}}">{{number_format($item->product->price)}}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        = Rp <span item-total="{{$item->id}}">{{number_format($item->quantity*$item->product->price)}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        <div class="row">
            <div class="col-6 text-left">
                <h5>Grand Total</h5>
            </div>
            <div class="col-6 text-right">
                <h5>Rp <span id="item-grand-total">{{number_format($transaction->transactionDetails()->sum('total'))}}</span></h5>
            </div>
        </div>
        <div class="row">
            <div class="col-12 pt-2 pb-2">
                <button type="button" id="checkout-button" class="btn btn-success btn-block"><h4><i class="fa fa-credit-card" aria-hidden="true"></i> Checkout</h4></button>
                <button id="modal-trigger" data-toggle="modal" data-target="#confirmationModal" style="display:none"></button>
            </div>
        </div>
    </div>
</div>
@else
<h2>Tidak ada item di keranjang</h2>
@endif

<script type="text/javascript">

    let startDate = $('#date-start').val();
    let endDate = $('#date-end').val();

    let items = Array.from($('.item-card'), item => {
            let item_size = document.querySelector(`[item-index="${item.getAttribute('item-index')}"][item-field="size"]`).value
            let item_notes = document.querySelector(`[item-index="${item.getAttribute('item-index')}"][item-field="notes"]`).value
            let item_quantity = document.querySelector(`[item-index="${item.getAttribute('item-index')}"][item-field="quantity"]`).value

            return {
                id: item.getAttribute('item-id'),
                size: item_size,
                notes: item_notes,
                quantity: item_quantity
            }
        }
    )

    let endDatePicker = $('#date-end').datepicker({
        uiLibrary: 'bootstrap4',
        modal: true
    });

    let startDatePicker = $('#date-start').datepicker({
        uiLibrary: 'bootstrap4',
        modal: true,
        minDate: new Date(),
        change: function(e){
            startDate = startDatePicker.value()

            endDate = new Date(startDate)
            endDate.setDate(endDate.getDate() + 3)

            endDay = endDate.getDate() > 9 ? endDate.getDate() : `0${endDate.getDate()}`
            endMonth = endDate.getMonth() + 1 > 9 ? endDate.getMonth() + 1 : `0${endDate.getMonth() + 1}`

            endDate = `${endMonth}/${endDay}/${endDate.getFullYear()}`

            endDatePicker.destroy()
            endDatePicker = $('#date-end').datepicker({
                uiLibrary: 'bootstrap4',
                minDate: startDate,
                maxDate: endDate,
                modal:true
            })
        }
    });

    // Event listeners
    $('form').submit(function(e){
        e.preventDefault()
    })

    $('.onchange-forms').change(function(){
        switch ($(this).attr('item-field')) {
            case 'size':
                items[$(this).attr('item-index')].size = $(this).val()
                break;
        
            case 'notes':
                items[$(this).attr('item-index')].notes = $(this).val()
                break;

            case 'quantity':
                items[$(this).attr('item-index')].quantity = $(this).val()
                break;
        }
    })

    // Remove Item
    $('.remove-item').click(function(e){
        e.preventDefault();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: `/cart/${$(this).attr('item-id')}`,
            type: 'DELETE',
            success: function(res){
                if(res.status == 200) {
                    location.reload()
                } else {
                    alert(res.message)
                }
            }
        })
    })

    // Background Submit
    $('#checkout-button').click(function(e){
        e.preventDefault();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '/cart',
            type: 'PATCH',
            data: {
                data: {
                    items: items,
                    dates: {
                        start: startDate,
                        end: endDate
                    }
                }
            },
            success: function (res){
                $('#trx-id').html(res.data.transaction.trans_no)

                let options = { year: 'numeric', month: 'long', day: 'numeric' }

                $('#trx-start-date').html(new Date(res.data.transaction.start_date).toLocaleDateString("id-ID", options))
                $('#trx-end-date').html(new Date(res.data.transaction.end_date).toLocaleDateString("id-ID", options))

                let itemContainer = $('#trx-item-list')

                itemContainer.html('')

                for(let i = 0; i<res.data.transactionDetails.length; i++){
                    let item = res.data.transactionDetails[i]
                    let itemElem = `
                        <div class="col-12 mb-2">
                            <div class="row">
                                <div class="col-6">
                                    ${i+1}. ${item.product.name}
                                </div>
                                <div class="col-6 text-right">
                                    ${item.quantity} x Rp ${new Intl.NumberFormat().format(item.product.price)}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 text-right">
                                    = Rp ${new Intl.NumberFormat().format(item.total)}
                                </div>
                            </div>
                        </div>
                    `

                    itemContainer.append(itemElem)
                    $(`span[item-quantity="${item.id}"]`).html(item.quantity)
                    $(`span[item-price="${item.id}"]`).html(new Intl.NumberFormat().format(item.product.price))
                    $(`span[item-total="${item.id}"]`).html(new Intl.NumberFormat().format(item.total))
                }
                
                let itemTotals = res.data.transactionDetails.map(item => item.total)
                let grandTotal = itemTotals.reduce((a, b) => a + b, 0)
                
                $('#trx-total').html(new Intl.NumberFormat().format(grandTotal))
                $('span#item-grand-total').html(new Intl.NumberFormat().format(grandTotal))

                $('#modal-trigger').trigger('click')
            }
        });

    })


</script>
@endsection