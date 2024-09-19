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

use App\Models\tb_meus_trabalhos;
use App\Models\tb_meus_materiais;
use App\Models\tb_meus_servicos;
use App\Models\tb_producao;
use App\Models\tb_equipamentos;
use App\Models\tb_empresa;
use App\Models\tb_dre;
use App\Models\view_tb_dre;
class ETController extends Controller
{
# editarMeuTrabalho
    public function editarMeuTrabalho(Request $request, string $id)
    {
        $empresa_id = auth()->user()->empresa_id; // ID da empresa

        if(auth()->user()->empresa_id == null){

            return redirect::to('inicio'); 

        }else{
        
        # TRABALHO
            $trabalho = tb_meus_trabalhos::where('id', $id)->first(); // Buscar o registro da tabela 'trabalhos' pelo ID

            if($trabalho['empresa_id'] != $empresa_id){

                return redirect::to('trabalhos');  

            } else {
        # MATERIAIS
            $materiaisArray = json_decode($trabalho->materiais, true); // Decodificar o JSON dos materiais associados ao trabalho
            $tb_meus_materiais = tb_meus_materiais::where('deleted', '0')->where('empresa_id', $empresa_id)->orderBy('material','ASC')->get();
            $materiaisFiltrados = collect();
            foreach ($materiaisArray as $ma) {
                $materialFiltrado = $tb_meus_materiais->firstWhere('id', $ma['id']);
                if ($materialFiltrado) {
                    $materialFiltrado['qtd'] = $ma['qtd']; // Adiciona a quantidade do material
                    $materiaisFiltrados->push($materialFiltrado); // Adiciona o material filtrado à coleção
                }
            }
            #dd($materiaisFiltrados);

        # SERVIÇOS
            $servicosArray = json_decode($trabalho->servicos, true);
            #dd($servicosArray);
            $tb_meus_servicos = tb_meus_servicos::where('deleted', '0')->where('empresa_id', $empresa_id)->orderBy('servico', 'ASC')->get();
            $servicosFiltrados = collect();
            foreach ($servicosArray as $sa) {
                $servicoFiltrado = $tb_meus_servicos->firstWhere('id', $sa['id']);
                if ($servicoFiltrado) {
                    $servicoFiltrado['t'] = $sa['t']; // Adiciona a quantidade de tempo
                    $servicosFiltrados->push($servicoFiltrado); // Adiciona o material filtrado à coleção
                }
            }
            #dd($servicosFiltrados);
            $tb_empresa = tb_empresa::where('id', $empresa_id)->first();

            $view_tb_dre = view_tb_dre::where('deleted','0')->where('empresa_id', $empresa_id)->get();
            $view_tb_dre_array = json_decode($view_tb_dre->toJson(), true);

            // Ordenar a array por anomes de forma decrescente
            usort($view_tb_dre_array, function ($a, $b) {
                return strcmp($b['anomes'], $a['anomes']);
            });

            // Limitar a array aos últimos 6 períodos
            $view_tb_dre_array = array_slice($view_tb_dre_array, 0, 6);

            // Calcular a média ponderada
            $numerador = 0;
            $denominador = 0;
            $peso = count($view_tb_dre_array);

            foreach ($view_tb_dre_array as $key => $item) {
                $numerador += $item['custoMinuto'] * $peso;
                $denominador += $peso;
                $peso--;
            }

            if($denominador==0){
                $mediaPonderada = 0;
            }
            else{

                $mediaPonderada = $numerador / $denominador;
            }

        #dd($servicosFiltrados);
                        
            # EQUIPAMENTOS
            $equipamentosArray = json_decode($trabalho->equipamentos, true);
            #dd($servicosArray);
            $tb_equipamentos = tb_equipamentos::where('deleted', '0')->where('empresa_id', $empresa_id)->get();
            $equipamentosFiltrados = collect();
            if( $equipamentosArray==null)
            {
                $equipamentosFiltrados = 0;
            } else {
                foreach ($equipamentosArray as $sa) {
                    $equipaFiltrado = $tb_equipamentos->firstWhere('id', $sa['id']);
                    if ($equipaFiltrado) {
                        $equipaFiltrado['t'] = $sa['t']; // Adiciona a quantidade de tempo
                        $equipamentosFiltrados->push($equipaFiltrado); // Adiciona o material filtrado à coleção
                    }
                }
            }

        $tb_producao = tb_producao::orderBy('anomes', 'DESC')->get();
        return view('meusDados.editarMeuTrabalho', compact('tb_equipamentos','equipamentosFiltrados','mediaPonderada','tb_empresa','tb_producao','view_tb_dre','tb_meus_servicos','tb_meus_materiais', 'trabalho', 'materiaisArray', 'materiaisFiltrados', 'servicosFiltrados'));
    }}}

# updateETMaterialQTD

