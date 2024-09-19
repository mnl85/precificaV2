<x-page-template bodyClass='g-sidenav-show  bg-gray-200'>
    <x-auth.navbars.sidebar activePage="admin" activeItem="membros" activeSubitem="usuariosCadastrados">
    </x-auth.navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar Route Titles -->
        <x-auth.navbars.navs.auth 
        pageTitle="Admin" 
        itemTitle="Membros" 
        subItemTitle="Usuários" 
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
              <h5 class="mb-0">Usuários</h5>
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
                       <!-- ############################## -->

                       <div class="card my-4 p-4">
                        <div class="table-responsive">
                            <table class="table table-flush" id="datatable-basic">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nome do Usuário</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">e-mail</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pertence à Empresa</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">CPF</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fone</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">WhatsApp</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Permissão</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder "></th>
                                    </tr>
                                </thead>
                                <tbody>
                                  @foreach ($tb_users as $d)
                                        @php
                                            $empresa = $empresasMap[$d->id] ?? "Sem empresa associada";
                                        @endphp
                                    <tr data-row-id="{{ $d['id'] }}">
                                        <td class="text-sm font-weight-normal">{{ $d['id'] }}</td>
                                        <td class="text-sm font-weight-bold " >{{ $d['name'] }}</td>
                                        <td class="text-sm font-weight-bold " >{{ $d['email']}}</td>
                                        <td class="text-sm font-weight-bold " >
                                          <select name='empresa_id'   onchange="updateCell(this, 'empresa_id')">
                                            <option value="" {{ $d['empresa_id'] == null ? 'selected' : '' }}>!-Sem empresa associada</option>
                                                    @foreach($tb_empresas as $e)
                                                        <option value="{{$e->id}}" {{ $e['id'] == $d->empresa_id ? 'selected' : '' }}>{{$e->id}}-{{$e->nome_empresa}}</option>
                                                    @endforeach
                                            </select>
                                        </td>
                                        <td class="text-sm font-weight-bold " >{{ $d['cpf'] }}</td>
                                        <td class="text-sm font-weight-normal">{{ $d['fone'] }}</td>
                                        <td class="text-sm font-weight-normal">{{ $d['whatsapp'] }}</td>
                                        <td class="text-sm font-weight-normal"> <select  onchange="updateCell(this, 'role_id')">
                                              <option value="1" {{ $d['role_id'] == '1' ? 'selected' : '' }}>adm</option>
                                              <option value="2" {{ $d['role_id'] == '2' ? 'selected' : '' }}>usr</option>
                                              <option value="3" {{ $d['role_id'] == '3' ? 'selected' : '' }}>ger</option>
                                          </select>
                                        </td>
                                        
                                        <td class="text-sm font-weight-normal opacity-8"><a href="javascript:void(0);" onclick="confirmDelete('/adminApagarUsuario/{{ $d['id'] }}')"><i class="trash-icon opacity-8 fa fa-trash" aria-hidden="true"></i></a></td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                        </div>
         


                       <!-- ############################## -->

      
            <x-auth.footers.auth.footer></x-auth.footers.auth.footer>
    </div>

    </main>
    <x-plugins></x-plugins>
    @push('js')
    <script>
       function updateCell(cell, fieldName) {
    var row = cell.closest('tr');
    var userId = row.getAttribute('data-row-id');

    var newValue;
    if (cell.tagName.toLowerCase() === 'select') {
        newValue = cell.value;
    } else {
        newValue = cell.innerText.trim();
    }

    var data = {
        userId: userId,
        fieldName: fieldName,
        newValue: newValue,
        _token: '{{ csrf_token() }}'
    };

    // Log dos dados que serão enviados
    console.log('Enviando dados:', {
        userId: userId,
        fieldName: fieldName,
        newValue: newValue
        });

    fetch('/updateUsuarios', {
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
            cell.classList.add('update-success'); // Adiciona classe de sucesso
            cell.title = data.message;
            // Se houver sucesso, recarrega a página após 1 segundo
            setTimeout(() => {
                location.reload(); 
            }, 1000);
        } else {
            cell.classList.add('update-error'); // Adiciona classe de erro
            cell.title = data.message;
        }
        setTimeout(() => {
            cell.classList.remove('update-success', 'update-error'); // Remove as classes após um tempo
            cell.title = '';
        }, 2000);
    })
    .catch(error => {
        console.error('Error:', error);
        cell.classList.add('update-error');
        cell.title = 'Erro ao atualizar';
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
