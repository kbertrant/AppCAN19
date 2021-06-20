<?php

namespace App\Http\Controllers;

use App\Utils\KeysFile\CountryName;
use App\Constantes\Consto;
use App\Utils\Utils;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use View;
use App\Http\Controllers\User;
use Illuminate\Support\Facades\Auth;


class PlayerController extends Controller {

	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    public function stats_player() {
        $i = 1;
        $j = 1;
        $k = 1;
        $userId = Auth::user()->id;
        //list of top scorers
        $list_all_scorers = DB::select('SELECT DISTINCT PLY_ID FROM t_goal WHERE USER_ID = '.$userId);
        $plyIds = '0';
        foreach($list_all_scorers as $ply_score){
            $plyIds .= ', '.$ply_score->PLY_ID;
        }
        /*$query = 'SELECT count(t_goal.PLY_ID) AS GOALS,t_goal.PLY_ID,t_player.LINE_ID,LINE_CODE,TEAM_NAME,PLY_NAME
        FROM t_goal
        INNER JOIN t_player ON t_player.PLY_ID = t_goal.PLY_ID
        INNER JOIN t_player_line ON t_player_line.LINE_ID = t_player.LINE_ID
        INNER JOIN t_team ON t_team.TEAM_ID = t_player.TEAM_ID
        INNER JOIN t_team_sta ON t_team.TEAM_ID = t_team_sta.TEAM_ID
        WHERE t_goal.PLY_ID IN ('.$plyIds.') AND t_team_sta.USER_ID = '.$userId.' GROUP BY PLY_ID ORDER BY GOALS DESC,PLY_NAME ASC LIMIT 5 ';*/

        $query = 'SELECT COUNT(t_player.PLY_ID) as GOALS, t_player.*, t_player_line.LINE_CODE, t_team.TEAM_NAME FROM t_player
        INNER JOIN t_goal ON t_player.PLY_ID = t_goal.PLY_ID
        INNER JOIN t_player_line ON t_player_line.LINE_ID = t_player.LINE_ID
        INNER JOIN t_team ON t_team.TEAM_ID = t_player.TEAM_ID
        INNER JOIN t_team_sta ON t_player.TEAM_ID = t_team_sta.TEAM_ID
        WHERE t_goal.PLY_ID IN ('.$plyIds.') AND t_team_sta.USER_ID = '.$userId.'
        GROUP BY t_player.PLY_ID ORDER BY GOALS DESC,PLY_NAME ASC LIMIT 5 ';

        // echo "<script>console.debug( \"PHP DEBUG: ".print_r($query)."\" );</script>";
        $list_best_scorers = DB::select($query);
        //list of top assists

        $list_all_assists = DB::select('SELECT DISTINCT T_P_PLY_ID FROM t_goal WHERE USER_ID = '.$userId);
        $ply_Ids = '0';
        foreach($list_all_assists as $ply_assist){
            $ply_Ids .= ', '.$ply_assist->T_P_PLY_ID;
        }

        $list_best_assists = DB::select('SELECT COUNT(t_player.PLY_ID) as ASSISTS, t_player.*, t_player_line.LINE_CODE, t_team.TEAM_NAME FROM t_player
        INNER JOIN t_goal ON t_player.PLY_ID = t_goal.T_P_PLY_ID
        INNER JOIN t_player_line ON t_player_line.LINE_ID = t_player.LINE_ID
        INNER JOIN t_team ON t_team.TEAM_ID = t_player.TEAM_ID
        INNER JOIN t_team_sta ON t_player.TEAM_ID = t_team_sta.TEAM_ID
        WHERE t_goal.T_P_PLY_ID IN ('.$ply_Ids.') AND t_team_sta.USER_ID = '.$userId.'
        GROUP BY t_player.PLY_ID ORDER BY ASSISTS DESC,PLY_NAME ASC LIMIT 5 ');
        //$list_best_assists[] = $nbr_asist;
        //$list_best[] = $line;

        //list of top CARDS

        $list_all_cards = DB::select('SELECT DISTINCT PLY_ID FROM t_card WHERE USER_ID = '.$userId);
        $ply_Id = '0';
        foreach($list_all_cards as $ply_card){
            $ply_Id .= ', '.$ply_card->PLY_ID;
        }

            $list_best_cardeds = DB::select('SELECT count(t_card.PLY_ID) AS CARDS,t_card.PLY_ID,t_player.LINE_ID,LINE_CODE,TEAM_NAME,PLY_NAME
            FROM t_card
            INNER JOIN t_player ON t_player.PLY_ID = t_card.PLY_ID
            INNER JOIN t_player_line ON t_player_line.LINE_ID = t_player.LINE_ID
            INNER JOIN t_team ON t_team.TEAM_ID = t_player.TEAM_ID
            INNER JOIN t_team_sta ON t_team.TEAM_ID = t_team_sta.TEAM_ID
            WHERE t_card.PLY_ID IN ('.$ply_Id.') AND t_team_sta.USER_ID = '.$userId.' GROUP BY PLY_ID ORDER BY CARDS DESC,PLY_NAME ASC LIMIT 5');
            //$list_best_cardeds[] = $nbr_card;
            //$list_best[] = $line;

        //dd($list_best_assists);
        $list_teams = DB::select('SELECT TEAM_ID,TEAM_NAME FROM t_team ORDER BY TEAM_NAME ASC');
        /** @var User $user */
        $user = Auth::user();
        return view('player.stats_player',['k'=>$k,'i'=>$i,'j'=>$j,'list_teams' => $list_teams,'list_best_scorers' => $list_best_scorers,
        'list_best_assists' => $list_best_assists,'list_best_cardeds' => $list_best_cardeds]);
    }

    public function player_sta(Request $request) {
        $id = $request->id;
        $userId = Auth::user()->id;

        $pl_sta = DB::select("SELECT t_player.*,t_player_sta.*,t_player_line.*,t_team.*
                            FROM t_player
                            INNER JOIN t_player_sta ON t_player_sta.PLY_ID = t_player.PLY_ID AND t_player_sta.USER_ID = ".$userId."
                            INNER JOIN t_player_line ON t_player_line.LINE_ID = t_player.LINE_ID
                            INNER JOIN t_team ON t_team.TEAM_ID = t_player.TEAM_ID
                            WHERE PLY_ID_STA =(".$id.")");

        return view('player.player_sta',['pl_sta'=>$pl_sta, 'countries'=>CountryName::getCountries()]);
    }

    public function list_player(Request $request) {
        $p = 1;
        $userId = Auth::user()->id;
        $idTeam = Auth::user()->TEAM_ID;

        $tac_pos = DB::select("SELECT * FROM t_tactic_pos
                                     JOIN t_team_tac
                                     ON t_tactic_pos.TAC_ID = t_team_tac.TAC_ID AND t_team_tac.USER_ID = ".$userId);

        $team_names = DB::select("SELECT TEAM_NAME
                                FROM t_team
                                WHERE TEAM_ID = ".$idTeam);

        $list_players = DB::select("SELECT *
                                    FROM t_player
                                    LEFT JOIN t_player_line ON t_player_line.LINE_ID = t_player.LINE_ID
                                    LEFT JOIN (SELECT TAC_ID_POS, USER_ID, PLY_ID AS PL_ID FROM t_player_tac_pos) AS plyTacPos ON plyTacPos.PL_ID = t_player.PLY_ID AND plyTacPos.USER_ID = ".$userId."
                                    WHERE t_player.TEAM_ID = ".$idTeam." ORDER BY t_player.LINE_ID ASC, PLY_NAME ASC");

        //echo "<script>console.debug( \"1 - PHP DEBUG: ".print_r($list_players)."\" );</script>";

        if (request('step')) {
            $tacPosIds = $request->tacPosIds;
            $plyIds = $request->plyIds;
            $count = count($tacPosIds);
            DB::delete('DELETE FROM t_player_tac_pos WHERE USER_ID = ' . $userId);
            for ($i = 0; $i < $count; $i++) {
                DB::insert('INSERT INTO t_player_tac_pos (TAC_ID_POS, PLY_ID, USER_ID) VALUES (?, ?, ?)', array($tacPosIds[$i], $plyIds[$i], $userId));
            }

            $list_players = DB::select("SELECT *
                                    FROM t_player
                                    LEFT JOIN t_player_line ON t_player_line.LINE_ID = t_player.LINE_ID
                                    LEFT JOIN (SELECT TAC_ID_POS, USER_ID, PLY_ID AS PL_ID FROM t_player_tac_pos) AS plyTacPos ON plyTacPos.PL_ID = t_player.PLY_ID AND plyTacPos.USER_ID = ".$userId."
                                    WHERE t_player.TEAM_ID = ".$idTeam." ORDER BY t_player.LINE_ID ASC, PLY_NAME ASC");

        } else {
            if (count($list_players) == 0) {
                $p = 0;
                $list_players = DB::select("SELECT t_player.*,t_player_line.*
                                    FROM t_player, t_player_line
                                    WHERE t_player_line.LINE_ID = t_player.LINE_ID
                                    AND TEAM_ID = ".$idTeam." ORDER BY t_player.LINE_ID ASC, PLY_NAME ASC");
            }
        }

        $cards = DB::select("SELECT PLY_ID FROM t_card where MATCH_ID = (SELECT MATCH_ID FROM t_match WHERE MATCH_T1 = " . $idTeam . " OR MATCH_T2 = " . $idTeam . " ORDER BY MATCH_ID DESC LIMIT 1) AND USER_ID = " . $userId);

        // echo "<script>console.debug( \"2 - PHP DEBUG: ".print_r($list_players)."\" );</script>";

        return view('player.list_player', ['list_players' => $list_players,'team_names'=>$team_names,'tac_pos'=>$tac_pos,'cards'=>$cards, 'p'=>$p]);
    }

    public function player_loose(Request $request) {
        $r = 1;
        $idTeam = Auth::user()->TEAM_ID;
        $userId = Auth::user()->id;
        $group = DB::select("SELECT GRP_ID FROM t_team_sta WHERE TEAM_ID = ".$idTeam." AND USER_ID = ".$userId." ORDER BY TEAM_STA_ID DESC LIMIT 1");

        $all_rankigs = DB::select("SELECT t_team_sta.TEAM_STA_ID,t_team_sta.TEAM_DRAW,t_team_sta.TEAM_LOS,t_team_sta.TEAM_AVG,
        t_team_sta.TEAM_WIN,t_team_sta.TEAM_PTS,t_team.TEAM_ID,t_team.TEAM_NAME,t_group.GRP_ID,t_group.GRP_NAME,t_rank.RNK_ID,t_rank.RNK_NAME
                FROM t_team_sta INNER JOIN t_team ON t_team_sta.TEAM_ID = t_team.TEAM_ID
                INNER JOIN t_group ON t_team_sta.GRP_ID = t_group.GRP_ID
                INNER JOIN t_rank ON t_team_sta.RNK_ID = t_rank.RNK_ID
                WHERE t_team_sta.GRP_ID = ".$group[0]->GRP_ID." AND t_team_sta.USER_ID = ".$userId." ORDER BY TEAM_PTS DESC, TEAM_AVG DESC, TEAM_NAME ASC");

        return view('player.player_loose',['all_rankigs'=>$all_rankigs,'r'=>$r]);
    }

    public function disconnect(Request $request) {

        Utils::deleteUserData();

        $request->session()->flush();
        // Session::flush();
        Auth::logout();
        return redirect('/login');
    }
}
