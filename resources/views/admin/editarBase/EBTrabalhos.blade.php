<x-page-template bodyClass='g-sidenav-show  bg-gray-200'>
    <x-auth.navbars.sidebar activePage="admin" activeItem="editarBase" activeSubitem="EBTrabalhos">
    </x-auth.navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar Route Titles -->
        <x-auth.navbars.navs.auth 
            pageTitle="Admin" 
            itemTitle="Editar Base" 
            subItemTitle="Trabalhos" 
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
        .checkbox-wrapper-10 .tgl {
            display: none;
        }
        .checkbox-wrapper-10 .tgl,
        .checkbox-wrapper-10 .tgl:after,
        .checkbox-wrapper-10 .tgl:before,
        .checkbox-wrapper-10 .tgl *,
        .checkbox-wrapper-10 .tgl *:after,
        .checkbox-wrapper-10 .tgl *:before,
        .checkbox-wrapper-10 .tgl + .tgl-btn {
            box-sizing: border-box;
        }
        .checkbox-wrapper-10 .tgl::selection,
        .checkbox-wrapper-10 .tgl + .tgl-btn::selection {
            background: none;
        }
        .checkbox-wrapper-10 .tgl + .tgl-btn {
            outline: 0;
            display: block;
            width: 4em;
            height: 2em;
            position: relative;
            cursor: pointer;
            user-select: none;
        }
        .checkbox-wrapper-10 .tgl + .tgl-btn:after,
        .checkbox-wrapper-10 .tgl + .tgl-btn:before {
            position: relative;
            display: block;
            content: "";
            width: 50%;
            height: 100%;
        }
        .checkbox-wrapper-10 .tgl + .tgl-btn:after {
            left: 0;
        }
        .checkbox-wrapper-10 .tgl + .tgl-btn:before {
            display: none;
        }
        .checkbox-wrapper-10 .tgl:checked + .tgl-btn:after {
            left: 50%;
        }
        .checkbox-wrapper-10 .tgl-flip + .tgl-btn {
            padding: 2px;
            transition: all 0.2s ease;
            font-family: sans-serif;
            perspective: 100px;
        }
        .checkbox-wrapper-10 .tgl-flip + .tgl-btn:after,
        .checkbox-wrapper-10 .tgl-flip + .tgl-btn:before {
            display: inline-block;
            transition: all 0.4s ease;
            width: 100%;
            text-align: center;
            position: absolute;
            line-height: 2em;
            font-weight: bold;
            color: #fff;
            top: 0;
            left: 0;
            backface-visibility: hidden;
            border-radius: 4px;
        }
        .checkbox-wrapper-10 .tgl-flip + .tgl-btn:after {
            content: attr(data-tg-on);
            background: var(--bs-teal);
            transform: rotateY(-180deg);
        }
        .checkbox-wrapper-10 .tgl-flip + .tgl-btn:before {
            background: var(--bs-corCocaRed);
            content: attr(data-tg-off);
        }
        .checkbox-wrapper-10 .tgl-flip + .tgl-btn:active:before {
            transform: rotateY(-20deg);
        }
        .checkbox-wrapper-10 .tgl-flip:checked + .tgl-btn:before {
            transform: rotateY(180deg);
        }
        .checkbox-wrapper-10 .tgl-flip:checked + .tgl-btn:after {
            transform: rotateY(0);
            left: 0;
            background: var(--bs-teal);
        }
        .checkbox-wrapper-10 .tgl-flip:checked + .tgl-btn:active:after {
            transform: rotateY(20deg);
        }
    </style>
    <div class="container-fluid py-4">
        <!-- CABEÇALHO INICIAL inicio -->
        <div class="card  p-4">
            <h5 class="mb-0">Editar Base - Trabalhos</h5>
            <div class="flex justify-between pt-4">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-block bg-gradient-primary mb-3" data-bs-toggle="modal" data-bs-target="#modal-form">Novo Trabalho Base</button>
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
        <!-- MODAL inicio -->
        <div class=" ">
            <div class="col-m-4 ">
                
                <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-body p-0">
                                <div class="card card-plain">
                                    <div class="card-header pb-0 text-left">
                                        <h5 class="">Novo Trabalho Base</h5>
                                    </div>
                                    <div class="card-body">
                                        <form role="form text-left" action="{{asset('/EBTrabalhosNovo')}}"  method="POST">
                                            @csrf
                                            <div class="input-group input-group-outline my-3">
                                                <label class="form-label">Nome do Trabalho</label>
                                                <input type="text" name="trabalho" id="trabalho" class="form-control" onfocus="focused(this)" onfocusout="defocused(this)">
                                            </div>
                                    </div>
                                    <div class="card-footer text-center pt-0 px-lg-2 px-1  justify-between">
                                    <div class="modal-footer ">
                                    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Fechar</button>
                                    <button type="submit" class="btn bg-gradient-primary">Salvar</button>
                                    </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL fim -->

        <div class="card my-4 p-4">
            <!-- ####################### -->
            <div class="table-responsive">
                <table class="table table-flush" id="datatable-basic">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                ID
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Nome do Trabalhos
                            </th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Qtd Materiais
                            </th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Qtd Servicos
                            </th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tb_base_trabalhos as $d)
                            @php
                                $materiais = json_decode($d['materiais'], true);
                                $servicos = json_decode($d['servicos'], true); 
                                $checkbox_id = $d['id'];
                                $visivel = $d['visivel'];
                            @endphp
                        <tr data-row-id="{{ $d['id'] }}">
                            <td class="text-center text-sm font-weight-normal">{{ $d['id'] }}</td>
                            <td class="text-sm font-weight-normal">{{ $d['trabalho'] }}</td>
                            <td class="text-center text-sm font-weight-normal">@if ($materiais==null) {{0}} @else {{ count($materiais) }} @endif</td>
                            <td class="text-center text-sm font-weight-normal">@if ($servicos==null) {{0}} @else {{ count($servicos) }} @endif</td>
                            <td class="text-center text-sm font-weight-normal opacity-8"><a href="/EBET/{{ $d['id'] }}" data-id="{{ $d['id'] }}"><i class="edit-icon opacity-8 fa fa-edit" aria-hidden="true"></i></a></td>
                            <td class="text-center text-sm font-weight-normal opacity-8"><a href="javascript:void(0);" onclick="confirmDelete('/EBTrabalhosDelete/{{ $d['id'] }}')"><i class="trash-icon opacity-8 fa fa-trash" aria-hidden="true"></i></a></td>
                            <td class="text-center text-sm font-weight-normal">
                            <div class="text-center checkbox-wrapper-10 ml-2 mr-2">
                                <input name="visivel" class="tgl tgl-flip" id="customSwitch{{ $checkbox_id }}" type="checkbox" @if($visivel == 1) checked @endif />
                                <label class="tgl-btn" data-tg-off="Oculto" data-tg-on="Visível" for="customSwitch{{ $checkbox_id }}"></label>
                            </div>
                        </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- ####################### -->
        </div>

        <x-auth.footers.auth.footer></x-auth.footers.auth.footer>
    </div>
    </main>
    <x-plugins></x-plugins>
    @push('js')
    <script>

