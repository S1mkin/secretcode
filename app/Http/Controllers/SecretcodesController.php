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
            'name' => 'required',
            'code' => 'required',
        ]);

        $secretcode = new Secretcode;
        $secretcode->user_id = 1;
        $secretcode->name = $data['name'];
        $secretcode->code = $data['code'];
        $secretcode->save();
/*
        Secretcode::create([
            'user_id' => 1,
            'name' => $data['name'],
           // 'code' => $data['code']
        ]);*/
       
        return "Add success: " . $data['name'];
        /*
        return new SecretcodeResource(Secretcode::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]));
        */
    }

}
