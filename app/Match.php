<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    public $table = "t_match";

     protected $fillable = [
    'MATCH_T1_TAC',
    'MATCH_T2_TAC',
    'MATCH_T1',
    'MATCH_T2',
    'MATCH_GRP',
    'MATCH_CODE',
    'MATCH_T1_VAL',
    'MATCH_T1_APT',
    'MATCH_T1_BON',
    'MATCH_T1_SCO',
    'MATCH_T1_ATT_BON', 
    'MATCH_T1_DEF_BON',
    'MATCH_T1_MID_BON',
    'MATCH_T2_VAL',
    'MATCH_T2_APT',
    'MATCH_T2_BON',
    'MATCH_T2_SCO',
    'MATCH_T2_ATT_BON', 
    'MATCH_T2_DEF_BON',
    'MATCH_T2_MID_BON',
    'MATCH_SCORE',
    'MATCH_WINNER'
  ];

   public function tactic() {
        return $this->belongsTo(Tactic::class);
    }

    public function team() {
        return $this->belongsTo(Team::class);
    }

     public function group() {
        return $this->belongsTo(Group::class);
    }

}
