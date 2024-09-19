<x-page-template bodyClass='g-sidenav-show  bg-gray-200'>
    <x-auth.navbars.sidebar activePage="painelControle" activeItem="painelAjustes" activeSubitem="">
    </x-auth.navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
         <!-- Navbar Route Titles -->
         <x-auth.navbars.navs.auth 
        pageTitle="Painel de Controle" 
        itemTitle="Ajustes da Empresa" 
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
@php
// Obter o ID da empresa do usuário autenticado
try {
    $user_empresa_id = Auth::user()->empresa_id;
} catch (Exception $e) {
    $user_empresa_id = 0;
}

// Obter os dados da empresa a partir do modelo TbEmpresa
$empresa = tb_empresa::find($user_empresa_id);

// Inicializar a variável nomeEmpresa
$nomeEmpresa = null;

// Verificar se a empresa foi encontrada e obter o nome da empresa
if ($empresa && isset($empresa->nome_empresa)) {
    $nomeEmpresa = $empresa->nome_empresa;
}
@endphp

     <!-- CABEÇALHO INICIAL inicio -->
     <div class="card m-4 p-4">
              <h5 class="mb-0">Ajustes da Empresa</h5>
              <div class="flex justify-between pt-4">
                  <!-- Button trigger modal -->
                  <!-- <button type="button" class="btn bg-gradient-primary" data-bs-toggle="modal" data-bs-target="#modalNovoMeuTrabalho">
                    Cadastrar Novo Serviço
                  </button> -->
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
               
     
         
        <div class="container-fluid my-3 py-3">
            <div class="row mb-5">
                <div class="col-lg-3">
                    <div class="card position-sticky top-1">
                        <ul class="nav flex-column bg-white border-radius-lg p-3">
                          
                            <li class="nav-item pt-2">
                                <a class="nav-link text-dark d-flex" data-scroll="" href="#basic-info">
                                    <i class="material-icons text-lg me-2">receipt_long</i>
                                    <span class="text-sm">Informações da Empresa</span>
                                </a>
                            </li>
                            <li class="nav-item pt-2">
                                <a class="nav-link text-dark d-flex" data-scroll="" href="#imposto">
                                    <i class="material-icons text-lg me-2">lock</i>
                                    <span class="text-sm">Ajustar Imposto</span>
                                </a>
                            </li>
                          
                           
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9 mt-lg-0 mt-4">
                    <!-- Card Profile -->
                    <div class="card card-body" id="profile">
                        <div class="row justify-content-center align-items-center">
                            
                            <div class="col-sm-auto col-8 my-auto">
                                <div class="h-100">
                                    <h5 class="mb-1 font-weight-bolder">
                                    {{ $nomeEmpresa}}
                                    </h5>
                                   
                                </div>
                            </div>
                            <div class="col-sm-auto ms-sm-auto mt-sm-0 mt-3 d-flex">
                                <!-- <label class="form-check-label mb-0">
                                    <small id="profileVisibility">
                                        Switch to invisible
                                    </small>
                                </label>
                                <div class="form-check form-switch ms-2 my-auto">
                                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault23"
                                        checked onchange="visible()">
                                </div> -->
                            </div>
                        </div>
                    </div>
                    <!-- Card Basic Info -->
                    <div class="card mt-4" id="basic-info">
                        <div class="card-header">
                            <h5>Informações da Empresa</h5>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-6">
                                    <div class="input-group input-group-static">
                                        <label>Nome da empresa</label>
                                        <input type="text" class="form-control" value="{{ $nomeEmpresa}}">
                                    </div>
                                </div>
                              
                            </div>
                           
                          
                            <div class="row mt-4">
                             
                                <div class="col-4">
                                    <div class="input-group input-group-static">
                                        <label>CEP da empresa</label>
                                        <input type="number" class="form-control" value="98600000" >
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="input-group input-group-static">
                                        <label>Domicílio Fiscal da Empresa</label>
                                        <input type="text" class="form-control" value="Três Passos" placeholder="Cidade">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="input-group input-group-static">
                                        <label>UF</label>
                                        <input type="text" class="form-control" value="{{ Auth::user()->uf }}">
                                    </div>
                                </div>
                            </div>
                                <div class="col-6">
                                <div class="row mt-4">
                                <label>Abrangência da Empresa</label>
                                <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                         
                                            <th class="text-center">
                                                <p class="mb-0">Local</p>
                                            </th>
                                            <th class="text-center">
                                                <p class="mb-0">Regional</p>
                                            </th>
                                            <th class="text-center">
                                                <p class="mb-0">Nacional</p>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                          
                                            <td>
                                                <div
                                                    class="form-check form-switch mb-0 d-flex align-items-center justify-content-center">
                                                    <input class="form-check-input" checked type="checkbox"
                                                        id="flexSwitchCheckDefault11">
                                                </div>
                                            </td>
                                            <td>
                                                <div
                                                    class="form-check form-switch mb-0 d-flex align-items-center justify-content-center">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="flexSwitchCheckDefault12">
                                                </div>
                                            </td>
                                            <td>
                                                <div
                                                    class="form-check form-switch mb-0 d-flex align-items-center justify-content-center">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="flexSwitchCheckDefault13">
                                                </div>
                                            </td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </div>
                                </div>
                                </div>
                            <div class="row mt-4">
                                <div class="col-6">
                                    <div class="input-group input-group-static">
                                        <label>Enquadramento Fiscal</label>
                                        <input type="text" class="form-control" value="Pequena Empresa / Simples">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input-group input-group-static">
                                        <label>Fone Comercial</label>
                                        <input type="number" class="form-control" value="{{ Auth::user()->fone }}">
                                    </div>
                                </div>
                                
                            </div>
                           
                        </div>
                    </div>
                    <!-- Card Change Password -->
                    <div class="card mt-4" id="imposto">
                        <div class="card-header">
                            <h5>Ajustar Imposto</h5>
                        </div>
                        <div class="card-body pt-0">
                            <div class=" flex text-center ">
                                <!-- Contêiner ao redor do input -->
                                <div class="input-with-percent">
                                    <input id="ajustar_imp"
                                
                                        type="text" 
                                        value="{{$empresaDados->imposto_padrao}}" 
                                        data-id="{{ $empresaDados->id }}" 
                                        placeholder="%" 
                                        style="font-weight: bold;color: #34acdb;"
                                        onblur="updateImposto()">
                                </div>
                            </div>
                        </div>
                            <p class="px-4 text-sm">Escolha quanto, em média, a empresa paga em impostos.</p>
                    </div>
                   
                  
                 
                   
                </div>
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
