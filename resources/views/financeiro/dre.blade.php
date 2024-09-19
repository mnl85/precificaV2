<x-page-template bodyClass='g-sidenav-show  bg-gray-200'>
    <x-auth.navbars.sidebar activePage="financeiro" activeItem="dre" activeSubitem="">
    </x-auth.navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar Route Titles -->
        <x-auth.navbars.navs.auth 
            pageTitle="Financeiro" 
            itemTitle="DRE" 
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
        @php
        $groupedData = [];
        foreach ($finalTable['Períodos'] as $index => $period) {
        $year = explode('-', $period)[0]; // Extrai o ano do período
        // Inicializa o ano se ainda não existir
        if (!isset($groupedData[$year])) {
        $groupedData[$year] = [
        'Períodos' => [],
        'Dados' => [
        'Entradas' => [],
        'Folha de Pessoal' => [],
        'Custo Materiais' => [],
        'Custo Depreciação' => [],
        'Custo Manutenção' => [],
        'Funcionários no Período' => [],
        'Horas por Mês' => [],
        'Custos Fixos' => []
        ]
        ];
        }
        // Adiciona o período e os dados ao ano correspondente
        $groupedData[$year]['Períodos'][] = $period;
        foreach (['Entradas', 'Folha de Pessoal', 'Custo Materiais', 'Funcionários no Período', 'Horas por Mês', 'Custos Fixos', 'Custo Depreciação', 'Custo Manutenção'] as $key) {
        $groupedData[$year]['Dados'][$key][] = $finalTable['Dados'][$key][$index];
        }
        }
        // Ordena os anos de forma decrescente
        krsort($groupedData);
        #dd($groupedData);
        @endphp
        <!-- ################################# -->
        <!-- Card header -->
        <div class="card m-4 p-4">
            <h5 class="mb-0">DRE</h5>
            <!-- MODAL inicio -->
            <div class="pt-4">
                <div class="col-m-4 ">
                    <button type="button" class="btn btn-block bg-gradient-primary mb-3" data-bs-toggle="modal" data-bs-target="#modal-form">Novo Período</button>
                    <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-body p-0">
                                    <div class="card card-plain">
                                        <div class="card-header pb-0 text-left">
                                            <h5 class="">Adicionar Novo Período</h5>
                                        </div>
                                        <div class="card-body">
                                            <form role="form text-left" action="{{asset('/DREnovoPeriodo')}}" method="POST">
                                                @csrf
                                                <?php
                                                // Array com os nomes dos meses em português brasileiro
                                                $meses_ptbr = array(
                                                    '01' => 'Janeiro',
                                                    '02' => 'Fevereiro',
                                                    '03' => 'Março',
                                                    '04' => 'Abril',
                                                    '05' => 'Maio',
                                                    '06' => 'Junho',
                                                    '07' => 'Julho',
                                                    '08' => 'Agosto',
                                                    '09' => 'Setembro',
                                                    '10' => 'Outubro',
                                                    '11' => 'Novembro',
                                                    '12' => 'Dezembro'
                                                );

                                                // Obtém o ano e mês atuais
                                                $ano_atual = date('Y');
                                                $mes_atual = date('m');

                                                // Exibe o select com a opção selecionada dinamicamente
                                                echo '<label for="anomes" class="mb-2">Ano e Mês</label>';
                                                echo '<select id="anomes" name="anomes" class="form-select">';

                                                // Loop para gerar as opções desde janeiro de 2022 até 3 meses no futuro
                                                for ($ano = 2022; $ano <= $ano_atual; $ano++) {
                                                    foreach ($meses_ptbr as $mes => $nome_mes) {
                                                        // Monta o valor e texto da opção
                                                        $valor_opcao = $ano . '-' . $mes;
                                                        $texto_opcao = "{$ano} - {$nome_mes}";

                                                        // Verifica se já existe um registro para o ano e mês atual na tabela $tb_dre
                                                        $registro_existente = false;
                                                        foreach ($tb_dre as $item) {
                                                            if ($item->ano == $ano && $item->mes == $mes) {
                                                                $registro_existente = true;
                                                                break; // Sai do loop se encontrar um registro correspondente
                                                            }
                                                        }

                                                        // Define se a opção está desabilitada ou não
                                                        $disabled_attr = $registro_existente ? 'disabled' : '';

                                                        // Verifica se é o mês selecionado
                                                        if ($ano == $ano_atual && $mes == $mes_atual) {
                                                            echo '<option value="' . $valor_opcao . '" selected ' . $disabled_attr . '>' . $texto_opcao . '</option>';
                                                        } else {
                                                            echo '<option value="' . $valor_opcao . '" ' . $disabled_attr . '>' . $texto_opcao . '</option>';
                                                        }

                                                        // Calcula o próximo mês
                                                        $mes_proximo = (int)$mes + 1;
                                                        $ano_proximo = $ano;
                                                        if ($mes_proximo > 12) {
                                                            $mes_proximo = 1;
                                                            $ano_proximo++;
                                                        }

                                                        // Se chegou até 3 meses no futuro, interrompe o loop
                                                        if ($ano_proximo > $ano_atual || ($ano_proximo == $ano_atual && $mes_proximo > $mes_atual + 3)) {
                                                            break 2; // Interrompe ambos os loops (ano e mês)
                                                        }
                                                    }
                                                }

                                                echo '</select>';
                                                ?>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mt-2">
                                                            <label for="entrada" class="block text-sm font-medium text-gray-700 ">Total de Entradas</label>
                                                            <input required type="text" id="entrada" name="entrada" class="form-control">
                                                        </div>
                                                        <div class="mt-2">
                                                            <label for="folha" class="block text-sm font-medium text-gray-700 ">Total da Folha</label>
                                                            <input required type="text" id="folha" name="folha" class="form-control">
                                                        </div>
                                                        <div class="mt-2">
                                                            <label for="materiais" class="block text-sm font-medium text-gray-700 ">Total de Materiais</label>
                                                            <input required type="text" id="materiais" name="materiais" class="form-control">
                                                        </div>
                                                        <!-- <div class="mt-2">
                                                            <label for="depreciacao" class="block text-sm font-medium text-gray-700 ">Depreciação</label>
                                                            <input required type="text" id="depreciacao" name="depreciacao" class="form-control">
                                                        </div> -->
                                                        <div class="mt-2">
                                                            <label for="manutencao" class="block text-sm font-medium text-gray-700 ">Custos com Manutenção</label>
                                                            <input required type="text" id="manutencao" name="manutencao" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mt-2">
                                                            <label for="funcionarios_qtd" class="block text-sm font-medium text-gray-700 ">Funcionários no período</label>
                                                            <input required type="text" id="funcionarios_qtd" name="funcionarios_qtd" class="form-control">
                                                        </div>
                                                        <div class="mt-2">
                                                            <label for="horas_mes_func" class="block text-sm font-medium text-gray-700 ">Horas de Trabalho Mensal</label>
                                                            <input required type="text" id="horas_mes_func" value="160" name="horas_mes_func" class="form-control">
                                                        </div>
                                                        <div class="mt-2">
                                                            <label for="custos_fixos" class="block text-sm font-medium text-gray-700 ">Custos Fixos</label>
                                                            <input required type="text" id="custos_fixos" name="custos_fixos" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-center pt-0 px-lg-2 px-1  justify-between">
                                                    <div class="modal-footer ">
                                                        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Fechar</button>
                                                        <button type="submit" class="btn bg-gradient-primary">Salvar</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- MODAL fim -->
        </div>
        @if (empty($finalTable['Períodos']))
        <h5>Tabela DRE vazia. Adicione dados.</h5>
        @else
        <!-- ##### FOREACH DO ANO ##### -->
        @foreach ($groupedData as $year => $data)
        <div class="card m-4 p-4">
            <h5>Ano: {{ $year }}</h5>
            <div class="table-responsive">
                <table class="table table-flush" id="datatable-basic">
                    <thead class="thead-light">
                        <tr>
                            <th class="px-1"></th>
                            @foreach($data['Períodos'] as $header)
                            <th class="px-1 text-center">
                                {{ $header }}
                                <a href="#" onclick="deleteColumn('{{ $header }}')" class="delete-button" data-anomes="{{ $header }}">
                                <i class="trash-icon opacity-8 fa fa-trash" aria-hidden="true"></i></a>
                                </a>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(['Entradas', 'Folha de Pessoal', 'Custo Materiais', 'Funcionários no Período', 'Horas por Mês', 'Custos Fixos', 'Custo Depreciação', 'Custo Manutenção'] as $key)
                        <tr>
                            <td class="px-1 text-center text-xs ">{{ ucwords(str_replace('_', ' ', $key)) }}</td>
                            @foreach($data['Dados'][$key] as $value)
                            @if (in_array($key, ['Entradas', 'Folha de Pessoal', 'Custo Materiais', 'Custos Fixos', 'Custo Depreciação', 'Custo Manutenção']))
                            <td   class="px-1 text-center text-xs ">R$ {{ number_format(floatval($value), 2, ',', '.') }}</td>
                            @else
                            <td  class="px-1 text-center text-xs ">{{ $value }}</td>
                            @endif
                            @endforeach
                        </tr>
                        @endforeach   
                        <tr>
                            <td  class="px-1 text-center text-xs ">Custo Hora</td>
                            @foreach($data['Períodos'] as $index => $period)
                            @php
                            $custoHora = ($data['Dados']['Folha de Pessoal'][$index]+$data['Dados']['Custos Fixos'][$index]+floatval($data['Dados']['Custo Depreciação'][$index])+floatval($data['Dados']['Custo Manutenção'][$index]))  / ($data['Dados']['Funcionários no Período'][$index] * $data['Dados']['Horas por Mês'][$index]);
                            @endphp
                            <td class="px-1 text-center text-xs ">R$ {{ number_format($custoHora, 2, ',', '.') }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td  class="px-1 text-center text-xs ">Custo Minuto</td>
                            @foreach($data['Períodos'] as $index => $period)
                            @php
                            $custoHora = ($data['Dados']['Folha de Pessoal'][$index]+$data['Dados']['Custos Fixos'][$index]+floatval($data['Dados']['Custo Depreciação'][$index])+floatval($data['Dados']['Custo Manutenção'][$index]))  / ($data['Dados']['Funcionários no Período'][$index] * $data['Dados']['Horas por Mês'][$index]);
                            $custoMinuto = $custoHora / 60;
                            @endphp
                            <td  class="px-1 text-center text-xs ">R$ {{ number_format($custoMinuto, 2, ',', '.') }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <td  class="px-1 text-center text-xs ">Resultado</td>
                            @foreach($data['Períodos'] as $index => $period)
                            @php
                            $resultado = floatval($data['Dados']['Entradas'][$index]) - (floatval($data['Dados']['Folha de Pessoal'][$index]) + floatval($data['Dados']['Custo Materiais'][$index]) + floatval($data['Dados']['Custos Fixos'][$index]) + floatval($data['Dados']['Custo Manutenção'][$index]));
                            @endphp
                            <td style="font-weight: bold; color: {{ $resultado > 0 ? '#24dba7' : '#d9265c' }};" class="px-1 text-center text-xs ">R$ {{ number_format($resultado, 2, ',', '.') }}</td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach
        @endif
        <!-- ##### FOREACH DO ANO ##### -->
        <script>
            function deleteColumn(columnName) {
                // Função para excluir coluna DRE
                document.addEventListener('click', function(event) {
                    const button = event.target.classList.contains('delete-button') ? event.target : event.target.closest('.delete-button');
                    
                    if (button) {
                        const anoMes = button.dataset.anomes;
                        const url = `/deleteColumnDRE/${anoMes}`;
            
                        if (confirm("Tem certeza que deseja excluir a coluna " + anoMes + "?")) {
                            fetch(url, {
                                method: 'GET',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
                            })
                            .then(data => {
                                location.reload(); // Recarrega a página após a exclusão
                            });
                        }
                    }
                });
            }
        </script>
        <!-- ################################# -->
        <x-auth.footers.auth.footer></x-auth.footers.auth.footer>
        </div>
    </main>
    <x-plugins></x-plugins>
    @push('js')
    <script src="{{ asset('assets') }}/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/datatables.js"></script>
    <script>
        const dataTableBasic = new simpleDatatables.DataTable("#datatable-basic", {
            searchable: false,
            fixedHeight: false
        });
        
    </script>
    @endpush
</x-page-template>