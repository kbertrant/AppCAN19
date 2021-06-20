<?php

namespace App\Http\Controllers;

use App\Utils\KeysFile\CountryName;
use App\Utils\EightFinalPhase;
use App\Utils\FinalPhase;
use App\Utils\QuarterFinalPhase;
use App\Utils\SemiFinalPhase;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use View;
use App\Utils\Utils;
use App\Http\Controllers\User;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
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


    public function view_group()
    {
        // get user ID
        $userId = Auth::user()->id;

        $groups = [[], [], [], [], [], []];

        for($i = 1; $i <= 4; $i++) {
            $teamsPots = DB::select('SELECT * FROM t_team WHERE TEAM_POT = ' . $i);
            $temp = [];
            foreach($teamsPots as $teamPot) {
                array_push($temp, $teamPot);
            }
            $temp2 = $temp;
            for ($j = 0; $j < count($temp); $j++) {
                $k = rand(0, count($temp2)-1);
                array_push($groups[$j], $temp2[$k]);
                $temp3 = array_udiff($temp2, $groups[$j], function ($teamA, $teamB) { return $teamA->TEAM_ID - $teamB->TEAM_ID; });
                $temp2 = [];
                foreach($temp3 as $tmp) {
                    array_push($temp2, $tmp);
                }
            }
        }

        $teamSta = DB::select("SELECT * FROM t_team_sta WHERE USER_ID = " . $userId);
        if (count($teamSta) == 0) {
            for($i = 0; $i < count($groups); $i++) {
                for($j = 0; $j < count($groups[$i]); $j++) {
                    DB::insert('INSERT INTO t_team_sta(TEAM_ID, USER_ID, RNK_ID, GRP_ID, TEAM_WIN, TEAM_LOS, TEAM_DRAW, TEAM_PTS, TEAM_SCO, TEAM_CON, TEAM_AVG, updated_at) '.
                        'VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', array($groups[$i][$j]->TEAM_ID, $userId, 1, ($i + 1), 0, 0, 0, 0, 0, 0, 0, date('Y-m-d H:i:s')));
                }
            }
        }

        $id_team = Auth::user()->TEAM_ID;
        //to get id of same group in array
        DB::select("SELECT t_team_sta.TEAM_ID
                 FROM t_team_sta
                 INNER JOIN t_group ON t_team_sta.GRP_ID = t_group.GRP_ID
                 WHERE t_team_sta.GRP_ID IN (SELECT GRP_ID
                 FROM t_team_sta
                 WHERE t_team_sta.TEAM_ID = (".$id_team.") AND t_team_sta.USER_ID = ".$userId.")");
                 //Utils::$array_group.add($id_team_group);
        //dd($id_team_group);
        /** @var User $user */
        $group_teams = DB::select("
            SELECT t_team_sta.TEAM_STA_ID,t_team_sta.TEAM_ID, t_team_sta.USER_ID,t_team_sta.GRP_ID,t_team_sta.TEAM_DRAW,t_team_sta.TEAM_LOS,
                    t_team_sta.TEAM_WIN,t_team_sta.TEAM_PTS,t_team.TEAM_ID,t_team.TEAM_NAME,t_group.GRP_ID,t_group.GRP_NAME,t_rank.RNK_ID,t_rank.RNK_NAME
            FROM t_team_sta
            INNER JOIN t_team ON t_team_sta.TEAM_ID = t_team.TEAM_ID
            INNER JOIN t_group ON t_team_sta.GRP_ID = t_group.GRP_ID
            INNER JOIN t_rank ON t_team_sta.RNK_ID = t_rank.RNK_ID
            WHERE t_team_sta.GRP_ID IN (SELECT GRP_ID FROM t_team_sta WHERE TEAM_ID = ".$id_team." AND t_team_sta.USER_ID = ".$userId.") AND t_team_sta.USER_ID = ".$userId." ORDER BY TEAM_POT ASC");

        // insert default tactic
        $bd_tac = DB::select('SELECT * FROM t_team_tac WHERE USER_ID = '.$userId.' ORDER BY USER_ID DESC LIMIT 1');
        if (count($bd_tac) > 0) {
            DB::table('t_team_tac')->where('USER_ID', $userId)->update(array('TAC_ID' => 1, 'TEAM_ID' => $id_team, 'updated_at' => date('Y-m-d H:i:s')));
        } else {
            DB::insert('INSERT INTO t_team_tac (TAC_ID, TEAM_ID, USER_ID, created_at, updated_at) '.
                'VALUES (?, ?, ?, ?, ?)', array(1, $id_team, $userId, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')));
        }

        //dd($group_teams);
        return view::make('team.view_group', ['group_teams' => $group_teams,'id_team'=>$id_team]);
    }

    public function team_sta()
    {
        $i = 1;
        $j = 1;
        $userId = Auth::user()->id;
        //$id = $request->id;
        $list_off_teams = DB::select("SELECT t_team_sta.TEAM_STA_ID,t_team_sta.TEAM_ID, t_team_sta.USER_ID,t_team_sta.TEAM_SCO,
                t_team.TEAM_ID,t_team.TEAM_NAME
        FROM t_team_sta
        INNER JOIN t_team ON t_team_sta.TEAM_ID = t_team.TEAM_ID
        WHERE t_team_sta.USER_ID = ".$userId." ORDER BY TEAM_SCO DESC LIMIT 5");

        $list_def_teams = DB::select("SELECT t_team_sta.TEAM_STA_ID,t_team_sta.TEAM_ID, t_team_sta.USER_ID,t_team_sta.TEAM_CON,
        t_team.TEAM_ID,t_team.TEAM_NAME
        FROM t_team_sta
        INNER JOIN t_team ON t_team_sta.TEAM_ID = t_team.TEAM_ID
        WHERE t_team_sta.USER_ID = ".$userId." ORDER BY TEAM_CON ASC LIMIT 5");


        return view('team.team_sta',['i'=>$i,'j'=>$j,'list_off_teams'=>$list_off_teams,'list_def_teams'=>$list_def_teams, 'countries'=>CountryName::getCountries()]);

    }

    public function list_team(Request $request)
    {
        //delete all match of the user
        // DB::table('t_match')->where('USER_ID', $userId)->delete();
        //init team_sta
        Utils::init_data();

        // this for id team request
        $id_team = $request->idTeam;
        $userId = Auth::user()->id;

        if ($request->method() == 'POST') {
            DB::table('users')->where('id', $userId)->update(array(
                'TEAM_ID'=>$id_team,'round'=>1));
        } else {
            $id_team = Auth::user()->TEAM_ID;
        }

        $list_teams = ['HAT ONE' => [], 'HAT TWO' => [], 'HAT THREE' => [], 'HAT FOUR' => []];

        $team = DB::table('t_team')->where('TEAM_ID', $id_team)->value('TEAM_NAME');
        $teams = DB::select('SELECT TEAM_NAME, TEAM_POT FROM t_team ORDER BY TEAM_POT ASC, TEAM_NAME ASC');

        for($i = 0; $i < count($teams); $i++) {
            if ($i < 6) {
                array_push($list_teams['HAT ONE'], $teams[$i]->TEAM_NAME);
            } elseif ($i >= 6 && $i < 12) {
                array_push($list_teams['HAT TWO'], $teams[$i]->TEAM_NAME);
            } elseif ($i >= 12 && $i < 18) {
                array_push($list_teams['HAT THREE'], $teams[$i]->TEAM_NAME);
            } elseif ($i >= 18 && $i < 24) {
                array_push($list_teams['HAT FOUR'], $teams[$i]->TEAM_NAME);
            }
        }

        return view('team.list_team',['list_teams' => $list_teams, 'team' => $team]);
    }

    public function eight_stage() {
        return view('team.eight_stage',['list_teams' => EightFinalPhase::teams_qualified()]);
    }

    public function eight_crossing() {
        $team = DB::table('t_team')->where('TEAM_ID', Auth::user()->TEAM_ID)->value('TEAM_NAME');
        return view('team.eight_crossing',['teamsCrossing' => EightFinalPhase::eight_crossing(),'team'=> $team]);
    }

    public function quarter_stage() {
        return view('team.quarter_stage',['list_teams' => QuarterFinalPhase::teams_qualified()]);
    }

    public function quarter_crossing() {
        $team = DB::table('t_team')->where('TEAM_ID', Auth::user()->TEAM_ID)->value('TEAM_NAME');
        return view('team.quarter_crossing',['teamsCrossing' => QuarterFinalPhase::quarter_crossing(),'team'=> $team]);
    }

    public function semi_stage() {
        return view('team.semi_stage',['list_teams' => SemiFinalPhase::teams_qualified()]);
    }

    public function semi_crossing() {
        $team = DB::table('t_team')->where('TEAM_ID', Auth::user()->TEAM_ID)->value('TEAM_NAME');
        return view('team.semi_crossing',['teamsCrossing' => SemiFinalPhase::semi_crossing(),'team'=> $team]);
    }

    public function final_stage() {
        return view('team.final_stage',['list_teams' => FinalPhase::teams_qualified()]);
    }

    public function final_crossing() {
        $team = DB::table('t_team')->where('TEAM_ID', Auth::user()->TEAM_ID)->value('TEAM_NAME');
        return view('team.final_crossing',['teamsCrossing' => FinalPhase::final_crossing(),'team'=> $team]);
    }
}
