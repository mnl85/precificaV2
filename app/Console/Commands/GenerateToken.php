<?php

namespace App\Console\Commands;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;
use App\Models\val_token; // Certifique-se de importar o modelo adequado

class GenerateToken extends Command
{
   

    protected $signature = 'generate:token';
    protected $description = 'Generate a new token and store in val_token table';

    public function handle()
    {
        Log::info('Rodando handle');
        $token = $this->generateToken();
        $valToken = val_token::create(['token' => $token]);
        // Recupera o ID do token recém-criado
        $tokenId = $valToken->id;
        $tokenmvinte = $tokenId-168; // Equivalente a uma semana de registros
        $this->info('Token generated successfully: ' . $token);
        Log::info('handle rodou com sucesso');
        val_token::where('id_val_token',$tokenmvinte)->delete();
    }

    private function generateToken($length = 5)
    {
        Log::info('Rodando a funcao generatetoken');
        $characters = '123456789ABCDEFGHIJKLMNPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $token = '';
        
        for ($i = 0; $i < $length; $i++) {
            $token .= $characters[rand(0, $charactersLength - 1)];
        }
 
        Log::info('Funcao generatetoken rodada');
        return $token;
    }
}
