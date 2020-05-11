@extends('layouts.default')

@section('title', 'Cart')

@section('extra-script')
<script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
<link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
@if($transaction)
<meta name="expected-start-time" content="{{ $transaction->expected_start_time }}" />
@endif
@endsection

@section('default-content')
<!-- Confirmation Modal -->
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

<!-- Queue Modal -->
<div class="modal fade bd-example-modal-xl" id="queueModal" tabindex="-1" role="dialog" aria-labelledby="queueModal" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="queueModal">Antrean Item</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-12">
                <p>Waktu sewa akan disesuaikan dengan waktu paling terakhir agar dapat mencakup semua item</p>
            </div>
            <div id="queue-container" class="col-12"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
      </div>
    </div>
  </div>
</div>

<!-- Content -->
<div class="row">
    <div class="col-12">
        <div class="alert alert-warning permanent" role="alert">
            <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <strong>Peringatan!</strong> Keranjang belanja akan otomatis kosong dalam 2 jam jika tidak diteruskan ke proses checkout.
        </div>
        @if($errors->any())
        <div class="alert alert-danger" role="alert">
            <i class="fa fa-ban" aria-hidden="true"></i> <strong>Peringatan!</strong> Keranjang belanja telah melebihi batas maksimal 5 item.
        </div>
        @endif
    </div>
    <div class="col-12">
        <h1><i class="fa fa-shopping-cart" aria-hidden="true"></i> My Cart</h1>
        <hr>
    </div>
</div>
@if($transaction && $transaction->products()->count() > 0)
<div class="row pb-2">
    <div class="col-12">
        <div id="queue-alert" class="alert alert-danger" role="alert" @if($transaction->item_queue->count() <= 0) style="display:none" @endif>
            <h6><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <strong>Peringatan!</strong></h6>
            Terdapat item yang masih belum tersedia. Hal ini akan berdampak pada waktu mulai peminjaman.<br>
            <a id="queue-info" href="#">Klik untuk lebih lanjut</a>
        </div>
    </div>
    <div class="col-6">
        <form> 
            <div class="form-group">
                <label for="notes">Waktu Mulai</label>
                <input id="date-start" class="transaction-time" width="100%" value="{{$transaction->start_date ? date('m/d/Y', strtotime($transaction->start_date)) : ''}}"/>
            </div>
        </form>
    </div>
    <div class="col-6">
        <form> 
            <div class="form-group">
                <label for="notes">Waktu Selesai</label>
                <input id="date-end" class="transaction-time" width="100%" value="{{$transaction->end_date ? date('m/d/Y', strtotime($transaction->end_date)) : ''}}"/>
            </div>
        </form>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <!-- Products as item -->
        @foreach($transaction->products as $item)
        <div class="card mb-4 item-card" item-index="{{$loop->index}}" item-code="{{$item->code}}">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 notification-box" item-index="{{$loop->index}}"></div>
                </div>
                <div class="row">
                    <div class="col-6">
                        @if($item->url)
                            <img style="height:auto;max-width:400px;" src="{{asset('img/'.$item->url)}}" alt="{{$item->name}}">
                        @endif
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-12">
                                <a href="/product/{{$item->code}}"><h3><span class="item-name" item-index="{{$loop->index}}" data-value="{{$item->name}}">{{$item->name}}</span></h3></a>
                            </div>
                            <div class="col-12">
                                <span><strong>Ukuran dan jumlah</strong></span>
                                <hr class="mt-2">
                            </div>
                            @foreach($item->sizes as $size)
                            <div class="col-12 mb-2">
                                <div class="row">
                                    <div class="col-2"><h4>{{strtoupper($size->size)}}</h4></div>
                                    <div class="col-4 pr-0">
                                        <div class="row">
                                            <div class="col-12">
                                                <input class="form-control item-quantity form-onchange" type="number" min="0" @if($size->stock < 5) max="{{$size->stock}}" else max="5" @endif value="{{$size->quantity}}" item-index="{{$loop->parent->index}}" item-code="{{$item->code}}" item-size="{{$size->size}}">
                                            </div>
                                            <div class="col-12 mb-2">
                                                Tersedia: <strong><span class="item-available" item-index="{{$loop->index}}" data-stock-value="{{$size->stock}}" item-code="{{$item->code}}" item-size="{{$size->size}}">{{$size->stock}}</span></strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6"><a href="#" class="remove-item" item-code="{{$item->code}}" item-size="{{$size->size}}"><h3 class="text-danger"><i class="fa fa-trash" aria-hidden="true"></i></h3></a></div>
                                </div>
                            </div>
                            @endforeach
                            <div class="col-12 text-right"><a href="/product/{{$item->code}}">Tambahkan ukuran lain</a></div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-right">
                                <hr>
                                <h5><span class="item-quantity" item-index="{{$loop->index}}">{{$item->sizes->sum('quantity')}}</span> x RP <span class="item-price" item-index="{{$loop->index}}" data-value="{{$item->price}}">{{number_format($item->price)}}</span></h5>
                                <h5>= Rp <span class="item-total" item-index="{{$loop->index}}" data-value="{{$item->sizes->sum('quantity')*$item->price}}">{{number_format($item->sizes->sum('quantity')*$item->price)}}</span></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        <!-- Summarize -->
        <div class="row">
            <div class="col-6 text-left">
                <h5>Grand Total</h5>
            </div>
            <div class="col-6 text-right">
                <h5>Rp <span id="transaction-grand-total" data-value="">{{number_format($transaction->products->sum('total'))}}</span></h5>
            </div>
        </div>
        <div class="row">
            <div class="col-12 pt-2 pb-2">
                <button type="button" id="checkout-button" class="btn btn-success btn-block"><h4><i class="fa fa-credit-card" aria-hidden="true"></i> Checkout</h4></button>
                <button id="confirmation-modal-trigger" data-toggle="modal" data-target="#confirmationModal" style="display:none"></button>
                <button id="queue-modal-trigger" data-toggle="modal" data-target="#queueModal" style="display:none"></button>
            </div>
        </div>
    </div>
