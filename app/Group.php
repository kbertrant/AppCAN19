<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
  protected $fillable = [
    'GRP_NAME',
    'GRP_STADIUM1',
    'GRP_STADIUM2',
    'GRP_STADIUM3',
    'GRP_STADIUM4'
  ];
  
}
