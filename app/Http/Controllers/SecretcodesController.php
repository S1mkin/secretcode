<?php

namespace App\Http\Controllers;

use App\Secretcode;
use App\Code;
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
            }
            elseif ( $char == "}" && $start_maybe_code && $this->is_code($maybe_code) ) {
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
    public function get()
    {
        $secretcodes = Secretcode::all();
        $secretcodes->load("codes");
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
        ]);

        $codes = $this->find_code($data['text']);

        $secretcode = Secretcode::create([
            'user_id' => 1,
            'name' => $data['name'],
            'text' => $data['text']
        ]);

        foreach ($codes as $code) {
            $secretcode->codes()->create([
                'value' => $code
            ]);
        }

        return $secretcode;
    }


    /**
    * Function delete secretcode by id
    * @return string
    */
    public function delete(Request $request)
    {       
        $data = $request->validate([
            'id' => 'required',
        ]);
        
        //Secretcode::findOrFail($data['id'])->delete();
        Secretcode::destroy($data['id']);

        return "Secretcode ID " . $data['id'] . " has been deleted";
    }

}
