<x-page-template bodyClass='g-sidenav-show  bg-gray-200'>
    <x-auth.navbars.sidebar activePage="base" activeItem="consultaBaseTrabalhos" activeSubitem="">
    </x-auth.navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
         <!-- Navbar Route Titles -->
         <x-auth.navbars.navs.auth 
        pageTitle="Base" 
        itemTitle="Trabalhos" 
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
  .custom-card-width {
                width: 70%;
                min-width: 1100px;
                margin-left: 0;
            }
  .fs-custom {
    font-size: 0.75rem; /* Ajuste conforme necessário */
  }
</style>
<div class="card m-4 p-4">

                        <h5 class="mb-0">Trabalhos na Base</h5> 
                       
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
                    
                        <div class="card m-4 p-4 custom-card-width">
                          <div class="table-responsive">
                              <table class="table table-flush" id="datatable-basic">
                                  <thead class="thead-light">
                                      <tr>
                                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                              ID</th>
                                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                              Nome do Trabalhos</th>
                                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                              Qtd Materiais</th>
                                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                              Qtd Servicos</th>
                                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                              Ver</th>
                                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                              Copiar</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                  @foreach ($tb_base_trabalhos as $d)
                                      @php
                                          $materiais = json_decode($d['materiais'], true);
                                          $servicos = json_decode($d['servicos'], true); 
                                      @endphp
                                      <tr>
                                          <td class="text-sm font-weight-normal">{{ $d['id'] }}</td>
                                          <td class="text-sm font-weight-normal">{{ $d['trabalho'] }}</td>
                                          <td class="text-sm font-weight-normal">@if ($materiais==null) {{0}} @else {{ count($materiais) }} @endif</td>
                                          <td class="text-sm font-weight-normal">@if ($servicos==null) {{0}} @else {{ count($servicos) }} @endif</td>
                                          <td class="text-sm font-weight-normal"> <a href="#" class="view-details" data-id="{{ $d['id'] }}"><i class=" eye-icon opacity-7 fa fa-eye" aria-hidden="true"></i></a></td>
                                          <td class="text-sm font-weight-normal">              <a href="/copiaBaseTrabalhos/{{ $d['id'] }}"><i class=" copy-icon opacity-7 fa fa-regular fa-copy " aria-hidden="true"></i></a></td>
                                        
                                      </tr>
                                      @endforeach
                                  </tbody>
                              </table>
                          </div>
               
            </div>


<!-- MODAL inicio -->
<div class="modal fade" id="toggleModal" tabindex="-1" role="dialog" aria-labelledby="toggleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title font-weight-normal" id="toggleModalLabel">Detalhes do Trabalho</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="trabalho-id" style="display:none;"></p>
        <h6>Materiais:</h6>
        <table class="table">
          <thead>
            <tr>
              <th style="width:170px;">ID</th>
              <th>Material</th>
              <th style="width:170px;">Quantidade</th>
            </tr>
          </thead>
          <tbody id="modal-body-content-materiais">
            <!-- Conteúdo será inserido via JS -->
          </tbody>
        </table>
        <br><br>
        <h6>Serviços:</h6>
        <table class="table">
          <thead>
            <tr>
              <th style="width:170px;">ID</th>
              <th>Serviço</th>
            </tr>
          </thead>
          <tbody id="modal-body-content-servicos">
            <!-- Conteúdo será inserido via JS -->
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Fechar</button>
        <!-- <button type="button" class="btn bg-gradient-primary">Save changes</button> -->
      </div>
    </div>
  </div>
</div>
<!-- MODAL fim -->

            <x-auth.footers.auth.footer></x-auth.footers.auth.footer>
        </div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const modal = new bootstrap.Modal(document.getElementById('toggleModal'), {});
    
    document.querySelectorAll('.view-details').forEach(item => {
      item.addEventListener('click', function(e) {
        e.preventDefault();
        
        const trabalhoId = this.getAttribute('data-id');
        document.getElementById('trabalho-id').innerText = trabalhoId;

        document.getElementById('modal-body-content-materiais').innerHTML = '';
        document.getElementById('modal-body-content-servicos').innerHTML = '';

        fetch(`/getTrabalhoDetails/${trabalhoId}`)
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              document.getElementById('toggleModalLabel').innerText = `Detalhes do Trabalho: ${data.trabalho}`;
              let htmlMateriais = '';
              data.materiais.forEach(item => {
                htmlMateriais += `<tr>
                  <td style="width:170px;" class="text-center border px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-500">${item.id}</td>
                  <td class="border px-6 py-2 whitespace-nowrap text-sm text-gray-500">${item.material}</td>
                  <td style="width:170px;" class="border px-6 py-2 whitespace-nowrap text-sm text-gray-500">${item.qtd}</td>
                </tr>`;
              });
              document.getElementById('modal-body-content-materiais').innerHTML = htmlMateriais;

              let htmlServicos = '';
              data.servicos.forEach(item => {
                htmlServicos += `<tr>
                  <td style="width:170px;" class="text-center border px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-500">${item.id}</td>
                  <td class="border px-6 py-2 whitespace-nowrap text-sm text-gray-500">${item.servico}</td>
                </tr>`;
              });
              document.getElementById('modal-body-content-servicos').innerHTML = htmlServicos;

              modal.show();
            } else {
              console.error('Erro ao carregar os detalhes do trabalho.', data.message);
            }
          })
          .catch(error => console.error('Erro:', error));
      });
    });
  });
</script>
    </main>
    <x-plugins></x-plugins>
    @push('js')
    
    <script src="{{ asset('assets') }}/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/datatables.js"></script>
    <script>
        const dataTableBasic = new simpleDatatables.DataTable("#datatable-basic", {
            searchable: true,
            fixedHeight: true
        });

    </script>
    @endpush
</x-page-template>
