@props(['activePage', 'activeItem', 'activeSubitem'])
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0 d-flex align-items-center text-wrap" href="{{ route('painelRelatorios') }}">
            <img src="https://mnl-imagens-publicas.s3.amazonaws.com/preclogo.svg" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-2 font-weight-bold text-white">Precifica</span>
        </a>
    </div>
    <hr class="horizontal light mt-0 mb-4">
    <div class="collapse navbar-collapse w-auto h-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#perfil" class="nav-link text-white {{ $activePage == 'perfil' ? ' active ' : '' }}" aria-controls="perfil" role="button" aria-expanded="false">
                    @if (auth()->user()->picture)
                    <img src="/storage/{{(auth()->user()->picture)}}" alt="avatar" class="avatar">
                    @else
                    <img src="{{ asset('assets') }}/img/icouser.png" alt="avatar" class="avatar">
                    @endif
                    <span class="nav-link-text ms-2 ps-1">{{ auth()->user()->name }}</span>
                </a>
                <div class="collapse {{ $activePage == 'perfil' ? ' show ' : '' }}" id="perfil">
                    <ul class="nav">
                        <li class="nav-item {{ $activeItem == 'configPerfil' ? ' active ' : '' }}">
                            <a class="nav-link text-white {{ $activeItem == 'configPerfil' ? ' active ' : '' }}" href="{{ route('configPerfil') }}">
                                <span class="sidenav-mini-icon"> C </span>
                                <span class="sidenav-normal ms-3 ps-1">Configurações</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <hr class="horizontal light mt-4">
        <!-- PAINEL DE CONTROLE inicio -->
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#painelControle" class="nav-link text-white {{ $activePage == 'painelControle' ? ' active ' : '' }}" aria-controls="painelControle" role="button" aria-expanded="false">
                    <i class="material-icons-round opacity-10">dashboard</i>
                    <span class="nav-link-text ms-2 ps-1">Painel de Controle</span>
                </a>
                <div class="collapse {{ $activePage == 'painelControle' ? ' show ' : '' }}" id="painelControle">
                    <ul class="nav">
                        @if(auth()->user()->role_id != 3)
                        <li class="nav-item {{ $activeItem == 'painelRelatorios' ? ' active ' : '' }}">
                            <a class="nav-link text-white {{ $activeItem == 'painelRelatorios' ? ' active ' : '' }}" href="{{ route('painelRelatorios') }}">
                                <span class="sidenav-mini-icon">
                                    <i class="corYellow fa fa-pie-chart" aria-hidden="true"></i>
                                </span>
                                <span class="sidenav-normal ms-3 ps-1">Relatórios</span>
                                <i class="fa-solid fa-chart-pie-simple"></i>
                            </a>
                        </li>
                        
                        <li class="nav-item {{ $activeItem == 'painelAjustes' ? ' active ' : '' }}">
                            <a class="nav-link text-white {{ $activeItem == 'painelAjustes' ? ' active ' : '' }}" href="{{ route('painelAjustes') }}">
                                <span class="sidenav-mini-icon">
                                    <i class="corYellow fa fa-cog fa-spin opacity-8" aria-hidden="true"></i>
                                </span>
                                <span class="sidenav-normal ms-3 ps-1">Ajustes da Empresa</span>
                            </a>
                        </li>
                        
                        <li class="nav-item {{ $activeItem == 'painelUsuarios' ? ' active ' : '' }}">
                            <a class="nav-link text-white {{ $activeItem == 'painelUsuarios' ? ' active ' : '' }}" href="{{ route('painelUsuarios') }}">
                                <span class="sidenav-mini-icon">
                            
                                    <i class="corYellow fa fa-user-o opacity-8" aria-hidden="true"></i>
                                </span>
                                <span class="sidenav-normal ms-3 ps-1">Usuários da Conta</span>
                            </a>
                        </li>
                        @endif
                        <li class="nav-item {{ $activeItem == 'painelAjuda' ? ' active ' : '' }}">
                            <a class="nav-link text-white {{ $activeItem == 'painelAjuda' ? ' active ' : '' }}" href="{{ route('painelAjuda') }}">
                                <span class="sidenav-mini-icon">
                                    <i class="corYellow fa fa-question-circle-o opacity-8" aria-hidden="true"></i>
                                    
                                </span>
                                <span class="sidenav-normal ms-3 ps-1">Ajuda</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <hr class="horizontal light">
            </li>
        <!-- PAINEL DE CONTROLE fim -->
        <!-- MEUS DADOS inicio -->
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#meusDados" class="nav-link text-white {{ $activePage == 'meusDados' ? ' active ' : '' }}" aria-controls="meusDados" role="button" aria-expanded="false">
                    <i class="fa fa-briefcase" aria-hidden="true"></i>
                    <span class="nav-link-text ms-2 ps-1">Meus Dados</span>
                </a>
                <div class="collapse {{ $activePage == 'meusDados' ? ' show ' : '' }}" id="meusDados">
                    <ul class="nav">
                        <li class="nav-item {{ $activeItem == 'meusTrabalhos' ? ' active ' : '' }}">
                            <a class="nav-link text-white {{ $activeItem == 'meusTrabalhos' ? ' active ' : '' }}" href="{{ route('meusTrabalhos') }}">
                                <span class="sidenav-mini-icon">
                                    <i class="corPreciRoxo fa fa-briefcase opacity-6" aria-hidden="true"></i>
                                </span>
                                <span class="sidenav-normal ms-2 ps-1">Meus Trabalhos</span>
                            </a>
                        </li>
                        <li class="nav-item {{ $activeItem == 'meusMateriais' ? ' active ' : '' }}">
                            <a class="nav-link text-white {{ $activeItem == 'meusMateriais' ? ' active ' : '' }}" href="{{ route('meusMateriais') }}">
                                <span class="sidenav-mini-icon">
                                    <i class="corPreciRoxo fa fa-briefcase opacity-6" aria-hidden="true"></i>
                                </span>
                                <span class="sidenav-normal ms-2 ps-1">Meus Materiais</span>
                            </a>
                        </li>
                        <li class="nav-item {{ $activeItem == 'meusServicos' ? ' active ' : '' }}">
                            <a class="nav-link text-white {{ $activeItem == 'meusServicos' ? ' active ' : '' }}" href="{{ route('meusServicos') }}">
                                <span class="sidenav-mini-icon">
                                    <i class="corPreciRoxo fa fa-briefcase opacity-6" aria-hidden="true"></i>
                                </span>
                                <span class="sidenav-normal ms-2 ps-1">Meus Serviços</span>
                            </a>
                        </li>
                        <li class="nav-item {{ $activeItem == 'meusEquipamentos' ? ' active ' : '' }}">
                            <a class="nav-link text-white {{ $activeItem == 'meusEquipamentos' ? ' active ' : '' }}" href="{{ route('meusEquipamentos') }}">
                                <span class="sidenav-mini-icon">
                                    <i class="corPreciRoxo fa fa-briefcase opacity-6" aria-hidden="true"></i>
                                </span>
                                <span class="sidenav-normal ms-2 ps-1">Meus Equipamentos</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        <!-- MEUS DADOS fim -->
        <!-- FINANCEIRO inicio -->
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#financeiro" class="nav-link text-white {{ $activePage == 'financeiro' ? ' active ' : '' }}" aria-controls="financeiro" role="button" aria-expanded="false">
                    <i class="fa fa-money" aria-hidden="true"></i>
                    <span class="nav-link-text ms-2 ps-1">Financeiro</span>
                </a>
                <div class="collapse {{ $activePage == 'financeiro' ? ' show ' : '' }}" id="financeiro">
                    <ul class="nav">
                        <li class="nav-item {{ $activeItem == 'dre' ? ' active ' : '' }}">
                            <a class="nav-link text-white {{ $activeItem == 'dre' ? ' active ' : '' }}" href="{{ route('dre') }}">
                                <span class="sidenav-mini-icon">
                                    <i class="corPreciAzul fa fa-money opacity-6" aria-hidden="true"></i>
                                  
                                </span>
                                <span class="sidenav-normal ms-2 ps-1">DRE</span>
                            </a>
                        </li>
                        <li class="nav-item {{ $activeItem == 'producao' ? ' active ' : '' }}">
                            <a class="nav-link text-white {{ $activeItem == 'producao' ? ' active ' : '' }}" href="{{ route('producao') }}">
                                <span class="sidenav-mini-icon">
                                <i class="corPreciAzul fa fa-money opacity-6" aria-hidden="true"></i>
                                </span>
                                <span class="sidenav-normal ms-2 ps-1">Produção</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <hr class="horizontal light">
            </li>
        <!-- FINANCEIRO fim -->
        <!-- BASE inicio -->
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#base" class="nav-link text-white {{ $activePage == 'base' ? ' active ' : '' }}" aria-controls="base" role="button" aria-expanded="false">
                    <i class="fa fa-database" aria-hidden="true"></i>
                    <span class="nav-link-text ms-2 ps-1">Base</span>
                </a>
                <div class="collapse {{ $activePage == 'base' ? ' show ' : '' }}" id="base">
                    <ul class="nav">
                        <li class="nav-item {{ $activeItem == 'consultaBaseTrabalhos' ? ' active ' : '' }}">
                            <a class="nav-link text-white {{ $activeItem == 'consultaBaseTrabalhos' ? ' active ' : '' }}" href="{{ route('consultaBaseTrabalhos') }}">
                                <span class="sidenav-mini-icon">
                                    <i class="laranjaNominal fa fa-database opacity-6" aria-hidden="true"></i>
                                </span>
                                <span class="sidenav-normal ms-2 ps-1">Trabalhos</span>
                            </a>
                        </li>
                        <li class="nav-item {{ $activeItem == 'consultaBaseMateriais' ? ' active ' : '' }}">
                            <a class="nav-link text-white {{ $activeItem == 'consultaBaseMateriais' ? ' active ' : '' }}" href="{{ route('consultaBaseMateriais') }}">
                                <span class="sidenav-mini-icon">
                                    <i class="laranjaNominal fa fa-database opacity-6" aria-hidden="true"></i>
                                </span>
                                <span class="sidenav-normal ms-2 ps-1">Materiais</span>
                            </a>
                        </li>
                        <li class="nav-item {{ $activeItem == 'consultaBaseServicos' ? ' active ' : '' }}">
                            <a class="nav-link text-white {{ $activeItem == 'consultaBaseServicos' ? ' active ' : '' }}" href="{{ route('consultaBaseServicos') }}">
                                <span class="sidenav-mini-icon">
                                    <i class="laranjaNominal fa fa-database opacity-6" aria-hidden="true"></i>
                                </span>
                                <span class="sidenav-normal ms-2 ps-1">Serviços</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        <!-- BASE fim -->
        <!-- ADMIN inicio -->
            @if(auth()->user()->role_id == "1")
            <li class="nav-item">
                <hr class="horizontal light">
            </li>
            <li class="nav-item mt-2">
                <a data-bs-toggle="collapse" href="#admin" class="nav-link text-white {{ $activePage == 'admin' ? ' active ' : '' }}" aria-controls="admin" role="button" aria-expanded="false">
                    <i class="corCocaRed fa fa-cogs" aria-hidden="true"></i>
                    <span class="nav-link-text ms-2 ps-1">Admin</span>
                </a>
                <div class="collapse {{ $activePage == 'admin' ? ' show ' : '' }}" id="admin">
                    <ul class="nav">
                        <li class="nav-item {{ $activeItem == 'adminInicio' ? ' active ' : '' }}">
                            <a class="nav-link text-white {{ $activeItem == 'adminInicio' ? ' active ' : '' }}" href="{{ route('adminInicio') }}">
                                <span class="sidenav-mini-icon">
                                    <i class="fa fa-home opacity-4" aria-hidden="true"></i>
                                </span>
                                <span class="sidenav-normal ms-2 ps-1">Início</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ $activeItem == 'membros' ? ' active ' : '' }}" data-bs-toggle="collapse" aria-expanded="false" href="#MEX">
                                <span class="sidenav-mini-icon">
                                    <i class="corCocaRed fa fa-users opacity-8" aria-hidden="true"></i>
                                </span>
                                <span class="sidenav-normal ms-2 ps-1">Membros <b class="caret"></b></span>
                            </a>
                            <div class="collapse {{ $activeItem == 'membros' ? ' show ' : '' }}" id="MEX">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link text-white {{ $activeSubitem == 'usuariosCadastrados' ? ' active ' : '' }}" href="{{ route('usuariosCadastrados') }}">
                                            <span class="sidenav-mini-icon">
                                                <i class="fa fa-users opacity-4" aria-hidden="true"></i>
                                            </span>
                                            <span class="sidenav-normal ms-2 ps-1">Usuários</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white {{ $activeSubitem == 'empresasCadastradas' ? ' active ' : '' }}" href="{{ route('empresasCadastradas') }}">
                                            <span class="sidenav-mini-icon">
                                                <i class="fa fa-registered opacity-4" aria-hidden="true"></i>
                                            </span>
                                            <span class="sidenav-normal ms-2 ps-1">Empresas</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ $activeItem == 'editarBase' ? ' active ' : '' }}" data-bs-toggle="collapse" aria-expanded="false" href="#EBex">
                                <span class="sidenav-mini-icon">
                                    <i class="corCocaRed fa fa-database opacity-8" aria-hidden="true"></i>
                                </span>
                                <span class="sidenav-normal ms-2 ps-1">Editar Base <b class="caret"></b></span>
                            </a>
                            <div class="collapse {{ $activeItem == 'editarBase' ? ' show ' : '' }}" id="EBex">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link text-white {{ $activeSubitem == 'EBTrabalhos' ? ' active ' : '' }}" href="{{ route('EBTrabalhos') }}">
                                            <span class="sidenav-mini-icon">
                                                <i class="fa fa-database opacity-4" aria-hidden="true"></i>
                                            </span>
                                            <span class="sidenav-normal ms-2 ps-1">Trabalhos</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white {{ $activeSubitem == 'EBMateriais' ? ' active ' : '' }}" href="{{ route('EBMateriais') }}">
                                            <span class="sidenav-mini-icon">
                                                <i class="fa fa-database opacity-4" aria-hidden="true"></i>
                                            </span>
                                            <span class="sidenav-normal ms-2 ps-1">Materiais</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white {{ $activeSubitem == 'EBServicos' ? ' active ' : '' }}" href="{{ route('EBServicos') }}">
                                            <span class="sidenav-mini-icon">
                                                <i class="fa fa-database opacity-4" aria-hidden="true"></i>
                                            </span>
                                            <span class="sidenav-normal ms-2 ps-1">Serviços</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ $activeItem == 'logins' ? ' active ' : '' }}" data-bs-toggle="collapse" aria-expanded="false" href="#LEx">
                                <span class="sidenav-mini-icon">
                                    <i class="corCocaRed fa fa-key opacity-8" aria-hidden="true"></i>
                                </span>
                                <span class="sidenav-normal ms-2 ps-1">Logins <b class="caret"></b></span>
                            </a>
                            <div class="collapse {{ $activeItem == 'logins' ? ' show ' : '' }}" id="LEx">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link text-white {{ $activeSubitem == 'relatorioLogins' ? ' active ' : '' }}" href="{{ route('relatorioLogins') }}">
                                            <span class="sidenav-mini-icon">
                                                <i class="fa fa-list opacity-4" aria-hidden="true"></i>
                                            </span>
                                            <span class="sidenav-normal ms-2 ps-1">Relatório de Logins</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white {{ $activeSubitem == 'loginTokens' ? ' active ' : '' }}" href="{{ route('loginTokens') }}">
                                            <span class="sidenav-mini-icon">
                                                <i class="fa fa-key opacity-4" aria-hidden="true"></i>
                                            </span>
                                            <span class="sidenav-normal ms-2 ps-1">Tokens para Login</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white {{ $activeItem == 'reiniciar' ? ' active ' : '' }}" data-bs-toggle="collapse" aria-expanded="false" href="#REX">
                                <span class="sidenav-mini-icon">
                                    <i class="corCocaRed fa fa-refresh opacity-8" aria-hidden="true"></i>
                                </span>
                                <span class="sidenav-normal ms-2 ps-1">Reiniciar <b class="caret"></b></span>
                            </a>
                            <div class="collapse {{ $activeItem == 'reiniciar' ? ' show ' : '' }}" id="REX">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link text-white {{ $activeSubitem == 'serverRestart' ? ' active ' : '' }}" href="{{ route('serverRestart') }}">
                                            <span class="sidenav-mini-icon">
                                                <i class="corRestart fa fa-refresh fa-spin" aria-hidden="true"></i>
                                            </span>
                                            <span class="sidenav-normal ms-2 ps-1">Server Restart</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </li>
            @endif
            <li class="nav-item mt-2">
                <hr class="horizontal light">
            </li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <div class="sidenav-footer w-100 bottom-0 mt-2">
                    <div class="mx-1">
                        <a class="btn bg-gradient-primary w-100" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" target="_blank" type="button">Sair</a>
                    </div>
                </div>
            </form>
        </ul>
    </div>
</aside>
