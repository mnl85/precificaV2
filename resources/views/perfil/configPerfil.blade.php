<x-page-template bodyClass='g-sidenav-show bg-gray-20'>
    <x-auth.navbars.sidebar activePage='perfil' activeItem='configPerfil' activeSubitem=''></x-auth.navbars.sidebar>
    <main class="main-content main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar Route Titles -->
        <x-auth.navbars.navs.auth 
        pageTitle="{{auth()->user()->name}}" 
        itemTitle="Configurações" 
        subItemTitle="" 
        >
        </x-auth.navbars.navs.auth>
        <!-- End Navbar Route Titles -->

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

        <div class="container-fluid my-3 py-3">
            <div class="row mb-5">
                <div class="col-lg-3">
                    <div class="card position-sticky top-1">
                        <ul class="nav flex-column bg-white border-radius-lg p-3">
                          
                            <li class="nav-item pt-2">
                                <a class="nav-link text-dark d-flex" data-scroll="" href="#basic-info">
                                    <i class="material-icons text-lg me-2">receipt_long</i>
                                    <span class="text-sm">Informações Básicas</span>
                                </a>
                            </li>
                            <li class="nav-item pt-2">
                                <a class="nav-link text-dark d-flex" data-scroll="" href="#password">
                                    <i class="material-icons text-lg me-2">lock</i>
                                    <span class="text-sm">Trocar a senha</span>
                                </a>
                            </li>
                          
                            <li class="nav-item pt-2">
                                <a class="nav-link text-dark d-flex" data-scroll="" href="#notifications">
                                    <i class="material-icons text-lg me-2">campaign</i>
                                    <span class="text-sm">Notificações</span>
                                </a>
                            </li>
                            
                            <li class="nav-item pt-2">
                                <a class="nav-link text-dark d-flex" data-scroll="" href="#delete">
                                    <i class="material-icons text-lg me-2">delete</i>
                                    <span class="text-sm">Apagar Conta</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9 mt-lg-0 mt-4">
                    <!-- Card Profile -->
                    <div class="card card-body" id="profile">
                        <div class="row justify-content-center align-items-center">
                            <div class="col-sm-auto col-4">
                                <div class="avatar avatar-xl position-relative">
                                    <img src="{{ asset('assets') }}/img/bruce-mars.jpg" alt="bruce"
                                        class="w-100 rounded-circle shadow-sm">
                                </div>
                            </div>
                            <div class="col-sm-auto col-8 my-auto">
                                <div class="h-100">
                                    <h5 class="mb-1 font-weight-bolder">
                                        {{ Auth::user()->name }}
                                    </h5>
                                    <p class="mb-0 font-weight-normal text-sm">
                                        {{ $nomeEmpresa}}
                                    </p>
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
                            <h5>Informações Básicas</h5>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-6">
                                    <div class="input-group input-group-static">
                                        <label>Nome</label>
                                        <input type="text" class="form-control" value="{{ Auth::user()->name }}">
                                    </div>
                                </div>
                              
                            </div>
                           
                            <div class="row mt-4">
                                <div class="col-6">
                                    <div class="input-group input-group-static">
                                        <label>Email</label>
                                        <input type="email" class="form-control" value="{{ Auth::user()->email }}">
                                    </div>
                                </div>
                               
                            </div>
                            <div class="row mt-4">
                                <div class="col-6">
                                    <div class="input-group input-group-static">
                                        <label>CEP</label>
                                        <input type="text" class="form-control" value="{{ Auth::user()->cep }}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input-group input-group-static">
                                        <label>Cidade</label>
                                        <input type="text" class="form-control" value="{{ Auth::user()->cidade }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-6">
                                    <div class="input-group input-group-static">
                                        <label>UF</label>
                                        <input type="text" class="form-control" value="{{ Auth::user()->uf }}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input-group input-group-static">
                                        <label>Fone</label>
                                        <input type="number" class="form-control" value="{{ Auth::user()->fone }}">
                                    </div>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                    <!-- Card Change Password -->
                    <div class="card mt-4" id="password">
                        <div class="card-header">
                            <h5>Trocar a Senha</h5>
                        </div>
                        <div class="card-body pt-0">
                            <div class="input-group input-group-outline">
                                <label class="form-label">Current password</label>
                                <input type="password" class="form-control">
                            </div>
                            <div class="input-group input-group-outline my-4">
                                <label class="form-label">New password</label>
                                <input type="password" class="form-control">
                            </div>
                            <div class="input-group input-group-outline">
                                <label class="form-label">Confirm New password</label>
                                <input type="password" class="form-control">
                            </div>
                            <h5 class="mt-5">Password requirements</h5>
                            <p class="text-muted mb-2">
                                Please follow this guide for a strong password:
                            </p>
                            <ul class="text-muted ps-4 mb-0 float-start">
                                <li>
                                    <span class="text-sm">One special characters</span>
                                </li>
                                <li>
                                    <span class="text-sm">Min 6 characters</span>
                                </li>
                                <li>
                                    <span class="text-sm">One number (2 are recommended)</span>
                                </li>
                                <li>
                                    <span class="text-sm">Change it often</span>
                                </li>
                            </ul>
                            <button class="btn bg-gradient-dark btn-sm float-end mt-6 mb-0">Update password</button>
                        </div>
                    </div>
                   
                  
                    <!-- Card Notifications -->
                    <div class="card mt-4" id="notifications">
                        <div class="card-header">
                            <h5>Notificações</h5>
                            <p class="text-sm">Choose how you receive notifications. These notification settings apply
                                to the things you’re watching.</p>
                        </div>
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th class="ps-1" colspan="4">
                                                <p class="mb-0">Activity</p>
                                            </th>
                                            <th class="text-center">
                                                <p class="mb-0">Email</p>
                                            </th>
                                            <th class="text-center">
                                                <p class="mb-0">Push</p>
                                            </th>
                                            <th class="text-center">
                                                <p class="mb-0">SMS</p>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="ps-1" colspan="4">
                                                <div class="my-auto">
                                                    <span class="text-dark d-block text-sm">Mentions</span>
                                                    <span class="text-xs font-weight-normal">Notify when another user
                                                        mentions you in a comment</span>
                                                </div>
                                            </td>
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
                                        <tr>
                                            <td class="ps-1" colspan="4">
                                                <div class="my-auto">
                                                    <span class="text-dark d-block text-sm">Comments</span>
                                                    <span class="text-xs font-weight-normal">Notify when another user
                                                        comments your item.</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div
                                                    class="form-check form-switch mb-0 d-flex align-items-center justify-content-center">
                                                    <input class="form-check-input" checked type="checkbox"
                                                        id="flexSwitchCheckDefault14">
                                                </div>
                                            </td>
                                            <td>
                                                <div
                                                    class="form-check form-switch mb-0 d-flex align-items-center justify-content-center">
                                                    <input class="form-check-input" checked type="checkbox"
                                                        id="flexSwitchCheckDefault15">
                                                </div>
                                            </td>
                                            <td>
                                                <div
                                                    class="form-check form-switch mb-0 d-flex align-items-center justify-content-center">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="flexSwitchCheckDefault16">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ps-1" colspan="4">
                                                <div class="my-auto">
                                                    <span class="text-dark d-block text-sm">Follows</span>
                                                    <span class="text-xs font-weight-normal">Notify when another user
                                                        follows you.</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div
                                                    class="form-check form-switch mb-0 d-flex align-items-center justify-content-center">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="flexSwitchCheckDefault17">
                                                </div>
                                            </td>
                                            <td>
                                                <div
                                                    class="form-check form-switch mb-0 d-flex align-items-center justify-content-center">
                                                    <input class="form-check-input" checked type="checkbox"
                                                        id="flexSwitchCheckDefault18">
                                                </div>
                                            </td>
                                            <td>
                                                <div
                                                    class="form-check form-switch mb-0 d-flex align-items-center justify-content-center">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="flexSwitchCheckDefault19">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ps-1" colspan="4">
                                                <div class="my-auto">
                                                    <p class="text-sm mb-0">Log in from a new device</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div
                                                    class="form-check form-switch mb-0 d-flex align-items-center justify-content-center">
                                                    <input class="form-check-input" checked type="checkbox"
                                                        id="flexSwitchCheckDefault20">
                                                </div>
                                            </td>
                                            <td>
                                                <div
                                                    class="form-check form-switch mb-0 d-flex align-items-center justify-content-center">
                                                    <input class="form-check-input" checked type="checkbox"
                                                        id="flexSwitchCheckDefault21">
                                                </div>
                                            </td>
                                            <td>
                                                <div
                                                    class="form-check form-switch mb-0 d-flex align-items-center justify-content-center">
                                                    <input class="form-check-input" checked type="checkbox"
                                                        id="flexSwitchCheckDefault22">
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Card Delete Account -->
                    <div class="card mt-4" id="delete">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-sm-0 mb-4">
                                <div class="w-50">
                                    <h5>Apagar Conta</h5>
                                    <p class="text-sm mb-0">Once you delete your account, there is no going back. Please
                                        be certain.</p>
                                </div>
                                <div class="w-50 text-end">
                               
                                    <button class="btn bg-gradient-danger mb-0 ms-2" type="button" name="button">Delete
                                        Account</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-auth.footers.auth.footer></x-auth.footers.auth.footer>
        </div>
    </main>
    <x-plugins></x-plugins>
    @push('js')
    <script src="{{ asset('assets') }}/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/choices.min.js"></script>
    <!-- Kanban scripts -->
    <script>
        if (document.getElementById('choices-gender')) {
            var gender = document.getElementById('choices-gender');
            const example = new Choices(gender);
        }

        if (document.getElementById('choices-language')) {
            var language = document.getElementById('choices-language');
            const example = new Choices(language);
        }

        if (document.getElementById('choices-skills')) {
            var skills = document.getElementById('choices-skills');
            const example = new Choices(skills, {
                delimiter: ',',
                editItems: true,
                maxItemCount: 5,
                removeItemButton: true,
                addItems: true
            });
        }

        if (document.getElementById('choices-year')) {
            var year = document.getElementById('choices-year');
            setTimeout(function () {
                const example = new Choices(year);
            }, 1);

            for (y = 1900; y <= 2020; y++) {
                var optn = document.createElement("OPTION");
                optn.text = y;
                optn.value = y;

                if (y == 2020) {
                    optn.selected = true;
                }

                year.options.add(optn);
            }
        }

        if (document.getElementById('choices-day')) {
            var day = document.getElementById('choices-day');
            setTimeout(function () {
                const example = new Choices(day);
            }, 1);


            for (y = 1; y <= 31; y++) {
                var optn = document.createElement("OPTION");
                optn.text = y;
                optn.value = y;

                if (y == 1) {
                    optn.selected = true;
                }

                day.options.add(optn);
            }

        }

        if (document.getElementById('choices-month')) {
            var month = document.getElementById('choices-month');
            setTimeout(function () {
                const example = new Choices(month);
            }, 1);

            var d = new Date();
            var monthArray = new Array();
            monthArray[0] = "January";
            monthArray[1] = "February";
            monthArray[2] = "March";
            monthArray[3] = "April";
            monthArray[4] = "May";
            monthArray[5] = "June";
            monthArray[6] = "July";
            monthArray[7] = "August";
            monthArray[8] = "September";
            monthArray[9] = "October";
            monthArray[10] = "November";
            monthArray[11] = "December";
            for (m = 0; m <= 11; m++) {
                var optn = document.createElement("OPTION");
                optn.text = monthArray[m];
                // server side month start from one
                optn.value = (m + 1);
                // if june selected
                if (m == 1) {
                    optn.selected = true;
                }
                month.options.add(optn);
            }
        }

        function visible() {
            var elem = document.getElementById('profileVisibility');
            if (elem) {
                if (elem.innerHTML == "Switch to visible") {
                    elem.innerHTML = "Switch to invisible"
                } else {
                    elem.innerHTML = "Switch to visible"
                }
            }
        }

        var openFile = function (event) {
            var input = event.target;

            // Instantiate FileReader
            var reader = new FileReader();
            reader.onload = function () {
                imageFile = reader.result;

                document.getElementById("imageChange").innerHTML = '<img width="200" src="' + imageFile +
                    '" class="rounded-circle w-100 shadow" />';
            };
            reader.readAsDataURL(input.files[0]);
        };

    </script>
    @endpush
</x-page-template>
