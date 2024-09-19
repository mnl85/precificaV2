<footer class="footer py-4  ">
@php
    use Illuminate\Support\Facades\Auth;
    use App\Facades\TbEmpresa;

    // Obter o ID da empresa do usuário autenticado
    $user_empresa_id = Auth::user()->empresa_id;

    // Obter os dados da empresa a partir do modelo TbEmpresa
    $empresa = tb_empresa::find($user_empresa_id);

    // Inicializar a variável nomeEmpresa
    $nomeEmpresa = null;

    // Verificar se a empresa foi encontrada e obter o nome da empresa
    if ($empresa && isset($empresa->nome_empresa)) {
        $nomeEmpresa = $empresa->nome_empresa;
    } 

    // Obter a função do usuário autenticado
    $role = Auth::user()->role;
@endphp
    <div class="container-fluid">
      <div class="row align-items-center justify-content-lg-between">
        <div class="col-lg-5 mb-lg-0 mb-4">
          <div class="copyright text-center text-sm text-muted text-lg-start opacity-6">
            ® <script>
              document.write(new Date().getFullYear())
            </script>, <span class="">Precifica - Sistema de Precificação de Serviços</span>
            .
          </div>
        </div>
        <div class="col-lg-7 mb-lg-0 mb-4">
          <div class="copyright text-center text-sm text-muted text-lg-start corPreciRoxo">
          <span class="corPreciRoxo">{{ $nomeEmpresa }}</span>
            
          </div>
        </div>
      
      </div>
    </div>
  
  </footer>