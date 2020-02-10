<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Code extends Model
{

    public $fillable = ["secretcode_id", "value"];
    public $timestamps = FALSE;

    /**
     * Get the secretcode of the code.
     */
    public function secretcode()
    {
        return $this->belongsTo('App\Secretcode')->withDefault();
    }
}
