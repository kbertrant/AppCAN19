<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rank extends Model
{
    public $table = "t_rank";

    protected $fillable = [
        'RNK_NAME'
    ];


    public function getRanking($id){

        $all_rankigs = DB::select("SELECT t_team_sta.TEAM_STA_ID,t_team_sta.TEAM_DRAW,t_team_sta.TEAM_LOS,
        t_team_sta.TEAM_WIN,t_team_sta.TEAM_PTS,t_team.TEAM_ID,t_team.TEAM_NAME,t_group.GRP_ID,t_group.GRP_NAME,t_rank.RNK_ID,t_rank.RNK_NAME
             FROM t_team_sta INNER JOIN t_team ON t_team_sta.TEAM_ID = t_team.TEAM_ID
            INNER JOIN t_group ON t_team_sta.GRP_ID = t_group.GRP_ID 
            INNER JOIN t_rank ON t_team_sta.RNK_ID = t_rank.RNK_ID 
             WHERE t_team_sta.GRP_ID =4");
        dd(all_rankigs);
        return $all_rankigs;

    }
}