function updateCheckboxState(checkboxElement) {
    const rowElement = checkboxElement.closest('tr');
    const rowId = rowElement ? rowElement.dataset.rowId : 0;
    const isChecked = checkboxElement.checked ? 1 : 0;

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    fetch(`/EBTrabalhosUpdateCheckboxState/${rowId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ visivel: isChecked })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            checkboxElement.style.backgroundColor = '#55f2c3'; // Verde claro para sucesso
            checkboxElement.title = data.message;

            Swal.fire({
                icon: 'success',
                title: 'Sucesso',
                text: data.message,
                toast: true,
                position: 'bottom-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
        } else {
            checkboxElement.style.backgroundColor = '#f8d7da'; // Vermelho claro para erro
            checkboxElement.title = data.message;

            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: data.message,
                toast: true,
                position: 'bottom-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
        }
        setTimeout(() => {
            checkboxElement.style.backgroundColor = ''; // Limpa a cor
        }, 1000);
    })
    .catch(error => {
        console.error('Error:', error);
        checkboxElement.style.backgroundColor = '#ed667e'; // Vermelho claro para erro
        checkboxElement.title = 'Erro ao atualizar';

        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: 'Erro ao atualizar',
            toast: true,
            position: 'bottom-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        setTimeout(() => {
            checkboxElement.style.backgroundColor = ''; // Limpa a cor
        }, 1000);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.tgl');

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateCheckboxState(this);
        });
    });
});



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