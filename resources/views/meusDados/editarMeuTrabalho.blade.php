<x-page-template bodyClass='g-sidenav-show bg-gray-200'>
    <x-auth.navbars.sidebar activePage="meusDados" activeItem="meusTrabalhos" activeSubitem="">
    </x-auth.navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <!-- Navbar Route Titles -->
        <x-auth.navbars.navs.auth 
            pageTitle="Meus Dados" 
            itemTitle="Meus Trabalhos" 
            subItemTitle="Editar Trabalho">
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
        <div id="alert-info" class="alert alert-info text-white" role="alert" style="display: none; position: fixed; z-index: 9999;">
    <strong>Atenção!</strong> Atualizando a ordem.
</div>


        <div class="container-fluid py-4">
            <!-- CABEÇALHO INICIAL -->
            <div class="card p-4">
                <div class="d-flex align-items-center">
                    <span class="me-2" style="font-size: 0.9rem;">Editar Trabalho:</span>
                    <h5 class="mb-0">{{$trabalho['trabalho']}}</h5>
                </div>
                <p>id: {{$trabalho['id']}}</p>
            </div>
            <!-- CABEÇALHO INICIAL -->
            @if (Session::has('status'))
            <div class="alert alert-success alert-dismissible text-white mx-4" role="alert">
                <span class="text-sm">{{ Session::get('status') }}</span>
                <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
            <!-- ######################## -->
            <!-- MATERIAIS inicio -->
            @php
            if ($view_tb_dre->isEmpty() || $tb_producao->isEmpty()) {
            $total_tempo_prod = 0;
            $total_tempo_dre = 0;
            } else {
            // Data atual (ano-mês)
            $mes_atual = date("Y-m");
            // Último ano-mês da tabela DRE
            $ultimo_anomes_dre = $view_tb_dre[0]->anomes; // Supondo que $view_tb_dre[0]->anomes seja algo como "2024-02"
            // Convertendo as datas em objetos DateTime
            $mes_atual_date = new DateTime($mes_atual);
            $ultimo_anomes_date_dre = new DateTime($ultimo_anomes_dre);
            // Calculando a diferença em dias
            $diferenca_dre = $mes_atual_date->diff($ultimo_anomes_date_dre);
            $total_tempo_dre = ($diferenca_dre->days) / 30;
            // Último ano-mês da tabela de produção
            $ultimo_anomes_prod = $tb_producao[0]->anomes;
            $ultimo_anomes_date_prod = new DateTime($ultimo_anomes_prod);
            // Calculando a diferença em dias
            $diferenca_prod = $mes_atual_date->diff($ultimo_anomes_date_prod);
            $total_tempo_prod = ($diferenca_prod->days) / 30;
            // Exibindo as diferenças em dias (apenas para debug)
            }
            $custoMateriais = 0; // CUIDAR PARA NAO APAGAR
            @endphp
            <div class="card my-4 p-4">
                <div class="table-responsive">
                    <h3 class="text-lg font-medium">Materiais</h3>
                    @if(count($materiaisFiltrados)==0)
                    <p>Sem materiais cadastrados</p>
                    <button type="button" class="btn bg-gradient-primary" data-bs-toggle="modal" data-bs-target="#modalAdicionarMeuMaterial">
                    Adicionar Material
                    </button>
                    @else
                    <p>Qtd: {{count($materiaisFiltrados)}}</p>
                    <table class="table align-items-center mb-0" id="sortable-table-m">
                        <thead>
                            <tr>
                                <!-- <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">ID</th> -->
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center"><i class="text-center ordenar-icon fa fa-sort" aria-hidden="true"></i></th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder ">Quantidade</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">Material</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder ">Apresentação</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder ">Custo Un.</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder ">Qtd Calculada</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder ">Custo Fração</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder ">Custo Parcial</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder "></th>
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
                                <!-- <td class="text-sm font-weight-normal">{{ $material['id'] }}</td> -->
                                <td class="text-center" title="id do material: {{ $material['id'] }}"><i class="fa fa-bars" aria-hidden="true" style="cursor: pointer;"></i></td>
                                <td class="text-center text-center text-sm font-weight-normal">
                                    <input type="hidden" id="{{ $material['id'] }}" value="{{ $material['id'] }}">
                                    <input type="hidden" id="trabalho-id" data-id="{{ $trabalho->id }}">
                                    <div class="text-center input-group input-group-static" style="width:80px;">
                                        <input type="number" value="{{ $material['qtd'] }}" id="q-{{ $material['id'] }}" class="text-center form-control rounded px-2 py-1 corUserEdit" title="id do material: {{ $material['id'] }}" style="font-weight: bold" onblur="updateETMaterialQTD({{ $material['id'] }})">
                                    </div>
                                </td>
                                <td class="text-sm font-weight-normal" title="id do material: {{ $material['id'] }}">{{ $material['material'] }}</td>
                                <td class="text-center text-sm font-weight-normal" title="id do material: {{ $material['id'] }}">{{ $material['apresentacao'] }}</td>
                                <td class="text-center text-sm font-weight-normal" title="id do material: {{ $material['id'] }}">R$ {{ number_format(floatval($material['custo']), 2, ',', '.') }}</td>
                                <td class="text-center text-sm font-weight-normal" title="id do material: {{ $material['id'] }}">{{ $material['qtd_calculada'] }}</td>
                                <td class="text-center text-sm font-weight-normal" title="id do material: {{ $material['id'] }}">R$ {{ number_format($valorPorcao, 2, ',', '.') }}</td>
                                <td class="text-center text-sm font-weight-normal" title="id do material: {{ $material['id'] }}">R$ {{ number_format($custoParcial, 2, ',', '.') }}</td>
                                <td class="text-center px-2 py-2 whitespace-nowrap text-sm text-gray-500" style="color:red" title="id do material: {{ $material['id'] }}">
                                    <a href="javascript:void(0);" onclick="confirmDeleteMaterial('/ETapagarMeuMaterial/{{$trabalho->id}}/{{ $material['id']  }}')"><b><i class="trash-icon opacity-8 fa fa-trash" aria-hidden="true"></i></b></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    <!-- Button trigger modal -->
                    <button type="button" class="btn bg-gradient-primary" data-bs-toggle="modal" data-bs-target="#modalAdicionarMeuMaterial">
                    Adicionar Material
                    </button>
                    <br>
                    <h6>Custo dos Materiais: R$ {{ number_format($custoMateriais, 2, ',', '.') }}</h6>
                    <br>
                    @endif
                    <!-- MODAL inicio -->
                    <div class="modal fade" id="modalAdicionarMeuMaterial" tabindex="-1" role="dialog" aria-labelledby="modalAdicionarMeuMaterialLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title font-weight-normal" id="modalAdicionarMeuMaterialLabel">Adicionar Material</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{asset('/ETaddMaterial/'.$trabalho->id)}}" method="POST">
                                        @csrf
                                        <select id="material" name="material" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 dark:border-gray-600 dark:placeholder-gray-400">
                                            @foreach ($tb_meus_materiais as $t)
                                            <option value="{{ $t['id'] }}">{{ $t['material']." - ".$t['apresentacao'] }}</option>
                                            @endforeach
                                        </select>
                                        <div class="mt-4">
                                            <label for="quantidade" class="block text-sm font-medium text-gray-700">Quantidade</label>
                                            <input required type="number" id="quantidade" name="quantidade" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 dark:border-gray-600 dark:placeholder-gray-400">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Fechar</button>
                                            <button type="submit" class="btn bg-gradient-primary">Salvar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- MODAL fim -->
                </div>
            </div>
            <!-- MATERIAIS fim -->
            <!-- SERVICOS inicio -->
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
            $custoServicos = 0;
            #dd($servicosFiltrados);
            @endphp
            <div class="row my-4 ">
                <!-- Card Serviços -->
                <div class="col-md-6">
                    <div class="card p-4">
                        <div class="table-responsive">
                            <h3 class="text-lg font-medium">Serviços</h3>
                            @if(count($servicosFiltrados) == 0)
                            <p>Sem serviços cadastrados</p>
                            <button type="button" class="btn bg-gradient-primary" data-bs-toggle="modal" data-bs-target="#modalAdicionarServico">
                            Adicionar Serviço
                            </button>
                            @else
                            <p>Qtd: {{ count($servicosFiltrados) }}</p>
                            <table class="table align-items-center mb-0" id="sortable-table-s">
                                <thead>
                                    <tr>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder "><i class="ordenar-icon fa fa-sort" aria-hidden="true"></i></th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">Serviço</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">Tempo (MIN)</th>
                                        @if(!$view_tb_dre->isEmpty())
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder ">Custo Serviço</th>
                                        @endif
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder "></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($servicosFiltrados as $servico)
                                    @php
                                    $duracaoTotal += floatval($servico['t']);
                                    if(!$view_tb_dre->isEmpty()) {
                                    $custoServicos += $servico['t'] * $mediaPonderada;
                                    }
                                    @endphp
                                    <tr class="hover:bg-gray-100" data-id="{{ $servico['id'] }}">
                                        <td class="text-center" title="id do serviço: {{ $servico['id'] }}"><i class="fa fa-bars" aria-hidden="true" style="cursor: pointer;"></i></td>
                                        <td class="text-sm font-weight-normal" title="id do serviço: {{ $servico['id'] }}">{{ $servico['servico'] }}</td>
                                        <td class="text-sm font-weight-normal">
                                            <div class="text-center input-group input-group-static" style="width:80px;">
                                                <input type="number" value="{{ $servico['t'] / 60 }}" id="t-{{ $servico['id'] }}" title="id do serviço: {{ $servico['id'] }}" class="text-center form-control rounded px-2 py-1 corUserEdit" style="width:80px;font-weight: bold;" onblur="updateETServicoT({{ $servico['id'] }})">
                                            </div>
                                            <input type="hidden" id="trabalho-id" data-id="{{ $trabalho->id }}">
                                            <input type="hidden" id="{{ $servico['id'] }}" value="{{ $servico['id'] }}">
                                        </td>
                                        @if(!$view_tb_dre->isEmpty())
                                        <td class="text-center text-sm font-weight-normal" title="id do serviço: {{ $servico['id'] }}">R$ {{ number_format($servico['t'] * $mediaPonderada / 60, 2, ',', '.') }}</td>
                                        @endif
                                        <td class="text-sm font-weight-normal" style="color:red" title="id do serviço: {{ $servico['id'] }}">
                                            <a href="javascript:void(0);" onclick="confirmDeleteServico('/ETapagarMeuServico/{{$trabalho->id}}/{{ $servico['id']  }}')"><b><i class="trash-icon opacity-8 fa fa-trash" aria-hidden="true"></i></b></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <br>
                            <button type="button" class="btn bg-gradient-primary" data-bs-toggle="modal" data-bs-target="#modalAdicionarServico">
                            Adicionar Serviço
                            </button>
                            <br>
                            <h6>Tempo dos Serviços: {{ formatarDuracao($duracaoTotal) }}</h6>
                            <br>
                            @if(!$view_tb_dre->isEmpty())
                            <h6>Custo Total dos Serviços: R$ {{ number_format($custoServicos / 60, 2, ',', '.') }}</h6>
                            @endif
                            @endif
                            <br>
                            <!-- MODAL inicio -->
                            <div class="modal fade" id="modalAdicionarServico" tabindex="-1" role="dialog" aria-labelledby="modalAdicionarServicoLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h3 class="modal-title font-weight-normal" id="modalAdicionarServicoLabel">Adicionar Serviço</h3>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{asset('/ETaddServico/'.$trabalho->id)}}" method="POST">
                                                @csrf
                                                <label for="servico" class="block text-sm font-medium text-gray-700 ">Nome do Serviço</label>
                                                <select id="servico" name="servico" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 dark:border-gray-600 dark:placeholder-gray-400 ">
                                                    @foreach ($tb_meus_servicos as $s)
                                                    <option value="{{ $s['id'] }}">{{ $s['servico'] }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="mt-4">
                                                    <label for="tempo" class="block text-sm font-medium text-gray-700 ">Tempo (em Minutos)</label>
                                                    <input required type="number" id="tempo" name="tempo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 dark:border-gray-600 dark:placeholder-gray-400 ">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Fechar</button>
                                                    <button type="submit" class="btn bg-gradient-primary">Salvar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- MODAL fim -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- SERVICOS fim -->
                <!-- EQUIPAMENTOS inicio -->
                @php
                $duracaoEquipamentosTotal = 0;
                $custoEquipamentos = 0;
                @endphp
                <div class="col-md-6">
                    <div class="card  p-4">
                        <div class="table-responsive">
                            <h3 class="text-lg font-medium">Equipamentos</h3>
                            @if ($equipamentosFiltrados==null)
                            <p>Sem equipamentos sendo utilizados</p>
                            <button type="button" class="btn bg-gradient-primary" data-bs-toggle="modal" data-bs-target="#modalAdicionarEquipamento">
                            Adicionar Equipamento
                            </button>
                            @else
                            <p>Qtd: {{ count($equipamentosFiltrados) }}</p>
                            <table class="table align-items-center mb-0" id="sortable-table-e">
                                <thead>
                                    <tr>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder "><i class="ordenar-icon fa fa-sort" aria-hidden="true"></i></th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">Equipamento</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder ">Tempo (MIN)</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder ">Custo</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder "></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($equipamentosFiltrados as $equipamento)
                                    @php
                                    $duracaoEquipamentosTotal += floatval($equipamento['t']);
                                    $valorEquipamento = $equipamento['t'] * ($equipamento['valor'] / ($equipamento['vida_util_horas'] * 60));
                                    $custoEquipamentos += $valorEquipamento;
                                    @endphp
                                    <tr class="hover:bg-gray-100" data-id="{{ $equipamento['id'] }}">
                                        <td class="text-center" title="id do equipamento: {{ $equipamento['id'] }}"><i class="fa fa-bars" aria-hidden="true" style="cursor: pointer;"></i></td>
                                        <td class="text-sm font-weight-normal" title="id do equipamento: {{ $equipamento['id'] }}">{{ $equipamento['nome_equipamento'] }}</td>
                                        <td class="text-sm font-weight-normal">
                                            <input type="number" value="{{ $equipamento['t'] }}" id="teq-{{ $equipamento['id'] }}" class="text-sm font-weight-normal" style="width:80px;font-weight: bold;color: #34acdb;" onblur="updateETEquipamentoT({{ $equipamento['id'] }})">
                                            <input type="hidden" id="{{ $equipamento['id'] }}" value="{{ $equipamento['id'] }}">
                                        </td>
                                        <td class="text-center text-sm font-weight-normal" title="Custo do equipamento">R$ {{ number_format($valorEquipamento, 2, ',', '.') }}</td>
                                        <td class="text-sm font-weight-normal" style="color:red" title="Excluir equipamento">
                                            <a href="/ETapagarEquipamento/{{$trabalho->id}}/{{ $equipamento['id'] }}" onclick="confirmarExclusaoETequipamento(event)"><b><i class="fa-solid fa-xmark"></i></b></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <br>
                            <button type="button" class="btn bg-gradient-primary" data-bs-toggle="modal" data-bs-target="#modalAdicionarEquipamento">
                            Adicionar Equipamento
                            </button>
                            <br>
                            <h6>Tempo dos Equipamentos: {{ formatarDuracao($duracaoEquipamentosTotal) }}</h6>
                            <br>
                            <h6>Custo Total dos Equipamentos: R$ {{ number_format($custoEquipamentos, 2, ',', '.') }}</h6>
                            @endif
                            <br>
                            <!-- MODAL inicio -->
                            <div class="modal fade" id="modalAdicionarEquipamento" tabindex="-1" role="dialog" aria-labelledby="modalAdicionarEquipamentoLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h3 class="modal-title font-weight-normal" id="modalAdicionarServicoLabel">Adicionar Serviço</h3>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{asset('/ETaddServico/'.$trabalho->id)}}" method="POST">
                                                @csrf
                                                <label for="servico" class="block text-sm font-medium text-gray-700 ">Nome do Serviço</label>
                                                <select id="servico" name="servico" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 dark:border-gray-600 dark:placeholder-gray-400 ">
                                                    @foreach ($tb_meus_servicos as $s)
                                                    <option value="{{ $s['id'] }}">{{ $s['servico'] }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="mt-4">
                                                    <label for="tempo" class="block text-sm font-medium text-gray-700 ">Tempo (em Minutos)</label>
                                                    <input required type="number" id="tempo" name="tempo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 dark:border-gray-600 dark:placeholder-gray-400 ">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Fechar</button>
                                                    <button type="submit" class="btn bg-gradient-primary">Salvar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- MODAL fim -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- EQUIPAMENTOS fim -->
            </div>
            @if ($servicosFiltrados->isEmpty() || $materiaisFiltrados->isEmpty())
            <p class="m-4">Cadastre materiais e serviços para visualizar as análises e gráficos deste trabalho.</p>
            @else
            <div class="row my-4 ">
                <div class="col-md-6">
                    <div class="card  p-4">
                        <div class="table-responsive ">
                            <h3 class="text-lg font-medium">Análise dos Valores</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card  p-4">
                        <div class="table-responsive ">
                            <h3 class="text-lg font-medium">Distribuição dos Custos</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <!-- ######################## -->
        <x-auth.footers.auth.footer></x-auth.footers.auth.footer>
        </div>
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
    // Função para exibir o alerta próximo ao mouse
    function showAlert(evt) {
        const alertBox = document.getElementById('alert-info');
        alertBox.style.display = 'block';
        updateAlertPosition(evt);  // Atualiza a posição inicial
    }

    // Função para esconder o alerta
    function hideAlert() {
        document.getElementById('alert-info').style.display = 'none';
    }

    // Função para atualizar a posição do alerta com base nas coordenadas do mouse
    function updateAlertPosition(evt) {
        const alertBox = document.getElementById('alert-info');
        alertBox.style.left = `${evt.clientX + 55}px`;  // Posição relativa ao viewport
        alertBox.style.top = `${evt.clientY + 15}px`;
    }

    // Inicialização da tabela de Materiais com ordenação
    const sortableMaterials = new Sortable(document.getElementById('sortable-table-m').querySelector('tbody'), {
        animation: 150,
        handle: '.fa-bars',
        onStart: function(evt) {
            showAlert(evt.originalEvent); // Passa o evento de arrastar
            document.addEventListener('mousemove', updateAlertPosition);
        },
        onEnd: function(evt) {
            hideAlert();
            document.removeEventListener('mousemove', updateAlertPosition);
            let order = [];
            document.querySelectorAll('#sortable-table-m tbody tr').forEach(function(row) {
                let id = row.getAttribute('data-id');
                order.push({id: id});
            });

            fetch('/update-order-m', {
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
                    Swal.fire({
                        icon: "success",
                        title: "Ordem dos materiais atualizada com sucesso"
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Falha ao atualizar a ordem dos materiais"
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: "error",
                    title: "Erro ao atualizar a ordem dos materiais"
                });
            });
        }
    });

    // Inicialização da tabela de Serviços com ordenação
    const sortableServices = new Sortable(document.getElementById('sortable-table-s').querySelector('tbody'), {
        animation: 150,
        handle: '.fa-bars',
        onStart: function(evt) {
            showAlert(evt.originalEvent);
            document.addEventListener('mousemove', updateAlertPosition);
        },
        onEnd: function(evt) {
            hideAlert();
            document.removeEventListener('mousemove', updateAlertPosition);
            let order = [];
            document.querySelectorAll('#sortable-table-s tbody tr').forEach(function(row) {
                let id = row.getAttribute('data-id');
                order.push({id: id});
            });

            fetch('/update-order-s', {
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
                    Swal.fire({
                        icon: "success",
                        title: "Ordem dos serviços atualizada com sucesso"
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Falha ao atualizar a ordem dos serviços"
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: "error",
                    title: "Erro ao atualizar a ordem dos serviços"
                });
            });
        }
    });

    // Inicialização da tabela de Equipamentos com ordenação
    const sortableEquipments = new Sortable(document.getElementById('sortable-table-e').querySelector('tbody'), {
        animation: 150,
        handle: '.fa-bars',
        onStart: function(evt) {
            showAlert(evt.originalEvent);
            document.addEventListener('mousemove', updateAlertPosition);
        },
        onEnd: function(evt) {
            hideAlert();
            document.removeEventListener('mousemove', updateAlertPosition);
            let order = [];
            document.querySelectorAll('#sortable-table-e tbody tr').forEach(function(row) {
                let id = row.getAttribute('data-id');
                order.push({id: id});
            });

            fetch('/update-order-e', {
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
                    Swal.fire({
                        icon: "success",
                        title: "Ordem dos equipamentos atualizada com sucesso"
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Falha ao atualizar a ordem dos equipamentos"
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: "error",
                    title: "Erro ao atualizar a ordem dos equipamentos"
                });
            });
        }
    });
});


          
    </script>
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            let statusMeusTrabalhos = "{{ session('statusMeusTrabalhos') }}";
            let trabalhoDuplicado = "{{ session('trabalhoDuplicado') }}";
            console.log("Valor de statusMeusTrabalhos:", statusMeusTrabalhos, trabalhoDuplicado);
        
            if (statusMeusTrabalhos === "1") {
                Swal.fire({
                    title: "Trabalho não copiado!",
                    html: "Você já tem um trabalho com o nome <br><br><b>" + trabalhoDuplicado + "</b> <br><br> Altere este nome e repita a importação",
                    icon: "error"
                });
            }
        });
    </script>
    <script>
        function updateETMaterialQTD(id) {
            const materialID = document.getElementById(id).value;
            const qtd = document.getElementById(`q-${id}`).value;
            const trabalhoID = document.getElementById('trabalho-id').dataset.id;
        
            const data = { materialID: materialID, trabalhoID: trabalhoID, qtd: qtd };
        
            console.log('Dados enviados para o request:', data);
        
            fetch(`/updateETMaterialQTD`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                const element = document.getElementById(`q-${id}`);
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
                const element = document.getElementById(`q-${id}`);
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
        function updateETServicoT(id) {
            const servicoID = document.getElementById(id).value;
            const tempo = document.getElementById(`t-${id}`).value * 60; // Convertendo minutos para segundos
            const trabalhoID = document.getElementById('trabalho-id').dataset.id;
        
            const data = { servicoID: servicoID, trabalhoID: trabalhoID, t: tempo };
        
            console.log('Dados enviados para o request:', data);
        
            fetch(`/updateETServicoT`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                const element = document.getElementById(`t-${id}`);
                if (data.success) {
                    element.style.backgroundColor = '#55f2c3'; // Verde claro para sucesso
                    element.title = data.message;
        
                    // Atualizar o valor exibido na célula
                    element.value = (tempo / 60).toFixed(2); // Exibir o tempo em minutos
        
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
                const element = document.getElementById(`t-${id}`);
                element.style.backgroundColor = '#ed667e'; // Vermelho claro para erro
                element.title = 'Erro ao atualizar';
        
                // Manter a cor por 1 segundo antes de voltar à cor original
                setTimeout(() => {
                    element.style.backgroundColor = ''; // Volta à cor original após 1 segundo
                }, 1000);
            });
        }
    </script>
    <script> // EXCLUI Editar Trabalho Material
        function confirmarExclusaoETAM(event) {
            event.preventDefault(); // Evita o comportamento padrão do link
        
            // Obtém o URL do link
            const url = event.currentTarget.getAttribute('href');
        
            // Exibe uma caixa de diálogo de confirmação
            const confirmacao = confirm("Tem certeza que deseja remover este Material?");
        
            // Se o usuário confirmar, prossegue com a exclusão
            if (confirmacao) {
                // Redireciona o navegador para o URL após a confirmação
                window.location.href = url;
            } else {
                // Se o usuário cancelar, não faz nada
            }
        }
        
        // Restante do seu script JavaScript...
    </script> 
    <script> // Atualizar campos valor_cobrado e frete
        function updateField(field, value) {
            const id = document.getElementById('trabalho-id').getAttribute('data-id');
        
            console.log(`Funcao updateField acionada: Updating field: ${field} with value: ${value} for trabalho ID: ${id}`);
        
            fetch(`/updateETcampos/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    field: field,
                    value: value.replace(',', '.')
                }),
            })
            .then(response => response.json().then(data => {
                if (response.ok) {
                    console.log('Field updated successfully:', data.message);
                } else {
                    console.error('Failed to update field:', data.message);
                }
            }))
            .catch(error => console.error('Erro:', error))
            .finally(() => { // Sempre recarrega a página, independentemente do resultado 
                location.reload(); // Recarrega a página
            });
            }
        
            function updateCellValorCobrado(element, field) {
                const value = element.value;
                console.log(`Funcao updateCellValorCobrado acionada: Updating ${field} with value: ${value}`);
                updateField(field, value);
            }
        
            function updateCellFrete(element, field) {
                const value = element.value;
                console.log(`Funcao updateCellFrete acionada: Updating ${field} with value: ${value}`);
                updateField(field, value);
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