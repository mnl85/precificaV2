<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PerfilController extends Controller
{
    # meuPerfil
        public function meuPerfil(){


        return view('perfil.meuPerfil');
        }
    # configPerfil
        public function configPerfil(){


        return view('perfil.configPerfil');
        }
}
