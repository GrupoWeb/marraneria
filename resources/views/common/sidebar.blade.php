 <!-- este seria el sidevar -->
 <div class="container-fluid">
     <div class="row">
         <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
             <div class="sidebar-sticky pt-3">
                 <ul class="nav">

                     @if (Auth()->user()->admin == 1)
                     <li class="nav-item nav-dropdown">
                         <ul class="nav-dropdown-items">
                             <!-- <li class="nav">
                    <a class="nav-link" href="./showEntidad">
                        <i class="nav-icon fas fa-edit"></i> Ingresos</a>
                </li> -->
                             <li class="nav">
                                 <!-- <a class="nav-link" href="./showunidades"> -->
                                 <a class="nav-link" href="{{ route('client') }}">
                                     <i class="nav-icon fas far fa-user"></i> Clientes</a>
                             </li>
                             <li class="nav">
                                 <a class="nav-link" href="{{ route('product') }}">
                                     <i class="nav-icon fas fa-cart-plus"></i> Productos</a>

                             </li>
                             <li class="nav">
                                 <a class="nav-link" href="{{ route('channel') }}">
                                     <i class="nav-icon fas fa-shipping-fast"></i> Canales</a>

                             </li>
                             <li class="nav">
                                 <a class="nav-link" href="{{ route('sale') }}">
                                    <i class="nav-icon fas fa-store"></i> Ventas</a>
                                    {{-- <i class="nav-icon far fa-chart-bar"></i> Ventas</a> --}}
                             </li>
                             <li class="nav">
                                 <a class="nav-link" href="{{  route('inventory') }}">
                                     <i class="nav-icon fas fa-ticket-alt"></i> Inventario</a>
                             </li>
                             <li class="nav">
                                 <a class="nav-link" href="./edificios">
                                     <i class="nav-icon fas fa-chart-line"></i> Reporte</a>
                             </li>
                             <li class="nav">
                                 <a class="nav-link" href="./edificios">
                                     {{-- <i class="nav-icon far fa-chart-bar"></i> Total Deuda</a> --}}
                                     <i class="nav-icon far fa-chart-bar"></i> Deuda</a>
                             </li>
                             {{-- <li class="nav">
                                 <a class="nav-link" href="./visualizar">
                                     <i class="nav-icon fas fa-dolly-flatbed"></i> Importar</a>
                             </li> --}}
                         </ul>
                     </li>
                     @endif
                 </ul>
             </div>
         </nav>
     </div>
 </div>
