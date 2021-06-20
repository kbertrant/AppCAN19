<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
     protected $fillable = [
    'TEAM_ID',
    'LINE_ID',
    'PLY_NAME',
    'PLY_NBR',
    'PLY_GKP_VAL',
    'PLY_DEF_VAL',
    'PLY_MID_VAL',
    'PLY_ATT_VAL',
    'PLY_VAL'
  ];

    public function team() {
        return $this->belongsTo(Team::class);
    }

     public function line() {
        return $this->belongsTo(PlayerLine::class);
    }
}
