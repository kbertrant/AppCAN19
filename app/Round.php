<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    protected $fillable = [
        'RND_ID',
        'RND_NAME',
        'TEAM_HOME',
        'TEAM_AWAY',
        'RND_PLAY',
        
      ];
}
