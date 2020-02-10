<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Secretcode extends Model
{

    public $fillable = ["user_id", "name", "text"];

    
    /**
     * Get the codes.
     */
    public function codes(){
        return $this->hasMany('App\Code');
    }


    /**
     * Declare event handlers
     */
    public static function boot() {
        parent::boot();

        // Before delete() method clear codes
        static::deleting(function($secretcode) {
            $secretcode->codes()->delete();
        });
    }

}
