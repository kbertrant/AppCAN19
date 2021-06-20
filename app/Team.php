<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
     protected $fillable = [
    'TEAM_NAME',
    'TEAM_CODE',
    'TEAM_FLAG'
  ];

  
}
