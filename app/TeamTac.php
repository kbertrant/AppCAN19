<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeamTac extends Model
{
    public $table = "t_team_tac";

    protected $fillable = [
        'TAC_ID',
        'TEAM_ID',
        'USER_ID',
        'created_at',
        'updated_at'
    ];

    public function tactic() {
        return $this->belongsTo(Tactic::class);
    }

    public function team() {
        return $this->belongsTo(Team::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
