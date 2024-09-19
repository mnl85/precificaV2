<x-page-template bodyClass='g-sidenav-show  bg-gray-200'>
    <!-- Sidebar Ativa inicio -->
    <x-auth.navbars.sidebar 
        activePage="admin" 
        activeItem="reiniciar" 
        activeSubitem="serverRestart"
    >
    </x-auth.navbars.sidebar>
    <!-- Sidebar Ativa fim -->
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar Route Titles -->
            <x-auth.navbars.navs.auth 
                pageTitle="Admin" 
                itemTitle="Reiniciar" 
                subItemTitle="Reiniciar Servidor" 
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
        <h5 class="mb-0">Reiniciar Servidor</h5>
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
    <!-- ###### -->
 
    <div class="card my-4 p-4">
        @if (session('status'))
            <p>{{ session('status') }}</p>
        @endif

        <div class="text-center pt-3">
            <form id="restart-server-form" action="{{ route('serverRestart') }}" method="POST">
                @csrf
                <button type="button" onclick="confirmRestart()" class="btn btn-danger">Reiniciar Servidor</button>
            </form>
        </div>

    </div>
    <div class="card my-4 p-4">
        <div class="table-responsive">
            <h5 class="text-center">Listagem de execuções manuais</h5>
            <table class="table table-flush" id="datatable-basic">
                <thead class="thead-light">
                    <tr>
                        <th >ID do Log</th>
                        <th >Id do User</th>
                        <th >Created At</th>
                    </tr>
                </thead>
                <tbody >
                    @foreach ($log_manual_restarts as $d)
                        <tr>
                            <td >{{ $d['id_log_manual_restarts'] }}</td>
                            <td >{{ $d['alt_user_id'] }}</td>
                            <td >{{ $d['created_at']->sub(new DateInterval('PT3H')) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
        

    <!-- ###### -->
                  
             
            <x-auth.footers.auth.footer></x-auth.footers.auth.footer>
        </div>
    </main>
    <x-plugins></x-plugins>
    @push('js')
    <script>
        function confirmRestart() {
            // Exibir um alerta de confirmação
            if (confirm("Você tem certeza que deseja reiniciar o servidor?")) {
                // Se o usuário confirmar, enviar o formulário
                document.getElementById('restart-server-form').submit();
            }
        }
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
