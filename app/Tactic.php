<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tactic extends Model
{
       protected $fillable = [
    'TAC_NAME',
    'TAC_CODE',
    'TAC_BON',
    'TAC_OPP',
    'TAC_APT',  
  ];
}
