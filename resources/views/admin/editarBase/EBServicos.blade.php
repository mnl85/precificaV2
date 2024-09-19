<x-page-template bodyClass='g-sidenav-show  bg-gray-200'>
    <x-auth.navbars.sidebar activePage="admin" activeItem="editarBase" activeSubitem="EBServicos">
    </x-auth.navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar Route Titles -->
        <x-auth.navbars.navs.auth 
        pageTitle="Admin" 
        itemTitle="Editar Base" 
        subItemTitle="Serviços" 
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
                width: 60%;
                max-width: 650px;
                margin-left: 0;
            }
</style>
        <div class="container-fluid py-4">
                 <!-- CABEÇALHO INICIAL inicio -->
        <div class="card p-4">
            <h5 class="mb-0">Editar Base - Serviços</h5>
            <div class="flex justify-between pt-4">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-block bg-gradient-primary mb-3" data-bs-toggle="modal" data-bs-target="#modal-form">Novo Material Base</button>
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
                        <!-- ###### -->

                        @php
                            function formatarDuracao($segundos) {
                                $minutos = floor($segundos / 60);
                                return "$minutos min";
                            }

                            $duracaoTotal = 0;
                            function formatDateTime($createdAt) {
                                            date_default_timezone_set('America/Sao_Paulo');
                                            $dateTime = new DateTime($createdAt); 
                                            $dateTime->modify('-3 hours');
                                            $now = new DateTime();
                                            if ($dateTime->format('Y-m-d') === $now->format('Y-m-d')) {
                                                return $dateTime->format('H:i');
                                            } else {
                                                return $dateTime->format('d/m/y');
                                            }
                                        }
                        @endphp

                        <div class="card custom-card-width my-4 p-4">
                            <div class="table-responsive">
                                <table class="table table-flush" id="datatable-basic">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">
                                                ID</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">
                                                Nome do Serviço</th>
                                                
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">
                                                Data Atualizado</th>
                                                <th class="text-center "></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($tb_base_servicos as $d)
                                        <tr data-row-id="{{ $d['id'] }}">
                                            <td class="text-center text-sm font-weight-normal">{{ $d['id'] }}</td>
                                            <td class="text-sm font-weight-bold corAdminEdit" contenteditable="true" onblur="updateCell(this, 'servico')">{{ $d['servico'] }}</td>
                                            
                                            <td class="text-center text-sm font-weight-normal">{{ formatDateTime($d['updated_at']) }}</td>
                                            <td class="text-center ">
                                                <a href="javascript:void(0);" onclick="confirmDelete('/EBServicosDelete/{{ $d['id'] }}')"><b><i class="trash-icon opacity-8 fa fa-trash" aria-hidden="true"></i></b></a>
                                            </td>
                                        
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
        function confirmDelete(url) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success custom-swal-button",
                    cancelButton: "btn btn-danger custom-swal-button"
                },
                buttonsStyling: false
            });
            swalWithBootstrapButtons.fire({
                title: "Você tem certeza que quer <span style='color:red;'>apagar da base </span> este serviço?", 
                text: "Esta ação é permanente!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sim, apagar!",
                cancelButtonText: "Não, cancelar!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                    swalWithBootstrapButtons.fire({
                        title: "Apagado!",
                        text: "O serviço foi apagado da base",
                        icon: "success"
                    });
                } 
            });
        }
    </script>

    <script>
        function updateCell(element, field) {
            const rowId = element.parentElement.dataset.rowId;
            const value = element.innerText.trim();

            fetch(`/EBServicosUpdate/${rowId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken
                },
                body: JSON.stringify({ [field]: value })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    element.style.backgroundColor = '#86d9a3'; // Verde claro para sucesso
                    element.title = data.message;
                    element.innerText = value;
                    const Toast = Swal.mixin({
                    toast: true,
                    position: "bottom-end",
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                    });
                    Toast.fire({
                    icon: "success",
                    title: "Nome do serviço atualizado"
                    }); // Atualiza a célula com o novo valor
                    setTimeout(() => {
                        element.style.backgroundColor = ''; // Volta à cor original após 1 segundo
                    }, 1000);
                } else {
                    element.style.backgroundColor = '#f28ea0'; // Vermelho claro para erro
                    console.error('Error:', error); // Log para mostrar erros
                Swal.fire({
                position: "top-end",
                icon: "error",
                title: "A alteração não foi salva! Se o erro persistir contate o administrador no Painel de Controle na seção de Ajuda",
                showConfirmButton: false,
                timer: 10000
                });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                element.style.backgroundColor = '#f28ea0'; // Vermelho claro para erro
                console.error('Error:', error); // Log para mostrar erros
                Swal.fire({
                position: "top-end",
                icon: "error",
                title: "A alteração não foi salva! Se o erro persistir contate o administrador no Painel de Controle na seção de Ajuda",
                showConfirmButton: false,
                timer: 10000
                });
            });
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
