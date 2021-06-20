<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TacticPos extends Model
{
     protected $fillable = [
    'TAC_ID',
    'TAC_POS_ID',
    'TAC_POS_NAME'
  ];


     public function tactic() {
        return $this->belongsTo(Tactic::class);
    }
}
