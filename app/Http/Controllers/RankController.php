<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RankController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function view_ranking(Request $request)
    {

        $userId = Auth::user()->id;
        $teamId = Auth::user()->TEAM_ID;
        $r = 1;

        $team = DB::table('t_team')->where('TEAM_ID', $teamId)->value('TEAM_NAME');

        $groups = DB::select("SELECT GRP_ID,GRP_NAME
            FROM t_group
            WHERE GRP_ID <= 6 
            ORDER BY GRP_NAME ASC");

        if (request('id')) {
            $id = $request->id;
            $grp_usr = $id;

            $all_rankigs = DB::select("SELECT t_team_sta.TEAM_STA_ID,t_team_sta.TEAM_DRAW,t_team_sta.TEAM_LOS,t_team_sta.TEAM_AVG,
            t_team_sta.TEAM_WIN,t_team_sta.TEAM_PTS,t_team.TEAM_ID,t_team.TEAM_POT,t_team.TEAM_NAME,t_group.GRP_ID,t_group.GRP_NAME,t_rank.RNK_ID,t_rank.RNK_NAME
            FROM t_team_sta INNER JOIN t_team ON t_team_sta.TEAM_ID = t_team.TEAM_ID
            INNER JOIN t_group ON t_team_sta.GRP_ID = t_group.GRP_ID
            INNER JOIN t_rank ON t_team_sta.RNK_ID = t_rank.RNK_ID
            WHERE t_team_sta.GRP_ID = (".$id.")  AND t_team_sta.USER_ID = ".$userId." ORDER BY TEAM_PTS DESC, TEAM_AVG DESC, TEAM_NAME ASC");

        } else {
            $grp = DB::select("SELECT GRP_ID
            FROM t_team_sta
            WHERE t_team_sta.TEAM_ID = ".$teamId."  AND t_team_sta.USER_ID = ".$userId." LIMIT 1");

            // $grp_usr = DB::table('t_team_sta')->where([['TEAM_ID', '=', $teamId], ['USER_ID', '=', $userId]])->first()->value('GRP_ID');

            $grp_usr = $grp[0]->GRP_ID;

            $all_rankigs = DB::select("SELECT t_team_sta.TEAM_STA_ID,t_team_sta.TEAM_DRAW,t_team_sta.TEAM_LOS,t_team_sta.TEAM_AVG,
            t_team_sta.TEAM_WIN,t_team_sta.TEAM_PTS,t_team.TEAM_ID,t_team.TEAM_POT,t_team.TEAM_NAME,t_group.GRP_ID,t_group.GRP_NAME,t_rank.RNK_ID,t_rank.RNK_NAME
            FROM t_team_sta INNER JOIN t_team ON t_team_sta.TEAM_ID = t_team.TEAM_ID
            INNER JOIN t_group ON t_team_sta.GRP_ID = t_group.GRP_ID
            INNER JOIN t_rank ON t_team_sta.RNK_ID = t_rank.RNK_ID
            WHERE t_team_sta.GRP_ID = (".$grp_usr.")  AND t_team_sta.USER_ID = ".$userId." ORDER BY TEAM_PTS DESC, TEAM_AVG DESC, TEAM_NAME ASC");
        }

        $decide = 0;
        foreach ($all_rankigs as $rank) {
            $decide += $rank->TEAM_PTS;
        }

        $collection = collect($all_rankigs);
        if ($decide == 0) {
            $sorted = $collection->sortBy('TEAM_POT');
        } else {
            $sorted = $collection->sortByDesc('TEAM_PTS');
        }
        $all_rankigs = $sorted->values()->all();

        /** @var User $user */
        $user = Auth::user();
        return view('rank.view_ranking',['groups'=>$groups,'all_rankigs'=>$all_rankigs,'r'=>$r, 'usr_grp'=>$grp_usr,'team'=> $team]);
    }
}
