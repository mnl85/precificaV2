<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\tb_empresa;

class MembrosController extends Controller
{
    ## empresas
        public function empresasCadastradas(){
            if(auth()->user()->empresa_id == null || auth()->user()->role_id != '1' ){

                return redirect::to('inicio'); 

            }else{
            $tb_empresa = tb_empresa::where('deleted','0')->orderBy('nome_empresa','ASC')->get();
            $tb_users = User::where('deleted','0')->orderBy('name','ASC')->get();
            #dd($tb_empresa);

            return view('admin.membros.empresasCadastradas', compact('tb_users','tb_empresa'));
            }
        }


    ## novaEmpresa
        public function novaEmpresa(Request $request){

            $new = new tb_empresa;
            $new->nome_empresa = $request->nome_empresa;
            $new->id_user_responsavel = $request->id_user_responsavel;
            $new->alt_user_id = auth()->user()->id;
            $new->save();
            $novoID = $new->id;

            User::where('id',$request->id_user_responsavel)->update(['empresa_id'=>$novoID, 'alt_user_id' => auth()->user()->id ]);

            return redirect::to('admin.membros.empresasCadastradas'); 
        }

    ## updateDadosEmpresa
        public function updateDadosEmpresa(Request $request, $id)
        {
            $data = $request->validate([
                'nome_empresa' => 'nullable|string|max:255',
                'id_user_responsavel' => 'nullable|integer|exists:users,id',
            ]);
            #dd($data);
            $get_old_user_responsavel = tb_empresa::where('id', $id)->first();
            $get_old_user_responsavel = $get_old_user_responsavel->id_user_responsavel;

            // Identificar o campo que está sendo atualizado
            $field = array_key_first($data);
            $value = $data[$field];

            $update = tb_empresa::where('id', $id)->update([$field => $value, 'alt_user_id' => auth()->user()->id]);
        
            if($request->id_user_responsavel==''){
                User::where('id',$get_old_user_responsavel)->update(['empresa_id'=> null, 'alt_user_id' => auth()->user()->id]);
            }
            else{
                User::where('id',$request->id_user_responsavel)->update(['empresa_id'=> $id, 'alt_user_id' => auth()->user()->id]);
            }
            
            if ($update) {
                return response()->json(['success' => true, 'message' => 'Dados atualizados com sucesso']);
            } else {
                return response()->json(['success' => false, 'message' => 'Falha ao atualizar os dados']);
            }
        }
    ## adminApagarEmpresa
        public function adminApagarEmpresa($id){
            #dd($id);
                tb_empresa::where('id',$id)->update(['deleted'=>1, 'alt_user_id' => auth()->user()->id]);
                User::where('empresa_id',$id)->update(['empresa_id'=>'', 'alt_user_id' => auth()->user()->id]);

            return redirect::to('empresasCadastradas');
        }
    ## usuarios
        public function usuariosCadastrados(){
            if( auth()->user()->role_id != '1' && auth()->user()->id != '1' ){

                return redirect::to('inicio'); 

            }else{

            $tb_users = User::where('deleted', '0')->get();
            #dd($tb_users);
            $tb_empresas = tb_empresa::where('deleted', '0')->orderBy('nome_empresa','ASC')->get();

            // Cria um array associativo de id para nome da empresa
            $empresasMap = $tb_empresas->pluck('nome_empresa', 'id');
        
            return view('admin.membros.usuariosCadastrados', compact('tb_users', 'empresasMap','tb_empresas'));
            }
        }
    ## adminNovoUsuario
        public function adminNovoUsuario(Request $request){
            $new = new user;
            $new->name = $request->nome;
            $new->email = $request->email;
            $new->password = $request->password;
            $new->empresa_id = $request->empresa_id;

            $new->cpf = $request->cpf;
            $new->fone = $request->fone;
            $new->whatsapp = $request->whatsapp;
            $new->role = $request->role;
            
            $new->alt_user_id = auth()->user()->id;
            $new->save();

            return redirect::to('admin.membros.usuariosCadastrados'); 
        }
    ## updateUsuarios
        public function updateUsuarios(Request $request) {
            // Validar os dados de entrada
            $validatedData = $request->validate([
                'userId' => 'required|exists:users,id',
                'fieldName' => 'required|string|in:name,email,empresa_id,fone,cpf,whatsapp,role_id', // Defina os campos permitidos
                'newValue' => 'required|string'
            ]);
        
            try {
                // Encontrar o usuário pelo ID
                $user = User::where('id',$validatedData['userId'])->first();

                $old_user = $user->id;
                $old_empresa = $user->empresa_id;
        
                // Obter o nome do campo e o novo valor
                $fieldName = $validatedData['fieldName'];
                $newValue = $validatedData['newValue'];
        
                // Atualizar o campo especificado com o novo valor
                // Verificar se o campo é 'password' para aplicar a hash
                if ($fieldName === 'password') {
                    $user->$fieldName = bcrypt($newValue);
                } else {
                    $user->$fieldName = $newValue;
                }
                $user->save();

                // if ($fieldName === 'empresa_id') {
                //     tb_empresa::where('id',$old_empresa)->update(['id_user_responsavel' => $user->id]);
                // }
        
                // Retornar uma resposta de sucesso
                return response()->json(['success' => true, 'message' => 'Dados atualizados com sucesso']);
            } catch (\Exception $e) {
                // Em caso de falha, retornar uma resposta de erro
                return response()->json(['success' => false, 'message' => 'Falha ao atualizar os dados', 'error' => $e->getMessage()], 500);
            }
        }

    ## adminApagarUsuario
        public function adminApagarUsuario($id){
            #dd($id);
                User::where('id',$id)->update(['deleted'=>1, 'alt_user_id' => auth()->user()->id]);
                tb_empresa::where('id_user_responsavel',$id)->update(['id_user_responsavel'=>'', 'alt_user_id' => auth()->user()->id]);
            return redirect::to('usuarios');
        }


}
 