<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Redirect;
use Illuminate\Support\Arr;
use Illuminate\DataBase\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Models\tb_base_trabalhos;
use App\Models\tb_base_materiais;
use App\Models\tb_base_servicos;
use App\Models\tb_meus_trabalhos;
use App\Models\tb_meus_materiais;
use App\Models\tb_meus_servicos;

class BaseController extends Controller
{
    # consultaBaseTrabalhos
        public function consultaBaseTrabalhos(){


            return view('base.consultaBaseTrabalhos',['tb_base_trabalhos'=>tb_base_trabalhos::where('deleted', '0')->where('visivel','1')->get()]);
        }

    # getTrabalhoDetails
        public function getTrabalhoDetails($id)
        {
            try {
                Log::info('Recebendo detalhes do trabalho', ['id' => $id]);
        
                $trabalho = tb_base_trabalhos::where('id', $id)->first();
        
                if (!$trabalho) {
                    Log::warning('Trabalho não encontrado', ['id' => $id]);
                    return response()->json(['success' => false, 'message' => 'Trabalho não encontrado']);
                }
        
                $materiaisIds = json_decode($trabalho->materiais, true);
                $servicosIds = json_decode($trabalho->servicos, true);
        
                Log::info('Materiais e serviços decodificados', [
                    'materiais' => $materiaisIds,
                    'servicos' => $servicosIds
                ]);
        
                // Extraindo apenas os IDs dos materiais
                $materiaisIdsArray = array_map(function($material) {
                    return $material['id'];
                }, $materiaisIds);
        
                // Extraindo apenas os IDs dos serviços
                $servicosIdsArray = array_map(function($servico) {
                    return $servico['id'];
                }, $servicosIds);
        
                $materiais = tb_base_materiais::whereIn('id', $materiaisIdsArray)
                    ->get(['id', 'material']);
        
                $materiais = $materiais->map(function ($material) use ($materiaisIds) {
                    $material->qtd = collect($materiaisIds)->firstWhere('id', $material->id)['qtd'];
                    return $material;
                });
        
                $servicos = tb_base_servicos::whereIn('id', $servicosIdsArray)
                    ->get(['id', 'servico']);
        
                return response()->json([
                    'success' => true,
                    'trabalho' => $trabalho->trabalho, // Nome do trabalho
                    'materiais' => $materiais,
                    'servicos' => $servicos
                ]);
            } catch (\Exception $e) {
                Log::error('Erro ao obter detalhes do trabalho', [
                    'id' => $id,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
        }
        

    # consultaBaseMateriais
        public function consultaBaseMateriais(){


            return view('base.consultaBaseMateriais',['tb_base_materiais'=>tb_base_materiais::where('deleted', '0')->get()]);
        }

    # consultaBaseServicos
        public function consultaBaseServicos(){


            return view('base.consultaBaseServicos',['tb_base_servicos'=>tb_base_servicos::where('deleted', '0')->get()]);
        }

    # copiaBaseServicos
        public function copiaBaseServicos($id){
            $empresa_id = auth()->user()->empresa_id;
            $alt_user_id = auth()->user()->id;

            $servicos = tb_base_servicos::where('id', $id)->first();

            $tb_meus_servicos = tb_meus_servicos::where('servico', $servicos->servico)->where('empresa_id', $empresa_id)->exists();

            if ($tb_meus_servicos){
                $tb_meus_servicos = tb_meus_servicos::where('servico', $servicos->servico)->where('empresa_id', $empresa_id)->get();
                $statusMeusServicos = 1;
                return redirect()->route('meusServicos')->with('statusMeusServicos', 1)->with('servicolDuplicado',$servicos->servico )->with('tb_meus_servicos',$tb_meus_servicos);
            } else {

            $servicos = tb_base_servicos::where('id', $id)->get();

            foreach ($servicos as $servico) {

                $novoServico = new tb_meus_servicos;
                // $novoServico->id = $servico->id;
                $novoServico->servico = $servico->servico;
                // $novoServico->tempo = $servico->tempo;
                $novoServico->alt_user_id = $alt_user_id;
                $novoServico->empresa_id = $empresa_id;
                $novoServico->copiada = 's';
                $novoServico->save();
            }
        }

            return Redirect::to('meusServicos');  
        }
    # copiaBaseMateriais
        public function copiaBaseMateriais($id){
            $empresa_id = auth()->user()->empresa_id;
            $alt_user_id = auth()->user()->id;

            $materiais = tb_base_materiais::where('id', $id)->first();

            $tb_meus_materiais = tb_meus_materiais::where('material', $materiais->material)->where('empresa_id', $empresa_id)->exists();

            if ($tb_meus_materiais){
                $tb_meus_materiais = tb_meus_materiais::where('material',$materiais->material)->where('empresa_id',$empresa_id)->get();
                $statusMeusMateriais = 1;
                return redirect()->route('meusMateriais')->with('statusMeusMateriais', 1)->with('materialDuplicado',$materiais->material )->with('tb_meus_materiais',$tb_meus_materiais);
            } else {
                $materiais = tb_base_materiais::where('id', $id)->get();
                foreach ($materiais as $material) {

                    $new = new tb_meus_materiais;
                    $new->material = $material->material;
                    $new->apresentacao = $material->apresentacao;
                    $new->custo = $material->custo;
                    $new->qtd_calculada = $material->qtd_calculada;
                    $new->alt_user_id = $alt_user_id;
                    $new->empresa_id = $empresa_id;
                    $new->copiada = 's';
                    $new->save();
                }

            return Redirect::to('meusMateriais');  }
        }

    # copiaBaseTrabalhos
        public function copiaBaseTrabalhos($id){
            $empresa_id = auth()->user()->empresa_id;
            $alt_user_id = auth()->user()->id;

            # Confere se um trabalho idêntico já existe nos meus trabalhos:
            
            $tb_base_trabalhos = tb_base_trabalhos::where('id',$id)->first();
            #dd($tb_base_trabalhos);
            $confereTrabalhoMeus = tb_meus_trabalhos::where('trabalho', $tb_base_trabalhos->trabalho)->where('empresa_id', $empresa_id)->where('deleted', '0')->exists();
            #dd($confereTrabalhoMeus);

            if ($confereTrabalhoMeus ) {
                $statusMeusTrabalhos = "1"; 
                $tb_meus_trabalhos = tb_meus_trabalhos::where('trabalho', $tb_base_trabalhos->trabalho)->where('empresa_id', $empresa_id)->where('deleted', '0')->first();
                $insertedId = $tb_meus_trabalhos->id;
                #dd($tb_meus_trabalhos,$insertedId);

                // Redirecionar para a rota com a variável de sessão
                return Redirect::to('editarMeuTrabalho/'.$insertedId)->with('statusMeusTrabalhos', $statusMeusTrabalhos)->with('trabalhoDuplicado', $tb_base_trabalhos->trabalho);

            } 
            else
            {
                #$tb_base_trabalhos = tb_base_trabalhos::where('id',$id)->first();
                # Comparar a array dos materiais novos com a dos materiais que já existem

                ########################## MATERIAIS #####################################
                $materiaisBaseArray = json_decode($tb_base_trabalhos->materiais, true);
                
                $novaArrayCompleta = [];
                // Loop foreach para iterar sobre $materiaisBaseArray
                foreach ($materiaisBaseArray as $item) {
                    // Buscar os dados na tabela tb_base_trabalhos para o ID atual
                    $dadosMateriais = tb_base_materiais::where('id', $item['id'])->first();
                    
                    // Verificar se os dados foram encontrados
                    if ($dadosMateriais) {
                        // Adicionar os dados cruzados à nova array
                        $novaArrayCompleta[] = [
                            'material' => $dadosMateriais->material,
                            'apresentacao' => $dadosMateriais->apresentacao,
                            'custo' => $dadosMateriais->custo,
                            'qtd_calculada' => $dadosMateriais->qtd_calculada,
                            'qtd' => $item['qtd'] 
                        ];
                    }
                }
                #dd($novaArrayCompleta);


                // Nova array para armazenar os dados que estão apenas em $novaArrayCompleta

                $dadosApenasEmNovaArray = [];

                // Loop foreach para iterar sobre $novaArrayCompleta
                foreach ($novaArrayCompleta as $item) {
                    // Verificar se os mesmos dados existem na tabela tb_meus_materiais
                    $dadosExistem = tb_meus_materiais::where('material', $item['material'])
                                                    ->where('apresentacao', $item['apresentacao'])
                                                    ->where('custo', $item['custo'])
                                                    ->where('qtd_calculada', $item['qtd_calculada'])
                                                    ->where('empresa_id', $empresa_id)
                                                    ->where('deleted', '0')
                                                    ->exists();
                    
                    // Se os dados existirem na tabela tb_meus_materiais, adicione-os à nova array
                    if ($dadosExistem) {
                        $dadosEmAmbasAsTabelas[] = $item;
                    } else {
                        // Caso contrário, adicione-os à array $dadosApenasEmNovaArray
                        $dadosApenasEmNovaArray[] = $item;
                    }
                }
                #dd($dadosApenasEmNovaArray);

                foreach ($dadosApenasEmNovaArray as $item) {
                    $novoItem = new tb_meus_materiais();
                    $novoItem->material = $item['material'];
                    $novoItem->apresentacao = $item['apresentacao'];
                    $novoItem->custo = $item['custo'];
                    $novoItem->qtd_calculada = $item['qtd_calculada'];
                    $novoItem->empresa_id = $empresa_id;
                    $novoItem->alt_user_id = $alt_user_id;
                    $novoItem->copiada = "s";
                    $novoItem->save();
                    $materiaisNovosIds[] = $novoItem->id;
                }

                $pegaIdsM = [];

                // Percorre $novaArrayCompleta para buscar registros em tb_meus_materiais
                foreach ($novaArrayCompleta as $item) {
                    // Buscar o registro na tabela tb_meus_materiais
                    $dadosExistem = tb_meus_materiais::where('material', $item['material'])
                                                    ->where('apresentacao', $item['apresentacao'])
                                                    ->where('custo', $item['custo'])
                                                    ->where('qtd_calculada', $item['qtd_calculada'])
                                                    ->where('empresa_id', $empresa_id)
                                                    ->first(); // Obtenha apenas o primeiro resultado
                    
                                                    if ($dadosExistem) {
                                                        // Se encontrou o registro, adiciona o ID e a quantidade com as chaves 'id' e 'qtd' à array $pegaIds
                                                        $pegaIdsM[] = [
                                                            'id' => $dadosExistem->id,
                                                            'qtd' => $item['qtd']
                                                        ];
                                                    }
                }
            #dd($pegaIds);
                

            
            ########################## SERVICOS #####################################
            $servicosBaseArray = json_decode($tb_base_trabalhos->servicos, true);
                #dd($servicosBaseArray);
                $arrayDadosBaseServicos = [];
                // Loop foreach para iterar sobre $materiaisBaseArray
                foreach ($servicosBaseArray as $item) {
                    // Buscar os dados na tabela tb_base_trabalhos para o ID atual
                    $dadosServicos = tb_base_servicos::where('id', $item['id'])->first();
                    
                    // Verificar se os dados foram encontrados
                    if ($dadosServicos) {
                        // Adicionar os dados cruzados à nova array
                        $arrayDadosBaseServicos[] = [
                            'servico' => $dadosServicos->servico,
                            'tempo' => $item['t'],
                        ];
                    }
                }

                #dd($arrayDadosBaseServicos);
            $dadosApenasEmNovaArray = [];
            $dadosEmAmbasAsTabelas = [];

                // Loop foreach para iterar sobre $novaArrayCompleta
                foreach ($arrayDadosBaseServicos as $item) {
                    // Verificar se os mesmos dados existem na tabela tb_meus_materiais
                    $dadosExistem = tb_meus_servicos::where('servico', $item['servico'])
                    ->where('empresa_id', $empresa_id)
                    ->where('deleted', '0')
                    ->exists();
                    
                    // Se os dados existirem na tabela tb_meus_materiais, adicione-os à nova array
                    if ($dadosExistem) {
                        $dadosEmAmbasAsTabelas[] = $item;
                    } else {
                        // Caso contrário, adicione-os à array $dadosApenasEmNovaArray
                        $dadosApenasEmNovaArray[] = $item;
                    }
                }
                #dd($dadosEmAmbasAsTabelas,$dadosApenasEmNovaArray);

                foreach ($dadosApenasEmNovaArray as $item) {
                    $novoItem = new tb_meus_servicos();
                    $novoItem->servico = $item['servico'];
                    $novoItem->empresa_id = $empresa_id;
                    $novoItem->alt_user_id = $alt_user_id;
                    $novoItem->copiada = "s";
                    $novoItem->save();
                    $servicosNovosIds[] = $novoItem->id;
                }

                #dd($arrayDadosBaseServicos);
                $pegaIdsS = [];

                // Percorre $novaArrayCompleta para buscar registros em tb_meus_materiais
                foreach ($arrayDadosBaseServicos as $item) {
                    // Buscar o registro na tabela tb_meus_materiais
                    $dadosExistem = tb_meus_servicos::where('servico', $item['servico'])
                                                    ->where('empresa_id', $empresa_id)
                                                    ->where('deleted', '0')
                                                    ->first(); // Obtenha apenas o primeiro resultado
                    
                                                    if ($dadosExistem) {
                                                        // Se encontrou o registro, adiciona o ID e a quantidade com as chaves 'id' e 'qtd' à array $pegaIds
                                                        $pegaIdsS[] = [
                                                            'id' => $dadosExistem->id,
                                                            't'=> $item['tempo']
                                                        ];
                                                    }
                }
            #dd($pegaIdsS);

            #dd($servicosFiltrados,$servicosNovosIds);
            
            $novoMateriais = json_encode($pegaIdsM);
            $novoServicos = json_encode($pegaIdsS);

            $novoItem = new tb_meus_trabalhos();
                    $novoItem->trabalho = $tb_base_trabalhos->trabalho;
                    $novoItem->materiais = $novoMateriais;
                    $novoItem->servicos = $novoServicos;
            
                    $novoItem->empresa_id = $empresa_id;
                    $novoItem->alt_user_id = $alt_user_id;
                    $novoItem->copiada = "s";
                    $novoItem->save();
                    $novoId = $novoItem->id;
            
            $statusMeusTrabalhos = "0";
        
            return Redirect::route('editarMeuTrabalho', ['id' => $novoId])->with('statusMeusTrabalhos', $statusMeusTrabalhos);
        }
    
        }
}
 