<ul class="nav justify-content-center">
  <li class="nav-item mx-2">
    <a class="nav-link admin-page-nav-link {{$nav_product ?? ''}}" href="{{route('admin-products-list')}}">Produk</a>
  </li>
  <li class="nav-item mx-2">
    <a class="nav-link admin-page-nav-link {{$nav_user ?? ''}}" href="{{route('admin-users-list')}}">Pengguna</a>
  </li>
  <li class="nav-item mx-2">
    <a class="nav-link admin-page-nav-link {{$nav_transaction ?? ''}}" href="{{route('admin-transaction-list')}}">Transaksi</a>
  </li>
  <li class="nav-item mx-2">
    <a class="nav-link admin-page-nav-link {{$nav_history ?? ''}}" href="{{route('admin-transaction-list-done')}}">Riwayat</a>
  </li>
</ul>