<x-page-template bodyClass='g-sidenav-show  bg-gray-200'>
    <x-auth.navbars.sidebar activePage="admin" activeItem="membros" activeSubitem="empresasCadastradas">
    </x-auth.navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar Route Titles -->
        <x-auth.navbars.navs.auth 
        pageTitle="Admin" 
        itemTitle="Membros" 
        subItemTitle="Empresas" 
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
            <div class="card p-4"> 
                <h5 class="mb-0">Empresas</h5>
                <div class="flex justify-between pt-4">
                  <!-- Button trigger modal -->
                  <button type="button" class="btn bg-gradient-primary" data-bs-toggle="modal" data-bs-target="#modalNovoMeuTrabalho">
                    Cadastrar Nova Empresa
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
            <!-- ########################### -->

            <div class="card my-4 p-4">
                <div class="table-responsive">
                    <table class="table table-flush" id="datatable-basic">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nome da Empresa</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nome do Responsável</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder "></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($tb_empresa as $d)

                            <tr data-row-id="{{ $d['id'] }}">
                                <td class="text-sm font-weight-normal">{{ $d['id'] }}</td>
                                <td class="text-sm font-weight-bold " >{{ $d['nome_empresa'] }}</td>
                                
                                <td class="text-sm font-weight-bold " >
                                
                                    <select name="id_user_responsavel"  onchange="updateCell(this, 'id_user_responsavel')">
                                    <option value="" @if ($d->id_user_responsavel == null  ) selected @endif>!-Sem responsável associado</option>
                                    @foreach ($tb_users as $u)
                                        
                                            <option value="{{ $u->id }}" @if ($u->id == $d->id_user_responsavel) selected @endif >{{ $u->name }}</option>
                                    
                                    @endforeach
                                </select>
                                </td>
                                
                                
                                <td class="text-sm font-weight-normal opacity-8"><a href="javascript:void(0);" onclick="confirmDelete('/adminApagarEmpresa/{{ $d['id'] }}')"><i class="trash-icon opacity-8 fa fa-trash" aria-hidden="true"></i></a></td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- ########################### -->
        <x-auth.footers.auth.footer></x-auth.footers.auth.footer>
    </div>


    </main>
    <x-plugins></x-plugins>
    @push('js')
    <script>
    function updateCell(element, field) {
        let rowElement = element;
        while (rowElement && !rowElement.hasAttribute('data-row-id')) {
            rowElement = rowElement.parentElement;
        }
        
        const rowId = rowElement ? rowElement.getAttribute('data-row-id') : null;
        const value = field === 'id_user_responsavel' ? element.value : element.innerText.trim();

        // Log dos dados que serão enviados
        console.log('Enviando dados:', {
            rowId: rowId,
            field: field,
            value: value
        });

        fetch(`/updateDadosEmpresa/${rowId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ [field]: value })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                element.style.backgroundColor = '#55f2c3'; // Verde claro para sucesso
                element.title = data.message;
                // Se houver sucesso, recarrega a página após 1 segundo
            setTimeout(() => {
                location.reload(); 
            }, 1000);
            } else {
                element.style.backgroundColor = '#f8d7da'; // Vermelho claro para erro
                element.title = data.message;
            }
            setTimeout(() => {
                element.style.backgroundColor = '';
                element.title = '';
            }, 2000);
        })
        .finally(() => { location.reload(); })
        .catch(error => {
            console.error('Error:', error);
            element.style.backgroundColor = '#ed667e'; // Vermelho claro para erro
            element.title = 'Erro ao atualizar';
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
