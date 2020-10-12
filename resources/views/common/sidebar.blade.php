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
                                     <i class="nav-icon fas fa-file-invoice"></i> Clientes</a>
                             </li>
                             <li class="nav">
                                 <a class="nav-link" href="./roles">
                                     <i class="nav-icon fas fa-file-invoice"></i> Producto</a>
                             </li>
                             <li class="nav">
                                 <a class="nav-link" href="./showUsuarios">
                                     <i class="nav-icon fas fa-ticket-alt"></i> Ventas</a>
                             </li>
                             <li class="nav">
                                 <a class="nav-link" href="./edificios">
                                     <i class="nav-icon fas fa-ticket-alt"></i> Reporte</a>
                             </li>
                             <li class="nav">
                                 <a class="nav-link" href="./edificios">
                                     <i class="nav-icon fas fa-ticket-alt"></i> Total Deuda</a>
                             </li>
                             <li class="nav">
                                 <a class="nav-link" href="./edificios">
                                     <i class="nav-icon fas fa-ticket-alt"></i> Deuda Clientes</a>
                             </li>
                             <li class="nav">
                                 <a class="nav-link" href="./visualizar">
                                     <i class="nav-icon fas fa-dolly-flatbed"></i> Importar</a>
                             </li>
                         </ul>
                     </li>
                     @endif
                 </ul>
             </div>
         </nav>
     </div>
 </div>