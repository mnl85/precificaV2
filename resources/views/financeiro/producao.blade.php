<x-page-template bodyClass='g-sidenav-show  bg-gray-200'>
    <x-auth.navbars.sidebar activePage="financeiro" activeItem="producao" activeSubitem="">
    </x-auth.navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
         <!-- Navbar Route Titles -->
         <x-auth.navbars.navs.auth 
        pageTitle="Financeiro" 
        itemTitle="Produção" 
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
    <div class="card m-4 p-4">
        <h5 class="mb-0">Produção</h5> 
        <div class="flex justify-between pt-4">
                
                @include('financeiro.modalAdicionarProducao')
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
                    
    @if( auth()->user()->role_id == '1' )
        <div class="card m-4 p-4">
            <div class="row">
                <div class="col-5">
              
                        <div class="card-header"> 
                            <h5 class="mb-0">Refatorar Produção</h5> 
                            <p>Permissão exclusiva do Admin</p>
                        </div>
                        <div class="card-body"> 
                            <form action="{{asset('/refatorarProducao/')}}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <select class="input-group custom-select pr-2 gray" id="empresa" name="empresa" >
                                        <option value="">Escolha a empresa...</option>
                                        @foreach ($tb_empresa as $t)
                                            <option value="{{ $t['id'] }}">{{ $t['nome_empresa'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="">
                                    <button type="submit" class="btn bg-gradient-primary">Refatorar</button>
                                </div>
                            </form>
                        </div>
                
                </div>
            </div>
        </div>
        @endif

    <div class="card p-4 m-4 ">
        <div class="table-responsive">
            
            @if($view_tb_dre->isEmpty())
                <h3>Tabela DRE vazia. Adicione dados no DRE. <a href="/dre" style="color:tomato"><u>Clique Aqui</u></a></h3>
                <br>
                @elseif ($grouped->isEmpty())
                <h3>Tabela de Produção vazia. Adicione dados. </h3>
                <br>
                <div class="text-center">
                    @include('financeiro.modalAdicionarProducao')
                </div>
            @else
           
            @if(count($valoresUnicosProducao)<2) <p style="font-weight: light; font-size: 12px; color: gray;" >Adicione mais de 1 trabalho distinto para ativar o Painel de Controle</p> @endif
                <table class="table table-flush" id="datatable-basic">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Período</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Trabalho</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Quantidade <br> Executada</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Valor <br> Cobrado</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Receita <br> Bruta</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Custo <br> Total</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Lucro <br> Bruto</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white  divide-y divide-gray-200">
                        @foreach($grouped as $anomes => $items)
                            @foreach($items as $item)
                                <tr data-id="{{ $item->id }}">
                                    <td class="text-sm font-weight-normal " >{{ $anomes }}</td>
                                    <td class="text-sm font-weight-normal " >{{ $item->trabalho }}</td>
                                    <td class="text-sm font-weight-normal corUserEdit" style="font-weight: bold;color: #34acdb;" contenteditable="true" onblur="updateCell(this, 'quantidade')">{{ $item->quantidade }}</td>
                                    <td class="text-sm font-weight-normal " > R$ {{ number_format($item->valor_cobrado, 2, ',', '.') }} </td>
                                    <td class="text-sm font-weight-normal " > R$ {{ number_format($item->valor_cobrado*$item->quantidade, 2, ',', '.') }} </td>
                                    <td class="text-sm font-weight-normal " > R$ {{ number_format($item->custo_total*$item->quantidade, 2, ',', '.') }} </td>
                                    <td class="text-sm font-weight-normal " > R$ {{ number_format($item->valor_cobrado*$item->quantidade-$item->custo_total*$item->quantidade, 2, ',', '.') }} </td>
                                
                                    <td class="text-sm font-weight-normal opacity-8"><a href="javascript:void(0);" onclick="confirmDelete('/apagarProducao/{{ $item->id }}')"><i class="trash-icon opacity-8 fa fa-trash" aria-hidden="true"></i></a></td>
                    
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        @if(count($valoresUnicosProducao)>0)
            <span class="px-6 py-2 " style="font-weight: light; font-size: 12px; color: gray;">* Células onde o texto é <span style="font-weight: bold; color: #34acdb;">azul </span> são editáveis. Altere o valor e clique fora para salvar.</span>
        @endif
    </div>

   
            <x-auth.footers.auth.footer></x-auth.footers.auth.footer>
        </div>
       
    </main>
    <x-plugins></x-plugins>
    @push('js')
    
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
