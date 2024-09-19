<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\val_token;
use Carbon\Carbon;

class LoginsController extends Controller
{
    # loginTokens
        public function loginTokens(){

            $latestToken = val_token::latest()->first(); // Busca o último registro
    
            if ($latestToken) {
                $tokenValue = $latestToken->token; // Obtém o valor do token
                $createdAt = $latestToken->created_at; // Obtém a data de criação
            } else {
                $tokenValue = null; // Caso não haja registros na tabela
                $createdAt = null;
            }
            $now = Carbon::now();
            $listaTokens = val_token::orderBy('id_val_token','DESC')->get();
            #dd($listaTokens);

            return view('admin.logins.loginTokens', compact('listaTokens','now','tokenValue', 'createdAt'));
        }
    
    # relatorioLogins
        public function relatorioLogins(){
 

            return view('admin.logins.relatorioLogins');
        }

        ## adminRelataLogins
    public function adminRelataLogins()
    {
        $tb_logins = DB::table('user_log')
                ->leftJoin('users', 'user_log.user_id', '=', 'users.id')
                ->select('user_log.*', 'users.name')
                ->orderBy('user_log.id_user_log', 'DESC')
                ->get();

                #dd($tb_logins);
    
        return view('admin.adminRelataLogins', compact('tb_logins'));
    }

}




