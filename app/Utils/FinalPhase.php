<?php
/**
 * Created by PhpStorm.
 * User: emmaus
 * Date: 20/03/19
 * Time: 09:44
 */

namespace App\Utils;


use App\Utils\KeysFile\CanPhase;
use App\Utils\KeysFile\DaysList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinalPhase
{

    static function sort_by_name($a, $b)
    {
        return strcmp($a->TEAM_NAME, $b->TEAM_NAME);
    }

    static function sort_array_obj($a, $b)
    {
        return strcmp($a->GRP_ID, $b->GRP_ID);
    }

    /**
     * @param $idTeam
     * @return bool
     */
    public static function isWinner() {

        $answer = false;
        $userId = Auth::user()->id;
        $query = 'select * from t_match where MATCH_CAN_PHASE = '. CanPhase::$FINAL. ' AND USER_ID = ' . $userId;
        $matchDuel = DB::select($query);
        $teamsId = [];
        foreach ($matchDuel as $match) {
            $matchCore = explode(" - ", $match->MATCH_SCORE);
            $score1 = $matchCore[0];
            $score2 = $matchCore[1];
            $temp = explode(" ", $score1);
            $but1 = $temp[count($temp)-1];
            $temp = explode(" ", $score2);
            $but2 = $temp[0];
            if ($but1 > $but2) {
                $answer = true;
            }
        }

        return $answer;
    }

    public static function teams_ids() {

        $userId = Auth::user()->id;
        $query = 'select * from t_match where MATCH_CAN_PHASE = '. CanPhase::$SEMI_FINAL. ' AND USER_ID = ' . $userId;
        $matchDuel = DB::select($query);
        $teamsId = [];
        foreach ($matchDuel as $match) {
            $matchCore = explode(" - ", $match->MATCH_SCORE);
            $score1 = $matchCore[0];
            $score2 = $matchCore[1];
            $temp = explode(" ", $score1);
            $but1 = $temp[count($temp)-1];
            $temp = explode(" ", $score2);
            $but2 = $temp[0];
            if ($but1 > $but2) {
                array_push($teamsId, $match->MATCH_T1);
            } else {
                array_push($teamsId, $match->MATCH_T2);
            }
        }

        return $teamsId;
    }

    public static function teams_ids_thrid_place() {

        $userId = Auth::user()->id;
        $query = 'select * from t_match where MATCH_CAN_PHASE = '. CanPhase::$SEMI_FINAL. ' AND USER_ID = ' . $userId;
        $matchDuel = DB::select($query);
        $teamsId = [];
        foreach ($matchDuel as $match) {
            $matchCore = explode(" - ", $match->MATCH_SCORE);
            $score1 = $matchCore[0];
            $score2 = $matchCore[1];
            $temp = explode(" ", $score1);
            $but1 = $temp[count($temp)-1];
            $temp = explode(" ", $score2);
            $but2 = $temp[0];
            if ($but1 > $but2) {
                array_push($teamsId, $match->MATCH_T2);
            } else {
                array_push($teamsId, $match->MATCH_T1);
            }
        }

        return $teamsId;
    }

    /**
     * @param $idTeam
     * @return bool
     */
    public static function isQualified($idTeam) {

        $list_teams = self::teams_ids();
        return in_array($idTeam, $list_teams);
    }

    /**
     * @return array which is the 8 teams qualified after group qualification
     */
    public static function teams_qualified() {

        $userId = Auth::user()->id;
        $teamsIds = self::teams_ids();
        $listIds = implode ( ",", $teamsIds);

        $query_team = 'select * from t_team '.
            'join t_team_sta on t_team_sta.TEAM_ID = t_team.TEAM_ID AND t_team_sta.USER_ID = '.$userId.' '.
            'where t_team.TEAM_ID in ('.$listIds.') '.
            'order by t_team_sta.GRP_ID desc, t_team_sta.TEAM_PTS desc, t_team_sta.TEAM_AVG desc';

        $list_teams = DB::select($query_team);
        usort($list_teams, array(self::class,'sort_by_name'));

        return $list_teams;
    }

    /**
     * @return array which is the 8 teams qualified after group qualification
     */
    public static function teams_third_place() {

        $userId = Auth::user()->id;
        $teamsIds = self::teams_ids_thrid_place();
        $listIds = implode ( ",", $teamsIds);

        $query_team = 'select * from t_team '.
            'join t_team_sta on t_team_sta.TEAM_ID = t_team.TEAM_ID AND t_team_sta.USER_ID = '.$userId.' '.
            'where t_team.TEAM_ID in ('.$listIds.') '.
            'order by t_team_sta.GRP_ID desc, t_team_sta.TEAM_PTS desc, t_team_sta.TEAM_AVG desc';

        $list_teams = DB::select($query_team);

        return $list_teams;
    }

    public static function adversary($idTeam) {

        $teams = self::teams_qualified();
        usort($teams, array(self::class,'sort_array_obj'));

        return ($teams[0]->TEAM_ID == $idTeam) ? $teams[1] : $teams[0];
    }

    public static function final_crossing() {
        $teams = self::teams_qualified();
        $teamsCrossing = self::crossing($teams);

        return $teamsCrossing;

    }

    public static function crossing($list_teams) {
        $teams_A = [];
        $teams_B = [];

        usort($list_teams, array(self::class,'sort_array_obj'));

        array_push($teams_A, $list_teams[0]->TEAM_NAME);
        array_push($teams_B, $list_teams[1]->TEAM_NAME);

        return array($teams_A, $teams_B);
    }

    public static function play_third_place() {
        $id = Auth::user()->id;
        $group_days = DaysList::getPhase();

        $teams = self::teams_third_place();
        usort($teams, array(self::class,'sort_array_obj'));

        $but1 = Utils::but($teams[0]->TEAM_ID);
        $but2 = Utils::but($teams[1]->TEAM_ID);
        while ($but1 == $but2) {
            $but1 = Utils::but($teams[0]->TEAM_ID);
            $but2 = Utils::but($teams[1]->TEAM_ID);
        }

        Utils::players_goals($but1, $teams[0]->TEAM_ID, 2);
        Utils::players_goals($but2, $teams[1]->TEAM_ID, 2);
        $players_1 = session('ply_ids_2_'.$teams[0]->TEAM_ID);
        $pass_ids_1 = session('pass_ids_2_'.$teams[0]->TEAM_ID);
        $times_1 = session('times_2_'.$teams[0]->TEAM_ID);
        $players_2 = session('ply_ids_2_'.$teams[1]->TEAM_ID);
        $pass_ids_2 = session('pass_ids_2_'.$teams[1]->TEAM_ID);
        $times_2 = session('times_2_'.$teams[1]->TEAM_ID);
        // save match
        Utils::save_match_infos(0,0,$teams[0]->TEAM_ID,$teams[1]->TEAM_ID,0,$group_days[DaysList::$UNKNOWN]['DAY'],$teams[0]->TEAM_NAME." ".$but1." - ".$but2." ".$teams[1]->TEAM_NAME,$id,CanPhase::$THIRD_PLACE_FINAL,DaysList::$UNKNOWN);
        Utils::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($teams[0]->TEAM_ID));
        Utils::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($teams[1]->TEAM_ID));
    }
}