    public function updateETMaterialQTD(Request $request)
    {
        $trabalhoID = $request->trabalhoID;
        $novoQtd = $request->qtd;
        $materialID = $request->materialID;

        // Log dos valores recebidos na request
        Log::info('updateETMaterialQTD - Request received', [
            'trabalhoID' => $trabalhoID,
            'novoQtd' => $novoQtd,
            'materialID' => $materialID
        ]);

        $tb_meus_trabalhos = tb_meus_trabalhos::where('id', $trabalhoID)->first();

        if (!$tb_meus_trabalhos) {
            Log::error('updateETMaterialQTD - Trabalho não encontrado', ['trabalhoID' => $trabalhoID]);
            return response()->json(['success' => false, 'message' => 'Trabalho não encontrado']);
        }

        $materiais = json_decode($tb_meus_trabalhos->materiais, true);
        if (!is_array($materiais)) {
            Log::warning('updateETMaterialQTD - Materiais não é um array', ['materiais' => $materiais]);
            $materiais = [];
        }

        $materialAtualizado = false;
        foreach ($materiais as &$material) {
            if ($material['id'] == $materialID) {
                $material['qtd'] = $novoQtd;
                $materialAtualizado = true;
                Log::info('updateETMaterialQTD - Material atualizado', ['material' => $material]);
                break;
            }
        }

        if (!$materialAtualizado) {
            Log::warning('updateETMaterialQTD - Material não encontrado no trabalho', ['materialID' => $materialID]);
        }

        $update = tb_meus_trabalhos::where('id', $trabalhoID)->update(['materiais' => json_encode(array_values($materiais))]);
        
        if ($update) {
            Log::info('updateETMaterialQTD - Atualização bem-sucedida', ['trabalhoID' => $trabalhoID]);
            return response()->json(['success' => true, 'message' => 'Dados atualizados com sucesso']);
        } else {
            Log::error('updateETMaterialQTD - Falha ao atualizar os dados', ['trabalhoID' => $trabalhoID]);
            return response()->json(['success' => false, 'message' => 'Falha ao atualizar os dados']);
        }
    }

# updateETServicoT

