<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Cliente;
use App\Models\Diario;

class ClienteController extends Controller
{
    public function cadastrar($user_id){
        $cliente = Cliente::where('user_id',$user_id)->first();

        if( !isset($cliente) ){
             $cliente = new Cliente;
             $cliente->user_id = $user_id;
             $cliente->dataCadastro = Carbon::now();
             $cliente->isActive = true;

             $cliente->save();

             return 1;  
         }else{
            $cliente->isActive = true;
            $cliente->dataCadastro = Carbon::now();

            $cliente->save();

            return 2;
         }

    }

    public function descadastrar($user_id){
        $cliente = Cliente::where('user_id',$user_id)->first();

        if( !isset($cliente) ){
             $cliente = new Cliente;
             $cliente->user_id = $user_id;
             $cliente->dataCadastro = Carbon::now();
             $cliente->isActive = false;

             $cliente->save();

             return 1;    
         }else{
            $cliente->isActive = false;
            $cliente->save();

            return 1;
         }
        
         return 0;
         
    }

    public function deletar($user_id){
        $cliente = Cliente::where('user_id',$user_id)->first()->delete();
        $diario = Diario::where('chat_id',$user_id)->delete();
        return 1;
    }
}
