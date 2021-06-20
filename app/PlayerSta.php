<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlayerSta extends Model
{
    public $table = "t_player_sta";

  protected $fillable = [
    'PLY_ID',
    'PLY_TIT',
    'PLY_SUB',
    'PLY_SHP',
    'PLY_INJ',
    'PLY_CRD',
    'PLY_DSQ',
    'PLY_SCO',
    'PLY_ASS'
  ];

     public function player() {
        return $this->belongsTo(Player::class);
    }
}
