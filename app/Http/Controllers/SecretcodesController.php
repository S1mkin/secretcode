<?php

namespace App\Http\Controllers;

use App\Secretcode;
use App\Code;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class SecretcodesController extends Controller
{

    /**
    * Function checks whether a variable is of type secret code or not.
    * @return bool
    */
    private function is_code($string) {
        return (in_array($string[0], ["+", "-"])) ? ctype_digit(substr($string, 1)) : ctype_digit($string);
    }



    /**
    * Function retrieves secret codes.
    * @return array
    */
    private function find_code($string) {
        $output = [];
        $string_length = strlen($string);
        $start_maybe_code = false;
        $maybe_code = "";
        for( $i = 0; $i <= $string_length; $i++ ) {
            $char = substr( $string, $i, 1 );
            if ( $char == "{" ) {
                $start_maybe_code = true;
                $maybe_code = "";
            } elseif ( $char == "}" && $start_maybe_code && !empty($maybe_code) && $this->is_code($maybe_code) ) {
                $start_maybe_code = false;
                $output[] = ($maybe_code[0] == "+") ? substr($maybe_code, 1) : $maybe_code;
            } elseif ( $start_maybe_code ) {
                $maybe_code .= $char;
            }
        }
        return $output;
    }


    /**
    * Function return all secretcodes
    * @return array
    */
    public function get(Request $request)
    {

        $data = $request->validate([
            'api_token' => 'required|min:8',
        ]);

        $user = User::where('api_token', $data['api_token'])->first();

        if (!$user) { abort(403, 'Unauthorized action: api_token not found'); }

        $secretcodes = Secretcode::where('user_id', $user->id)->get()->load("codes");

        return $secretcodes;
    }

    /**
    * Function return secretcodes by condition
    * @return array
    */
    public function filter(Request $request)
    {
        $data = $request->validate([
            'condition' => 'required',
            'code' => 'required',
            'api_token' => 'required|min:8',
        ]);

        $user = User::where('api_token', $data['api_token'])->first();

        if (!$user) { abort(403, 'Unauthorized action: api_token not found'); }

        if (!in_array($data['condition'], ['>', '<', '='])) {
            $data['condition'] == '=';
        }

        $secretcodes = Secretcode::where('user_id', $user->id)->whereHas('codes', function($q) use ($data) {
            $q->where('value', $data['condition'], $data['code']);
        })->get()->load("codes");

        return $secretcodes;
    }

    /**
    * Function add new secretcode and return object
    * @return object
    */
    public function add(Request $request)
    {

        $data = $request->validate([
            'name' => 'required|unique:secretcodes|min:3|max:50',
            'text' => 'required|min:8',
            'api_token' => 'required|min:8',
        ]);

        $user = User::where('api_token', $data['api_token'])->first();

        if (!$user) { abort(403, 'Unauthorized action: api_token not found'); }

        $codes = $this->find_code($data['text']);

        $secretcode = Secretcode::create([
            'user_id' => $user->id,
            'name' => $data['name'],
            'text' => $data['text']
        ]);

        foreach ($codes as $code) {
            $secretcode->codes()->create([
                'value' => $code
            ]);
        }

        return $secretcode->load('codes');
    }


    /**
    * Function delete secretcode by id
    * @return string
    */
    public function delete(Request $request)
    {       
        $data = $request->validate([
            'id' => 'required',
            'api_token' => 'required|min:8',
        ]);
        
        
        $user = User::where('api_token', $data['api_token'])->first();

        if (!$user) { abort(403, 'Unauthorized action: api_token not found'); }

        //Secretcode::findOrFail($data['id'])->delete();

        //Secretcode::destroy($data['id']);
        Secretcode::where('user_id', $user->id)->findOrFail($data['id'])->delete();

        return "Secretcode ID " . $data['id'] . " has been deleted";
    }

}
