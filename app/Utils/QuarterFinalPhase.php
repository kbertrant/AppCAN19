<?php
/**
 * Created by PhpStorm.
 * User: emmaus
 * Date: 19/03/19
 * Time: 13:44
 */

namespace App\Utils;


use App\Utils\KeysFile\CanPhase;
use App\Utils\KeysFile\DaysList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuarterFinalPhase
{

    static function sort_by_name($a, $b)
    {
        return strcmp($a->TEAM_NAME, $b->TEAM_NAME);
    }

    static function sort_array_obj($a, $b)
    {
        return strcmp($a->GRP_ID, $b->GRP_ID);
    }

    public static function teams_ids() {

        $userId = Auth::user()->id;
        $query = 'select * from t_match where MATCH_CAN_PHASE = '. CanPhase::$EIGHT_FINAL. ' AND USER_ID = ' . $userId;
        $matchDuel = DB::select($query);
        // echo "<script>console.debug( \"PHP DEBUG: ".print_r($matchDuel)."\" );</script>";
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

    public static function adversary($idTeam) {

        $teams = self::teams_qualified();
        usort($teams, array(self::class,'sort_array_obj'));

        $other = null;
        $size = count($teams);
        /*for ($j = 0; $j < $size; $j++) {
            if ($teams[$j]->TEAM_ID == $idTeam) {
                $other = $teams[7-$j];
                break;
            }
        }*/
        for ($j = 0; $j < 4; $j++) {
            if ($teams[$j]->TEAM_ID == $idTeam || $teams[7-$j]->TEAM_ID == $idTeam) {
                $other = ($teams[$j]->TEAM_ID == $idTeam) ? $teams[7-$j] : $teams[$j];
                break;
            }
        }

        return $other;
    }

    public static function quarter_crossing() {
        $teams = self::teams_qualified();
        $teamsCrossing = self::crossing($teams);

        return $teamsCrossing;

    }

    public static function crossing($list_teams) {
        $teams_A = [];
        $teams_B = [];

        usort($list_teams, array(self::class,'sort_array_obj'));

        for ($j = 0; $j < 4; $j++) {
            array_push($teams_A, $list_teams[$j]->TEAM_NAME);
            array_push($teams_B, $list_teams[7-$j]->TEAM_NAME);
        }

        return array($teams_A, $teams_B);
    }

    public static function play_round() {
        $userId = Auth::user()->id;
        $teamId = Auth::user()->TEAM_ID;
        $group_days = DaysList::getPhase();

        $teams = self::teams_qualified();
        usort($teams, array(self::class,'sort_array_obj'));

        for ($j = 0; $j < 4; $j++) {
            if ($teams[$j]->TEAM_ID != $teamId && $teams[7-$j]->TEAM_ID != $teamId) {
                $but1 = Utils::but($teams[$j]->TEAM_ID);
                $but2 = Utils::but($teams[7-$j]->TEAM_ID);
                while ($but1 == $but2) {
                    $but1 = Utils::but($teams[$j]->TEAM_ID);
                    $but2 = Utils::but($teams[7-$j]->TEAM_ID);
                }

                Utils::players_goals($but1, $teams[$j]->TEAM_ID, 2);
                Utils::players_goals($but2, $teams[7-$j]->TEAM_ID, 2);
                $players_1 = session('ply_ids_2_'.$teams[$j]->TEAM_ID);
                $pass_ids_1 = session('pass_ids_2_'.$teams[$j]->TEAM_ID);
                $times_1 = session('times_2_'.$teams[$j]->TEAM_ID);
                $players_2 = session('ply_ids_2_'.$teams[7-$j]->TEAM_ID);
                $pass_ids_2 = session('pass_ids_2_'.$teams[7-$j]->TEAM_ID);
                $times_2 = session('times_2_'.$teams[7-$j]->TEAM_ID);
                // save match
                Utils::save_match_infos(0, 0, $teams[$j]->TEAM_ID, $teams[7-$j]->TEAM_ID, 0, $group_days[DaysList::$UNKNOWN]['DAY'],
                    $teams[$j]->TEAM_NAME." ".$but1." - ".$but2." ".$teams[7-$j]->TEAM_NAME, $userId, CanPhase::$QUARTER_FINAL, DaysList::$UNKNOWN);
                Utils::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($teams[$j]->TEAM_ID));
                Utils::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($teams[7-$j]->TEAM_ID));
            }
        }
    }
}