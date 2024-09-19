<x-page-template bodyClass='g-sidenav-show  bg-gray-200'>
    <x-auth.navbars.sidebar activePage="admin" activeItem="logins" activeSubitem="relatorioLogins">
    </x-auth.navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar Route Titles -->
        <x-auth.navbars.navs.auth 
        pageTitle="Admin" 
        itemTitle="Logins" 
        subItemTitle="Relatório de Logins" 
        >
        </x-auth.navbars.navs.auth>
        <!-- End Navbar Route Titles -->
        <style>
  .table-responsive {
    overflow-x: auto;
  }
  #dataTable {
    max-width: 800px;
    margin: 0 auto;
  }

  .fs-custom {
    font-size: 0.75rem; /* Ajuste conforme necessário */
  }
</style>
<div class="container-fluid py-4">
    <!-- CABEÇALHO INICIAL inicio -->
    <div class="card  p-4">
        <h5 class="mb-0">Relatório de Logins</h5>
        <!-- <div class="flex justify-between pt-4">
        
            <button type="button" class="btn btn-block bg-gradient-primary mb-3" data-bs-toggle="modal" data-bs-target="#modal-form">Novo Trabalho Base</button>
        </div> -->
    </div>
    <!-- CABEÇALHO INICIAL fim -->
    @if (Session::has('status'))
    <div class="alert alert-success alert-dismissible text-white mx-4" role="alert">
        <span class="text-sm">{{ Session::get('status') }}</span>
        <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert"
            aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    
    <x-auth.footers.auth.footer></x-auth.footers.auth.footer>
</div>

    </main>
    <x-plugins></x-plugins>
    @push('js')
    
    <script src="{{ asset('assets') }}/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/datatables.js"></script>
    <script>
        const dataTableBasic = new simpleDatatables.DataTable("#datatable-basic", {
            searchable: true,
            fixedHeight: false
        });

    </script>
    @endpush
</x-page-template>
