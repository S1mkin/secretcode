<?php

namespace App\Http\Controllers;

use App\Secretcode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App;

class SecretcodesController extends Controller
{
    public function get()
    {
        return App\Secretcode::all();
    }

    public function add(Request $request)
    {
        
        $data = $request->validate([
            'name' => 'required|unique:secretcodes|min:3|max:50',
            'code' => 'required|min:8',
        ]);

        $secretcode = new Secretcode;
        $secretcode->user_id = 1;
        $secretcode->name = $data['name'];
        $secretcode->code = $data['code'];
        $secretcode->save();

        return "Secretcode has been added";
    }

}
