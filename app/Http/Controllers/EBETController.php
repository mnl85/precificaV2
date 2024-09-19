<?php

namespace App\Http\Controllers;
use Redirect;
use App\Models\tb_base_trabalhos;
use App\Models\tb_base_materiais;
use App\Models\tb_base_servicos;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class EBETController extends Controller
{
    # EBET
        public function EBET(string $id)

         {
            if(auth()->user()->empresa_id == null || auth()->user()->role_id != '1' ){

                return redirect::to('inicio'); 
    
            }else{
        
            # TRABALHO
                $trabalho = tb_base_trabalhos::where('id', $id)->first(); // Buscar o registro da tabela 'trabalhos' pelo ID

            # MATERIAIS
                $materiaisArray = json_decode($trabalho->materiais, true); // Decodificar o JSON dos materiais associados ao trabalho
                $tb_base_materiais = tb_base_materiais::where('deleted','0')->get();
                $materiaisFiltrados = collect();
                if ($materiaisArray)
                foreach ($materiaisArray as $ma) {
                    $materialFiltrado = $tb_base_materiais->firstWhere('id', $ma['id']);
                    if ($materialFiltrado) {
                        #$materialFiltrado['qtd'] = $ma['qtd']; // Adiciona a quantidade do material
                        $materialFiltrado['qtd'] = str_replace('.',',',$ma['qtd']); // Replace

                        $materiaisFiltrados->push($materialFiltrado); // Adiciona o material filtrado à coleção
                    }
                }
                else{
                    $materiaisFiltrados = False;
                }
 
            # SERVIÇOS
                $servicosArray = json_decode($trabalho->servicos, true);
                #dd($servicosArray);
                $tb_base_servicos = tb_base_servicos::where('deleted','0')->get();
                $servicosFiltrados = collect();
                
                if ($servicosArray)
                foreach ($servicosArray as $sa) {
                $servicoFiltrado = $tb_base_servicos->firstWhere('id', $sa['id']);
                if ($servicoFiltrado) {
                    $servicoFiltrado['t'] = $sa['t']; // Adiciona a quantidade de tempo
                    $servicosFiltrados->push($servicoFiltrado); // Adiciona o material filtrado à coleção
                }
                }
                else
                {
                    $servicosFiltrados = False;
                }
                #dd($materiaisFiltrados, $servicosFiltrados);
     
                
                
                $tb_base_servicos = tb_base_servicos::where('deleted','0')->orderBy('servico','ASC')->get();

             return view('admin.editarBase.EBET', compact('tb_base_servicos','tb_base_materiais', 'trabalho', 'materiaisArray', 'materiaisFiltrados', 'servicosFiltrados'));
         }}

    # EBETMaterialEdit
        public function EBETMaterialEdit(Request $request)
        {
            $trabalhoID = $request->trabalhoID;
            $novoQtd = str_replace(',','.',$request->qtd);
            
            $materialID = $request->materialID;
        
            // Log dos valores recebidos na request
            Log::info('EBETMaterialEdit - Request received', [
                'trabalhoID' => $trabalhoID,
                'novoQtd' => $novoQtd,
                'materialID' => $materialID
            ]);
        
            $tb_base_trabalhos = tb_base_trabalhos::where('id', $trabalhoID)->first();
        
            if (!$tb_base_trabalhos) {
                Log::error('EBETMaterialEdit - Trabalho não encontrado', ['trabalhoID' => $trabalhoID]);
                return response()->json(['success' => false, 'message' => 'Trabalho não encontrado']);
            }
        
            $materiais = json_decode($tb_base_trabalhos->materiais, true);
            if (!is_array($materiais)) {
                Log::warning('EBETMaterialEdit - Materiais não é um array', ['materiais' => $materiais]);
                $materiais = [];
            }
        
            $materialAtualizado = false;
            foreach ($materiais as &$material) {
                if ($material['id'] == $materialID) {
                    $material['qtd'] = $novoQtd;
                    $materialAtualizado = true;
                    Log::info('EBETMaterialEdit - Material atualizado', ['material' => $material]);
                    break;
                }
            }
        
            if (!$materialAtualizado) {
                Log::warning('EBETMaterialEdit - Material não encontrado no trabalho', ['materialID' => $materialID]);
                return response()->json(['success' => false, 'message' => 'Material não encontrado no trabalho'], 404);
            }
        
            $update = tb_base_trabalhos::where('id', $trabalhoID)->update(['materiais' => json_encode(array_values($materiais))]);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('EBETMaterialEdit - Error encoding JSON.', ['error' => json_last_error_msg()]);
                return response()->json(['success' => false, 'message' => 'Error encoding JSON.'], 500);
            }
        
            if ($update) {
                Log::info('EBETMaterialEdit - Atualização bem-sucedida', ['trabalhoID' => $trabalhoID]);
                return response()->json(['success' => true, 'message' => 'Dados atualizados com sucesso']);
            } else {
                Log::error('EBETMaterialEdit - Falha ao atualizar os dados', ['trabalhoID' => $trabalhoID]);
                return response()->json(['success' => false, 'message' => 'Falha ao atualizar os dados']);
            }
        }
    
        
    # EBETServicoEdit
        public function EBETServicoEdit(Request $request)
            {
                $trabalhoID = $request->trabalhoID;
                $novoTempo = strval($request->t); // Converter o novo tempo para string
                $servicoID = $request->servicoID;

                // Log dos valores recebidos na request
                Log::info('EBETServicoEdit - Request received', [
                    'trabalhoID' => $trabalhoID,
                    'novoTempo' => $novoTempo,
                    'servicoID' => $servicoID
                ]);

                $tb_base_trabalhos = tb_base_trabalhos::where('id', $trabalhoID)->first();

                if (!$tb_base_trabalhos) {
                    Log::error('EBETServicoEdit - Trabalho não encontrado', ['trabalhoID' => $trabalhoID]);
                    return response()->json(['success' => false, 'message' => 'Trabalho não encontrado']);
                }

                $servicos = json_decode($tb_base_trabalhos->servicos, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    Log::error('EBETServicoEdit - Error decoding JSON.', ['error' => json_last_error_msg()]);
                    return response()->json(['success' => false, 'message' => 'Error decoding JSON.'], 500);
                }

                if (!is_array($servicos)) {
                    Log::warning('EBETServicoEdit - Serviços não é um array', ['servicos' => $servicos]);
                    $servicos = [];
                }

                $servicoAtualizado = false;
                foreach ($servicos as &$servico) {
                    if ($servico['id'] == $servicoID) {
                        $servico['t'] = $novoTempo;
                        $servicoAtualizado = true;
                        Log::info('EBETServicoEdit - Serviço atualizado', ['servico' => $servico]);
                        break;
                    }
                }

                if (!$servicoAtualizado) {
                    Log::warning('EBETServicoEdit - Serviço não encontrado no trabalho', ['servicoID' => $servicoID]);
                    return response()->json(['success' => false, 'message' => 'Serviço não encontrado no trabalho'], 404);
                }

                $update = tb_base_trabalhos::where('id', $trabalhoID)->update(['servicos' => json_encode(array_values($servicos))]);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    Log::error('EBETServicoEdit - Error encoding JSON.', ['error' => json_last_error_msg()]);
                    return response()->json(['success' => false, 'message' => 'Error encoding JSON.'], 500);
                }

                if ($update) {
                    Log::info('EBETServicoEdit - Atualização bem-sucedida', ['trabalhoID' => $trabalhoID]);
                    return response()->json(['success' => true, 'message' => 'Dados atualizados com sucesso']);
                } else {
                    Log::error('EBETServicoEdit - Falha ao atualizar os dados', ['trabalhoID' => $trabalhoID]);
                    return response()->json(['success' => false, 'message' => 'Falha ao atualizar os dados']);
                }
            }

    # EBETMaterialAdd
        public function EBETMaterialAdd(Request $request, $id)
        {
            Log::info('Function called.', ['request_data' => $request->all()]);
            // Recuperar o trabalho BASE pelo ID
            $tb_base_trabalhos = tb_base_trabalhos::where('id', $id)->first();
        
            // Decodificar o JSON existente
            $materiaisArray = json_decode($tb_base_trabalhos->materiais, true);
        
            // Adicionar o novo material ao array
            $novoMaterial = [
                "id" => $request->material,
                "qtd" => $request->quantidade
            ];
        
            // Verificar se $materiaisArray é um array, se não inicializar como array vazio
            if (!is_array($materiaisArray)) {
                $materiaisArray = [];
            }
        
            // Adicionar o novo material ao array existente
            $materiaisArray[] = $novoMaterial;
        
            // Codificar novamente o array para JSON
            $json = json_encode($materiaisArray);
        
            // Atualizar os materiais no banco de dados
            $add = tb_base_trabalhos::where('id', $id)->update([
                'materiais' => $json,
                'alt_user_id' => auth()->user()->id
            ]);
        

            if ($add) {
          
                return response()->json(['success' => true, 'message' => 'Dados atualizados com sucesso']);
            } else {
    
                return response()->json(['success' => false, 'message' => 'Falha ao atualizar os dados']);
            }
        }
    # EBETMaterialNovo
        public function EBETMaterialNovo(Request $request, $id)
        {

            Log::info('Function called.', ['request_data' => $request->all()]);
            
            $new = new tb_base_materiais;
            $new->material = $request->material;
            $new->apresentacao = $request->apresentacao;
            $new->custo = $request->custo;
            $new->qtd_calculada = $request->quantidade;
            $new->alt_user_id = auth()->user()->id;
            $new->save();
            $novo = $new->id;
            
                if ($novo) {
                    return response()->json(['success' => true, 'message' => 'Dados atualizados com sucesso']);
                } else {
                    return response()->json(['success' => false, 'message' => 'Falha ao atualizar os dados']);
                }
                
        }
    # EBETServicoAdd
        public function EBETServicoAdd(Request $request, $id)
        {
            Log::info('Function called.', ['request_data' => $request->all()]);
            // Buscar o registro da tabela 'trabalhos' pelo ID
            $trabalho = tb_base_trabalhos::where('id', $id)->first(); 
        

                // Decodificar o JSON do campo 'servicos'
                $servicosArray = json_decode($trabalho->servicos, true);
        
                // Adicionar o novo serviço ao array
                $servicosArray[] = [
                    "id" => $request->servico,
                    "t"  => $request->t * 60
                ];
        
                // Re-encodar o array para JSON com as devidas opções
                $json = json_encode($servicosArray, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
                $add = tb_base_trabalhos::where('id', $id)->update(['servicos' => $json]); 
        
                if ($add) {

                    return response()->json(['success' => true, 'message' => 'Dados atualizados com sucesso']);
                } else {

                    return response()->json(['success' => false, 'message' => 'Falha ao atualizar os dados']);
                }

        }
        
    # EBETServicoNovo
        public function EBETServicoNovo(Request $request, $id)
        {

            $new = new tb_base_servicos;
            $new->servico = $request->servico;
            $new->alt_user_id = auth()->user()->id;
            $new->save();
            $novo = $new->id;
        
            if ($novo) {
                return response()->json(['success' => true, 'message' => 'Dados atualizados com sucesso']);
            } else {
                return response()->json(['success' => false, 'message' => 'Falha ao atualizar os dados']);
            }
            

        }

    # EBETMaterialDelete
        public function EBETMaterialDelete(Request $request,  $trab, $id)
        {

            $trabalho = tb_base_trabalhos::where('id', $trab)->first();        
            $array = json_decode($trabalho->materiais, true);
            foreach ($array as $key => $value) {
                if ($value['id'] == $id) {
                    unset($array[$key]);
                }
            }
            
            $json = json_encode($array);

            tb_base_trabalhos::where('id', $trab)->update(['materiais' => $json, 'alt_user_id' => auth()->user()->id]);

            return Redirect::to('EBET/'.$trab);
        }    
        
    # EBETServicoDelete
        public function EBETServicoDelete(Request $request, $trab, $id)
        {
            $trabalho = tb_base_trabalhos::where('id', $trab)->first();        
            $array = json_decode($trabalho->servicos, true);
            foreach ($array as $key => $value) {
                if ($value['id'] == $id) {
                    unset($array[$key]);
                }
            }
            $json = json_encode($array);


            tb_base_trabalhos::where('id', $trab)->update(['servicos' => $json, 'alt_user_id' => auth()->user()->id]);

            return Redirect::to('EBET/'.$trab);
        }   
        
        
    # EBETServicolOrder
        public function EBETServicolOrder(Request $request)
        {
            Log::info('EBETServicolOrder function called.', ['request_data' => $request->all()]);
        
            $order = $request->input('order');
            Log::info('Order received.', ['order' => $order]);
        
            // Valide o input recebido
            if (!is_array($order) || empty($order)) {
                Log::error('Invalid order received.', ['order' => $order]);
                return response()->json(['success' => false, 'message' => 'Invalid order.'], 400);
            }
        
            // Obtenha os dados atuais do JSON do banco de dados
            $trabalho = tb_base_trabalhos::where('id', $request->input('trabalho_id'))->first();
            if (!$trabalho) {
                Log::error('Trabalho not found.', ['trabalho_id' => $request->input('trabalho_id')]);
                return response()->json(['success' => false, 'message' => 'Trabalho not found.'], 404);
            }
            Log::info('Trabalho found.', ['trabalho' => $trabalho]);
        
            $servicos = json_decode($trabalho->servicos, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Error decoding JSON.', ['error' => json_last_error_msg()]);
                return response()->json(['success' => false, 'message' => 'Error decoding JSON.'], 500);
            }
            Log::info('Servicos decoded.', ['servicos' => $servicos]);
        
            // Atualize a ordem dos serviços no JSON com base na nova ordem
            $servicosOrdenados = [];
            foreach ($order as $item) {
                foreach ($servicos as $servico) {
                    if ($servico['id'] == $item['id']) {
                        $servicosOrdenados[] = $servico;
                        break;
                    }
                }
            }
        
            // Verifique se todos os serviços foram encontrados e ordenados corretamente
            if (count($servicosOrdenados) !== count($order)) {
                Log::error('Mismatch in the number of ordered services.', [
                    'expected' => count($order),
                    'actual' => count($servicosOrdenados),
                    'order' => $order,
                    'servicosOrdenados' => $servicosOrdenados
                ]);
                return response()->json(['success' => false, 'message' => 'Mismatch in the number of ordered services.'], 400);
            }
            Log::info('Servicos reordered.', ['servicosOrdenados' => $servicosOrdenados]);
        
            // Salve o JSON atualizado de volta no banco de dados
            $trabalho->servicos = json_encode($servicosOrdenados);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Error encoding JSON.', ['error' => json_last_error_msg()]);
                return response()->json(['success' => false, 'message' => 'Error encoding JSON.'], 500);
            }
        
            if (!$trabalho->save()) {
                Log::error('Error saving trabalho.', ['trabalho' => $trabalho]);
                return response()->json(['success' => false, 'message' => 'Error saving trabalho.'], 500);
            }
        
            Log::info('Trabalho updated successfully.', ['trabalho' => $trabalho]);
        
            return response()->json(['success' => true]);
        }
        

}
 