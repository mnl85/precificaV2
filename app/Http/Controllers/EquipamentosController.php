<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tb_equipamentos;
use Redirect;

class EquipamentosController extends Controller
{
## meusEquipamentos
    public function meusEquipamentos()
    {
        $empresa_id = auth()->user()->empresa_id; // ID da empresa

        if(auth()->user()->empresa_id == null){

            return view('inicio'); 

        }else{
        $empresa_id = auth()->user()->empresa_id;
        $tb_equipamentos = tb_equipamentos::where('deleted', '0')->where('empresa_id',$empresa_id )->get();

        return view('meusDados.meusEquipamentos', compact('tb_equipamentos'));
    }}
## novoEquipamento
    public function novoEquipamento(Request $request){

        $newValor = str_replace('.', '', $request->valor);
        $newValor = str_replace(',', '.', $newValor);
        $newValor = str_replace('R$', '', $newValor);
        $newValor = str_replace(' ', '', $newValor);

        
        $newVida_util_horas = str_replace('.', '', $request->vida_util_horas);
        $newVida_util_horas = str_replace(',', '.', $newVida_util_horas);
        $newVida_util_horas = str_replace('R$', '', $newVida_util_horas);
        $newVida_util_horas = str_replace(' ', '', $newVida_util_horas);



        $new = new tb_equipamentos;
        $new->nome_equipamento = $request->nome_equipamento;
        $new->marca = $request->marca;
        $new->modelo = $request->modelo;
        $new->valor = $newValor;
        $new->vida_util_horas = $newVida_util_horas;
        $new->empresa_id = auth()->user()->empresa_id;
        $new->alt_user_id = auth()->user()->id;
        $new->save();

        return redirect::to('meusAjustes'); 
    }

## updateEquipamento
public function updateEquipamento(Request $request) {
    $equipamento = tb_equipamentos::where('id',$request->id)->first();

    try {
        $fieldName = $request['fieldName'];
        $newValue = $request['newValue'];

        // Aplicar str_replace apenas nos campos 'valor' e 'tempo'
        if (in_array($fieldName, ['valor', 'vida_util_horas'])) {
            $newValue = str_replace('.', '', $newValue);
            $newValue = str_replace(',', '.', $newValue);
            $newValue = str_replace('R$', '', $newValue);
            $newValue = str_replace(' ', '', $newValue);
        }

        $equipamento->$fieldName = $newValue;
        $equipamento->alt_user_id = auth()->user()->id;
        $equipamento->save();

        // Retornar uma resposta de sucesso
        return response()->json(['success' => true, 'message' => 'Dados atualizados com sucesso']);
    } catch (\Exception $e) {
        // Em caso de falha, retornar uma resposta de erro
        return response()->json(['success' => false, 'message' => 'Falha ao atualizar os dados', 'error' => $e->getMessage()], 500);
    }
}


## deleteEquipamento
    public function apagarEquipamento($id){

        tb_equipamentos::where('id', $id)->update(['deleted' => '1', 'alt_user_id' => auth()->user()->id]);

        return Redirect::to('meusAjustes');  
    }

}
