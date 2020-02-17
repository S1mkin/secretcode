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
    * Function verify user by api_token.
    * @return object
    */
    private function get_user_by_api_token($api_token) {
        // search user by api_token
        $user = User::where('api_token', $api_token)->first();

        // If user not found 
        if (!$user) { 
            // return Exception
            abort(403, 'Unauthorized action: api_token not found'); 
        } else {
            // else return object: user
            return $user;
        }
    }


    /**
    * Function return all secretcodes
    * @return array
    */
    public function get(Request $request)
    {
        // validate request data
        $data = $request->validate([
            'api_token' => 'required|min:8',
        ]);
        
        // get user by api_token
        $user = $this->get_user_by_api_token($data['api_token']);
        
        // get all secretcodes by user_id
        $secretcodes = Secretcode::where('user_id', $user->id)->get()->load("codes");
        
        // return object: secretcode + codes
        return $secretcodes;
    }


    /**
    * Function return secretcodes by condition
    * @return array
    */
    public function filter(Request $request)
    {   
        // validate request data
        $data = $request->validate([
            'condition' => 'required',
            'code' => 'required',
            'api_token' => 'required|min:8',
        ]);
        
        // get user by api_token
        $user = $this->get_user_by_api_token($data['api_token']);
        
        // if bad condition, then set =
        if (!in_array($data['condition'], ['>', '<', '='])) {
            $data['condition'] == '=';
        }

        // search secretcodes by condition
        $secretcodes = Secretcode::where('user_id', $user->id)->whereHas('codes', function($q) use ($data) {
            $q->where('value', $data['condition'], $data['code']);
        })->get()->load("codes");

        // return object: secretcode + codes
        return $secretcodes;
    }


    /**
    * Function add new secretcode and return object
    * @return object
    */
    public function add(Request $request)
    {
        // validate request data
        $data = $request->validate([
            'name' => 'required|unique:secretcodes|min:3|max:50',
            'text' => 'required|min:8',
            'api_token' => 'required|min:8',
        ]);

        // get user by api_token
        $user = $this->get_user_by_api_token($data['api_token']);

        // search codes into text
        $codes = $this->find_code($data['text']);
        
        // create new secretcode
        $secretcode = Secretcode::create([
            'user_id' => $user->id,
            'name' => $data['name'],
            'text' => $data['text']
        ]);
        
        // add codes to secretcode
        foreach ($codes as $code) {
            $secretcode->codes()->create([
                'value' => $code
            ]);
        }

        // return object: secretcode + codes
        return $secretcode->load('codes');
    }


    /**
    * Function delete secretcode by id
    * @return string
    */
    public function delete(Request $request)
    {   
        // validate request data
        $data = $request->validate([
            'id' => 'required',
            'api_token' => 'required|min:8',
        ]);
        
        // get user by api_token
        $user = $this->get_user_by_api_token($data['api_token']);
        
        // delete Secretcode by id && user_id
        Secretcode::where('user_id', $user->id)->findOrFail($data['id'])->delete();
        
        // return result
        return "Secretcode ID " . $data['id'] . " has been deleted";
    }

}
