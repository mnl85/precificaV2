@props(['bodyClass'])
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets') }}/img/apple-icon.png">
  <link rel="icon" type="image/ico" href="{{ asset('/favicon.ico') }}">
  <title>
    Precifica - Sistema de Precificação de Serviços
  </title>

  <!-- Fonts and icons -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <!-- Nucleo Icons -->
  <link href="{{ asset('assets') }}/css/nucleo-icons.css" rel="stylesheet" />
  <link href="{{ asset('assets') }}/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha512-SfTiTlX6kk+qitfevl/7LibUOeJWlt9rbyDn92a1DqWOw9vWG2MFoays0sgObmWazO5BQPiFucnnEAjpAB+/Sw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <!-- CSS Files -->
  <link id="pagestyle" href="{{ asset('assets') }}/css/material-dashboard.min.css?v=3.0.1" rel="stylesheet" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script>
    window.csrfToken = "{{ csrf_token() }}";
  </script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  @stack('head')
</head>

@php
use Illuminate\Support\Facades\Auth;
use App\Facades\TbEmpresa;

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

<body class="{{ $bodyClass }} {{ Session::get('dark_mode') ? 'dark-version' : '' }}">

<!-- Aplicar diretamente as configurações da sessão ao HTML -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(Session::has('sidebar_color'))
            document.querySelector('.sidenav').setAttribute('data-color', '{{ Session::get('sidebar_color') }}');
        @endif
        
        @if(Session::has('sidebar_type'))
            document.querySelector('.sidenav').classList.add('{{ Session::get('sidebar_type') }}');
        @endif

        @if(Session::has('navbar_fixed'))
            var navbarFixed = document.getElementById('navbarFixed');
            navbarFixed.checked = {{ Session::get('navbar_fixed') ? 'true' : 'false' }};
            navbarFixed.dispatchEvent(new Event('change'));
        @endif

        @if(Session::has('navbar_minimize'))
            var navbarMinimize = document.getElementById('navbarMinimize');
            navbarMinimize.checked = {{ Session::get('navbar_minimize') ? 'true' : 'false' }};
            navbarMinimize.dispatchEvent(new Event('change'));
        @endif

        @if(Session::has('dark_mode'))
            var darkMode = document.getElementById('dark-version');
            darkMode.checked = {{ Session::get('dark_mode') ? 'true' : 'false' }};
            darkMode.dispatchEvent(new Event('change'));
        @endif
    });
</script>

{{ $slot }}

<script src="{{ asset('assets') }}/js/core/popper.min.js"></script>
<script src="{{ asset('assets') }}/js/core/bootstrap.min.js"></script>
<script src="{{ asset('assets') }}/js/plugins/smooth-scrollbar.min.js"></script>
<!-- Kanban scripts -->
<script src="{{ asset('assets') }}/js/plugins/dragula/dragula.min.js"></script>
<script src="{{ asset('assets') }}/js/plugins/jkanban/jkanban.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@stack('js')

<script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
</script>
<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
<script src="{{ asset('assets') }}/js/material-dashboard.min.js?v=3.0.1"></script>
</body>
</html>
