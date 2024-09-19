<?php

namespace App\Http\Controllers;
use Redirect;
use App\Models\tb_base_trabalhos;
use App\Models\tb_base_materiais;
use App\Models\tb_base_servicos;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class EBController extends Controller
{
    # EBTrabalhos
        public function EBTrabalhos(){
            if(auth()->user()->empresa_id == null || auth()->user()->role_id != '1' ){

                return redirect::to('inicio'); 

            }else{
            $tb_base_trabalhos = tb_base_trabalhos::all();

            return view('admin.editarBase.EBTrabalhos', compact('tb_base_trabalhos'));
            }
        }
        

    # EBTrabalhosUpdateCheckboxState
        public function EBTrabalhosUpdateCheckboxState(Request $request, $id)
        {
            // Validação do campo 'visivel' para garantir que é um valor esperado (0 ou 1)
            $request->validate([
                'visivel' => 'required|boolean'
            ]);
        
            // Atualização do registro no banco de dados
            $update = tb_base_trabalhos::where('id', $id)
                ->update([
                    'visivel' => $request->visivel, 
                    'alt_user_id' => auth()->user()->id
                ]);
        
            // Retorna a resposta em JSON com base no sucesso ou falha da atualização
            if ($update) {
                return response()->json(['success' => true, 'message' => 'Visibilidade atualizada']);
            } else {
                return response()->json(['success' => false, 'message' => 'Erro ao atualizar visibilidade']);
            }
        }
        
    # EBMateriais
        public function EBMateriais(){
            if(auth()->user()->empresa_id == null || auth()->user()->role_id != '1' ){

                return redirect::to('inicio'); 
    
            }else{
                $tb_base_materiais = tb_base_materiais::where('deleted', '0')->get();

                foreach ($tb_base_materiais as $material) {
                    $material->custo_fracao = floatval($material->qtd_calculada) != 0 
                        ? number_format(floatval($material->custo) / floatval($material->qtd_calculada), 2, ',', '.') 
                        : '0,00';

                        return view('admin.editarBase.EBMateriais', compact('tb_base_materiais'));
                }
            }
        }
        
    # EBServicos
        public function EBServicos(){
            if(auth()->user()->empresa_id == null || auth()->user()->role_id != '1' ){

                return redirect::to('inicio'); 

            }else{
            $tb_base_servicos = tb_base_servicos::where('deleted', '0')->orderBy('servico','ASC')->get();

            return view('admin.editarBase.EBServicos', compact('tb_base_servicos'));
            }
           
        }

    # EBMateriaisUpdate
        public function EBMateriaisUpdate(Request $request, $id)
        {
            $fields = ['material', 'apresentacao', 'custo', 'qtd_calculada'];

            foreach ($fields as $field) {
                if ($request->has($field)) {
                    if($request->has('custo')){
                        $str = str_replace(['R$ ', ','], ['', '.'], $request->$field);
                        $update = tb_base_materiais::where('id', $id)->update([$field => $str]);
                    }else {
                        $update = tb_base_materiais::where('id', $id)->update([$field => $request->$field]);
                    }
                    
                }
            }

            tb_base_materiais::where('id', $id)->update(['alt_user_id' => auth()->user()->id]); // Salva o ID do usuário que alterou
              // Verificar se a atualização foi bem-sucedida e retornar a resposta adequada
              if ($update) {
                return response()->json(['success' => true, 'message' => 'Dados atualizados com sucesso']);
            } else {
                return response()->json(['success' => false, 'message' => 'Falha ao atualizar os dados']);
            }
        }
    # EBServicosUpdate
        public function EBServicosUpdate(Request $request, $id)
        { 
            $update = tb_base_servicos::where('id', $id)->update(['servico' => $request->servico, 'alt_user_id' => auth()->user()->id]);

            if ($update) {
                return response()->json(['success' => true, 'message' => 'Dados atualizados com sucesso']);
            } else {
                return response()->json(['success' => false, 'message' => 'Falha ao atualizar os dados']);
            }
        }

    # EBTrabalhosUpdates
        public function EBTrabalhosUpdates(Request $request, $id)
        {
    
            $update = tb_base_trabalhos::where('id_tb_base_trabalhos', $id)->update(['trabalho' => $request->trabalho],['alt_user_id' => auth()->user()->id]);


        
            if ($update) {
                return response()->json(['success' => true, 'message' => 'Dados atualizados com sucesso']);
            } else {
                return response()->json(['success' => false, 'message' => 'Falha ao atualizar os dados']);
            }
        }

    # EBServicosDelete
        public function EBServicosDelete(Request $request, $id)
        {
            tb_base_servicos::where('id', $id)->update(['deleted' => '1','alt_user_id' => auth()->user()->id]);
            
            return Redirect::to('EBServicos');  
        }
    # EBMateriaisDelete
        public function EBMateriaisDelete(Request $request, $id)
        {
            tb_base_materiais::where('id', $id)->update(['deleted' => '1', 'alt_user_id' => auth()->user()->id]);
            
            return Redirect::to('EBMateriais');   
        }
   
       
    # EBTrabalhosDelete
        public function EBTrabalhosDelete(Request $request, $id)
        {
            tb_base_trabalhos::where('id', $id)->update(['deleted' => '1','alt_user_id' => auth()->user()->id]);
            
            return Redirect::to('EBTrabalhos');
        }    
    # EBTrabalhosNovo
        public function EBTrabalhosNovo(Request $request)
        {
            #dd($request);
            $new = new tb_base_trabalhos;
            $new->trabalho = $request->trabalho;
            $new->alt_user_id = auth()->user()->id;
            $new->save();
            $id = $new->id;
            
        
            return Redirect::to('EBET/'.$id);
        }    

        public function BASEnovoTrabalho(Request $request)
        {
        
            $new = new tb_base_trabalhos;
            $new->trabalho = $request->trabalho;
            $new->alt_user_id = auth()->user()->id;
            $new->save();
        
        }
           
}
