<x-page-template bodyClass='g-sidenav-show  bg-gray-200'>
    <x-auth.navbars.sidebar activePage="meusDados" activeItem="meusEquipamentos" activeSubitem="">
    </x-auth.navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <x-auth.navbars.navs.auth 
            pageTitle="Meus Dados" 
            itemTitle="Meus Equipamentos" 
            subItemTitle=""> 
        </x-auth.navbars.navs.auth>

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
            .custom-card-width {
                width: 50%;
                max-width: 600px;
                margin-left: 0;
            }
            
           
        </style>
    <div class="container-fluid py-4">
          <!-- CABEÇALHO INICIAL -->
          <div class="card p-4">
              <h5 class="mb-0">Meus Equipamentos</h5>
              <div class="flex justify-between pt-4">
                  <!-- Button trigger modal -->
                  <button type="button" class="btn bg-gradient-primary" data-bs-toggle="modal" data-bs-target="#modalNovoMeuTrabalho">
                    Cadastrar Novo Equipamento
                  </button>
              </div>
            </div>
            <!-- CABEÇALHO INICIAL -->
                        @if (Session::has('status'))
                        <div class="alert alert-success alert-dismissible text-white mx-4" role="alert">
                            <span class="text-sm">{{ Session::get('status') }}</span>
                            <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert"
                                aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif
                        <div class="card  my-4 p-4">
                            <div class="table-responsive">
                                <table class="table table-flush" id="datatable-basic" id="dataTable" >
                                    <thead class="thead-light">
                                        <tr>
                                            <!-- <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th> -->
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nome do Equipamento</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Marca</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Modelo</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Valor</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Vida Útil (horas)</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Vida Útil (minutos)</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Custo Minuto</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody> 
                                        @foreach ($tb_equipamentos as $m)
                                        <tr data-row-id="{{ $m['id'] }}">
                                            <!-- <td class="text-sm font-weight-normal">{{ $m['id'] }}</td> -->
                                            <td class="text-sm font-weight-bold corUserEdit" contenteditable="true" onblur="updateEquipamento(this, 'nome_equipamento')">{{ $m['nome_equipamento'] }}</td>
                                            <td class="text-sm font-weight-bold corUserEdit" contenteditable="true" onblur="updateEquipamento(this, 'marca')">{{ $m['marca'] }}</td>
                                            <td class="text-sm font-weight-bold corUserEdit" contenteditable="true" onblur="updateEquipamento(this, 'modelo')">{{ $m['modelo'] }}</td>
                                            <td class="text-sm font-weight-bold corUserEdit" contenteditable="true" onblur="updateEquipamento(this, 'valor')">R$ {{ number_format($m['valor'],'2',',','.') }}</td>
                                            <td class="text-sm font-weight-bold corUserEdit" contenteditable="true" onblur="updateEquipamento(this, 'vida_util_horas')">{{ number_format($m['vida_util_horas'],'0',',','.') }}</td>
                                            <td class="text-sm font-weight-bold " >{{ number_format($m['vida_util_horas']*60,'0',',','.') }}</td>
                                            <td class="text-sm font-weight-bold " >R$ {{ number_format($m['valor'] /( $m['vida_util_horas']*60),'2',',','.')}}</td>
                                            <td class="text-sm font-weight-normal opacity-8"><a href="javascript:void(0);" onclick="confirmDelete('/apagarEquipamento/{{ $m['id'] }}')"><i class="trash-icon opacity-8 fa fa-trash" aria-hidden="true"></i></a></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if(!$tb_equipamentos->isEmpty())
                                <span class="px-6 py-2" style="font-weight: light; font-size: 12px; color: gray;">* Células onde o texto é <span style="font-weight: bold; color: #34acdb;">azul</span> são editáveis. Altere o valor e clique fora para salvar.</span>
                            @endif
                        </div>
                   
            
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
                title: "Você tem certeza apagar este equipamento?",
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
                        text: "O equipamento foi apagado",
                        icon: "success"
                    });
                } 
            });
        }
    </script>

    <script>
 function updateEquipamento(cell, fieldName) {
    var row = cell.closest('tr');
    var id = row.getAttribute('data-row-id');
    
    var newValue = cell.innerText.trim();

    // Coleta o valor atual dos campos necessários
    var valor = parseFloat(row.querySelector('td[onblur*="valor"]').innerText.replace('R$', '').replace(/\./g, '').replace(',', '.'));
    var vidaUtilHoras = parseInt(row.querySelector('td[onblur*="vida_util_horas"]').innerText.replace(/\./g, ''));

    // Recalcula "Vida Útil (minutos)" e "Custo Minuto" se necessário
    if (fieldName === 'valor' || fieldName === 'vida_util_horas') {
        var vidaUtilMinutos = vidaUtilHoras * 60;
        var custoMinuto = valor / vidaUtilMinutos;

        // Atualiza os campos "Vida Útil (minutos)" e "Custo Minuto"
        row.querySelector('td:nth-child(6)').innerText = vidaUtilMinutos.toLocaleString('pt-BR');
        row.querySelector('td:nth-child(7)').innerText = 'R$ ' + custoMinuto.toFixed(2).replace('.', ',');
    }

    var data = {
        id: id,
        fieldName: fieldName,
        newValue: newValue,
        _token: '{{ csrf_token() }}'
    };

    fetch('/updateEquipamento', {
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
            cell.style.backgroundColor = '#86d9a3'; // Verde claro para sucesso
            cell.title = data.message;
            
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
                title: "Equipamento atualizado"
            });

            setTimeout(() => {
                cell.style.backgroundColor = ''; // Volta à cor original após 1 segundo
            }, 1000);
        } else {
            cell.style.backgroundColor = '#f28ea0'; // Vermelho claro para erro
            cell.title = data.message;

            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Erro ao atualizar o equipamento! Se o erro persistir, contate o administrador.",
                showConfirmButton: false,
                timer: 10000
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        cell.style.backgroundColor = '#f28ea0'; // Vermelho claro para erro
        cell.title = 'Erro ao atualizar';

        Swal.fire({
            position: "top-end",
            icon: "error",
            title: "Erro ao atualizar o equipamento! Se o erro persistir, contate o administrador.",
            showConfirmButton: false,
            timer: 10000
        });
    });
}

    </script>
    @endpush
</x-page-template>
