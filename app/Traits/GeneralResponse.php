<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Auth;

trait GeneralResponse 
{
    public function genResponse($status_code = 200, $items = null, $custom_message = null, $action = null)
    {
        $default_messages = [
            200 => 'OK',
            201 => 'Elemento creado con éxito',
            208 => 'Ese elemento ya existe',
            400 => 'Datos invalidos',
            401 => 'No autorizado',
            403 => 'Prohibido',
            404 => 'Elemento no encontrado',
            406 => 'Invalid id payload',
            500 => 'Error interno',
        ];
        if($custom_message){
            $message = $custom_message;
        }else{
            $message = $default_messages[$status_code];
        }
        $res = [
            'message' => $message,
        ];
        if( $action !== NULL ){
            $res['action'] = $action;
        }
        if( $items ){
            $res['items'] = $items;
        }
        return response()->json($res,$status_code);
    }

    public function checkPermissions($perms)
    {
        if( !Auth::user()->hasPermissions($perms) ){
            return false;
        }
        return true;
    }
}