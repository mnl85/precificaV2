<x-page-template bodyClass='g-sidenav-show  bg-gray-200'>
    <x-auth.navbars.sidebar activePage="painelControle" activeItem="painelUsuarios" activeSubitem="">
    </x-auth.navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
         <!-- Navbar Route Titles -->
         <x-auth.navbars.navs.auth 
        pageTitle="Painel de Controle" 
        itemTitle="Ajustes de Usuários" 
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
<style>
    /* Estiliza o contêiner que envolve o input */
    .input-with-percent {
        position: relative;
        display: inline-block;
    }

    /* Define a largura do input para ser menor e centraliza o texto */
    .input-with-percent input.input-percentage {
        width: 100px; /* Ajusta para metade do tamanho original ou conforme necessário */
        padding-right: 20px; /* Espaço para o % */
        text-align: center; /* Alinha o texto à direita para ficar próximo ao % */
    }

    /* Adiciona o símbolo % como conteúdo fictício */
    .input-with-percent::after {
        content: '%';
        position: absolute;
        top: 50%;
        right: 15px; /* Ajuste a posição horizontal conforme necessário */
        transform: translateY(-50%);
        color: gray; /* Cor do símbolo % */
        pointer-events: none; /* Evita que o símbolo interfira na interação com o input */
    }

    /* Centraliza verticalmente o texto no input */
    .input-with-percent input.input-percentage {
        height: 2.5rem; /* Ajusta conforme a altura desejada do input */
        line-height: 2.5rem; /* Deve ser igual à altura para centralizar verticalmente */
    }
</style>
         <!-- CABEÇALHO INICIAL inicio -->
         <div class="card m-4 p-4">
              <h5 class="mb-0">Ajustes da Conta</h5>
              <div class="flex justify-between pt-4">
                  <!-- Button trigger modal -->
                  <button type="button" class="btn bg-gradient-primary" data-bs-toggle="modal" data-bs-target="#modalNovoMeuTrabalho">
                    Cadastrar Novo Usuário
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
                        <!-- ################################## -->
                        <div class="card m-4 p-4">
                        <div class="table-responsive">
                            <table class="table table-flush" id="datatable-basic">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="px-1 text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                        <th class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nome do Usuário</th>
                                        <th class="px-1 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">e-mail</th>
                                        <th class="px-1 text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">CPF</th>
                                        <th class="px-1 text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fone</th>
                                        <th class="px-1 text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">WhatsApp</th>
                                        <th class="px-1 text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Permissão</th>
                              
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tb_users as $d)
                                    @php
                                        $empresa = $empresasMap[$d->empresa_id] ?? "Sem empresa associada";
                                    @endphp
                                    <tr data-row-id="{{ $d['id'] }}">
                                        <td class="text-sm font-weight-normal text-center">{{ $d['id'] }}</td>
                                        <td class="text-sm font-weight-bold corUserEdit px-1" >{{ $d['name'] }}</td>
                                        <td class="text-sm font-weight-bold corUserEdit px-1" >{{ $d['email']}}</td>
                                        <td class="text-sm font-weight-bold corUserEdit px-1 text-center" >{{ $d['cpf'] }}</td>
                                        <td class="text-sm font-weight-bold corUserEdit px-1 text-center">{{ $d['fone'] }}</td>
                                        <td class="text-sm font-weight-bold corUserEdit px-1 text-center">{{ $d['whatsapp'] }}</td>
                                        <td class="text-sm font-weight-bold corUserEdit px-1 text-center"> <select  onchange="updateCell(this, 'role_id')">
                                            @if($d['role_id'] == '1' )
                                            <option value="1" {{ $d['role_id'] == '1' ? 'selected' : '' }}>adm</option>
                                            @else
                                            <option value="2" {{ $d['role_id'] == '2' ? 'selected' : '' }}>usr</option>
                                            <option value="3" {{ $d['role_id'] == '3' ? 'selected' : '' }}>ger</option>
                                            @endif
                                          </select>
                                        </td>
                                        
                                        <td class="text-sm font-weight-bold opacity-8"><a href="javascript:void(0);" onclick="confirmDelete('/adminApagarUsuario/{{ $d['id'] }}')"><i class="trash-icon opacity-8 fa fa-trash" aria-hidden="true"></i></a></td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <span class="px-6 py-2" style="font-weight: light; font-size: 12px; color: gray;">* Células onde o texto é <span style="font-weight: bold; color: #34acdb;">azul </span> são editáveis. Altere o valor e clique fora para salvar.</span>
                        </div>
                        </div>
                        <!-- ################################## -->

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
    <script>

function updateImposto() {
    var inputElement = document.getElementById('ajustar_imp');
    var newValue = inputElement.value.trim();
    var empresaId = inputElement.getAttribute('data-id');

    // Prepare the data to be sent to the server
    var data = {
        fieldName: 'imposto_padrao',
        newValue: newValue,
        empresaId: empresaId, // Incluindo o ID da empresa na solicitação
        _token: '{{ csrf_token() }}'
    };

    // Send the update request to the server
    fetch('/updateImposto', {
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
            inputElement.style.backgroundColor = '#55f2c3'; // Verde claro para sucesso
            inputElement.title = data.message; // Exibir mensagem de sucesso como tooltip
            // Se houver sucesso, recarrega a página após 1 segundo
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            inputElement.style.backgroundColor = '#f8d7da'; // Vermelho claro para erro
            inputElement.title = data.message; // Exibir mensagem de erro como tooltip
            // Remover a cor de destaque após 1 segundo
            setTimeout(() => {
                inputElement.style.backgroundColor = ''; // Remover cor de fundo
                inputElement.title = ''; // Remover tooltip
            }, 1000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        inputElement.style.backgroundColor = '#ed667e'; // Vermelho claro para erro
        inputElement.title = 'Erro ao atualizar';

        // Remover a cor de destaque após 1 segundo
        setTimeout(() => {
            inputElement.style.backgroundColor = ''; // Remover cor de fundo
            inputElement.title = ''; // Remover tooltip
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