</div>
@else
<h2>Tidak ada item di keranjang</h2>
@endif

<script type="text/javascript">

    const itemPrices = Array.from($('.item-card'), (el) => {
        let elem = $(el)
        let itemIndex = elem.attr('item-index')
        let ItemPrice = $(`.item-price[item-index="${itemIndex}"]`).attr('data-value')

        return ItemPrice
    })

    // ======================================================================================================
    // Date Picker
    // ======================================================================================================
    let startDate = $('#date-start').val();
    let endDate = $('#date-end').val();
    let metaMinDate = $('meta[name="expected-start-time"]').attr('content') == '' ? new Date() : new Date($('meta[name="expected-start-time"]').attr('content'));

    let endDatePicker = $('#date-end').datepicker({
        uiLibrary: 'bootstrap4',
        modal: true
    });

    let startDatePicker = $('#date-start').datepicker({
        uiLibrary: 'bootstrap4',
        modal: true,
        minDate: metaMinDate,
        change: function(e){
            startDate = startDatePicker.value()

            endDate = new Date(startDate)
            endDate.setDate(endDate.getDate() + 2)

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

    // ======================================================================================================
    // Event listeners
    // ======================================================================================================

    // 1. Detect changing quantity numbers
    $('.item-quantity.form-onchange').change(function(){
        let itemCode = $(this).attr('item-code')
        let itemSize = $(this).attr('item-size')
        let newQty = $(this).val()

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: `/cart/${itemCode}`,
            type: 'PATCH',
            data: {
                size: itemSize,
                quantity: newQty
            },
            success: function(res){
                if(newQty <= 0){
                    setTimeout(() => {
                        location.reload()
                    }, 1000);
                } else {
                    reusabeleFunctions.updateNumbers()

                    if(res.data.expected_start_time){
                        $('meta[name="expected-start-time"]').attr('content', res.data.expected_start_time)
                        $('#queue-alert').fadeIn()
                    }else{
                        $('meta[name="expected-start-time"]').attr('content', '')
                        $('#queue-alert').fadeOut()
                    }

                    
                    startDate = ''
                    endDate = ''
                    metaMinDate = $('meta[name="expected-start-time"]').attr('content') == '' ? new Date() : new Date($('meta[name="expected-start-time"]').attr('content'));
                    
                    endDatePicker.destroy()
                    startDatePicker.destroy()
                    
                    endDatePicker = $('#date-end').datepicker({
                        uiLibrary: 'bootstrap4',
                        modal: true
                    });
                    
                    startDatePicker = $('#date-start').datepicker({
                        uiLibrary: 'bootstrap4',
                        modal: true,
                        minDate: metaMinDate,
                        change: function(e){
                            startDate = startDatePicker.value()

                            endDate = new Date(startDate)
                            endDate.setDate(endDate.getDate() + 2)

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
                    })

                    $('#date-end').val('')
                    $('#date-start').val('')
                }
            }
        })
    })
    

    // 2. Remove Item
    $('.remove-item').click(function(e){
        e.preventDefault();

        let itemCode = $(this).attr('item-code')
        let itemSize = $(this).attr('item-size')

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: `/cart/${itemCode}`,
            type: 'DELETE',
            data: {
                size: itemSize
            },
            success: function(res){
                console.log(res.message)

                setTimeout(() => {
                    location.reload()
                }, 1000);
            }
        })
    })

    // ========================================================================================================
    // Reusable Functions
    // ========================================================================================================
    reusabeleFunctions = {
        updateNumbers: function() {
            // Currency updates
            let itemQuantities = Array.from($('.item-card'), (el) => {
                let elem = $(el)
                let itemIndex = elem.attr('item-index')

                return Array.from($(`.item-quantity.form-onchange[item-index="${itemIndex}"]`), em => parseInt($(em).val())).reduce((a, b) => a + b, 0)
            })

            for(let i=0;i<itemQuantities.length;i++){
                $(`span.item-quantity[item-index="${i}"]`).html(itemQuantities[i])
                $(`span.item-total[item-index="${i}"]`).attr('data-value', parseInt(itemQuantities[i])*parseInt(itemPrices[i]))
                $(`span.item-total[item-index="${i}"]`).html(new Intl.NumberFormat().format(parseInt(itemQuantities[i])*parseInt(itemPrices[i])))
            }

            let grandTotal = itemQuantities.map((price => (qty, i )=> {
                return parseInt(qty) * parseInt(price[i])
            })(itemPrices)).reduce((a, b) => a + b, 0)

            $('#transaction-grand-total').attr('data-value', grandTotal)
            $('#transaction-grand-total').html(new Intl.NumberFormat().format(grandTotal))
        }
    }

    // ========================================================================================================
    // Background Submit
    // ========================================================================================================
    $('#checkout-button').click(function(e){
        e.preventDefault();

        if(!startDate || !endDate){
            alert('Silakan isikan tanggal mulai dan selesai peminjaman terlebih dahulu')
            return
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '/cart',
            type: 'PATCH',
            data: {
                dates: {
                    start: startDate,
                    end: endDate
                }
            },
            success: function (res){
                $('#trx-id').html(res.data.transaction.trans_no)

                let options = { year: 'numeric', month: 'long', day: 'numeric' }

                $('#trx-start-date').html(new Date(res.data.transaction.start_date).toLocaleDateString("id-ID", options))
                $('#trx-end-date').html(new Date(res.data.transaction.end_date).toLocaleDateString("id-ID", options))

                let itemContainer = $('#trx-item-list')

                itemContainer.html('')

                reusabeleFunctions.updateNumbers()

                let itemCollection = Array.from($('.item-card'), item => {
                    let elem = $(item)
                    let name = $(`span.item-name[item-index="${elem.attr('item-index')}"]`).attr('data-value')
                    let price = $(`span.item-price[item-index="${elem.attr('item-index')}"]`).attr('data-value')
                    let total = $(`span.item-total[item-index="${elem.attr('item-index')}"]`).attr('data-value')
                    let sub = Array.from($(`.item-quantity.form-onchange[item-index="${elem.attr('item-index')}"]`), el => ({
                        index: $(el).attr('item-index'),
                        size: $(el).attr('item-size'),
                        quantity: $(el).val()
                    }))

                    return {
                        id: elem.attr('item-id'),
                        name: name,
                        price: price,
                        total: total,
                        sub: sub
                    }
                    
                })

                for(let i = 0; i<itemCollection.length; i++){
                    let item = itemCollection[i]
                    let itemElem = `
                        <div class="col-12 mb-2">
                            <div class="row">
                                <div class="col-6">
                                    ${i+1}. ${item.name}
                                </div>
                                <div class="col-6">
                                    <ul class="trx-size text-right" item-index="${i}"></ul>
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

                    for(let j = 0; j<item.sub.length; j++){
                        $(`.trx-size[item-index="${i}"]`).append(`
                            <li>(${item.sub[j].size.toUpperCase()}) ${item.sub[j].quantity} x Rp ${new Intl.NumberFormat().format(item.price)}</li>
                        `)
                    }

                }

                // size
                
                let grandTotal = $('#transaction-grand-total').attr('data-value')
                
                $('#trx-total').html(new Intl.NumberFormat().format(grandTotal))

                $('#confirmation-modal-trigger').trigger('click')
            }
        });

    })
    
    $('#queue-info').click(function(e){
        e.preventDefault()
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '/cart/api/item-queue',
            type: 'GET',
            success: function (res){
                $('#queue-container').html('')
                let queue = res.data.items
                let frameCounter = 0

                queue.forEach(el => {
                    let queueFrame = `
                            <div class="row">
                                <div class="col-12">
                                    ${frameCounter == 0 ? '' : '<hr>'}
                                    <h6><strong>${el.name}</strong></h6>
                                </div>
                                <div class="col-12">
                                    <table class="table table-responsive table-bordered" style="font-size:small;">
                                        <tr class="queue-header" queue-index="${frameCounter}"></tr>
                                        <tr class="queue-list" queue-index="${frameCounter}"></tr>
                                    </table>
                                </div>
                            </div>
                            `
                    $('#queue-container').append(queueFrame)

                    let borrowerCounter = 1

                    el.transaction_queue.forEach(em => {
                        let header = `<th class="text-center" colspan="2">Penyewa ${borrowerCounter}</th>`
                        let content = `
                            <td>
                                Mulai<br>
                                ${em.start_date}
                            </td>
                            <td>
                                Selesai<br>
                                ${em.end_date}
                            </td>
                        `
                        $(`.queue-header[queue-index="${frameCounter}"]`).append(header)
                        $(`.queue-list[queue-index="${frameCounter}"]`).append(content)
                    });

                    let endHeader = `<th class="text-center bg-warning">Kosong</th>`
                    let endContent = `<td class="bg-warning">Mulai<br>${$('meta[name="expected-start-time"]').attr('content')}</td>`

                    $(`.queue-header[queue-index="${frameCounter}"]`).append(endHeader)
                    $(`.queue-list[queue-index="${frameCounter}"]`).append(endContent)

                    frameCounter++
                    
                });

                $('#queue-modal-trigger').trigger('click')
            }
        });

        
    })


</script>
@endsection