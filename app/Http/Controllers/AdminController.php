<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Redirect;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use App\Models\tb_meus_trabalhos;
use App\Models\tb_meus_materiais;
use App\Models\tb_meus_servicos;
use App\Models\tb_base_trabalhos;
use App\Models\tb_base_materiais;
use App\Models\tb_base_servicos;
use App\Models\tb_dre;
use App\Models\tb_empresa;
use App\Models\tb_producao;
use App\Models\User;
use App\Models\val_token;
use App\Models\log_manual_restarts;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    # adminInicio
        public function adminInicio(){

            if(auth()->user()->role_id != '1'){

                return redirect::to('inicio'); 

            }else{

            $tb_meus_trabalhos = tb_meus_trabalhos::orderBy('updated_at', 'DESC')->take(20)->get();
            
            $tb_meus_materiais = tb_meus_materiais::orderBy('updated_at', 'DESC')->take(20)->get();
            $tb_meus_servicos = tb_meus_servicos::orderBy('updated_at', 'DESC')->take(20)->get();
            
            $tb_producao = tb_producao::with('fc_trabalho')->where('deleted','0')->get();
            #dd($tb_producao);
            $primeiraArray = [];

            foreach ($tb_producao as $producao) {
                // Acessa as propriedades diretas do objeto tb_producao
                $trabalho_nome = $producao->trabalho_nome;
                $quantidade = $producao->quantidade;

                // Acessa a relação fc_trabalho para obter os materiais
                $materiais = $producao->fc_trabalho->materiais;

                // Constrói um novo array com as colunas desejadas
                $primeiraArray[] = [
                    'trabalho_nome' => $trabalho_nome,
                    'quantidade' => $quantidade,
                    'materiais' => $materiais,
                ];
            }

            #dd($primeiraArray);
            // Array onde vamos armazenar os resultados expandidos
            $segundaArray = [];

            foreach ($primeiraArray as $item) {
                $trabalho_nome = $item['trabalho_nome'];
                $quantidade = floatval($item['quantidade']); // Convertendo para float
                
                // Convertendo o JSON para array associativo
                $materiais = json_decode($item['materiais'], true); 
                
                foreach ($materiais as $material) {
                    $material_id = $material['id'];
                    $material_qtd = floatval($material['qtd']); // Convertendo para float
                    
                    // Calculando a quantidade final
                    $qtd_final = $quantidade * $material_qtd;
                    
                    // Criando novo item com todas as informações necessárias
                    $newItem = [
                        'trabalho_nome' => $trabalho_nome,
                        'quantidade' => $quantidade,
                        'material_id' => $material_id,
                        'material_qtd' => $material_qtd,
                        'qtd_final' => $qtd_final,
                    ];
                    
                    // Adicionando o novo item ao array de resultados
                    $segundaArray[] = $newItem;
                }
            }
            
            #dd($segundaArray);
            // Colete todos os material_id da array original
            $materialIds = array_column($segundaArray, 'material_id');

            // Consultar todos os materiais necessários
            $materiais = tb_meus_materiais::whereIn('id', $materialIds)->get();
            // Inicializar o mapa
            $materiaisMap = [];

            // Populando o mapa
            foreach ($materiais as $material) {
                $materiaisMap[$material->id] = [
                    'material' => $material->material,
                    'custo' => $material->custo,
                    'qtd_calculada' => $material->qtd_calculada,
                    'id' => $material->id,
                ];
            }

            // Nova array
            $terceiraArray = [];

            // Preenchendo a nova array
            foreach ($segundaArray as $item) {
                $materialId = $item['material_id'];
                if (isset($materiaisMap[$materialId])) {
                    $terceiraArray[] = array_merge($item, $materiaisMap[$materialId]);
                }
            }
            #dd($terceiraArray);


            // Array para armazenar os resultados agrupados
            $resultado = [];

            // Percorrer os dados originais para realizar o agrupamento
            foreach ($terceiraArray as $item) {
                $id = $item['id'];
                $qtd_final = (float) $item['qtd_final'];
                $custo = (float) $item['custo'];

                if (!isset($resultado[$id])) {
                    // Inicializar o grupo se ainda não existir
                    $resultado[$id] = [
                        'trabalho_nome' => $item['trabalho_nome'],
                        'qtd_final_total' => $qtd_final,
                        'custo_total' => $custo,
                        'material' => $item['material'],
                        'qtd_calculada' => $item['qtd_calculada'],
                        'num_ocorrencias' => 1
                    ];
                } else {
                    // Atualizar os valores existentes no grupo
                    $resultado[$id]['qtd_final_total'] += $qtd_final;
                    $resultado[$id]['custo_total'] += $custo;
                    $resultado[$id]['num_ocorrencias']++;
                }
            }

            // Calcular a média de custo para cada grupo
            foreach ($resultado as $key => $value) {
                $resultado[$key]['custo_medio'] = $value['custo_total'] / $value['num_ocorrencias'];
                $resultado[$key]['pacotes'] = $value['qtd_final_total'] / $value['qtd_calculada'];
                // Remover informações não mais necessárias
                unset($resultado[$key]['custo_total']);
                unset($resultado[$key]['num_ocorrencias']);
            }

            // Ordenar o resultado por qtd_final_total em ordem decrescente
            usort($resultado, function($a, $b) {
                return $b['pacotes'] <=> $a['pacotes'];
            });

            // Filtrar resultados para remover linhas com qtd_final_total e custo_medio como null
            $listaMateriais = array_filter($resultado, function($item) {
                return $item['material'] != null ;
            });

            #dd($listaMateriais);

           

            return view('admin.adminInicio', compact('listaMateriais','tb_meus_trabalhos','tb_meus_materiais','tb_meus_servicos'));

        }}

    # serverRestart
        public function serverRestart(){

            $log_manual_restarts = log_manual_restarts::all();

            return view('admin.serverRestart',compact('log_manual_restarts'));
        }

    # serverRestartRUN
        public function serverRestartRUN(Request $request)
        {
            // Verifica se o usuário está autenticado e autorizado
            if (!auth()->check()) {
                return redirect('/login');
            }

            log_manual_restarts::create(['alt_user_id' => auth()->user()->id]);

        
            // Verifica o caminho correto do comando reboot
            $rebootPath = '/sbin/reboot';
            if (!file_exists($rebootPath)) {
                $rebootPath = '/usr/sbin/reboot';
            }
        
            // Se o caminho do reboot não existir, lança uma exceção
            if (!file_exists($rebootPath)) {
                throw new \Exception('Comando de reboot não encontrado.');
            }
        
            // Cria o comando completo com sudo
            $command = '/usr/bin/sudo ' . $rebootPath;
        
            // Cria o processo para executar o comando
            $process = new Process(explode(' ', $command));
            $process->run();
        
            // Verifica se o processo falhou
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
        
            // Retorna uma resposta para o usuário
            return redirect('/')->with('status', 'O servidor será reiniciado em breve!');
        }

}
