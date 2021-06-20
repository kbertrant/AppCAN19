<?php

namespace App\Utils;

//use App\Utils\DB;
use App\Utils\KeysFile\CanPhase;
use App\Utils\KeysFile\DaysList;
use App\Utils\KeysFile\LineKeys;
use App\Utils\KeysFile\TacticKeys;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Utils{

    public static function get_amount(){
        $id = Auth::user()->id;
        $amount = DB::select("SELECT CRED_BALANCE FROM `t_credit` WHERE CRED_USER_ID = ".$id.";");
        return $amount[0]->CRED_BALANCE;
        //return 1;
    }

    public static function deleteUserData(){

        $userId = Auth::user()->id;

        DB::table('t_card')->where('CRED_USER_ID', $userId)->delete();
        DB::table('t_goal')->where('USER_ID', $userId)->delete();
        DB::table('t_match')->where('USER_ID', $userId)->delete();
        DB::table('t_player_sta')->where('USER_ID', $userId)->delete();
        DB::table('t_player_tac_pos')->where('USER_ID', $userId)->delete();
        DB::table('t_team_sta')->where('USER_ID', $userId)->delete();
        DB::table('t_team_tac')->where('USER_ID', $userId)->delete();
    }

    public static function init_data(){
        $userId = Auth::user()->id;
        $teamId = Auth::user()->TEAM_ID;
        DB::table('t_team_sta')->where([['TEAM_ID', '=', $teamId], ['USER_ID', '=', $userId]])->update(array('TEAM_DRAW'=>0,'TEAM_LOS'=>0,'TEAM_WIN'=>0,'TEAM_PTS'=>0,'TEAM_AVG'=>0,'TEAM_SCO'=>0,'TEAM_CON'=>0));
    }

    public static function match_decision($id_team1,$but_team1,$id_team2,$but_team2){

        $userId = Auth::user()->id;

        if($but_team1 == $but_team2){
            $data_team1 = DB::select("SELECT TEAM_PTS,TEAM_DRAW,TEAM_SCO,TEAM_CON,TEAM_AVG FROM t_team_sta WHERE TEAM_ID = ".$id_team1." AND t_team_sta.USER_ID = ".$userId);
            DB::table('t_team_sta')->where([['TEAM_ID', '=', $id_team1], ['USER_ID', '=', $userId]])->update(array('TEAM_SCO'=>$data_team1[0]->TEAM_SCO+$but_team1,
                'TEAM_CON'=>$data_team1[0]->TEAM_CON+$but_team2,'TEAM_DRAW'=>$data_team1[0]->TEAM_DRAW+1,'TEAM_PTS'=>$data_team1[0]->TEAM_PTS+1));
            $avg1 = DB::select("SELECT * FROM t_team_sta WHERE TEAM_ID = ".$id_team1." AND t_team_sta.USER_ID = ".$userId);
            DB::table('t_team_sta')->where([['TEAM_ID', '=', $id_team1], ['USER_ID', '=', $userId]])->update(array('TEAM_AVG'=>$avg1[0]->TEAM_AVG+($but_team1-$but_team2)));

            $data_team2 = DB::select("SELECT TEAM_PTS,TEAM_DRAW,TEAM_SCO,TEAM_CON FROM t_team_sta WHERE TEAM_ID = ".$id_team2." AND t_team_sta.USER_ID = ".$userId);
            DB::table('t_team_sta')->where([['TEAM_ID', '=', $id_team2], ['USER_ID', '=', $userId]])->update(array('TEAM_SCO'=>$data_team2[0]->TEAM_SCO+$but_team2,
                'TEAM_CON'=>$data_team2[0]->TEAM_CON+$but_team1,'TEAM_DRAW'=>$data_team2[0]->TEAM_DRAW+1,'TEAM_PTS'=>$data_team2[0]->TEAM_PTS+1));
            $avg2 = DB::select("SELECT * FROM t_team_sta WHERE TEAM_ID = ".$id_team2." AND t_team_sta.USER_ID = ".$userId);
            DB::table('t_team_sta')->where([['TEAM_ID', '=', $id_team2], ['USER_ID', '=', $userId]])->update(array('TEAM_AVG'=>$avg2[0]->TEAM_AVG+($but_team2-$but_team1)));

        }elseif($but_team1 > $but_team2){
            $data_team1 = DB::select("SELECT TEAM_PTS,TEAM_WIN,TEAM_SCO,TEAM_CON FROM t_team_sta WHERE TEAM_ID = ".$id_team1." AND t_team_sta.USER_ID = ".$userId);
            DB::table('t_team_sta')->where([['TEAM_ID', '=', $id_team1], ['USER_ID', '=', $userId]])->update(array('TEAM_SCO'=>$data_team1[0]->TEAM_SCO+$but_team1,
                'TEAM_CON'=>$data_team1[0]->TEAM_CON+$but_team2,'TEAM_WIN'=>$data_team1[0]->TEAM_WIN+1,'TEAM_PTS'=>$data_team1[0]->TEAM_PTS+3));
            $avg1 = DB::select("SELECT * FROM t_team_sta WHERE TEAM_ID = ".$id_team1." AND t_team_sta.USER_ID = ".$userId);
            DB::table('t_team_sta')->where([['TEAM_ID', '=', $id_team1], ['USER_ID', '=', $userId]])->update(array('TEAM_AVG'=>$avg1[0]->TEAM_AVG+($but_team1-$but_team2)));

            $data_team2 = DB::select("SELECT TEAM_PTS,TEAM_LOS,TEAM_SCO,TEAM_CON FROM t_team_sta WHERE TEAM_ID = ".$id_team2." AND t_team_sta.USER_ID = ".$userId);
            DB::table('t_team_sta')->where([['TEAM_ID', '=', $id_team2], ['USER_ID', '=', $userId]])->update(array('TEAM_SCO'=>$data_team2[0]->TEAM_SCO+$but_team2,
                'TEAM_CON'=>$data_team2[0]->TEAM_CON+$but_team1,'TEAM_LOS'=>$data_team2[0]->TEAM_LOS+1));
            $avg2 = DB::select("SELECT * FROM t_team_sta WHERE TEAM_ID = ".$id_team2." AND t_team_sta.USER_ID = ".$userId);
            DB::table('t_team_sta')->where([['TEAM_ID', '=', $id_team2], ['USER_ID', '=', $userId]])->update(array('TEAM_AVG'=>$avg2[0]->TEAM_AVG+($but_team2-$but_team1)));

        }elseif($but_team1 < $but_team2){
            $data_team2 = DB::select("SELECT TEAM_PTS,TEAM_WIN,TEAM_SCO,TEAM_CON FROM t_team_sta WHERE TEAM_ID = ".$id_team2." AND t_team_sta.USER_ID = ".$userId);
            DB::table('t_team_sta')->where([['TEAM_ID', '=', $id_team2], ['USER_ID', '=', $userId]])->update(array('TEAM_SCO'=>$data_team2[0]->TEAM_SCO+$but_team2,
                'TEAM_CON'=>$data_team2[0]->TEAM_CON+$but_team1,'TEAM_WIN'=>$data_team2[0]->TEAM_WIN+1,'TEAM_PTS'=>$data_team2[0]->TEAM_PTS+3));
            $avg2 = DB::select("SELECT * FROM t_team_sta WHERE TEAM_ID = ".$id_team2." AND t_team_sta.USER_ID = ".$userId);
            DB::table('t_team_sta')->where([['TEAM_ID', '=', $id_team2], ['USER_ID', '=', $userId]])->update(array('TEAM_AVG'=>$avg2[0]->TEAM_AVG+($but_team2-$but_team1)));

            $data_team1 = DB::select("SELECT TEAM_PTS,TEAM_LOS,TEAM_SCO,TEAM_CON FROM t_team_sta WHERE TEAM_ID = ".$id_team1." AND t_team_sta.USER_ID = ".$userId);
            DB::table('t_team_sta')->where([['TEAM_ID', '=', $id_team1], ['USER_ID', '=', $userId]])->update(array('TEAM_SCO'=>$data_team1[0]->TEAM_SCO+$but_team1,
                'TEAM_CON'=>$data_team1[0]->TEAM_CON+$but_team2,'TEAM_LOS'=>$data_team1[0]->TEAM_LOS+1));
            $avg1 = DB::select("SELECT * FROM t_team_sta WHERE TEAM_ID = ".$id_team1." AND t_team_sta.USER_ID = ".$userId);
            DB::table('t_team_sta')->where([['TEAM_ID', '=', $id_team1], ['USER_ID', '=', $userId]])->update(array('TEAM_AVG'=>$avg1[0]->TEAM_AVG+($but_team1-$but_team2)));
        }
    }

    // define a value for every team
    public static function note_equipe($id){

        $team_val = DB::select("SELECT SUM(PLY_VAL) AS TEAM_VAL
                                FROM t_player
                                WHERE TEAM_ID =(".$id.")");
        $sum = $team_val[0]->TEAM_VAL/20;
        $somme = round($sum);
        //dd($team_val[0]->TEAM_VAL);
        return $somme;
    }

    public static function nbre_occasions($nbre) {
        return $nbr =  ($nbre - 10)*2;
    }

    //use this function for match user and divide attempts
    public static function nbre_occasions_half($nbre) {
        return $nbr =  ($nbre - 10);
    }

    public static function get_attempts($id_team){
        $note = self::note_equipe($id_team);
        $nbre = self::nbre_occasions_half($note);
        return $nbre;
    }

    public static function taux_occasions($tactique,$nbre) {
        if ($tactique == TacticKeys::$NEUTRAL) {
            return $nbre;
        }
        if ($tactique == TacticKeys::$OFFENSIVE) {
            return $nbre = $nbre + (($nbre) * 0.1);
        }
        if ($tactique == TacticKeys::$DEFENSIVE) {
            return $nbre = $nbre - (( $nbre) * 0.1);
        }
        return $nbre;
    }

    public static function occasions($nbre_occasions,$bonus){
        $but= 0;
        $i = 0;
        $occaz = false;

        for ( $i = 1; $i <= $nbre_occasions; $i++) {
            if (rand(1,100) < $bonus) {
                $occaz = true;
                $but++;
            } else {
                $occaz = false;
            }
        }
        return $but;
    }

    public static function filter_by_line ($array, $line){
        $res = [];
        if(is_array($array) && count($array) > 0) {
            foreach($array as $player){
                if ($player->LINE_ID == $line){
                    array_push($res, $player);;
                }
            }
        }

        return $res;
    }

    static function sort_by_ply_val($a, $b) {
        // return strcmp($a->PLY_VAL, $b->PLY_VAL);
        $val = 0;
        if ($a->PLY_VAL < $b->PLY_VAL){
            $val = -1;
        } elseif ($a->PLY_VAL > $b->PLY_VAL) {
            $val = 1;
        } else {
            if ($a->PLY_ATT_VAL <= $b->PLY_ATT_VAL){
                $val = -1;
            } else {
                $val = 1;
            }
        }

        return $val;
    }

    public static function players_goals($goals_nber, $team_id, $half_time = 1){

        $userId = Auth::user()->id;

        $nb_buts_1 = 0;
        $nb_buts_2 = 0;
        $nb_buts_3 = 0;

        // -------------------------------------------- build players line ---------------------------------------------
        /*
         $players = DB::select("SELECT *
                                    FROM t_player
                                    INNER JOIN t_player_line ON t_player_line.LINE_ID = t_player.LINE_ID
                                    INNER JOIN t_player_tac_pos ON t_player_tac_pos.PLY_ID = t_player.PLY_ID AND t_player_tac_pos.USER_ID = ".$userId."
                                    WHERE t_player.TEAM_ID = ".$team_id);
        */

        $players = DB::select("SELECT * FROM t_player
                                        INNER JOIN t_player_line ON t_player_line.LINE_ID = t_player.LINE_ID
                                        INNER JOIN (SELECT TAC_ID_POS, USER_ID, PLY_ID AS PL_ID FROM t_player_tac_pos) AS plyTacPos ON plyTacPos.PL_ID = t_player.PLY_ID AND plyTacPos.USER_ID = ".$userId."
                                        WHERE t_player.TEAM_ID = ".$team_id." AND (plyTacPos.PL_ID <> NULL OR plyTacPos.PL_ID <> '') ORDER BY t_player.LINE_ID ASC, PLY_NAME ASC");

        /*$players = DB::select("SELECT * FROM t_player
                                        LEFT JOIN (SELECT TAC_ID_POS, USER_ID, PLY_ID AS PL_ID FROM t_player_tac_pos) AS plyTacPos
                                        ON plyTacPos.PL_ID = t_player.PLY_ID AND plyTacPos.USER_ID = ".$userId."
                                        LEFT JOIN t_team_tac ON t_team_tac.TEAM_ID = ".$team_id."
                                        LEFT JOIN t_tactic_pos ON t_tactic_pos.TAC_POS_ID = plyTacPos.TAC_ID_POS AND t_tactic_pos.TAC_ID = t_team_tac.TAC_ID
                                        WHERE t_player.TEAM_ID = ".$team_id." AND (plyTacPos.PL_ID <> NULL OR plyTacPos.PL_ID <> '') ORDER BY t_player.LINE_ID ASC, PLY_NAME ASC");*/

        if (count($players) == 0) {
            $players = DB::select("SELECT * FROM t_player WHERE TEAM_ID = ".$team_id);
        }

        $players_1 = self::filter_by_line($players, LineKeys::$ATT);
        $players_2 = self::filter_by_line($players, LineKeys::$MID);
        $players_3 = self::filter_by_line($players, LineKeys::$DEF);
        usort($players_1, array(self::class,'sort_by_ply_val'));
        usort($players_2, array(self::class,'sort_by_ply_val'));
        usort($players_3, array(self::class,'sort_by_ply_val'));

        // ---------------------------------------------- build goals line ---------------------------------------------
        $rest = $goals_nber;
        if (count($players_1) > 0) {
            if (count($players_2) > 0 && count($players_3) > 0) {
                $temp = $goals_nber * 0.5;
                if ($temp > 1) {
                    $nb_buts_1 = round($temp);
                } else {
                    $nb_buts_1 = ceil($temp);
                }
            } elseif (count($players_2) > 0 || count($players_3) > 0) {
                $temp = $goals_nber * 0.6;
                if ($temp > 1) {
                    $nb_buts_1 = round($temp);
                } else {
                    $nb_buts_1 = ceil($temp);
                }
            } else {
                $nb_buts_1 = $goals_nber;
            }
        }
        $rest -= $nb_buts_1;

        if (count($players_2) > 0 && $rest > 0) {
            if (count($players_1) > 0 && count($players_3) > 0) {
                $temp = ($goals_nber - $nb_buts_1) * 0.6;
                if ($temp > 1) {
                    $nb_buts_2 = round($temp);
                } else {
                    $nb_buts_2 = ceil($temp);
                }
            } elseif (count($players_1) > 0 || count($players_3) > 0) {
                if (count($players_1) > 0) {
                    $nb_buts_2 = $goals_nber - $nb_buts_1;
                } else {
                    $temp = $goals_nber * 0.6;
                    if ($temp > 1) {
                        $nb_buts_2 = round($temp);
                    } else {
                        $nb_buts_2 = ceil($temp);
                    }
                }
            } else {
                $nb_buts_2 = $goals_nber;
            }
        }
        $rest -= $nb_buts_2;

        if (count($players_3) > 0 && $rest > 0) {
            $nb_buts_3 = $rest;
            /*if (count($players_1) > 0 && count($players_2) > 0) {
                $nb_buts_3 = $goals_nber - ($nb_buts_1 + $nb_buts_2);
            } elseif (count($players_1) > 0 || count($players_2) > 0) {
                if (count($players_1) > 0) {
                    $nb_buts_3 = $goals_nber - $nb_buts_1;
                } else {
                    $nb_buts_3 = $goals_nber - $nb_buts_2;
                }
            } else {
                $nb_buts_3 = $goals_nber;
            }*/
        }

        // --------------------------------------------- save in session -----------------------------------------------
        $temp_time = [];
        for ($i = 0; $i < $goals_nber; $i++) {
            if ($half_time == 1) {
                array_push($temp_time, rand(0, 45));
            } else {
                array_push($temp_time, rand(45, 90));
            }
        }
        sort($temp_time);

        $ply_ids = [];
        $pass_ids = [];
        $ply_Names = [];
        $times = [];

        if ($nb_buts_1 > 0) {
            $best = array_slice ($players_1, count($players_1)-1, 1);
            $others = array_slice ($players_1, 0, count($players_1)-1);
            $best_pass = array_slice($players_2, count($players_2) - 1, 1);

            $others_pass = array_slice($players_2, 0, count($players_2) - 1);
            for ($i = 0; $i < $nb_buts_1; $i++) {
                if ($i <= round($nb_buts_1 / 2)) {
                    array_push($ply_ids, $best[0]->PLY_ID);
                    array_push($pass_ids, $best_pass[0]->PLY_ID);
                    array_push($ply_Names, $best[0]->PLY_NAME);
                    array_push($times, $temp_time[$i]);
                } else {
                    $k = rand(0, count($others) - 1);           // $k = $i % count($others);
                    $r = rand(0, count($others_pass) - 1);      // $r = $i % count($others_pass);
                    array_push($ply_ids, $others[$k]->PLY_ID);
                    array_push($pass_ids, $others_pass[$r]->PLY_ID);
                    array_push($ply_Names, $others[$k]->PLY_NAME);
                    array_push($times, $temp_time[$i]);
                }
            }
        }

        if ($nb_buts_2 > 0) {
            $best = array_slice($players_2, count($players_2) - 1, 1);
            $others = array_slice($players_2, 0, count($players_2) - 1);
            $best_pass = array_slice($players_1, count($players_1) - 1, 1);
            $others_pass = array_slice($players_1, 0, count($players_1) - 1);
            for ($i = 0; $i < $nb_buts_2; $i++) {
                $p = $nb_buts_1 + $i;
                // $p = ($nb_buts_1 == 1) ? ($nb_buts_1 - 1) + $i : $nb_buts_1 + $i;
                if ($i <= round($nb_buts_2 / 2)) {
                    array_push($ply_ids, $best[0]->PLY_ID);
                    array_push($pass_ids, $best_pass[0]->PLY_ID);
                    array_push($ply_Names, $best[0]->PLY_NAME);
                    // echo "<script>console.debug( ".$p." - ".$nb_buts_1." - ".$i." - ".$nb_buts_2." - Time: ".print_r($temp_time)." );</script>";
                    array_push($times, $temp_time[$p]);
                } else {
                    $k = rand(0, count($others) - 1);           // $k = $i % count($others);
                    $r = rand(0, count($others_pass) - 1);      // $r = $i % count($others_pass);
                    array_push($ply_ids, $others[$k]->PLY_ID);
                    array_push($pass_ids, $others_pass[$r]->PLY_ID);
                    array_push($ply_Names, $others[$k]->PLY_NAME);
                    array_push($times, $temp_time[$p]);
                }
            }
        }

        if ($nb_buts_3 > 0) {
            $best = array_slice($players_3, count($players_3) - 1, 1);
            $others = array_slice($players_3, 0, count($players_3) - 1);
            $best_pass = array_slice($players_2, count($players_2) - 1, 1);
            $others_pass = array_slice($players_2, 0, count($players_2) - 1);
            for ($i = 0; $i < $nb_buts_3; $i++) {
                $p = $nb_buts_1 + $nb_buts_2 + $i;
                // $p = (($nb_buts_1 + $nb_buts_2) == 1) ? ((($nb_buts_1 + $nb_buts_2) - 1) + $i) : ($nb_buts_1 + $nb_buts_2 + $i);
                if ($i <= round($nb_buts_3 / 2)) {
                    array_push($ply_ids, $best[0]->PLY_ID);
                    array_push($pass_ids, $best_pass[0]->PLY_ID);
                    array_push($ply_Names, $best[0]->PLY_NAME);
                    array_push($times, $temp_time[$p]);
                } else {
                    $k = rand(0, count($others) - 1);           // $k = $i % count($others);
                    $r = rand(0, count($others_pass) - 1);      // $r = $i % count($others_pass);
                    array_push($ply_ids, $others[$k]->PLY_ID);
                    array_push($pass_ids, $others_pass[$r]->PLY_ID);
                    array_push($ply_Names, $others[$k]->PLY_NAME);
                    array_push($times, $temp_time[$p]);
                }
            }
        }

        session(['ply_ids_' . $half_time . '_' . $team_id => $ply_ids]);
        session(['pass_ids_' . $half_time . '_' . $team_id => $pass_ids]);
        session(['ply_names_' . $half_time . '_' . $team_id => $ply_Names]);
        session(['times_' . $half_time . '_' . $team_id => $times]);
    }

    //function for live match  2 periods
    public static function but_half($team_id){
        $note_equip = self::note_equipe($team_id);
        $nbre_occasions = self::nbre_occasions_half($note_equip);
        $but = self::occasions($nbre_occasions,50);
        return $but;
    }

    //function for other teams in process
    public static function but($team_id){
        $note_equip = self::note_equipe($team_id);
        $nbre_occasions = self::nbre_occasions($note_equip);
        $but = self::occasions($nbre_occasions,50);
        return $but;
    }


    //Set round for competition
    static function roundRobin( array $teams ){
        if (count($teams)%2 != 0){
            array_push($teams,"bye");
        }
        $away = array_splice($teams,(count($teams)/2));
        $home = $teams;
        for ($i=0; $i < count($home)+count($away)-1; $i++)
        {
            for ($j=0; $j<count($home); $j++)
            {
                $round[$i][$j]["Home"]=$home[$j];
                $round[$i][$j]["Away"]=$away[$j];
            }
            if(count($home)+count($away)-1 > 2)
            {
                $s = array_splice( $home, 1, 1 );
                $slice = array_shift($s);
                array_unshift($away,$slice );
                array_push( $home, array_pop($away));
            }
        }
        return $round;
    }

    public static function get_group_id(){
        $userId = Auth::user()->id;
        $teamId = Auth::user()->TEAM_ID;
        $team_name = DB::select("SELECT GRP_ID FROM t_team_sta WHERE TEAM_ID = ".$teamId." AND USER_ID = ".$userId);
        return $team_name;
    }

    public static function get_team_name(){
        $teamId = Auth::user()->TEAM_ID;
        $team_name = DB::select("SELECT TEAM_NAME FROM t_team WHERE TEAM_ID = ".$teamId);
        return $team_name;
    }

    public static function list_id_group(){
        $userId = Auth::user()->id;
        $teamId = Auth::user()->TEAM_ID;
        $team_e = DB::select("SELECT GRP_ID FROM t_group WHERE GRP_ID <> (SELECT GRP_ID FROM t_team_sta WHERE TEAM_ID = ".$teamId." AND USER_ID = ".$userId.") LIMIT 5");
        return $team_e;
    }

    public static function get_opponent(){

        $userId = Auth::user()->id;
        $teamId = Auth::user()->TEAM_ID;

        $id_team_group = DB::select("SELECT t_team_sta.TEAM_ID,t_team.TEAM_NAME,GRP_NAME,GRP_STADIUM1,GRP_STADIUM2
                 FROM t_team_sta
                 INNER JOIN t_group ON t_team_sta.GRP_ID = t_group.GRP_ID
                 INNER JOIN t_team ON t_team_sta.TEAM_ID = t_team.TEAM_ID
                 WHERE t_team_sta.TEAM_ID <> ".$teamId." AND t_team_sta.GRP_ID = (select GRP_ID from t_team_sta where team_id = ".$teamId." and user_id = ".$userId.") AND t_team_sta.USER_ID = ".$userId);

        return $id_team_group;
    }

    public static function get_round_grp1(){
        $userId = Auth::user()->id;
        $list_g1 = self::list_id_group();
        $grp1 = DB::select("SELECT TEAM_ID FROM t_team_sta WHERE GRP_ID = ".$list_g1[0]->GRP_ID." AND t_team_sta.USER_ID = ".$userId);
        $array_grp1 = array($grp1[0]->TEAM_ID,$grp1[1]->TEAM_ID,$grp1[2]->TEAM_ID,$grp1[3]->TEAM_ID);
        // do the rounds
        $rounds = self::roundRobin($array_grp1);

        foreach($rounds as $round => $games){
            $round+1;
            foreach($games as $play){
                $array_group1 = array($round+1,$play["Home"],$play["Away"]);
                $array_group[] = $array_group1;
            }
        }
        return $array_group;
    }

    public static function get_round_grp2(){
        $userId = Auth::user()->id;
        $list_g1 = self::list_id_group();
        $grp2 = DB::select("SELECT TEAM_ID FROM t_team_sta WHERE GRP_ID = ".$list_g1[1]->GRP_ID." AND t_team_sta.USER_ID = ".$userId);
        $array_grp2 = array($grp2[0]->TEAM_ID,$grp2[1]->TEAM_ID,$grp2[2]->TEAM_ID,$grp2[3]->TEAM_ID);
        // do the rounds
        $rounds = self::roundRobin($array_grp2);

        foreach($rounds as $round => $games){
            $round+1;
            foreach($games as $play){
                $array_group2= array($round+1,$play["Home"],$play["Away"]);
                $array_group[] = $array_group2;
            }
        }
        return $array_group;
    }

    public static function get_round_grp3(){
        $userId = Auth::user()->id;
        $list_g1 = self::list_id_group();
        $grp3 = DB::select("SELECT TEAM_ID FROM t_team_sta WHERE GRP_ID = ".$list_g1[2]->GRP_ID." AND t_team_sta.USER_ID = ".$userId);
        $array_grp3 = array($grp3[0]->TEAM_ID,$grp3[1]->TEAM_ID,$grp3[2]->TEAM_ID,$grp3[3]->TEAM_ID);
        //var_dump($array_grp1);
        // do the rounds
        $rounds = self::roundRobin($array_grp3);

        foreach($rounds as $round => $games){
            $round+1;
            foreach($games as $play){
                $array_group3 = array($round+1,$play["Home"],$play["Away"]);
                $array_group[] = $array_group3;
            }
        }
        return $array_group;
    }

    public static function get_round_grp4(){
        $userId = Auth::user()->id;
        $list_g1 = self::list_id_group();
        $grp4 = DB::select("SELECT TEAM_ID FROM t_team_sta WHERE GRP_ID = ".$list_g1[3]->GRP_ID." AND t_team_sta.USER_ID = ".$userId);
        $array_grp4 = array($grp4[0]->TEAM_ID,$grp4[1]->TEAM_ID,$grp4[2]->TEAM_ID,$grp4[3]->TEAM_ID);
        //var_dump($array_grp1);
        // do the rounds
        $rounds = self::roundRobin($array_grp4);

        foreach($rounds as $round => $games){
            $round+1;
            foreach($games as $play){
                $array_group4 = array($round+1,$play["Home"],$play["Away"]);
                $array_group[] = $array_group4;

            }
        }
        return $array_group;
    }

    public static function get_round_grp5(){
        $userId = Auth::user()->id;
        $list_g1 = self::list_id_group();
        $grp5 = DB::select("SELECT TEAM_ID FROM t_team_sta WHERE GRP_ID = ".$list_g1[4]->GRP_ID." AND t_team_sta.USER_ID = ".$userId);
        $array_grp5 = array($grp5[0]->TEAM_ID,$grp5[1]->TEAM_ID,$grp5[2]->TEAM_ID,$grp5[3]->TEAM_ID);
        //var_dump($array_grp1);
        // do the rounds
        $rounds = self::roundRobin($array_grp5);

        foreach($rounds as $round => $games){
            $round+1;
            foreach($games as $play){
                $array_group5 = array($round+1,$play["Home"],$play["Away"]);
                $array_group[] = $array_group5;
            }
        }
        return $array_group;
    }

    public static function get_team_details($idTeam) {
        $userId = Auth::user()->id;
        $teamDetail = DB::select("SELECT t_team.TEAM_NAME,GRP_ID
            FROM t_team, t_team_sta
            WHERE t_team.TEAM_ID = ".$idTeam." and t_team.TEAM_ID = t_team_sta.TEAM_ID AND t_team_sta.USER_ID = ".$userId);

        return $teamDetail;
    }

    public static function save_match_infos($MATCH_T1_TAC, $MATCH_T2_TAC, $MATCH_T1, $MATCH_T2, $MATCH_GRP, $MATCH_CODE, $MATCH_SCORE, $USER_ID, $MATCH_CAN_PHASE, $MATCH_GRP_PHASE) {
        DB::insert('INSERT INTO t_match (MATCH_T1_TAC, MATCH_T2_TAC, MATCH_T1, MATCH_T2, MATCH_GRP, MATCH_CODE, MATCH_SCORE, USER_ID, MATCH_CAN_PHASE, MATCH_GRP_PHASE)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            array( $MATCH_T1_TAC, $MATCH_T2_TAC, $MATCH_T1, $MATCH_T2, $MATCH_GRP, $MATCH_CODE, $MATCH_SCORE, $USER_ID, $MATCH_CAN_PHASE, $MATCH_GRP_PHASE));
    }

    public static function get_match_infos($idTeam) {
        $id = Auth::user()->id;
        $match_detail = DB::select("SELECT *
            FROM t_match
            WHERE (MATCH_T1 = ".$idTeam." OR MATCH_T2 = ".$idTeam.") AND USER_ID = ".$id." ORDER BY MATCH_ID DESC LIMIT 1");

        return $match_detail[0]->MATCH_ID;
    }

    public static function save_match_goals($ply_ids, $pass_ids, $times, $match) {
        $nbr = count($ply_ids);
        $userId = Auth::user()->id;
        for ($i = 0; $i < $nbr; $i++) {
            DB::insert('INSERT INTO t_goal (USER_ID, GOAL_TIME, MATCH_ID, PLY_ID, T_P_PLY_ID, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?)',
                array($userId, $times[0], $match, $ply_ids[$i], $pass_ids[$i], date('Y-m-d H:i:s'), date('Y-m-d H:i:s')));
        }
    }

    public static function array_merge_custom($out, $in) {
        foreach ($in as $item) {
            array_push($out, $item);
        }
        return $out;
    }

    public static function play_round1(){

        $group_days = DaysList::getPhase();

        //group 1 and round 1
        // match 11
        $gr1 = self::get_round_grp1();
        $id = Auth::user()->id;
        $id_t111 = $gr1[0][1];
        $but111 = self::but($id_t111);
        $name111 = self::get_team_details($id_t111);
        self::players_goals($but111, $id_t111, 2);

        $id_t112 = $gr1[0][2];
        $but112 = self::but($id_t112);
        $name112 = self::get_team_details($id_t112);
        self::players_goals($but112, $id_t112, 2);

        $players_1 = session('ply_ids_2_'.$id_t111);
        $pass_ids_1 = session('pass_ids_2_'.$id_t111);
        $times_1 = session('times_2_'.$id_t111);
        $players_2 = session('ply_ids_2_'.$id_t112);
        $pass_ids_2 = session('pass_ids_2_'.$id_t112);
        $times_2 = session('times_2_'.$id_t112);

        // save match
        self::save_match_infos(0,0,$id_t111,$id_t112,$name111[0]->GRP_ID,$group_days[DaysList::$DAY_ONE]['DAY'],$name111[0]->TEAM_NAME." ".$but111." - ".$but112." ".$name112[0]->TEAM_NAME,$id,CanPhase::$GROUP_PHASE,DaysList::$DAY_ONE);
        self::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_t111));
        self::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($id_t112));
        //update TEAM_STA
        self::match_decision($id_t111,$but111,$id_t112,$but112);

        //match 12
        $id_t121 = $gr1[1][1];
        $but121 = self::but($id_t121);
        $name121 = self::get_team_details($id_t121);
        self::players_goals($but121, $id_t121, 2);

        $id_t122 = $gr1[1][2];
        $but122 = self::but($id_t122);
        $name122 = self::get_team_details($id_t122);
        self::players_goals($but122, $id_t122, 2);

        $players_1 = session('ply_ids_2_'.$id_t121);
        $pass_ids_1 = session('pass_ids_2_'.$id_t121);
        $times_1 = session('times_2_'.$id_t121);
        $players_2 = session('ply_ids_2_'.$id_t122);
        $pass_ids_2 = session('pass_ids_2_'.$id_t122);
        $times_2 = session('times_2_'.$id_t122);

        // save match
        self::save_match_infos(0,0,$id_t121,$id_t122,$name121[0]->GRP_ID,$group_days[DaysList::$DAY_ONE]['DAY'],$name121[0]->TEAM_NAME." ".$but121." - ".$but122." ".$name122[0]->TEAM_NAME,$id,CanPhase::$GROUP_PHASE,DaysList::$DAY_ONE);
        self::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_t121));
        self::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($id_t122));
        self::match_decision($id_t121,$but121,$id_t122,$but122);


        //group 2 and round 1
        // match 21
        $gr2 = self::get_round_grp2();

        $id_t211 = $gr2[0][1];
        $but211 = self::but($id_t211);
        $name211 = self::get_team_details($id_t211);
        self::players_goals($but211, $id_t211, 2);

        $id_t212 = $gr2[0][2];
        $but212 = self::but($id_t212);
        $name212 = self::get_team_details($id_t212);
        self::players_goals($but212, $id_t212, 2);

        $players_1 = session('ply_ids_2_'.$id_t211);
        $pass_ids_1 = session('pass_ids_2_'.$id_t211);
        $times_1 = session('times_2_'.$id_t211);
        $players_2 = session('ply_ids_2_'.$id_t212);
        $pass_ids_2 = session('pass_ids_2_'.$id_t212);
        $times_2 = session('times_2_'.$id_t212);

        // save match
        self::save_match_infos(0,0,$id_t211,$id_t212,$name211[0]->GRP_ID,$group_days[DaysList::$DAY_ONE]['DAY'],$name211[0]->TEAM_NAME." ".$but211." - ".$but212." ".$name212[0]->TEAM_NAME,$id,CanPhase::$GROUP_PHASE,DaysList::$DAY_ONE);
        self::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_t121));
        self::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($id_t212));
        self::match_decision($id_t211,$but211,$id_t212,$but212);

        //match 22
        $id_t221 = $gr2[1][1];
        $but221 = self::but($id_t221);
        $name221 = self::get_team_details($id_t221);
        self::players_goals($but221, $id_t221, 2);

        $id_t222 = $gr2[1][2];
        $but222 = self::but($id_t222);
        $name222 = self::get_team_details($id_t222);
        self::players_goals($but222, $id_t222, 2);

        $players_1 = session('ply_ids_2_'.$id_t221);
        $pass_ids_1 = session('pass_ids_2_'.$id_t221);
        $times_1 = session('times_2_'.$id_t221);
        $players_2 = session('ply_ids_2_'.$id_t222);
        $pass_ids_2 = session('pass_ids_2_'.$id_t222);
        $times_2 = session('times_2_'.$id_t222);

        // save match
        self::save_match_infos(0,0,$id_t221,$id_t222,$name221[0]->GRP_ID,$group_days[DaysList::$DAY_ONE]['DAY'],$name221[0]->TEAM_NAME." ".$but221." - ".$but222." ".$name222[0]->TEAM_NAME,$id,CanPhase::$GROUP_PHASE,DaysList::$DAY_ONE);
        self::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_t221));
        self::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($id_t222));
        self::match_decision($id_t221,$but221,$id_t222,$but222);


        //group 3 and round 1
        // match 31
        $gr3 = self::get_round_grp3();

        $id_t311 = $gr3[0][1];
        $but311 = self::but($id_t311);
        $name311 = self::get_team_details($id_t311);
        self::players_goals($but311, $id_t311, 2);

        $id_t312 = $gr3[0][2];
        $but312 = self::but($id_t312);
        $name312 = self::get_team_details($id_t312);
        self::players_goals($but312, $id_t312, 2);

        $players_1 = session('ply_ids_2_'.$id_t311);
        $pass_ids_1 = session('pass_ids_2_'.$id_t311);
        $times_1 = session('times_2_'.$id_t311);
        $players_2 = session('ply_ids_2_'.$id_t312);
        $pass_ids_2 = session('pass_ids_2_'.$id_t312);
        $times_2 = session('times_2_'.$id_t312);

        // save match
        self::save_match_infos(0,0,$id_t311,$id_t312,$name311[0]->GRP_ID,$group_days[DaysList::$DAY_ONE]['DAY'],$name311[0]->TEAM_NAME." ".$but311." - ".$but312." ".$name312[0]->TEAM_NAME,$id,CanPhase::$GROUP_PHASE,DaysList::$DAY_ONE);
        self::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_t311));
        self::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($id_t312));
        self::match_decision($id_t311,$but311,$id_t312,$but312);

        //match 32
        $id_t321 = $gr3[1][1];
        $but321 = self::but($id_t321);
        $name321 = self::get_team_details($id_t321);
        self::players_goals($but321, $id_t321, 2);

        $id_t322 = $gr3[1][2];
        $but322 = self::but($id_t322);
        $name322 = self::get_team_details($id_t322);
        self::players_goals($but322, $id_t322, 2);

        $players_1 = session('ply_ids_2_'.$id_t321);
        $pass_ids_1 = session('pass_ids_2_'.$id_t321);
        $times_1 = session('times_2_'.$id_t321);
        $players_2 = session('ply_ids_2_'.$id_t322);
        $pass_ids_2 = session('pass_ids_2_'.$id_t322);
        $times_2 = session('times_2_'.$id_t322);

        // save match
        self::save_match_infos(0,0,$id_t321,$id_t322,$name321[0]->GRP_ID,$group_days[DaysList::$DAY_ONE]['DAY'],$name321[0]->TEAM_NAME." ".$but321." - ".$but322." ".$name322[0]->TEAM_NAME,$id,CanPhase::$GROUP_PHASE,DaysList::$DAY_ONE);
        self::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_t321));
        self::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($id_t322));
        self::match_decision($id_t321,$but321,$id_t322,$but322);


        //group 4 and round 1
        // match 41
        $gr4 = self::get_round_grp4();

        $id_t411 = $gr4[0][1];
        $but411 = self::but($id_t411);
        $name411 = self::get_team_details($id_t411);
        self::players_goals($but411, $id_t411, 2);

        $id_t412 = $gr4[0][2];
        $but412 = self::but($id_t412);
        $name412 = self::get_team_details($id_t412);
        self::players_goals($but412, $id_t412, 2);

        $players_1 = session('ply_ids_2_'.$id_t411);
        $pass_ids_1 = session('pass_ids_2_'.$id_t411);
        $times_1 = session('times_2_'.$id_t411);
        $players_2 = session('ply_ids_2_'.$id_t412);
        $pass_ids_2 = session('pass_ids_2_'.$id_t412);
        $times_2 = session('times_2_'.$id_t412);

        // save match
        self::save_match_infos(0,0,$id_t411,$id_t412,$name411[0]->GRP_ID,$group_days[DaysList::$DAY_ONE]['DAY'],$name411[0]->TEAM_NAME." ".$but411." - ".$but412." ".$name412[0]->TEAM_NAME,$id,CanPhase::$GROUP_PHASE,DaysList::$DAY_ONE);
        self::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_t411));
        self::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($id_t412));
        self::match_decision($id_t411,$but411,$id_t412,$but412);

        //match 42
        $id_t421 = $gr4[1][1];
        $but421 = self::but($id_t421);
        $name421 = self::get_team_details($id_t421);
        self::players_goals($but421, $id_t421, 2);

        $id_t422 = $gr4[1][2];
        $but422 = self::but($id_t422);
        $name422 = self::get_team_details($id_t422);
        self::players_goals($but422, $id_t422, 2);

        $players_1 = session('ply_ids_2_'.$id_t421);
        $pass_ids_1 = session('pass_ids_2_'.$id_t421);
        $times_1 = session('times_2_'.$id_t421);
        $players_2 = session('ply_ids_2_'.$id_t422);
        $pass_ids_2 = session('pass_ids_2_'.$id_t422);
        $times_2 = session('times_2_'.$id_t422);

        // save match
        self::save_match_infos(0,0,$id_t421,$id_t422,$name421[0]->GRP_ID,$group_days[DaysList::$DAY_ONE]['DAY'],$name421[0]->TEAM_NAME." ".$but421." - ".$but422." ".$name422[0]->TEAM_NAME,$id,CanPhase::$GROUP_PHASE,DaysList::$DAY_ONE);
        self::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_t421));
        self::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($id_t422));
        self::match_decision($id_t421,$but421,$id_t422,$but422);


        //group 5 and round 1
        // match 51
        $gr5 = self::get_round_grp5();

        $id_t511 = $gr5[0][1];
        $but511 = self::but($id_t511);
        $name511 = self::get_team_details($id_t511);
        self::players_goals($but511, $id_t511, 2);

        $id_t512 = $gr5[0][2];
        $but512 = self::but($id_t512);
        $name512 = self::get_team_details($id_t512);
        self::players_goals($but512, $id_t512, 2);

        $players_1 = session('ply_ids_2_'.$id_t511);
        $pass_ids_1 = session('pass_ids_2_'.$id_t511);
        $times_1 = session('times_2_'.$id_t511);
        $players_2 = session('ply_ids_2_'.$id_t512);
        $pass_ids_2 = session('pass_ids_2_'.$id_t512);
        $times_2 = session('times_2_'.$id_t512);

        // save match
        self::save_match_infos(0,0,$id_t511,$id_t512,$name511[0]->GRP_ID,$group_days[DaysList::$DAY_ONE]['DAY'],$name511[0]->TEAM_NAME." ".$but511." - ".$but512." ".$name512[0]->TEAM_NAME,$id,CanPhase::$GROUP_PHASE,DaysList::$DAY_ONE);
        self::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_t511));
        self::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($id_t512));
        self::match_decision($id_t511,$but511,$id_t512,$but512);

        //match 52
        $id_t521 = $gr5[1][1];
        $but521 = self::but($id_t521);
        $name521 = self::get_team_details($id_t521);
        self::players_goals($but521, $id_t521, 2);

        $id_t522 = $gr5[1][2];
        $but522 = self::but($id_t522);
        $name522 = self::get_team_details($id_t522);
        self::players_goals($but522, $id_t522, 2);

        $players_1 = session('ply_ids_2_'.$id_t521);
        $pass_ids_1 = session('pass_ids_2_'.$id_t521);
        $times_1 = session('times_2_'.$id_t521);
        $players_2 = session('ply_ids_2_'.$id_t522);
        $pass_ids_2 = session('pass_ids_2_'.$id_t522);
        $times_2 = session('times_2_'.$id_t522);

        // save match
        self::save_match_infos(0,0,$id_t521,$id_t522,$name521[0]->GRP_ID,$group_days[DaysList::$DAY_ONE]['DAY'],$name521[0]->TEAM_NAME." ".$but521." - ".$but522." ".$name522[0]->TEAM_NAME,$id,CanPhase::$GROUP_PHASE,DaysList::$DAY_ONE);
        self::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_t521));
        self::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($id_t522));
        self::match_decision($id_t521,$but521,$id_t522,$but522);
    }


    public static function play_round2(){

        $group_days = DaysList::getPhase();

        //group 1 and round 2
        // match 12
        $gr1 = self::get_round_grp1();
        $id = Auth::user()->id;
        $id_t131 = $gr1[2][1];
        $but131 = self::but($id_t131);
        $name131 = self::get_team_details($id_t131);
        self::players_goals($but131, $id_t131, 2);

        $id_t132 = $gr1[2][2];
        $but132 = self::but($id_t132);
        $name132 = self::get_team_details($id_t132);
        self::players_goals($but132, $id_t132, 2);

        $players_1 = session('ply_ids_2_'.$id_t131);
        $pass_ids_1 = session('pass_ids_2_'.$id_t131);
        $times_1 = session('times_2_'.$id_t131);
        $players_2 = session('ply_ids_2_'.$id_t132);
        $pass_ids_2 = session('pass_ids_2_'.$id_t132);
        $times_2 = session('times_2_'.$id_t132);

        // save match
        self::save_match_infos(0,0,$id_t131,$id_t132,$name131[0]->GRP_ID,$group_days[DaysList::$DAY_TWO]['DAY'],$name131[0]->TEAM_NAME." ".$but131." - ".$but132." ".$name132[0]->TEAM_NAME,$id,CanPhase::$GROUP_PHASE,DaysList::$DAY_TWO);
        self::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_t131));
        self::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($id_t132));
        self::match_decision($id_t131,$but131,$id_t132,$but132);

        //match 12
        $id_t141 = $gr1[3][1];
        $but141 = self::but($id_t141);
        $name141 = self::get_team_details($id_t141);
        self::players_goals($but141, $id_t141, 2);

        $id_t142 = $gr1[3][2];
        $but142 = self::but($id_t142);
        $name142 = self::get_team_details($id_t142);
        self::players_goals($but142, $id_t142, 2);

        $players_1 = session('ply_ids_2_'.$id_t141);
        $pass_ids_1 = session('pass_ids_2_'.$id_t141);
        $times_1 = session('times_2_'.$id_t141);
        $players_2 = session('ply_ids_2_'.$id_t142);
        $pass_ids_2 = session('pass_ids_2_'.$id_t142);
        $times_2 = session('times_2_'.$id_t142);

        // save match
        self::save_match_infos(0,0,$id_t141,$id_t142,$name141[0]->GRP_ID,$group_days[DaysList::$DAY_TWO]['DAY'],$name141[0]->TEAM_NAME." ".$but141." - ".$but142." ".$name142[0]->TEAM_NAME,$id,CanPhase::$GROUP_PHASE,DaysList::$DAY_TWO);
        self::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_t141));
        self::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($id_t142));
        self::match_decision($id_t141,$but141,$id_t142,$but142);


        //group 2 and round 2
        // match 22
        $gr2 = self::get_round_grp2();

        $id_t231 = $gr2[2][1];
        $but231 = self::but($id_t231);
        $name231 = self::get_team_details($id_t231);
        self::players_goals($but231, $id_t231, 2);

        $id_t232 = $gr2[2][2];
        $but232 = self::but($id_t232);
        $name232 = self::get_team_details($id_t232);
        self::players_goals($but232, $id_t232, 2);

        $players_1 = session('ply_ids_2_'.$id_t231);
        $pass_ids_1 = session('pass_ids_2_'.$id_t231);
        $times_1 = session('times_2_'.$id_t231);
        $players_2 = session('ply_ids_2_'.$id_t232);
        $pass_ids_2 = session('pass_ids_2_'.$id_t232);
        $times_2 = session('times_2_'.$id_t232);

        // save match
        self::save_match_infos(0,0,$id_t231,$id_t232,$name231[0]->GRP_ID,$group_days[DaysList::$DAY_TWO]['DAY'],$name231[0]->TEAM_NAME." ".$but231." - ".$but232." ".$name232[0]->TEAM_NAME,$id,CanPhase::$GROUP_PHASE,DaysList::$DAY_TWO);
        self::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_t141));
        self::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($id_t142));
        self::match_decision($id_t231,$but231,$id_t232,$but232);

        //match 22
        $id_t241 = $gr2[3][1];
        $but241 = self::but($id_t241);
        $name241 = self::get_team_details($id_t241);
        self::players_goals($but241, $id_t241, 2);

        $id_t242 = $gr2[3][2];
        $but242 = self::but($id_t242);
        $name242 = self::get_team_details($id_t242);
        self::players_goals($but242, $id_t242, 2);

        $players_1 = session('ply_ids_2_'.$id_t241);
        $pass_ids_1 = session('pass_ids_2_'.$id_t241);
        $times_1 = session('times_2_'.$id_t241);
        $players_2 = session('ply_ids_2_'.$id_t242);
        $pass_ids_2 = session('pass_ids_2_'.$id_t242);
        $times_2 = session('times_2_'.$id_t242);

        // save match
        self::save_match_infos(0,0,$id_t241,$id_t242,$name241[0]->GRP_ID,$group_days[DaysList::$DAY_TWO]['DAY'],$name241[0]->TEAM_NAME." ".$but241." - ".$but242." ".$name242[0]->TEAM_NAME,$id,CanPhase::$GROUP_PHASE,DaysList::$DAY_TWO);
        self::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_t241));
        self::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($id_t242));
        self::match_decision($id_t241,$but241,$id_t242,$but242);


        //group 3 and round 1
        // match 31
        $gr3 = self::get_round_grp3();

        $id_t331 = $gr3[2][1];
        $but331 = self::but($id_t331);
        $name331 = self::get_team_details($id_t331);
        self::players_goals($but331, $id_t331, 2);

        $id_t332 = $gr3[2][2];
        $but332 = self::but($id_t332);
        $name332 = self::get_team_details($id_t332);
        self::players_goals($but332, $id_t332, 2);

        $players_1 = session('ply_ids_2_'.$id_t331);
        $pass_ids_1 = session('pass_ids_2_'.$id_t331);
        $times_1 = session('times_2_'.$id_t331);
        $players_2 = session('ply_ids_2_'.$id_t332);
        $pass_ids_2 = session('pass_ids_2_'.$id_t332);
        $times_2 = session('times_2_'.$id_t332);

        // save match
        self::save_match_infos(0,0,$id_t331,$id_t332,$name331[0]->GRP_ID,$group_days[DaysList::$DAY_TWO]['DAY'],$name331[0]->TEAM_NAME." ".$but331." - ".$but332." ".$name332[0]->TEAM_NAME,$id,CanPhase::$GROUP_PHASE,DaysList::$DAY_TWO);
        self::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_t331));
        self::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($id_t332));
        self::match_decision($id_t331,$but331,$id_t332,$but332);

        //match 32
        $id_t341 = $gr3[3][1];
        $but341 = self::but($id_t341);
        $name341 = self::get_team_details($id_t341);
        self::players_goals($but341, $id_t341, 2);

        $id_t342 = $gr3[3][2];
        $but342 = self::but($id_t342);
        $name342 = self::get_team_details($id_t342);
        self::players_goals($but342, $id_t342, 2);

        $players_1 = session('ply_ids_2_'.$id_t341);
        $pass_ids_1 = session('pass_ids_2_'.$id_t341);
        $times_1 = session('times_2_'.$id_t341);
        $players_2 = session('ply_ids_2_'.$id_t342);
        $pass_ids_2 = session('pass_ids_2_'.$id_t342);
        $times_2 = session('times_2_'.$id_t342);

        // save match
        self::save_match_infos(0,0,$id_t341,$id_t342,$name341[0]->GRP_ID,$group_days[DaysList::$DAY_TWO]['DAY'],$name341[0]->TEAM_NAME." ".$but341." - ".$but342." ".$name342[0]->TEAM_NAME,$id,CanPhase::$GROUP_PHASE,DaysList::$DAY_TWO);
        self::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_t341));
        self::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($id_t342));
        self::match_decision($id_t341,$but341,$id_t342,$but342);


        //group 4 and round 2
        // match 42
        $gr4 = self::get_round_grp4();

        $id_t431 = $gr4[2][1];
        $but431 = self::but($id_t431);
        $name431 = self::get_team_details($id_t431);
        self::players_goals($but431, $id_t431, 2);

        $id_t432 = $gr4[2][2];
        $but432 = self::but($id_t432);
        $name432 = self::get_team_details($id_t432);
        self::players_goals($but432, $id_t432, 2);

        $players_1 = session('ply_ids_2_'.$id_t431);
        $pass_ids_1 = session('pass_ids_2_'.$id_t431);
        $times_1 = session('times_2_'.$id_t431);
        $players_2 = session('ply_ids_2_'.$id_t432);
        $pass_ids_2 = session('pass_ids_2_'.$id_t432);
        $times_2 = session('times_2_'.$id_t432);

        // save match
        self::save_match_infos(0,0,$id_t431,$id_t432,$name431[0]->GRP_ID,$group_days[DaysList::$DAY_TWO]['DAY'],$name431[0]->TEAM_NAME." ".$but431." - ".$but432." ".$name432[0]->TEAM_NAME,$id,CanPhase::$GROUP_PHASE,DaysList::$DAY_TWO);
        self::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_t431));
        self::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($id_t432));
        self::match_decision($id_t431,$but431,$id_t432,$but432);

        //match 42
        $id_t441 = $gr4[3][1];
        $but441 = self::but($id_t441);
        $name441 = self::get_team_details($id_t441);
        self::players_goals($but441, $id_t441, 2);

        $id_t442 = $gr4[3][2];
        $but442 = self::but($id_t442);
        $name442 = self::get_team_details($id_t442);
        self::players_goals($but442, $id_t442, 2);

        $players_1 = session('ply_ids_2_'.$id_t441);
        $pass_ids_1 = session('pass_ids_2_'.$id_t441);
        $times_1 = session('times_2_'.$id_t441);
        $players_2 = session('ply_ids_2_'.$id_t442);
        $pass_ids_2 = session('pass_ids_2_'.$id_t442);
        $times_2 = session('times_2_'.$id_t442);

        // save match
        self::save_match_infos(0,0,$id_t441,$id_t442,$name441[0]->GRP_ID,$group_days[DaysList::$DAY_TWO]['DAY'],$name441[0]->TEAM_NAME." ".$but441." - ".$but442." ".$name442[0]->TEAM_NAME,$id,CanPhase::$GROUP_PHASE,DaysList::$DAY_TWO);
        self::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_t441));
        self::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($id_t442));
        self::match_decision($id_t441,$but441,$id_t442,$but442);


        //group 5 and round 1
        // match 51
        $gr5 = self::get_round_grp5();

        $id_t551 = $gr5[2][1];
        $but551 = self::but($id_t551);
        $name551 = self::get_team_details($id_t551);
        self::players_goals($but551, $id_t551, 2);

        $id_t552 = $gr5[2][2];
        $but552 = self::but($id_t552);
        $name552 = self::get_team_details($id_t552);
        self::players_goals($but552, $id_t552, 2);

        $players_1 = session('ply_ids_2_'.$id_t551);
        $pass_ids_1 = session('pass_ids_2_'.$id_t551);
        $times_1 = session('times_2_'.$id_t551);
        $players_2 = session('ply_ids_2_'.$id_t552);
        $pass_ids_2 = session('pass_ids_2_'.$id_t552);
        $times_2 = session('times_2_'.$id_t552);

        // save match
        self::save_match_infos(0,0,$id_t551,$id_t552,$name551[0]->GRP_ID,$group_days[DaysList::$DAY_TWO]['DAY'],$name551[0]->TEAM_NAME." ".$but551." - ".$but552." ".$name552[0]->TEAM_NAME,$id,CanPhase::$GROUP_PHASE,DaysList::$DAY_TWO);
        self::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_t551));
        self::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($id_t552));
        self::match_decision($id_t551,$but551,$id_t552,$but552);

        //match 52
        $id_t561 = $gr5[3][1];
        $but561 = self::but($id_t561);
        $name561 = self::get_team_details($id_t561);
        self::players_goals($but561, $id_t561, 2);

        $id_t562 = $gr5[3][2];
        $but562 = self::but($id_t562);
        $name562 = self::get_team_details($id_t562);
        self::players_goals($but562, $id_t562, 2);

        $players_1 = session('ply_ids_2_'.$id_t561);
        $pass_ids_1 = session('pass_ids_2_'.$id_t561);
        $times_1 = session('times_2_'.$id_t561);
        $players_2 = session('ply_ids_2_'.$id_t562);
        $pass_ids_2 = session('pass_ids_2_'.$id_t562);
        $times_2 = session('times_2_'.$id_t562);

        // save match
        self::save_match_infos(0,0,$id_t561,$id_t562,$name561[0]->GRP_ID,$group_days[DaysList::$DAY_TWO]['DAY'],$name561[0]->TEAM_NAME." ".$but561." - ".$but562." ".$name562[0]->TEAM_NAME,$id,CanPhase::$GROUP_PHASE,DaysList::$DAY_TWO);
        self::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_t561));
        self::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($id_t562));
        self::match_decision($id_t561,$but561,$id_t562,$but562);
    }



    public static function play_round3(){

        $group_days = DaysList::getPhase();

        //group 1 and round 3
        // match 31
        $gr1 = self::get_round_grp1();
        $id = Auth::user()->id;
        $id_t151 = $gr1[4][1];
        $but151 = self::but($id_t151);
        $name151 = self::get_team_details($id_t151);
        self::players_goals($but151, $id_t151, 2);

        $id_t152 = $gr1[4][2];
        $but152 = self::but($id_t152);
        $name152 = self::get_team_details($id_t152);
        self::players_goals($but152, $id_t152, 2);

        $players_1 = session('ply_ids_2_'.$id_t151);
        $pass_ids_1 = session('pass_ids_2_'.$id_t151);
        $times_1 = session('times_2_'.$id_t151);
        $players_2 = session('ply_ids_2_'.$id_t152);
        $pass_ids_2 = session('pass_ids_2_'.$id_t152);
        $times_2 = session('times_2_'.$id_t152);

        // save match
        self::save_match_infos(0,0,$id_t151,$id_t152,$name151[0]->GRP_ID,$group_days[DaysList::$DAY_THREE]['DAY'],$name151[0]->TEAM_NAME." ".$but151." - ".$but152." ".$name152[0]->TEAM_NAME,$id,CanPhase::$GROUP_PHASE,DaysList::$DAY_THREE);
        self::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_t151));
        self::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($id_t152));
        self::match_decision($id_t151,$but151,$id_t152,$but152);

        //match 32
        $id_t161 = $gr1[5][1];
        $but161 = self::but($id_t161);
        $name161 = self::get_team_details($id_t161);
        self::players_goals($but161, $id_t161, 2);

        $id_t162 = $gr1[5][2];
        $but162 = self::but($id_t162);
        $name162 = self::get_team_details($id_t162);
        self::players_goals($but162, $id_t162, 2);

        $players_1 = session('ply_ids_2_'.$id_t161);
        $pass_ids_1 = session('pass_ids_2_'.$id_t161);
        $times_1 = session('times_2_'.$id_t161);
        $players_2 = session('ply_ids_2_'.$id_t162);
        $pass_ids_2 = session('pass_ids_2_'.$id_t162);
        $times_2 = session('times_2_'.$id_t162);

        // save match
        self::save_match_infos(0,0,$id_t161,$id_t162,$name161[0]->GRP_ID,$group_days[DaysList::$DAY_THREE]['DAY'],$name161[0]->TEAM_NAME." ".$but161." - ".$but162." ".$name162[0]->TEAM_NAME,$id,CanPhase::$GROUP_PHASE,DaysList::$DAY_THREE);
        self::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_t161));
        self::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($id_t162));
        self::match_decision($id_t161,$but161,$id_t162,$but162);


        //group 2 and round 3
        // match 22
        $gr2 = self::get_round_grp2();

        $id_t251 = $gr2[4][1];
        $but251 = self::but($id_t251);
        $name251 = self::get_team_details($id_t251);
        self::players_goals($but251, $id_t251, 2);

        $id_t252 = $gr2[4][2];
        $but252 = self::but($id_t252);
        $name252 = self::get_team_details($id_t252);
        self::players_goals($but252, $id_t252, 2);

        $players_1 = session('ply_ids_2_'.$id_t251);
        $pass_ids_1 = session('pass_ids_2_'.$id_t251);
        $times_1 = session('times_2_'.$id_t251);
        $players_2 = session('ply_ids_2_'.$id_t252);
        $pass_ids_2 = session('pass_ids_2_'.$id_t252);
        $times_2 = session('times_2_'.$id_t252);

        // save match
        self::save_match_infos(0,0,$id_t251,$id_t252,$name251[0]->GRP_ID,$group_days[DaysList::$DAY_THREE]['DAY'],$name251[0]->TEAM_NAME." ".$but251." - ".$but252." ".$name252[0]->TEAM_NAME,$id,CanPhase::$GROUP_PHASE,DaysList::$DAY_THREE);
        self::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_t251));
        self::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($id_t252));
        self::match_decision($id_t251,$but251,$id_t252,$but252);

        //match 22
        $id_t261 = $gr2[5][1];
        $but261 = self::but($id_t261);
        $name261 = self::get_team_details($id_t261);
        self::players_goals($but261, $id_t261, 2);

        $id_t262 = $gr2[5][2];
        $but262 = self::but($id_t262);
        $name262 = self::get_team_details($id_t262);
        self::players_goals($but262, $id_t262, 2);

        $players_1 = session('ply_ids_2_'.$id_t261);
        $pass_ids_1 = session('pass_ids_2_'.$id_t261);
        $times_1 = session('times_2_'.$id_t261);
        $players_2 = session('ply_ids_2_'.$id_t262);
        $pass_ids_2 = session('pass_ids_2_'.$id_t262);
        $times_2 = session('times_2_'.$id_t262);

        // save match
        self::save_match_infos(0,0,$id_t261,$id_t262,$name261[0]->GRP_ID,$group_days[DaysList::$DAY_THREE]['DAY'],$name261[0]->TEAM_NAME." ".$but261." - ".$but262." ".$name262[0]->TEAM_NAME,$id,CanPhase::$GROUP_PHASE,DaysList::$DAY_THREE);
        self::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_t261));
        self::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($id_t262));
        self::match_decision($id_t261,$but261,$id_t262,$but262);


        //group 3 and round 3
        // match 33
        $gr3 = self::get_round_grp3();

        $id_t351 = $gr3[4][1];
        $but351 = self::but($id_t351);
        $name351 = self::get_team_details($id_t351);
        self::players_goals($but351, $id_t351, 2);

        $id_t352 = $gr3[4][2];
        $but352 = self::but($id_t352);
        $name352 = self::get_team_details($id_t352);
        self::players_goals($but352, $id_t352, 2);

        $players_1 = session('ply_ids_2_'.$id_t351);
        $pass_ids_1 = session('pass_ids_2_'.$id_t351);
        $times_1 = session('times_2_'.$id_t351);
        $players_2 = session('ply_ids_2_'.$id_t352);
        $pass_ids_2 = session('pass_ids_2_'.$id_t352);
        $times_2 = session('times_2_'.$id_t352);

        // save match
        self::save_match_infos(0,0,$id_t351,$id_t352,$name351[0]->GRP_ID,$group_days[DaysList::$DAY_THREE]['DAY'],$name351[0]->TEAM_NAME." ".$but351." - ".$but352." ".$name352[0]->TEAM_NAME,$id,CanPhase::$GROUP_PHASE,DaysList::$DAY_THREE);
        self::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_t351));
        self::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($id_t352));
        self::match_decision($id_t351,$but351,$id_t352,$but352);

        //match 32
        $id_t361 = $gr3[5][1];
        $but361 = self::but($id_t361);
        $name361 = self::get_team_details($id_t361);
        self::players_goals($but361, $id_t361, 2);

        $id_t362 = $gr3[5][2];
        $but362 = self::but($id_t362);
        $name362 = self::get_team_details($id_t362);
        self::players_goals($but362, $id_t362, 2);

        $players_1 = session('ply_ids_2_'.$id_t361);
        $pass_ids_1 = session('pass_ids_2_'.$id_t361);
        $times_1 = session('times_2_'.$id_t361);
        $players_2 = session('ply_ids_2_'.$id_t362);
        $pass_ids_2 = session('pass_ids_2_'.$id_t362);
        $times_2 = session('times_2_'.$id_t362);

        // save match
        self::save_match_infos(0,0,$id_t361,$id_t362,$name361[0]->GRP_ID,$group_days[DaysList::$DAY_THREE]['DAY'],$name361[0]->TEAM_NAME." ".$but361." - ".$but362." ".$name362[0]->TEAM_NAME,$id,CanPhase::$GROUP_PHASE,DaysList::$DAY_THREE);
        self::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_t361));
        self::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($id_t362));
        self::match_decision($id_t361,$but361,$id_t362,$but362);


        //group 4 and round 2
        // match 42
        $gr4 = self::get_round_grp4();

        $id_t451 = $gr4[4][1];
        $but451 = self::but($id_t451);
        $name451 = self::get_team_details($id_t451);
        self::players_goals($but451, $id_t451, 2);

        $id_t452 = $gr4[4][2];
        $but452 = self::but($id_t452);
        $name452 = self::get_team_details($id_t452);
        self::players_goals($but452, $id_t452, 2);

        $players_1 = session('ply_ids_2_'.$id_t451);
        $pass_ids_1 = session('pass_ids_2_'.$id_t451);
        $times_1 = session('times_2_'.$id_t451);
        $players_2 = session('ply_ids_2_'.$id_t452);
        $pass_ids_2 = session('pass_ids_2_'.$id_t452);
        $times_2 = session('times_2_'.$id_t452);

        // save match
        self::save_match_infos(0,0,$id_t451,$id_t452,$name451[0]->GRP_ID,$group_days[DaysList::$DAY_THREE]['DAY'],$name451[0]->TEAM_NAME." ".$but451." - ".$but452." ".$name452[0]->TEAM_NAME,$id,CanPhase::$GROUP_PHASE,DaysList::$DAY_THREE);
        self::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_t451));
        self::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($id_t452));
        self::match_decision($id_t451,$but451,$id_t452,$but452);

        //match 42
        $id_t461 = $gr4[5][1];
        $but461 = self::but($id_t461);
        $name461 = self::get_team_details($id_t461);
        self::players_goals($but461, $id_t461, 2);

        $id_t462 = $gr4[5][2];
        $but462 = self::but($id_t462);
        $name462 = self::get_team_details($id_t462);
        self::players_goals($but462, $id_t462, 2);

        $players_1 = session('ply_ids_2_'.$id_t461);
        $pass_ids_1 = session('pass_ids_2_'.$id_t461);
        $times_1 = session('times_2_'.$id_t461);
        $players_2 = session('ply_ids_2_'.$id_t462);
        $pass_ids_2 = session('pass_ids_2_'.$id_t462);
        $times_2 = session('times_2_'.$id_t462);

        // save match
        self::save_match_infos(0,0,$id_t461,$id_t462,$name461[0]->GRP_ID,$group_days[DaysList::$DAY_THREE]['DAY'],$name461[0]->TEAM_NAME." ".$but461." - ".$but462." ".$name462[0]->TEAM_NAME,$id,CanPhase::$GROUP_PHASE,DaysList::$DAY_THREE);
        self::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_t461));
        self::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($id_t462));
        self::match_decision($id_t461,$but461,$id_t462,$but462);


        //group 5 and round 3
        // match 53
        $gr5 = self::get_round_grp5();

        $id_t551 = $gr5[4][1];
        $but551 = self::but($id_t551);
        $name551 = self::get_team_details($id_t551);
        self::players_goals($but551, $id_t551, 2);

        $id_t552 = $gr5[4][2];
        $but552 = self::but($id_t552);
        $name552 = self::get_team_details($id_t552);
        self::players_goals($but552, $id_t552, 2);

        $players_1 = session('ply_ids_2_'.$id_t551);
        $pass_ids_1 = session('pass_ids_2_'.$id_t551);
        $times_1 = session('times_2_'.$id_t551);
        $players_2 = session('ply_ids_2_'.$id_t552);
        $pass_ids_2 = session('pass_ids_2_'.$id_t552);
        $times_2 = session('times_2_'.$id_t552);

        // save match
        self::save_match_infos(0,0,$id_t551,$id_t552,$name551[0]->GRP_ID,$group_days[DaysList::$DAY_THREE]['DAY'],$name551[0]->TEAM_NAME." ".$but551." - ".$but552." ".$name552[0]->TEAM_NAME,$id,CanPhase::$GROUP_PHASE,DaysList::$DAY_THREE);
        self::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_t551));
        self::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($id_t552));
        self::match_decision($id_t551,$but551,$id_t552,$but552);

        //match 52
        $id_t561 = $gr5[5][1];
        $but561 = self::but($id_t561);
        $name561 = self::get_team_details($id_t561);
        self::players_goals($but561, $id_t561, 2);

        $id_t562 = $gr5[5][2];
        $but562 = self::but($id_t562);
        $name562 = self::get_team_details($id_t562);
        self::players_goals($but562, $id_t562, 2);

        $players_1 = session('ply_ids_2_'.$id_t561);
        $pass_ids_1 = session('pass_ids_2_'.$id_t561);
        $times_1 = session('times_2_'.$id_t561);
        $players_2 = session('ply_ids_2_'.$id_t562);
        $pass_ids_2 = session('pass_ids_2_'.$id_t562);
        $times_2 = session('times_2_'.$id_t562);

        // save match
        self::save_match_infos(0,0,$id_t561,$id_t562,$name561[0]->GRP_ID,$group_days[DaysList::$DAY_THREE]['DAY'],$name561[0]->TEAM_NAME." ".$but561." - ".$but562." ".$name562[0]->TEAM_NAME,$id,CanPhase::$GROUP_PHASE,DaysList::$DAY_THREE);
        self::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_t561));
        self::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($id_t562));
        self::match_decision($id_t561,$but561,$id_t562,$but562);
    }

}

$utils = new Utils();
