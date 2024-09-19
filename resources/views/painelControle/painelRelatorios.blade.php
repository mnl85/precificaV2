<x-page-template bodyClass='g-sidenav-show  bg-gray-200'>
    <x-auth.navbars.sidebar activePage="painelControle" activeItem="painelRelatorios" activeSubitem="">
    </x-auth.navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
                 <!-- Navbar Route Titles -->
                 <x-auth.navbars.navs.auth 
        pageTitle="Painel de Controle" 
        itemTitle="Relatórios" 
        subItemTitle="" 
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
          <!-- CABEÇALHO INICIAL inicio -->
          <div class="card m-4 p-4">
              <h5 class="mb-0">Relatorios</h5>
              <div class="flex justify-between pt-4">
                  <!-- Button trigger modal -->
                  <button type="button" class="btn bg-gradient-primary" data-bs-toggle="modal" data-bs-target="#modalNovoMeuTrabalho">
                    Cadastrar Novo Serviço
                  </button>
              </div>
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
                       <!-- ############################### -->





                       <!-- ############################### -->

    

            <x-auth.footers.auth.footer></x-auth.footers.auth.footer>


    </main>
    <x-plugins></x-plugins>
    @push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if(auth()->user()->role_id == 3)

                    window.history.back(); 
                
            @endif
        });
    </script>
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
