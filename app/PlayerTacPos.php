<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlayerTacPos extends Model
{
    public $table = "t_player_tac_pos";

    protected $fillable = [
        'PLY_ID',
        'TAC_ID_POS'
    ];


    public function player() {
        return $this->belongsTo(Player::class);
    }
}
