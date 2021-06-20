<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeamSta extends Model
{
    public $table = "t_team_sta";

    protected $fillable = [
        'USER_ID',
        'TEAM_ID',
        'GRP_ID',
        'RNK_ID',
        'PLY_NAME',
        'TEAM_WIN',
        'TEAM_LOS',
        'TEAM_DRAW',
        'TEAM_SCO',
        'TEAM_CON',
        'TEAM_AVG'
    ];

    public function team() {
        return $this->belongsTo(Team::class);
    }

    public function group() {
        return $this->belongsTo(Group::class);
    }

    public function rank() {
        return $this->belongsTo(Rank::class);
    }
}