    public function updateETServicoT(Request $request)
    {
        $trabalhoID = $request->trabalhoID;
        $novoTempo = $request->t;
        $servicoID = $request->servicoID;

        // Log dos valores recebidos na request
        Log::info('updateETServicoT - Request received', [
            'trabalhoID' => $trabalhoID,
            'novoTempo' => $novoTempo,
            'servicoID' => $servicoID
        ]);

        $tb_meus_trabalhos = tb_meus_trabalhos::where('id', $trabalhoID)->first();

        if (!$tb_meus_trabalhos) {
            Log::error('updateETServicoT - Trabalho não encontrado', ['trabalhoID' => $trabalhoID]);
            return response()->json(['success' => false, 'message' => 'Trabalho não encontrado']);
        }

        $servicos = json_decode($tb_meus_trabalhos->servicos, true);
        if (!is_array($servicos)) {
            Log::warning('updateETServicoT - Serviços não é um array', ['servicos' => $servicos]);
            $servicos = [];
        }

        $servicoAtualizado = false;
        foreach ($servicos as &$servico) {
            if ($servico['id'] == $servicoID) {
                $servico['t'] = $novoTempo;
                $servicoAtualizado = true;
                Log::info('updateETServicoT - Serviço atualizado', ['servico' => $servico]);
                break;
            }
        }

        if (!$servicoAtualizado) {
            Log::warning('updateETServicoT - Serviço não encontrado no trabalho', ['servicoID' => $servicoID]);
        }

        $update = tb_meus_trabalhos::where('id', $trabalhoID)->update(['servicos' => json_encode(array_values($servicos))]);
        
        if ($update) {
            Log::info('updateETServicoT - Atualização bem-sucedida', ['trabalhoID' => $trabalhoID]);
            return response()->json(['success' => true, 'message' => 'Dados atualizados com sucesso']);
        } else {
            Log::error('updateETServicoT - Falha ao atualizar os dados', ['trabalhoID' => $trabalhoID]);
            return response()->json(['success' => false, 'message' => 'Falha ao atualizar os dados']);
        }
    }

# ETaddEquipamento
    public function ETaddEquipamento(Request $request, string $id)
    {
        #dd($request);
        $trabalho = tb_meus_trabalhos::where('id', $id)->first(); // Buscar o registro da tabela 'trabalhos' pelo ID
        $equipamentosArray = json_decode($trabalho->equipamentos, true);
        $equipamentosArray[] = [
            "id" => $request->equipamento,
            "t"  => $request->tempo
        ];
        $json = json_encode($equipamentosArray);

        tb_meus_trabalhos::where('id', $id)->update(['equipamentos' => $json, 'alt_user_id' => auth()->user()->id]);
        return Redirect::to('editarMeuTrabalho/'.$id); 
    }
# updateETEquipamentoT
    public function updateETEquipamentoT(Request $request)
    {
        // Recebe os dados do request
        $trabalhoID = $request->trabalhoID;
        $novoTempo = $request->t; // Corrigido para corresponder ao nome do campo no request
        $id = $request->equipamentoID; // Corrigido para corresponder ao nome do campo no request
    
        // Busca o trabalho específico no banco de dados
        $tb_meus_trabalhos = tb_meus_trabalhos::where('id', $trabalhoID)->first();
    
        // Decodifica o campo equipamentos
        $equipamentos = json_decode($tb_meus_trabalhos->equipamentos, true);
        if (!is_array($equipamentos)) {
            $equipamentos = [];
        }
    
        // Atualiza o tempo do equipamento
        $eqAtualizado = false;
        foreach ($equipamentos as &$eq) {
            if ($eq['id'] == $id) {
                $eq['t'] = $novoTempo;
                $eqAtualizado = true;
                break;
            }
        }
    
        // Atualiza o registro no banco de dados
        $update = tb_meus_trabalhos::where('id', $trabalhoID)->update(['equipamentos' => json_encode(array_values($equipamentos))]);
        
        // Retorna a resposta JSON
        if ($update && $eqAtualizado) {
            return response()->json(['success' => true, 'message' => 'Dados atualizados com sucesso']);
        } else {
            return response()->json(['success' => false, 'message' => 'Falha ao atualizar os dados']);
        }
    }

# ETapagarEquipamento
    public function ETapagarEquipamento(Request $request, $trab, $id)
    {
        $trabalho = tb_meus_trabalhos::where('id', $trab)->first();        
        $array = json_decode($trabalho->equipamentos, true);
        foreach ($array as $key => $value) {
            if ($value['id'] == $id) {
                unset($array[$key]);
            }
        }
        $json = json_encode($array);

        tb_meus_trabalhos::where('id', $trab)->update(['equipamentos' => $json]);

        tb_meus_trabalhos::where('id', $trab)->update(['alt_user_id' => auth()->user()->id]); // Salva o ID do usuário que alterou
        return Redirect::to('editarMeuTrabalho/'.$trab);

    }

# ETaddMaterial
    public function ETaddMaterial(Request $request, string $id)
    {
        $trabalho = tb_meus_trabalhos::where('id', $id)->first(); // Buscar o registro da tabela 'trabalhos' pelo ID
        $materiaisArray = json_decode($trabalho->materiais, true); // Decodificar o JSON dos materiais associados ao trabalho
        #dd($materiaisArray);

        $materiaisArray[] = [
            "id" => $request->material,
            "qtd" => $request->quantidade
        ];
        #dd($materiaisArray);
        $json = json_encode($materiaisArray);

        tb_meus_trabalhos::where('id', $id)->update(['materiais' => $json]);
        tb_meus_trabalhos::where('id', $id)->update(['alt_user_id' => auth()->user()->id]); // Salva o ID do usuário que alterou
        
        return Redirect::to('editarMeuTrabalho/'.$id); 
    }

# ETaddServico
    public function ETaddServico(Request $request, string $id)
    {
        # dd($request);
        $trabalho = tb_meus_trabalhos::where('id', $id)->first(); // Buscar o registro da tabela 'trabalhos' pelo ID
        $servicosArray = json_decode($trabalho->servicos, true);
        $servicosArray[] = [
            "id" => $request->servico,
            "t"  => $request->tempo*60
        ];
        $json = json_encode($servicosArray);

        tb_meus_trabalhos::where('id', $id)->update(['servicos' => $json, 'alt_user_id' => auth()->user()->id]);
        return Redirect::to('editarMeuTrabalho/'.$id); 
    }


# modalETMeusNovoMaterial
    public function modalETMeusNovoMaterial(Request $request, $id)
    {
        $empresa_id = auth()->user()->empresa_id;
        $alt_user_id = auth()->user()->id;
        
        $new = new tb_meus_materiais;
        $new->material = $request->material;
        $new->apresentacao = $request->apresentacao;
        $new->custo = $request->custo;
        $new->qtd_calculada = $request->qtd_calculada;
        $new->empresa_id = $empresa_id;
        $new->alt_user_id = $alt_user_id;
        $new->save();
    
        return Redirect::to('editarMeuTrabalho/' . $id); 
    }

# modalETMeusNovoServico
    public function modalETMeusNovoServico(Request $request, $id)
    {
        $empresa_id = auth()->user()->empresa_id;
        $alt_user_id = auth()->user()->id;

        $new = new tb_meus_servicos;
        $new->servico = $request->servico;

        $new->alt_user_id = $alt_user_id;
        $new->empresa_id = $empresa_id;
        $new->save();
    
        return Redirect::to('editarMeuTrabalho/' . $id); 
    }

