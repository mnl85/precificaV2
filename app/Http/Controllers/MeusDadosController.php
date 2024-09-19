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
use DateTime;
use App\Models\tb_meus_trabalhos;
use App\Models\tb_meus_materiais;
use App\Models\tb_meus_servicos;
use App\Models\tb_producao;
use App\Models\tb_empresa;
use App\Models\tb_equipamentos;
use App\Models\tb_dre;
use App\Models\view_tb_dre;


class MeusDadosController extends Controller
{ 
# inicio
    public function inicio()
    {

        return redirect::to('userRelatorios'); 

    }
# meusTrabalhos
    public function meusTrabalhos()
    {
        
        ################################################
    
        $empresa_id = auth()->user()->empresa_id; // ID da empresa

        if(auth()->user()->empresa_id == null){

            return view('meusDados.inicio'); 

        }else{

            

    
        # VALOR COBRADO = TABELA EMPRESA TRABALHOS
        $empresa_id = auth()->user()->empresa_id; // ID da empresa

        $view_tb_dre = view_tb_dre::where('deleted', '0')->where('empresa_id', $empresa_id)->get();
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
        $tb_meus_trabalhos = tb_meus_trabalhos::where('deleted', '0')->where('empresa_id', $empresa_id)->get();
        $tb_meus_materiais = tb_meus_materiais::where('deleted', '0')->where('empresa_id', $empresa_id)->get();
        $tb_empresa = tb_empresa::where('id', $empresa_id)->first();
        $tb_meus_servicos = tb_meus_servicos::where('deleted', '0')->where('empresa_id', $empresa_id)->get();
        $tb_producao = tb_producao::where('deleted', '0')->where('empresa_id', $empresa_id)->orderBy('anomes', 'DESC')->get();
        
        #dd($tb_producao);
        if ($view_tb_dre->isEmpty() || $tb_producao->isEmpty()){

            $total_tempo_prod = 0;
            $total_tempo_dre = 0;
    
            }
        else{
        
     
            // Data atual (ano-mês)
            $mes_atual = date("Y-m");
    
            // Último ano-mês da tabela DRE
            $ultimo_anomes_dre = $view_tb_dre[0]->anomes; // Supondo que $view_tb_dre[0]->anomes seja algo como "2024-02"
    
            // Convertendo as datas em objetos DateTime
            $mes_atual_date = new DateTime($mes_atual);
            $ultimo_anomes_date_dre = new DateTime($ultimo_anomes_dre);
    
            // Calculando a diferença em dias
            $diferenca_dre = $mes_atual_date->diff($ultimo_anomes_date_dre);
            $total_tempo_dre = ($diferenca_dre->days)/30;
    
            // Último ano-mês da tabela de produção
            $ultimo_anomes_prod = $tb_producao[0]->anomes;
            $ultimo_anomes_date_prod = new DateTime($ultimo_anomes_prod);
    
            // Calculando a diferença em dias
            $diferenca_prod = $mes_atual_date->diff($ultimo_anomes_date_prod);
            $total_tempo_prod = ($diferenca_prod->days)/30;
    
            // Exibindo as diferenças em dias (apenas para debug)
            #dd($total_tempo_dre, $total_tempo_prod);
        }
        
        if ($tb_empresa['imposto_padrao']==null || $tb_empresa['imposto_padrao']==""){
            $imposto_padrao = 0;
        } else {
            $imposto_padrao = $tb_empresa['imposto_padrao'];
        }

        return view('meusDados.meusTrabalhos', compact('total_tempo_prod','total_tempo_dre','imposto_padrao','mediaPonderada','tb_empresa','tb_producao','view_tb_dre','tb_meus_trabalhos','tb_meus_materiais','tb_meus_servicos'));
    }}
    
# novoMeuTrabalho
    public function novoMeuTrabalho(Request $request)
    {
        $empresa_id = auth()->user()->empresa_id;
        $alt_user_id = auth()->user()->id;
        
        $new = new tb_meus_trabalhos;
        $new->trabalho = $request->trabalho;

        $new->alt_user_id = $alt_user_id;
        $new->empresa_id = $empresa_id;
        $new->copiada = 'n';
        $new->save();
        $novoID = $new->id;
    
        return Redirect::to('editarMeuTrabalho/'.$novoID); 
    }

# updateTrabalhoValorCobrado
    public function updateTrabalhoValorCobrado(Request $request, $id)
    {
        Log::info('Iniciando atualização:', ['id' => $id, 'request_data' => $request->all()]);

        if (!$request->has('valor_cobrado')) {
            Log::error('Campo valor_cobrado não encontrado na requisição.');
            return response()->json(['success' => false, 'message' => 'Campo valor_cobrado não encontrado.'], 400);
        }

        $newValue = str_replace(',', '.', $request->input('valor_cobrado'));

        tb_meus_trabalhos::where('id', $id)->update(['valor_cobrado' => $newValue]);

        return response()->json(['success' => true, 'message' => 'Quantidade atualizada com sucesso.', 'updatedValue' => $newValue], 200);
    }

# meusMateriais 
    public function meusMateriais()
    {
        $empresa_id = auth()->user()->empresa_id; // ID da empresa

        if(auth()->user()->empresa_id == null){

            return redirect::to('inicio'); 

        }else{
        $tb_meus_materiais = tb_meus_materiais::where('empresa_id',auth()->user()->empresa_id)->where('deleted', '0')->get();

        $materiais_velhos = tb_meus_materiais::where('empresa_id', auth()->user()->empresa_id)
            ->where('deleted', '0')
            ->where('updated_at', '<', Carbon::now()->subYear())
            ->get();

        $materiais_velhos = count($materiais_velhos);
        #dd($materiais_velhos);
        // No controlador ou na classe de modelo
        foreach ($tb_meus_materiais as $material) {
            $material->custo_fracao = floatval($material->qtd_calculada) != 0 
                ? number_format(floatval($material->custo) / floatval($material->qtd_calculada), 2, ',', '.') 
                : '0,00';
        }

        // Passar os dados recuperados para a view 'materiais'
        return view('meusDados.meusMateriais', compact('tb_meus_materiais','materiais_velhos'));
    }}


# novoMeuMaterial
    public function novoMeuMaterial(Request $request)
    {
        $empresa_id = auth()->user()->empresa_id;
        $alt_user_id = auth()->user()->id;
        
        $new = new tb_meus_materiais;
        $new->material = $request->material;
        $new->apresentacao = $request->apresentacao;
        $new->custo = $request->custo;
        $new->qtd_calculada = $request->qtd_calculada;
        $new->alt_user_id = $alt_user_id;
        $new->empresa_id = $empresa_id;
        $new->copiada = 'n';
        $new->save();
    
        return Redirect::to('materiais'); 
    }


# updateMeusMateriais
    public function updateMeusMateriais(Request $request, $id)
    {
        $fields = ['material', 'apresentacao', 'custo', 'qtd_calculada'];
        Log::info('Iniciando atualização de materiais', ['id' => $id, 'request_data' => $request->all()]);

        $updateData = [];
        foreach ($fields as $field) {
            if ($request->has($field)) {
                Log::info('Campo encontrado no request', ['field' => $field, 'value' => $request->$field]);
                
                if (in_array($field, ['custo', 'qtd_calculada'])) {
                    $str = str_replace(['R$ ', ','], ['', '.'], $request->$field);
                    Log::info('Campo processado', ['field' => $field, 'original' => $request->$field, 'processed' => $str]);
                    $updateData[$field] = $str;
                } else {
                    $updateData[$field] = $request->$field;
                }
            }
        }

        if (!empty($updateData)) {
            $updateData['alt_user_id'] = auth()->user()->id;
            $update = tb_meus_materiais::where('id', $id)->update($updateData);
            
            if ($update) {
                Log::info('Atualização concluída com sucesso', ['id' => $id]);
                $updatedValue = $request->input(key($updateData));

                // Recalcular custo fração
                $material = tb_meus_materiais::find($id);
                $custoFracao = floatval($material->custo) / floatval($material->qtd_calculada);

                return response()->json([
                    'success' => true, 
                    'message' => 'Dados atualizados com sucesso', 
                    'updatedValue' => $updatedValue,
                    'custoFracao' => number_format($custoFracao, 2, ',', '.')
                ]);
            } else {
                Log::error('Falha na atualização dos dados', ['id' => $id]);
                return response()->json(['success' => false, 'message' => 'Falha ao atualizar os dados']);
            }
        } else {
            Log::warning('Nenhum campo atualizado', ['id' => $id]);
            return response()->json(['success' => false, 'message' => 'Nenhum dado para atualizar']);
        }
    }



# meusServicos
    public function meusServicos()
    {
        $empresa_id = auth()->user()->empresa_id; // ID da empresa

        if(auth()->user()->empresa_id == null){

            return view('inicio'); 

        }else{
        $empresa_id = auth()->user()->empresa_id;
        $tb_meus_servicos = tb_meus_servicos::where('deleted', '0')->where('empresa_id',$empresa_id )->get();

        return view('meusDados.meusServicos', compact('tb_meus_servicos'));
    }}



# novoMeuServico
    public function novoMeuServico(Request $request)
    {
        $empresa_id = auth()->user()->empresa_id;
        $alt_user_id = auth()->user()->id;
    
        $new = new tb_meus_servicos;
        $new->servico = $request->servico;

        $new->alt_user_id = $alt_user_id;
        $new->empresa_id = $empresa_id;
        $new->copiada = 'n';
        $new->save();
    
        return Redirect::to('servicos'); 
    }

# updateMeusServicos
    public function updateMeusServicos(Request $request, $id)
    {
        $fields = ['servico', 'tempo'];
        $updateData = ['alt_user_id' => auth()->user()->id]; // Sempre salvar o ID do usuário que alterou

        foreach ($fields as $field) {
            if ($request->has($field)) {
                // Verificar se o campo é 'tempo' e processá-lo conforme necessário
                if ($field === 'tempo') {
                    $tempoValue = floatval($request->input($field)) * 60;
                    $updateData[$field] = $tempoValue;
                } else {
                    $updateData[$field] = $request->input($field);
                }
            }
        }

        // Atualizar o registro com os dados processados
        try {
            $update = tb_meus_servicos::where('id', $id)->update($updateData);

            // Verificar se a atualização foi bem-sucedida e retornar a resposta adequada
            if ($update) {
                return response()->json(['success' => true, 'message' => 'Dados atualizados com sucesso']);
            } else {
                return response()->json(['success' => false, 'message' => 'Falha ao atualizar os dados']);
            }
        } catch (\Exception $e) {
            // Log the error message for debugging
            \Log::error('Erro ao atualizar serviço: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erro ao atualizar dados', 'error' => $e->getMessage()]);
        }
    }

# apagarMeuServico
    public function apagarMeuServico(Request $request, $id)
    {
        tb_meus_servicos::where('id', $id)->update(['deleted' => '1']);
        tb_meus_servicos::where('id', $id)->update(['alt_user_id' => auth()->user()->id]); // Salva o ID do usuário que alterou
        return Redirect::to('meusServicos');  
    }
# apagarMeuMaterial
    public function apagarMeuMaterial(Request $request, $id)
    {
        tb_meus_materiais::where('id', $id)->update(['deleted' => '1']);
        tb_meus_materiais::where('id', $id)->update(['alt_user_id' => auth()->user()->id]); // Salva o ID do usuário que alterou
        return Redirect::to('meusMateriais');   
    }

# apagarMeuTrabalho
    public function apagarMeuTrabalho(Request $request, $id)
    {
        tb_meus_trabalhos::where('id', $id)->update(['deleted' => '1']);
        tb_meus_trabalhos::where('id', $id)->update(['alt_user_id' => auth()->user()->id]); // Salva o ID do usuário que alterou
        return Redirect::to('meusTrabalhos');
    }
}
