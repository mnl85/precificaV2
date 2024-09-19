<x-page-template bodyClass='g-sidenav-show  bg-gray-200'>
  <x-auth.navbars.sidebar activePage="meusDados" activeItem="meusTrabalhos" activeSubitem="">
  </x-auth.navbars.sidebar>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar Route Titles -->
        <x-auth.navbars.navs.auth 
        pageTitle="Meus Dados" 
        itemTitle="Meus Trabalhos" 
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


          <!-- <button type="button" class="btn bg-gradient-warning" data-bs-toggle="modal" data-bs-target="#modalNovoMeuTrabalho">
              Buscar Base
            </button>
          <div class="card"> -->

      
          <div class="container-fluid py-4">
            <!-- Card header -->
            <div class="card p-4">
              <h5 class="mb-0">Meus Trabalhos</h5>
              <div class="flex justify-between pt-4">
                  <!-- Button trigger modal -->
                  <button type="button" class="btn bg-gradient-primary" data-bs-toggle="modal" data-bs-target="#modalNovoMeuTrabalho">
                    Cadastrar Novo Trabalho
                  </button>
              </div>
            </div>
              <!-- MODAL inicio --> 
                <div class="modal fade" id="modalNovoMeuTrabalho" tabindex="-1" role="dialog" aria-labelledby="modalNovoMeuTrabalhoLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title font-weight-normal" id="modalNovoMeuTrabalhoLabel">Novo Trabalho</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <form action="{{asset('/novoMeuTrabalho')}}" method="POST">
                          @csrf
                            <div class="input-group input-group-static">
                              <label>Nome do Trabalho</label>
                              <input type="text" class="form-control"  id="trabalho" name="trabalho" >
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
       
            @if (Session::has('status'))
              <div class="alert alert-success alert-dismissible text-white mx-4" role="alert">
                <span class="text-sm">{{ Session::get('status') }}</span>
                <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            @endif
            

 
            <!-- ###### -->
           
            @if ($tb_meus_trabalhos->isEmpty())
              <h3 class="text-center">Tabela de Trabalhos vazia. Adicione um trabalho.</h3>
              <br>
              <div class="text-center flex justify-between">
                <!-- include('components.modalNovoMeuTrabalho') -->
                <!-- include('components.botaoBuscarBaseTrabalhos') -->
              </div>
            @else
              <div class="flex justify-between ">
                <span style="max-height:40px" class="flex justify-between ">
                  <!-- include('components.botaoBuscarBaseTrabalhos') -->
                  <!-- include('components.modalAdicionarMeuTrabalho') -->
                </span>
              </div>
         
              
  
              <div class="card my-4 p-4">
                <div class="table-responsive">
                  <table class="table table-flush" id="datatable-basic">
                    <thead class="thead-light">
                      <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">ID</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">Nome dos Trabalhos</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">Qtd <br> Materiais</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">Qtd <br> Servicos</th>
                        @if (!$view_tb_dre->isEmpty())
                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">Custo <br> Total</th>
                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">Valor <br> Cobrado</th>
                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">Lucro <br> Bruto</th>
                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">Imposto</th>
                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">Margem <br> Lucro L.</th>
                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 px-1">QL</th>
                        @endif
                      </tr>
                    </thead>
                    <tbody>
                      @php
                        if ($view_tb_dre->isEmpty()) {
                          $custosFixos = 0;
                        } else {
                          $custosFixos = (floatval($view_tb_dre[0]->custos_fixos) / floatval($view_tb_dre[0]->funcionarios_qtd) / (floatval($view_tb_dre[0]->horas_mes_func) * 60));
                        }
                      @endphp

                      @foreach ($tb_meus_trabalhos as $d)
                        @php
                          // Decodifica os dados de materiais e serviços.
                          $materiais = collect(json_decode($d['materiais'], true));
                          $servicos = collect(json_decode($d['servicos'], true));
                          #dd($servicos);

                          // Filtra e adiciona quantidade ao material.
                          $materiaisFiltrados = $materiais->map(function ($ma) use ($tb_meus_materiais) {
                            $materialFiltrado = $tb_meus_materiais->firstWhere('id_tb_meus_materiais', $ma['id']);
                            if ($materialFiltrado) {
                              $materialFiltrado['qtd'] = $ma['qtd']; // Adiciona a quantidade do material
                              return $materialFiltrado;
                            }
                            return null;
                          })->filter(); // Remove nulos.

                          // Calcula o custo total dos materiais.
                          $custoMateriais = $materiaisFiltrados->sum(function ($material) {
                            $valorPorcao = floatval($material['custo']) / floatval($material['qtd_calculada']);
                            return $valorPorcao * floatval($material['qtd']);
                          });
                          // Calcula o tempo total dos serviços.
                          $total_tempo = $servicos->sum('t');

                          // Converte tempo total para horas.
                          $tempo = $total_tempo;
                          $total_tempo_mins = $total_tempo / 60;
                          #dd($total_tempo_mins);

                          // Valores base para cálculo.
                          $valorCobrado = floatval($d['valor_cobrado']);
                          $frete = $d['frete'];

                          // Calcula os custos.
                          if ($view_tb_dre->isEmpty()) {
                            $custoServicos = 0;
                            $custo_ms = 0;
                          } else {
                            $custoMinuto = $mediaPonderada;
                            $custoServicos = $custoMinuto * $total_tempo_mins;
                            $custo_ms = $custoServicos + $custoMateriais;
                          }

                          $custo_msf = $custo_ms + $frete;
                          $lucro_bruto = $valorCobrado - $custo_msf;
                          $margem_bruta = ($valorCobrado > 0) ? ($lucro_bruto / $valorCobrado) * 100 : 0;
                          $valor_imposto = ($lucro_bruto > 0) ? $lucro_bruto * $imposto_padrao / 100 : 0;
                          $lucro_liq = $lucro_bruto - $valor_imposto;
                          $margem_ll = ($valorCobrado > 0) ? ($lucro_liq / $valorCobrado) * 100 : 0;

                          // Calcula qualidade do lucro.
                          $ql = ($tempo > 0) ? $lucro_liq / ($tempo / 60) : 0;
                        @endphp
                        <tr data-row-id="{{ $d['id'] }}">
                          <td class="text-sm font-weight-normal px-1">{{ $d['id'] }}</td>
                          <td class="text-sm font-weight-normal px-1">{{ $d['trabalho'] }}</td>
                          <td class="text-sm font-weight-normal px-1">{{ count($materiais) }}</td>
                          <td class="text-sm font-weight-normal px-1">{{ count($servicos) }}</td>
                          @if (!$view_tb_dre->isEmpty())
                            <td class="text-sm font-weight-normal valor-monetario px-1">{{ number_format($custo_msf, 2, ',', '.') }}</td>
                            <td class="text-sm font-weight-bold valor-monetario corUserEdit  px-1" contenteditable="true" data-field="valor_cobrado" onblur="updateCell(this, 'valor_cobrado')">{{ number_format(floatval($d['valor_cobrado']), 2, ',', '.') }}</td>
                            <td class="text-sm font-weight-normal valor-monetario px-1">{{ number_format($lucro_bruto, 2, ',', '.') }}</td>
                            <td class="text-sm font-weight-normal valor-monetario px-1">{{ number_format($valor_imposto, 2, ',', '.') }}</td>
                            <td class="text-sm font-weight-normal valor-monetario px-1">{{ number_format($margem_ll, 1, ',', '.') }}%</td>
                            <td class="text-sm font-weight-normal px-1">{{ number_format($ql, 2, ',', '.') }}</td>
                          @endif
                          <td class="text-sm font-weight-normal opacity-8"><a href="/editarMeuTrabalho/{{ $d['id'] }}" data-id="{{ $d['id'] }}"><i class="edit-icon opacity-8 fa fa-edit" aria-hidden="true"></i></a>
                          &nbsp; <a href="javascript:void(0);" onclick="confirmDelete('/apagarMeuTrabalho/{{ $d['id'] }}')"><i class="trash-icon opacity-8 fa fa-trash" aria-hidden="true"></i></a></td>
               
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
                @if(!$tb_meus_trabalhos->isEmpty())
              <span class="px-6 py-2" style="font-weight: light; font-size: 12px; color: gray;">* Células onde o texto é <span style="font-weight: bold; color: #34acdb;">azul</span> são editáveis. Altere o valor e clique fora para salvar.</span>
            @endif
              </div>
            @endif
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
                title: "Você tem certeza apagar este trabalho?",
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
                        text: "O trabalho foi apagado",
                        icon: "success"
                    });
                } 
            });
        }
    </script>
  <script>
 function updateCell(element, field) {
    const row = element.closest('tr');
    const rowId = row.dataset.rowId;
    const value = element.innerText.trim();

    fetch(`/updateTrabalhoValorCobrado/${rowId}`, {
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
            element.innerText = data.updatedValue; // Atualiza a célula com o novo valor retornado

            // Atualizar outros valores relacionados na linha, se necessário
            if (data.updatedFields) {
                Object.keys(data.updatedFields).forEach(updatedField => {
                    const cell = row.querySelector(`[data-field="${updatedField}"]`);
                    if (cell) {
                        cell.innerText = data.updatedFields[updatedField];
                    }
                });
            }

            // Exibir mensagem de sucesso com SweetAlert
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
                title: "Valor atualizado com sucesso!"
            });
            // Reinicia a página após 3 segundos
            setTimeout(() => {
                location.reload();
            }, 1000);

            setTimeout(() => {
                element.style.backgroundColor = ''; // Volta à cor original após 1 segundo
            }, 1000);
        } else {
            element.style.backgroundColor = '#f28ea0'; // Vermelho claro para erro
            element.title = data.message;

            // Exibir mensagem de erro com SweetAlert
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Erro ao atualizar o valor! Se o erro persistir, contate o administrador.",
                showConfirmButton: false,
                timer: 10000
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        element.style.backgroundColor = '#f28ea0'; // Vermelho claro para erro
        element.title = 'Erro ao atualizar';

        // Exibir mensagem de erro com SweetAlert
        Swal.fire({
            position: "top-end",
            icon: "error",
            title: "Erro ao atualizar o valor! Se o erro persistir, contate o administrador.",
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