    # ETapagarMaterial
    public function ETapagarMeuMaterial(Request $request, $trab, $id)
    {
        $trabalho = tb_meus_trabalhos::where('id', $trab)->first();        
        $array = json_decode($trabalho->materiais, true);
        foreach ($array as $key => $value) {
            if ($value['id'] == $id) {
                unset($array[$key]);
            }
        }
        
        $json = json_encode($array);

        tb_meus_trabalhos::where('id', $trab)->update(['materiais' => $json]);
        tb_meus_trabalhos::where('id', $trab)->update(['alt_user_id' => auth()->user()->id]); // Salva o ID do usuário que alterou
        return Redirect::to('editarMeuTrabalho/'.$trab);
    }

# ETapagarMeuServico
    public function ETapagarMeuServico(Request $request, $trab, $id)
    {
        $trabalho = tb_meus_trabalhos::where('id', $trab)->first();        
        $array = json_decode($trabalho->servicos, true);
        foreach ($array as $key => $value) {
            if ($value['id'] == $id) {
                unset($array[$key]);
            }
        }
        $json = json_encode($array);

        tb_meus_trabalhos::where('id', $trab)->update(['servicos' => $json]);

        tb_meus_trabalhos::where('id', $trab)->update(['alt_user_id' => auth()->user()->id]); // Salva o ID do usuário que alterou
        return Redirect::to('editarMeuTrabalho/'.$trab);

    }

# updateOrderS
    public function updateOrderS(Request $request)
    {
        Log::info('updateOrderS function called.', ['request_data' => $request->all()]);

        $order = $request->input('order');
        Log::info('Order received.', ['order' => $order]);

        // Obtenha os dados atuais do JSON do banco de dados
        $trabalho = tb_meus_trabalhos::where('id', $request->trabalho_id)->first();
        if (!$trabalho) {
            Log::error('Trabalho not found.', ['trabalho_id' => $request->trabalho_id]);
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
        Log::info('Servicos reordered.', ['servicosOrdenados' => $servicosOrdenados]);

        // Salve o JSON atualizado de volta no banco de dados
        $trabalho->servicos = json_encode($servicosOrdenados);
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Error encoding JSON.', ['error' => json_last_error_msg()]);
            return response()->json(['success' => false, 'message' => 'Error encoding JSON.'], 500);
        }
        $trabalho->save();

        Log::info('Trabalho updated successfully.', ['trabalho' => $trabalho]);

        return response()->json(['success' => true]);
    }
# updateOrderM
    public function updateOrderM(Request $request)
    {
        Log::info('updateOrderM function called.', ['request_data' => $request->all()]);

        $order = $request->input('order');
        Log::info('Order received.', ['order' => $order]);

        // Obtenha os dados atuais do JSON do banco de dados
        $trabalho = tb_meus_trabalhos::where('id', $request->trabalho_id)->first();
        if (!$trabalho) {
            Log::error('Trabalho not found.', ['trabalho_id' => $request->trabalho_id]);
            return response()->json(['success' => false, 'message' => 'Trabalho not found.'], 404);
        }
        Log::info('Trabalho found.', ['trabalho' => $trabalho]);

        $materiais = json_decode($trabalho->materiais, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Error decoding JSON.', ['error' => json_last_error_msg()]);
            return response()->json(['success' => false, 'message' => 'Error decoding JSON.'], 500);
        }
        Log::info('materiais decoded.', ['materiais' => $materiais]);

        // Atualize a ordem dos serviços no JSON com base na nova ordem
        $materiaisOrdenados = [];
        foreach ($order as $item) {
            foreach ($materiais as $material) {
                if ($material['id'] == $item['id']) {
                    $materiaisOrdenados[] = $material;
                    break;
                }
            }
        }
        Log::info('materiais reordered.', ['materiaisOrdenados' => $materiaisOrdenados]);

        // Salve o JSON atualizado de volta no banco de dados
        $trabalho->materiais = json_encode($materiaisOrdenados);
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Error encoding JSON.', ['error' => json_last_error_msg()]);
            return response()->json(['success' => false, 'message' => 'Error encoding JSON.'], 500);
        }
        $trabalho->save();

