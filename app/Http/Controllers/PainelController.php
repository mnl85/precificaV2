<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\tb_empresa;
use App\Models\tb_producao;
use Illuminate\Http\Request;
use Redirect;

class PainelController extends Controller
{
    
    # painelRelatorios
        public function painelRelatorios(){


            return view('painelControle.painelRelatorios');
        }


    # painelAjustes
        public function painelAjustes(){
            if(auth()->user()->empresa_id == null || auth()->user()->role_id == '3' ){

                return redirect::to('painelAjuda'); 

            }else{
            $tb_users = User::where('empresa_id',auth()->user()->empresa_id )->where('deleted', '0')->get();
            $tb_empresas = tb_empresa::all();
        
            // Cria um array associativo de id para nome da empresa
            $empresaDados = $tb_empresas->where('id',auth()->user()->empresa_id)->first();
        #dd($empresaDados->id_tb_empresa);
            return view('painelControle.painelAjustes', compact('tb_users', 'empresaDados'));
        }}


    # painelUsuarios
        public function painelUsuarios(){
            if(auth()->user()->empresa_id == null || auth()->user()->role_id == '3' ){

                return redirect::to('painelAjuda'); 

            }else{
            $tb_users = User::where('empresa_id',auth()->user()->empresa_id )->where('deleted', '0')->get();
            $tb_empresas = tb_empresa::all();
        
            // Cria um array associativo de id para nome da empresa
            $empresaDados = $tb_empresas->where('id',auth()->user()->empresa_id)->first();
        #dd($empresaDados->id_tb_empresa);
            return view('painelControle.painelUsuarios', compact('tb_users', 'empresaDados'));
        }}


    # painelAjuda
        public function painelAjuda(){

            return view('painelControle.painelAjuda');
        }
}
