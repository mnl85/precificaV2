<x-page-template bodyClass='g-sidenav-show  bg-gray-200'>
    <x-auth.navbars.sidebar activePage="base" activeItem="consultaBaseServicos" activeSubitem="">
    </x-auth.navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
         <!-- Navbar Route Titles -->
         <x-auth.navbars.navs.auth 
        pageTitle="Base" 
        itemTitle="Serviços" 
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
  .custom-card-width {
                width: 50%;
                min-width: 500px;
                margin-left: 0;
            }
</style>
<div class="card m-4 p-4">

                        <h5 class="mb-0">Serviços na Base</h5> 
                       
                        @if (Session::has('status'))
                        <div class="alert alert-success alert-dismissible text-white mx-4" role="alert">
                            <span class="text-sm">{{ Session::get('status') }}</span>
                            <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert"
                                aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif
                    </div>
                    
                        <div class="card m-4 p-4 custom-card-width">
                            <div class="table-responsive">
                                <table class="table table-flush" id="datatable-basic">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                ID</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Nome do Serviço</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Copiar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($tb_base_servicos as $d)
                                        <tr>
                                            <td class="text-sm font-weight-normal">{{ $d['id'] }}</td>
                                            <td class="text-sm font-weight-normal">{{ $d['servico'] }}</td>
                                            <td class="text-sm font-weight-normal"><a href="/copiaBaseServicos/{{ $d['id'] }}"><i class="copy-icon opacity-7 fa fa-regular fa-copy aria-hidden="true"></i></a></td>
                                        
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
           

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
