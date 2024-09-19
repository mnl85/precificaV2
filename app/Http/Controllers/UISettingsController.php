<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class UISettingsController extends Controller
{
    public function saveSettings(Request $request)
    {
        Log::info('Recebido no saveSettings:', $request->all()); // Log dos dados recebidos

        $key = $request->input('key');
        $value = $request->input('value');

        Log::info('Salvando na sessão:', ['key' => $key, 'value' => $value]); // Log dos dados sendo salvos

        Session::put($key, $value);
        return response()->json(['status' => 'success']);
    }

    public function viewSessionData()
    {
        $sessionData = Session::all();
        return response()->json($sessionData);
    }
}
