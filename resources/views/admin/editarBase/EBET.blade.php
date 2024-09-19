<x-page-template bodyClass='g-sidenav-show bg-gray-200'>
    <!-- Sidebar Ativa inicio -->
    <x-auth.navbars.sidebar 
        activePage="admin" 
        activeItem="editarBase" 
        activeSubitem=""
        >
    </x-auth.navbars.sidebar>
    <!-- Sidebar Ativa fim -->
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar Route Titles -->
        <x-auth.navbars.navs.auth 
            pageTitle="Admin" 
            itemTitle="Editar Base" 
            subItemTitle="Editar Trabalho" 
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
            max-width: 600px;
            margin-left: 0;
            }
            .custom-select {
    border: 1px solid #d2d6da;
    border-radius: 0.375rem;
    padding: 12px;
}

        </style>
        <div class="container-fluid mb-4">
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <!-- Card header -->
                        <div class="card-header">
                            <h5 class="mb-0">Editar Trabalho: {{$trabalho['trabalho']}}</h5>
                            <p>id: {{$trabalho['id']}}</p>
                        </div>
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
                </div>
            </div>
        </div>
        <!-- ###### -->
        @php
        $custoMateriais = 0; // CUIDAR PARA NAO APAGAR
        @endphp
        <!-- CARD MATERIAIS inicio -->
            <div class="container-fluid mb-0">
                <div class="row ">
                    <div class="col-12">
                        <div class="card p-4">
                            <div class="table-responsive">
                                <h3 class="text-lg font-medium">Materiais</h3>
                                <p>Qtd: @if($materiaisFiltrados) {{count($materiaisFiltrados)}} @else 0 @endif</p>
                                @if($materiaisFiltrados)
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">Quantidade</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">Material</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">Apresentação</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">Custo Un.</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">Qtd Calculada</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">Custo Fração</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">Custo Parcial</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder "></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($materiaisFiltrados as $material)
                                        @php
                                        $valorPorcao = floatval($material['custo']) / floatval($material['qtd_calculada']);
                                        $custoParcial = $valorPorcao * floatval($material['qtd']);
                                        $custoMateriais += $custoParcial;
                                        @endphp
                                        <tr class="hover:bg-gray-100" data-id="{{ $material['id'] }}">
                                            <td class="text-center text-sm font-weight-bold corAdminEdit" contenteditable="true" onblur="EBETMaterialEdit(this)">
                                                {{ $material['qtd'] }}
                                                <input type="hidden" id="trabalho-id" data-id="{{ $trabalho->id }}">
                                                <input type="hidden" id="material-id" value="{{ $material['id'] }}">
                                            </td>
                                            <td class="text-sm font-weight-normal" title="id do material: {{ $material['id'] }}">{{ $material['material'] }}</td>
                                            <td class="text-sm font-weight-normal" title="id do material: {{ $material['id'] }}">{{ $material['apresentacao'] }}</td>
                                            <td class="text-sm font-weight-normal" title="id do material: {{ $material['id'] }}">R$ {{ number_format(floatval($material['custo']), 2, ',', '.') }}</td>
                                            <td class="text-sm font-weight-normal" title="id do material: {{ $material['id'] }}">{{ $material['qtd_calculada'] }}</td>
                                            <td class="text-sm font-weight-normal" title="id do material: {{ $material['id'] }}">R$ {{ number_format($valorPorcao, 2, ',', '.') }}</td>
                                            <td class="text-sm font-weight-normal" title="id do material: {{ $material['id'] }}">R$ {{ number_format($custoParcial, 2, ',', '.') }}</td>
                                            <td class="px-2 py-2 whitespace-nowrap text-sm text-gray-500" style="color:red" title="id do material: {{ $material['id'] }}">
                                                <a href="javascript:void(0);" onclick="confirmDeleteMaterial('/EBETMaterialDelete/{{$trabalho->id}}/{{ $material['id']  }}')">
                                                <b><i class="trash-icon opacity-8 fa fa-trash" aria-hidden="true"></i></b>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @endif
                                <h6 class="my-4">Custo dos Materiais: R$ {{ number_format($custoMateriais, 2, ',', '.') }}</h6>
                                @include('admin.editarBase.EBETmodalComplexoMateriais')
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- CARD MATERIAIS fim -->

        <!-- CARD SERVIÇOS inicio -->
            @php
            function formatarDuracao($segundos) {
            if ($segundos < 3600) {
            $minutos = floor($segundos / 60);
            return "$minutos min";
            } else {
            $horas = floor($segundos / 3600);
            $minutos = floor(($segundos % 3600) / 60);
            return $minutos == 0 ? "$horas horas" : "$horas horas e $minutos min";
            }
            }
            $duracaoTotal = 0;
            #dd($servicosFiltrados);
            @endphp
            <div class="row ">
                <div class="col-12">
                    <div class="card custom-card-width my-4 mx-4 p-4">
                        <div class="table-responsive">
                            <h3 class="text-lg font-medium">Serviços</h3>
                            <p>Qtd: @if($servicosFiltrados) {{count($servicosFiltrados)}} @else 0 @endif</p>
                            @if($servicosFiltrados)
                            <table class="table table-flush" id="sortable-table">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">Ordenar</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">Serviço</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder ">Tempo (MIN)</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder "></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($servicosFiltrados as $servico)
                                    @php
                                    $duracaoTotal += floatval($servico['t']);
                                    @endphp
                                    <tr class="hover:bg-gray-100" data-id="{{ $servico['id'] }}">
                                        <td class="text-center" title="id do serviço: {{ $servico['id'] }}">
                                            <i class="fa fa-bars" aria-hidden="true" style="cursor: pointer;"></i>
                                        </td>
                                        <td class="text-sm font-weight-normal" title="id do serviço: {{ $servico['id'] }}">
                                            {{ $servico['servico'] }}
                                        </td>
                                        <td class="text-center text-sm font-weight-bold corAdminEdit" contenteditable="true" onblur="EBETServicoEdit(this)">
                                            {{ $servico['t'] / 60 }}
                                            <input type="hidden" id="trabalho-id" data-id="{{ $trabalho->id }}">
                                            <input type="hidden" id="servico-id" data-id="{{ $servico['id'] }}">
                                        </td>
                                        <td class="text-sm font-weight-normal" style="color:red" title="id do serviço: {{ $servico['id'] }}">
                                            <a href="javascript:void(0);" onclick="confirmDeleteServico('/EBETServicoDelete/{{$trabalho->id}}/{{ $servico['id']  }}')">
                                            <b><i class="trash-icon opacity-8 fa fa-trash" aria-hidden="true"></i></b>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @endif
                           
                            
                            <h6 class="mb-4">Tempo dos Serviços: {{ formatarDuracao($duracaoTotal) }}</h6>
                            @include('admin.editarBase.EBETmodalComplexoServicos')
                        </div>
                    <!-- ###### -->
                    </div>
                </div>
                <x-auth.footers.auth.footer></x-auth.footers.auth.footer>
            </div>
        <!-- CARD SERVIÇOS fim -->
    </main>
    <x-plugins></x-plugins>
    @push('js')
    <script>
        function confirmDeleteMaterial(url) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success custom-swal-button",
                    cancelButton: "btn btn-danger custom-swal-button"
                },
                buttonsStyling: false
            });
            swalWithBootstrapButtons.fire({
                title: "Você tem certeza apagar este material?",
                text: "Esta ação é permanente",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Sim, apagar!",
                cancelButtonText: "Não, cancelar!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                    swalWithBootstrapButtons.fire({
                        title: "Apagado!",
                        text: "O material foi apagado",
                        icon: "success"
                    });
                }
            });
        }
    </script>
    <script>
        function confirmDeleteServico(url) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success custom-swal-button",
                    cancelButton: "btn btn-danger custom-swal-button"
                },
                buttonsStyling: false
            });
            swalWithBootstrapButtons.fire({
                title: "Você tem certeza apagar este serviço?",
                text: "Esta ação é permanente",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Sim, apagar!",
                cancelButtonText: "Não, cancelar!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                    swalWithBootstrapButtons.fire({
                        title: "Apagado!",
                        text: "O serviço foi apagado",
                        icon: "success"
                    });
                } 
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
        const sortable = new Sortable(document.getElementById('sortable-table').querySelector('tbody'), {
         animation: 150,
         handle: '.fa-bars',
         onEnd: function(evt) {
             let order = [];
             document.querySelectorAll('#sortable-table tbody tr').forEach(function(row) {
                 let id = row.getAttribute('data-id');
                 let t = parseFloat(row.querySelector('.corAdminEdit').textContent.trim()) * 60; // Convertendo minutos para segundos
                 order.push({id: id, t: t});
             });
        
             fetch('/EBETServicolOrder', {
                 method: 'POST',
                 headers: {
                     'Content-Type': 'application/json',
                     'X-CSRF-TOKEN': '{{ csrf_token() }}'
                 },
                 body: JSON.stringify({order: order, trabalho_id: '{{ $trabalho->id }}'})
             })
             .then(response => response.json())
             .then(data => {
                 if (data.success) {
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
                         title: "Ordem atualizada"
                     });
                 } else {
                     alert('Failed to update order.');
                 }
             })
             .catch(error => {
                 Swal.fire({
                     position: "top-end",
                     icon: "error",
                     title: "A ordenação não foi salva! Se o erro persistir contate o administrador no Painel de Controle na seção de Ajuda",
                     showConfirmButton: false,
                     timer: 10000
                 });
             });
         }
        });
        });
        
    </script>
    <script>
        function EBETMaterialEdit(element) {
            const materialID = element.closest('td').querySelector('#material-id').value;
            let qtd = element.textContent.trim().replace(',', '.'); // Substituindo vírgula por ponto
            qtd = parseFloat(qtd); // Convertendo para float
            const trabalhoID = document.getElementById('trabalho-id').dataset.id;
        
            const data = { materialID: materialID, trabalhoID: trabalhoID, qtd: qtd };
        
            console.log('Dados enviados para o request:', data);
        
            fetch(`/EBETMaterialEdit`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    element.style.backgroundColor = '#55f2c3'; // Verde claro para sucesso
                    element.title = data.message;
        
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
                        title: "Quantidade atualizada com sucesso"
                    });
        
                    // Manter a cor por 1 segundo antes de voltar à cor original
                    setTimeout(() => {
                        element.style.backgroundColor = ''; // Volta à cor original após 1 segundo
                    }, 1000);
                } else {
                    element.style.backgroundColor = '#f8d7da'; // Vermelho claro para erro
                    element.title = data.message;
        
                    // Manter a cor por 1 segundo antes de voltar à cor original
                    setTimeout(() => {
                        element.style.backgroundColor = ''; // Volta à cor original após 1 segundo
                    }, 1000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                element.style.backgroundColor = '#ed667e'; // Vermelho claro para erro
                element.title = 'Erro ao atualizar';
        
                // Manter a cor por 1 segundo antes de voltar à cor original
                setTimeout(() => {
                    element.style.backgroundColor = ''; // Volta à cor original após 1 segundo
                }, 1000);
            });
        }
        
        
            
    </script>
    <script>
        function EBETServicoEdit(element) {
            const servicoID = document.getElementById('servico-id').dataset.id;
            const tempo = parseFloat(element.textContent.trim()) * 60; // Convertendo minutos para segundos
            const trabalhoID = document.getElementById('trabalho-id').dataset.id;
        
            const data = { servicoID: servicoID, trabalhoID: trabalhoID, t: tempo };
        
            console.log('Dados enviados para o request:', data);
        
            fetch(`/EBETServicoEdit`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    element.style.backgroundColor = '#55f2c3'; // Verde claro para sucesso
                    element.title = data.message;
        
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
                        title: "Tempo atualizado com sucesso"
                    });
        
                    // Manter a cor por 1 segundo antes de voltar à cor original
                    setTimeout(() => {
                        element.style.backgroundColor = ''; // Volta à cor original após 1 segundo
                    }, 1000);
                } else {
                    element.style.backgroundColor = '#f8d7da'; // Vermelho claro para erro
                    element.title = data.message;
        
                    // Manter a cor por 1 segundo antes de voltar à cor original
                    setTimeout(() => {
                        element.style.backgroundColor = ''; // Volta à cor original após 1 segundo
                    }, 1000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                element.style.backgroundColor = '#ed667e'; // Vermelho claro para erro
                element.title = 'Erro ao atualizar';
        
                // Manter a cor por 1 segundo antes de voltar à cor original
                setTimeout(() => {
                    element.style.backgroundColor = ''; // Volta à cor original após 1 segundo
                }, 1000);
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