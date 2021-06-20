<?php

namespace App\Utils;

//use App\Utils\DB;
use App\Utils\KeysFile\CanPhase;
use App\Utils\KeysFile\DaysList;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EightFinalPhase{

    //echo "<script>console.debug( \"PHP DEBUG: ".print_r($teamsId)."\" );</script>";
    
    static function sort_by_name($a, $b)
    {
        return strcmp($a->TEAM_NAME, $b->TEAM_NAME);
    }

    static function sort_by_grp($a, $b)
    {
        return strcmp($a->GRP_ID, $b->GRP_ID);
    }

   static function sort_by_pts($a, $b)
    {
        if ($a->TEAM_PTS == $b->TEAM_PTS) {
            if ($a->TEAM_AVG < $b->TEAM_AVG){
                return -1;
            } elseif ($a->TEAM_AVG > $b->TEAM_AVG) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return strcmp($a->TEAM_PTS, $b->TEAM_PTS);
        }
    }

    /**
     * Return de list id of teams rank in different group after qualification
     * first place(1) => offset= 0
     * second place(2) => offset= 1
     * thrid place(3) => offset= 2
     *
     * @param $offset
     * @return array|null
     */
    public static function teams_ids($offset) {

        $userId = Auth::user()->id;
        $query_grp = 'select GRP_ID from t_group';
        $grp_list = DB::select($query_grp);
        $teamsOb = [];
        $teamsId = [];
        foreach ($grp_list as $grp) {
            $query_id = 'select TEAM_ID, TEAM_PTS, TEAM_AVG from t_team_sta where GRP_ID = '.$grp->GRP_ID.' AND USER_ID = '.$userId.' order by TEAM_PTS desc, TEAM_AVG desc limit 1 offset '.$offset;
            $temps = DB::select($query_id);
            foreach ($temps as $temp) {
                array_push($teamsOb, $temp);
            }
        }
        usort($teamsOb, array(self::class,'sort_by_pts'));

        $size = count($teamsOb);
        for($i = 0; $i < $size; $i++) {
            $teamsId = array_merge($teamsId, array($teamsOb[$i]->TEAM_ID));
        }
        if ($offset == 2) {
            $teamsId = array_slice ($teamsId, 2, 4);
        }

        return $teamsId;
    }

    /**
     * @return array which is the 16 teams qualified after group qualification
     */
    public static function teams_qualified() {

        $userId = Auth::user()->id;
        $teamsIds = self::teams_ids(0);
        $teamsIds = array_merge($teamsIds, self::teams_ids(1));
        $teamsIds = array_merge($teamsIds, self::teams_ids(2));

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
     * Return de list id and name of teams rank in different group after qualification
     * first place(1) => offset= 0
     * second place(2) => offset= 1
     * thrid place(3) => offset= 2
     *
     * @param $offset
     * @return array
     */
    public static function teams_place($offset) {

        $userId = Auth::user()->id;
        $teamsId = self::teams_ids($offset);
        $listIds = implode ( ",", $teamsId);

        $query_team = 'select * from t_team '.
            'join t_team_sta on t_team_sta.TEAM_ID = t_team.TEAM_ID AND t_team_sta.USER_ID = '.$userId.' '.
            'where t_team.TEAM_ID in ('.$listIds.') '.
            // 'order by t_team_sta.TEAM_PTS desc, t_team_sta.TEAM_AVG desc';
            'order by t_team_sta.GRP_ID desc, t_team_sta.TEAM_PTS desc, t_team_sta.TEAM_AVG desc';

        $list_teams = DB::select($query_team);

        return $list_teams;

    }

    /**
     * @param $idTeam
     * @return bool
     */
    public static function isQualified($idTeam) {

        $teamsIds = self::teams_ids(0);
        $teamsIds = array_merge($teamsIds, self::teams_ids(1));
        $teamsIds = array_merge($teamsIds, self::teams_ids(2));

        return in_array($idTeam, $teamsIds);
    }

    public static function eight_crossing() {
        $teams_1 = self::teams_place(0);
        $teams_2 = self::teams_place(1);
        $teams_3 = self::teams_place(2);

        $listTeamsCrossing = self::crossing($teams_1, $teams_2, $teams_3);

        return $listTeamsCrossing;

    }

    public static function crossing($teams_1, $teams_2, $teams_3) {

        $teams_A = [];
        $teams_B = [];

        usort($teams_1, array(self::class,'sort_by_pts'));
        usort($teams_2, array(self::class,'sort_by_pts'));
        usort($teams_3, array(self::class,'sort_by_pts'));

        $teams_11 = array_slice ($teams_1, 2, 4);     // 4 Bests First teams of all groups
        $teams_12 = array_slice ($teams_1, 0, 2);
        $teams_21 = array_slice ($teams_2, 2, 4);     // 4 Bests Second teams of all groups
        $teams_22 = array_slice ($teams_2, 0, 2);

        usort($teams_11, array(self::class,'sort_by_grp'));
        usort($teams_12, array(self::class,'sort_by_grp'));
        usort($teams_21, array(self::class,'sort_by_grp'));
        usort($teams_22, array(self::class,'sort_by_grp'));
        usort($teams_3, array(self::class,'sort_by_grp'));

        $size = count($teams_11);
        for ($j = 0; $j < $size; $j++) {
            array_push($teams_A, $teams_11[$j]->TEAM_NAME);
            array_push($teams_B, $teams_3[3-$j]->TEAM_NAME);
        }

        $size = count($teams_12);
        for ($j = 0; $j < $size; $j++) {
            array_push($teams_A, $teams_12[$j]->TEAM_NAME);
            array_push($teams_B, $teams_22[1-$j]->TEAM_NAME);
        }

        for ($j = 0; $j < $size; $j++) {
            array_push($teams_A, $teams_21[$j]->TEAM_NAME);
            array_push($teams_B, $teams_21[3-$j]->TEAM_NAME);
        }

        return array($teams_A, $teams_B);
    }

    public static function adversary($idTeam) {

        $found = false;
        $other = null;

        $teamsId_1 = self::teams_place(0);
        $teamsId_2 = self::teams_place(1);
        $teamsId_3 = self::teams_place(2);

        usort($teamsId_1, array(self::class,'sort_by_pts'));
        usort($teamsId_2, array(self::class,'sort_by_pts'));
        usort($teamsId_3, array(self::class,'sort_by_pts'));

        $teams_11 = array_slice ($teamsId_1, 2, 4);     // 4 Bests First teams of all groups
        $teams_12 = array_slice ($teamsId_1, 0, 2);
        $teams_21 = array_slice ($teamsId_2, 2, 4);     // 4 Bests Second teams of all groups
        $teams_22 = array_slice ($teamsId_2, 0, 2);

        usort($teams_11, array(self::class,'sort_by_grp'));
        usort($teams_12, array(self::class,'sort_by_grp'));
        usort($teams_21, array(self::class,'sort_by_grp'));
        usort($teams_22, array(self::class,'sort_by_grp'));
        usort($teamsId_3, array(self::class,'sort_by_grp'));

        $size = count($teams_11);
        for ($j = 0; $j < $size; $j++) {
            if ($teams_11[$j]->TEAM_ID == $idTeam || $teamsId_3[3-$j]->TEAM_ID == $idTeam) {
                $other = ($teams_11[$j]->TEAM_ID == $idTeam) ? $teamsId_3[3-$j] : $teams_11[$j];
                $found = !$found;
                break;
            }
        }
        if ($found) {
            return $other;
        }

        $size = count($teams_12);
        for ($j = 0; $j < $size; $j++) {
            if ($teams_12[$j]->TEAM_ID == $idTeam || $teams_22[1-$j]->TEAM_ID == $idTeam) {
                $other = ($teams_12[$j]->TEAM_ID == $idTeam) ? $teams_22[1-$j] : $teams_12[$j];
                $found = !$found;
                break;
            }
        }
        if ($found) {
            return $other;
        }

        for ($j = 0; $j < $size; $j++) {
            if ($teams_21[$j]->TEAM_ID == $idTeam || $teams_21[3-$j]->TEAM_ID == $idTeam) {
                $other = ($teams_21[$j]->TEAM_ID == $idTeam) ? $teams_21[3-$j] : $teams_21[$j];
                break;
            }
        }

        return $other;
    }

    public static function play_round(){

        $id = Auth::user()->id;
        $teamId = Auth::user()->TEAM_ID;
        $group_days = DaysList::getPhase();

        $teamsId_1 = self::teams_place(0);
        $teamsId_2 = self::teams_place(1);
        $teamsId_3 = self::teams_place(2);

        usort($teamsId_1, array(self::class,'sort_by_pts'));
        usort($teamsId_2, array(self::class,'sort_by_pts'));
        usort($teamsId_3, array(self::class,'sort_by_pts'));

        $teams_11 = array_slice ($teamsId_1, 2, 4);     // 4 Bests First teams of all groups
        $teams_12 = array_slice ($teamsId_1, 0, 2);
        $teams_21 = array_slice ($teamsId_2, 2, 4);     // 4 Bests Second teams of all groups
        $teams_22 = array_slice ($teamsId_2, 0, 2);

        usort($teams_11, array(self::class,'sort_by_grp'));
        usort($teams_12, array(self::class,'sort_by_grp'));
        usort($teams_21, array(self::class,'sort_by_grp'));
        usort($teams_22, array(self::class,'sort_by_grp'));
        usort($teamsId_3, array(self::class,'sort_by_grp'));

        $size = count($teams_11);
        for ($j = 0; $j < $size; $j++) {
            if ($teams_11[$j]->TEAM_ID != $teamId && $teamsId_3[3-$j]->TEAM_ID != $teamId) {
                $but1 = Utils::but($teams_11[$j]->TEAM_ID);
                $but2 = Utils::but($teamsId_3[3-$j]->TEAM_ID);
                while ($but1 == $but2) {
                    $but1 = Utils::but($teams_11[$j]->TEAM_ID);
                    $but2 = Utils::but($teamsId_3[3-$j]->TEAM_ID);
                }


                Utils::players_goals($but1, $teams_11[$j]->TEAM_ID, 2);
                Utils::players_goals($but2, $teamsId_3[3-$j]->TEAM_ID, 2);
                $players_1 = session('ply_ids_2_'.$teams_11[$j]->TEAM_ID);
                $pass_ids_1 = session('pass_ids_2_'.$teams_11[$j]->TEAM_ID);
                $times_1 = session('times_2_'.$teams_11[$j]->TEAM_ID);
                $players_2 = session('ply_ids_2_'.$teamsId_3[3-$j]->TEAM_ID);
                $pass_ids_2 = session('pass_ids_2_'.$teamsId_3[3-$j]->TEAM_ID);
                $times_2 = session('times_2_'.$teamsId_3[3-$j]->TEAM_ID);
                // save match
                Utils::save_match_infos(0,0,$teams_11[$j]->TEAM_ID,$teamsId_3[3-$j]->TEAM_ID,0,$group_days[DaysList::$UNKNOWN]['DAY'],$teams_11[$j]->TEAM_NAME." ".$but1." - ".$but2." ".$teamsId_3[3-$j]->TEAM_NAME,$id,CanPhase::$EIGHT_FINAL,DaysList::$UNKNOWN);
                Utils::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($teams_11[$j]->TEAM_ID));
                Utils::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($teamsId_3[3-$j]->TEAM_ID));
            }
        }

        $size = count($teams_12);
        for ($j = 0; $j < $size; $j++) {
            if ($teams_12[$j]->TEAM_ID != $teamId && $teams_22[1-$j]->TEAM_ID != $teamId) {
                $but1 = Utils::but($teams_12[$j]->TEAM_ID);
                $but2 = Utils::but($teams_22[1-$j]->TEAM_ID);
                while ($but1 == $but2) {
                    $but1 = Utils::but($teams_12[$j]->TEAM_ID);
                    $but2 = Utils::but($teams_22[1-$j]->TEAM_ID);
                }

                Utils::players_goals($but1, $teams_12[$j]->TEAM_ID, 2);
                Utils::players_goals($but2, $teams_22[1-$j]->TEAM_ID, 2);
                $players_1 = session('ply_ids_2_'.$teams_12[$j]->TEAM_ID);
                $pass_ids_1 = session('pass_ids_2_'.$teams_12[$j]->TEAM_ID);
                $times_1 = session('times_2_'.$teams_12[$j]->TEAM_ID);
                $players_2 = session('ply_ids_2_'.$teams_22[1-$j]->TEAM_ID);
                $pass_ids_2 = session('pass_ids_2_'.$teams_22[1-$j]->TEAM_ID);
                $times_2 = session('times_2_'.$teams_22[1-$j]->TEAM_ID);
                // save match
                Utils::save_match_infos(0,0,$teams_12[$j]->TEAM_ID,$teams_22[1-$j]->TEAM_ID,0,$group_days[DaysList::$UNKNOWN]['DAY'],$teams_12[$j]->TEAM_NAME." ".$but1." - ".$but2." ".$teams_22[1-$j]->TEAM_NAME,$id,CanPhase::$EIGHT_FINAL,DaysList::$UNKNOWN);
                Utils::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($teams_12[$j]->TEAM_ID));
                Utils::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($teams_22[1-$j]->TEAM_ID));
            }
        }

        for ($j = 0; $j < $size; $j++) {
            if ($teams_21[$j]->TEAM_ID != $teamId && $teams_21[3-$j]->TEAM_ID != $teamId) {
                $but1 = Utils::but($teams_21[$j]->TEAM_ID);
                $but2 = Utils::but($teams_21[3-$j]->TEAM_ID);;
                while ($but1 == $but2) {
                    $but1 = Utils::but($teams_21[$j]->TEAM_ID);
                    $but2 = Utils::but($teams_21[3-$j]->TEAM_ID);
                }

                Utils::players_goals($but1, $teams_21[$j]->TEAM_ID, 2);
                Utils::players_goals($but2, $teams_21[3-$j]->TEAM_ID, 2);
                $players_1 = session('ply_ids_2_'.$teams_21[$j]->TEAM_ID);
                $pass_ids_1 = session('pass_ids_2_'.$teams_21[$j]->TEAM_ID);
                $times_1 = session('times_2_'.$teams_21[$j]->TEAM_ID);
                $players_2 = session('ply_ids_2_'.$teams_21[3-$j]->TEAM_ID);
                $pass_ids_2 = session('pass_ids_2_'.$teams_21[3-$j]->TEAM_ID);
                $times_2 = session('times_2_'.$teams_21[3-$j]->TEAM_ID);
                // save match
                Utils::save_match_infos(0,0,$teams_21[$j]->TEAM_ID,$teams_21[3-$j]->TEAM_ID,0,$group_days[DaysList::$UNKNOWN]['DAY'],$teams_21[$j]->TEAM_NAME." ".$but1." - ".$but2." ".$teams_21[3-$j]->TEAM_NAME,$id,CanPhase::$EIGHT_FINAL,DaysList::$UNKNOWN);
                Utils::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($teams_21[$j]->TEAM_ID));
                Utils::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($teams_21[3-$j]->TEAM_ID));
            }
        }

    }

}