        Log::info('Trabalho updated successfully.', ['trabalho' => $trabalho]);

        return response()->json(['success' => true]);
    }
# updateOrderE
    public function updateOrderE(Request $request)
    {
        Log::info('updateOrderE function called.', ['request_data' => $request->all()]);

        $order = $request->input('order');
        Log::info('Order received.', ['order' => $order]);

        // Obtenha os dados atuais do JSON do banco de dados
        $trabalho = tb_meus_trabalhos::where('id', $request->trabalho_id)->first();
        if (!$trabalho) {
            Log::error('Trabalho not found.', ['trabalho_id' => $request->trabalho_id]);
            return response()->json(['success' => false, 'message' => 'Trabalho not found.'], 404);
        }
        Log::info('Trabalho found.', ['trabalho' => $trabalho]);

        $equipamentos = json_decode($trabalho->equipamentos, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Error decoding JSON.', ['error' => json_last_error_msg()]);
            return response()->json(['success' => false, 'message' => 'Error decoding JSON.'], 500);
        }
        Log::info('equipamentos decoded.', ['equipamentos' => $equipamentos]);

        // Atualize a ordem dos serviços no JSON com base na nova ordem
        $equipamentosOrdenados = [];
        foreach ($order as $item) {
            foreach ($equipamentos as $equipamento) {
                if ($equipamento['id'] == $item['id']) {
                    $equipamentosOrdenados[] = $equipamento;
                    break;
                }
            }
        }
        Log::info('equipamentos reordered.', ['equipamentosOrdenados' => $equipamentosOrdenados]);

        // Salve o JSON atualizado de volta no banco de dados
        $trabalho->equipamentos = json_encode($equipamentosOrdenados);
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Error encoding JSON.', ['error' => json_last_error_msg()]);
            return response()->json(['success' => false, 'message' => 'Error encoding JSON.'], 500);
        }
        $trabalho->save();

        Log::info('Trabalho updated successfully.', ['trabalho' => $trabalho]);

        return response()->json(['success' => true]);
    }


}
