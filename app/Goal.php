<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    public $table = "t_goal";

   protected $fillable = [
    'MATCH_ID',
    'PLY_ID',
    'T_P_PLY_ID'
  ];


  public function match() {
        return $this->belongsTo(Match::class);
    }

    public function player() {
        return $this->belongsTo(Player::class);
    }
}
