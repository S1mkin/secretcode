<?php

namespace App\Http\Controllers;

use App\Secretcode;
use Illuminate\Http\Request;

class SecretcodesController extends Controller
{
    public function get()
    {
        return "4444";

        //$secretcodes = DB::table('secretcodes')->get();
        // $secretcodes = App\Secretcode::all();
        // return view('page.task.index', compact('tasks'));
        // return $secretcodes;
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
