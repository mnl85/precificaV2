<!-- Button trigger modal -->
<button type="button" class=" btn bg-gradient-primary" data-bs-toggle="modal" data-bs-target="#modal-adicionarProducao">
    Adicionar Produção
</button>

<!-- MODAL COMPLEXO 2 MATERIAIS inicio -->
<div class="modal fade" id="modal-adicionarProducao" tabindex="-1" role="dialog" aria-labelledby="modal-adicionarProducao-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-normal p-4" id="modal-adicionarProducao-label">Adicionar Nova Produção</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @php
                
                    // Data atual (ano-mês)
                    $mes_atual = date("Y-m");

                    // Último ano-mês da tabela DRE
                    $ultimo_anomes_dre = $view_tb_dre[0]->anomes; 

                    // Convertendo as datas em objetos DateTime
                    $mes_atual_date = new DateTime($mes_atual); 
                    $ultimo_anomes_date_dre = new DateTime($ultimo_anomes_dre);

                    // Calculando a diferença em dias
                    $diferenca_dre = $mes_atual_date->diff($ultimo_anomes_date_dre);
                    $total_tempo_dre = ($diferenca_dre->days)/30;

                @endphp
                @if($total_tempo_dre > 2) 
                
                <!-- <h5 class="modal-title font-weight-normal corCocaRed" id="modal-adicionarProducao-label">Atenção: O seu último valor no DRE tem mais de 1 mês</h5> -->

                @endif
                <form class="px-4" action="{{ asset('/adicionarProducaoNovo') }}" method="POST">
                    @csrf
                    
                    <div class="mt-4">
                        <label for="id_trabalho" class="block text-sm font-medium text-gray-700 ">Trabalho</label>
                        <select required id="id_trabalho" name="id_trabalho" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50  dark:border-gray-600 dark:placeholder-gray-400 ">
                            @foreach ($tb_meus_trabalhos as $t)
                            @if ($t->valor_cobrado==null || $t->valor_cobrado==''|| $t->valor_cobrado=='0' )
                                <option disabled value="">{{ $t->id }} - {{ $t->trabalho }} - Trabalho sem 'Valor Cobrado' cadastrado</option>
                            @elseif ($t->servicos=='[]'|| $t->materiais=='[]')
                            <option disabled value="">{{ $t->id }} - {{ $t->trabalho }} - Trabalho sem Serviço ou Material cadastrado</option>
                          
                            @else
                                <option value="{{ $t->id }}">{{ $t->id }} - {{ $t->trabalho }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div> 
                    @php
                        $meses = [
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
                        ];
                    @endphp
                
                <div class="row my-4">
                    <div class="input-group input-group-outline py-3" style="max-width: 50%;" >
                        <label for="ref_dre" class="block text-sm font-medium text-gray-700 ">Período Referenciado do DRE</label>
                        <select required class="input-group custom-select pr-2 gray" id="ref_dre" name="ref_dre" placeholder="escolha...">
                        @foreach ($view_tb_dre as $t)
                            <option value="{{ $t->id_tb_dre }}">{{ $t->ano }} - {{ $meses[$t->mes] }}</option>
                         
                        @endforeach
                        </select>
                    </div>
                  
                    <div class="input-group input-group-outline py-3" style="max-width: 50%;" >
                            <label class="form-label">Qtd Trabalhos Realizados</label>
                            <input type="number" class="form-control" id="quantidade" name="quantidade"  required>
                        </div>
                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn bg-gradient-primary">Salvar</button>
                    </div>
                </form>

            <!-- <div class="modal-body">
                <form action="{{asset('/adicionarProducaoNovo')}}" method="POST">
                    @csrf
                    
                    <div class="input-group input-group-outline  my-3" >
                        <label class="form-label">Nome do Novo Material</label>
                        <input type="text" class="form-control" id="material" name="material" required>
                    </div>
                    <div class="input-group input-group-outline  my-3"  >
                        <label class="form-label">Apresentação</label>
                        <input type="text" class="form-control" id="apresentacao" name="apresentacao" required>
                    </div>
                
                    <div class="row justify-between my-3 ">
                        <div class="input-group input-group-outline " style="max-width: 50%;" >
                            <label class="form-label">Custo</label>
                            <input type="number" class="form-control" id="custo" name="custo"  required>
                        </div>
                        <div class="input-group input-group-outline " style="max-width: 50%;" >
                            <label class="form-label">Quantidade Calculada</label>
                            <input type="number" class="form-control" id="quantidade" name="quantidade"  required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn bg-gradient-primary">Salvar</button>
                    </div>
                </form>
            </div> -->
        </div>
    </div>
</div>
<!-- MODAL COMPLEXO 2 MATERIAIS fim -->