<x-page-template bodyClass='g-sidenav-show  bg-gray-200'>
    <x-auth.navbars.sidebar activePage="meusDados" activeItem="meusMateriais" activeSubitem="">
    </x-auth.navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar Route Titles -->
        <x-auth.navbars.navs.auth 
        pageTitle="Meus Dados" 
        itemTitle="Meus Materiais" 
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
            <h5 class="mb-0">Meus Materiais</h5> 
            <div class="flex justify-between pt-4">
            <!-- Button trigger modal -->
            <button type="button" class=" btn bg-gradient-primary" data-bs-toggle="modal" data-bs-target="#modal-adicionarProducao">
                Cadastrar Novo Material
            </button>
            </div>
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
                        <!-- ###### -->
                        <div class="card m-4 p-4">
                        <div class="table-responsive">
                            <table class="table table-flush" id="datatable-basic">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Material</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Apresentação</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Custo <br> Apresentação</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Qtd <br> Calculada</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Custo <br> Fração</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder "></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tb_meus_materiais as $m)
                                    @php
                                    $custoFracao = floatval($m['custo']) / floatval($m['qtd_calculada']);
                                    @endphp
                                    <tr data-row-id="{{ $m['id'] }}">
                                        <td class="text-sm font-weight-normal">{{ $m['id'] }}</td>
                                        <td class="text-sm font-weight-bold corUserEdit" contenteditable="true" onblur="updateCell(this, 'material')">{{ $m['material'] }}</td>
                                        <td class="text-sm font-weight-bold corUserEdit" contenteditable="true" onblur="updateCell(this, 'apresentacao')">{{ $m['apresentacao'] }}</td>
                                        <td class="text-sm font-weight-bold corUserEdit" contenteditable="true" onblur="updateCell(this, 'custo')">{{ number_format(floatval($m['custo']), 2, ',', '.') }}</td>
                                        <td class="text-sm font-weight-bold corUserEdit" contenteditable="true" onblur="updateCell(this, 'qtd_calculada')">{{ str_replace('.', ',', $m['qtd_calculada']) }}</td>

                                        <td class="text-sm font-weight-normal">R$ {{ number_format($custoFracao, 2, ',', '.') }}</td>

                                        <td class="text-sm font-weight-normal opacity-8"><a href="javascript:void(0);" onclick="confirmDelete('/apagarMeuMaterial/{{ $m['id'] }}')"><i class="trash-icon opacity-8 fa fa-trash" aria-hidden="true"></i></a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- ###### -->
                        @if(!$tb_meus_materiais->isEmpty())
                        <span class="px-6 py-2" style="font-weight: light; font-size: 12px; color: gray;">* Células onde o texto é <span style="font-weight: bold; color: #34acdb;">azul</span> são editáveis. Altere o valor e clique fora para salvar.</span>
                        @endif
                    </div>
                    </div>
           
            <x-auth.footers.auth.footer></x-auth.footers.auth.footer>
        
    </main>
    <x-plugins></x-plugins>
    @push('js')
    <script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        let statusMeusMateriais = "{{ session('statusMeusMateriais') }}";
        let materialDuplicado = "{{ session('materialDuplicado') }}";
        console.log("Valor de statusMeusMateriais:", statusMeusMateriais, materialDuplicado);

        if (statusMeusMateriais === "1") {
            Swal.fire({
                title: "Material não copiado!",
                html: "Você já tem um material com o nome <br><br><b>" + materialDuplicado + "</b> <br><br> Altere este nome e repita a importação",
                icon: "error"
            });
        }
    });
    </script>

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
                title: "Você tem certeza apagar este material?",
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
                        text: "O material foi apagado",
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

    fetch(`/updateMeusMateriais/${rowId}`, {
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

            // Atualizar a célula "Custo Fração"
            const custoFracaoCell = row.querySelector('td:nth-child(6)');
            custoFracaoCell.innerText = `R$ ${data.custoFracao}`;

            // Manter a cor verde por 1 segundo
            setTimeout(() => {
                element.style.backgroundColor = ''; // Volta à cor original após 1 segundo
            }, 1000);

            // Exibir um alerta de sucesso
            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: 'Material atualizado com sucesso.',
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'bottom-end'
            });

        } else {
            element.style.backgroundColor = '#f28ea0'; // Vermelho claro para erro
            element.title = data.message;

            // Exibir um alerta de erro
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: 'Não foi possível atualizar o material. Tente novamente.',
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'bottom-end'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        element.style.backgroundColor = '#f28ea0'; // Vermelho claro para erro
        element.title = 'Erro ao atualizar';

        // Exibir um alerta de erro
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Ocorreu um erro ao tentar atualizar o material.',
            timer: 2000,
            showConfirmButton: false,
            toast: true,
            position: 'bottom-end'
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
