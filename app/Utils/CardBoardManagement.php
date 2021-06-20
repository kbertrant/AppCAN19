<?php
/**
 * Created by PhpStorm.
 * User: emmaus
 * Date: 11/04/19
 * Time: 16:38
 */

namespace App\Utils;


use App\Utils\KeysFile\LineKeys;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CardBoardManagement
{

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

    public static function players_cards($team_id, $match_id, $half_time = 1){

        $res = array();
        $userId = Auth::user()->id;

        if ($half_time == 1) {

            $cards_nber = 0;

            $nb_ply_1 = 0;
            $nb_ply_2 = 0;
            $nb_ply_3 = 0;

            // -------------------------------------------- build players line ---------------------------------------------
            $players = DB::select("SELECT * FROM t_player
                                            INNER JOIN t_player_line ON t_player_line.LINE_ID = t_player.LINE_ID
                                            INNER JOIN (SELECT TAC_ID_POS, USER_ID, PLY_ID AS PL_ID FROM t_player_tac_pos) AS plyTacPos ON plyTacPos.PL_ID = t_player.PLY_ID AND plyTacPos.USER_ID = ".$userId."
                                            WHERE t_player.TEAM_ID = ".$team_id." AND (plyTacPos.PL_ID <> NULL OR plyTacPos.PL_ID <> '') ORDER BY t_player.LINE_ID ASC, PLY_NAME ASC");

            if (count($players) == 0) {
                $players = DB::select("SELECT * FROM t_player WHERE TEAM_ID = ".$team_id);
            }

            $players_1 = self::filter_by_line($players, LineKeys::$ATT);
            $players_2 = self::filter_by_line($players, LineKeys::$MID);
            $players_3 = self::filter_by_line($players, LineKeys::$DEF);
            usort($players_1, array(Utils::class,'sort_by_ply_val'));
            usort($players_2, array(Utils::class,'sort_by_ply_val'));
            usort($players_3, array(Utils::class,'sort_by_ply_val'));

            $tacId = DB::table('t_team_tac')->where('USER_ID', $userId)->value('TAC_ID');
            if ($tacId == 1) {
                $cards_nber = 4;
            } elseif ($tacId == 2) {
                $cards_nber = 5;
            } else {
                $cards_nber = 3;
            }

            // ---------------------------------------------- build cards line -----------------------------------------
            $rest = $cards_nber;
            if (count($players_3) > 0) {
                if (count($players_2) > 0 && count($players_1) > 0) {
                    $temp = $cards_nber * 0.5;
                    if ($temp > 1) {
                        $nb_ply_3 = round($temp);
                    } else {
                        $nb_ply_3 = ceil($temp);
                    }
                } elseif (count($players_2) > 0 || count($players_1) > 0) {
                    $temp = $cards_nber * 0.6;
                    if ($temp > 1) {
                        $nb_ply_3 = round($temp);
                    } else {
                        $nb_ply_3 = ceil($temp);
                    }
                } else {
                    $nb_ply_3 = $cards_nber;
                }
            }
            $rest -= $nb_ply_3;

            if (count($players_2) > 0 && $rest > 0) {
                if (count($players_1) > 0 && count($players_3) > 0) {
                    $temp = ($cards_nber - $nb_ply_3) * 0.6;
                    if ($temp > 1) {
                        $nb_ply_2 = round($temp);
                    } else {
                        $nb_ply_2 = ceil($temp);
                    }
                } elseif (count($players_1) > 0 || count($players_3) > 0) {
                    if (count($players_3) > 0) {
                        $nb_ply_2 = $cards_nber - $nb_ply_3;
                    } else {
                        $temp = $cards_nber * 0.6;
                        if ($temp > 1) {
                            $nb_ply_2 = round($temp);
                        } else {
                            $nb_ply_2 = ceil($temp);
                        }
                    }
                } else {
                    $nb_ply_2 = $cards_nber;
                }
            }
            $rest -= $nb_ply_2;

            if (count($players_1) > 0 && $rest > 0) {
                $nb_ply_1 = $rest;
            }

            // --------------------------------------------- save in database ----------------------------------------------
            $ply_cards = [];
            $ply_name_cards = [];
            $times = [];
            $red_cards = [];
            $isViewed = [];
            /*$half_nb = 0;
            if ($tacId == 1 || $tacId == 2) {
                $half_nb = rand(1, 2);
                for ($i = 0; $i < $cards_nber; $i++) {
                    if ($i < $half_nb) {
                        array_push($times, rand(0, 45));
                    } else {
                        array_push($times, rand(45, 90));
                    }
                    array_push($red_cards, 0);
                }
            } else {
                $half_nb = rand(0, 1);
                for ($i = 0; $i < $cards_nber; $i++) {
                    if ($i < $half_nb) {
                        array_push($times, rand(0, 45));
                    } else {
                        array_push($times, rand(45, 90));
                    }
                    array_push($red_cards, 0);
                }
            }*/
            for ($i = 0; $i < $cards_nber; $i++) {
                if ($tacId == 1 || $tacId == 2) {
                    if ($i < 2) {
                        array_push($times, rand(0, 45));
                    } else {
                        array_push($times, rand(45, 90));
                    }
                } else {
                    if ($i < 1) {
                        array_push($times, rand(0, 45));
                    } else {
                        array_push($times, rand(45, 90));
                    }
                }
                array_push($red_cards, 0);
                array_push($isViewed, rand(0, 100));
            }
            sort($times);

            if ($nb_ply_1 > 0) {
                for ($i = 0; $i < $nb_ply_1; $i++) {
                    $k = rand(0, count($players_1) - 1);           // $k = $i % count($players_1);
                    array_push($ply_cards, $players_1[$k]->PLY_ID);
                    array_push($ply_name_cards, $players_1[$k]->PLY_NAME);
                }
            }
            if ($nb_ply_2 > 0) {
                for ($i = 0; $i < $nb_ply_2; $i++) {
                    $k = rand(0, count($players_2) - 1);           // $k = $i % count($players_2);
                    array_push($ply_cards, $players_2[$k]->PLY_ID);
                    array_push($ply_name_cards, $players_2[$k]->PLY_NAME);
                }
            }
            if ($nb_ply_3 > 0) {
                for ($i = 0; $i < $nb_ply_3; $i++) {
                    $k = rand(0, count($players_3) - 1);           // $k = $i % count($players_3);
                    array_push($ply_cards, $players_3[$k]->PLY_ID);
                    array_push($ply_name_cards, $players_3[$k]->PLY_NAME);
                }
            }

            // Build red cards
            if (array_unique($ply_cards) !== $ply_cards) {
                for ($i = 0; $i < count($ply_cards); $i++) {
                    $keys = array_keys($ply_cards, $ply_cards[$i]);
                    if (count($keys) > 1) {
                        for ($j = 0; $j < count($keys); $j++) {
                            if ($j > 0 && $isViewed[$keys[$j]] <= 65) {
                                $red_cards[$keys[$j]] = 1;
                            }
                        }
                    }
                }
            }

            if ($tacId == 1 || $tacId == 2) {
                session(['card_ids_' . $half_time . '_' . $team_id => array_slice ($ply_cards, 0, 2)]);
                session(['ply_name_cards_' . $half_time . '_' . $team_id => array_slice ($ply_name_cards, 0, 2)]);
                session(['ply_time_cards_' . $half_time . '_' . $team_id => array_slice ($times, 0, 2)]);
                session(['red_cards_' . $half_time . '_' . $team_id => array_slice ($red_cards, 0, 2)]);
                session(['isViewed_' . $half_time . '_' . $team_id => array_slice ($isViewed, 0, 2)]);
            } else {
                session(['card_ids_' . $half_time . '_' . $team_id => array_slice ($ply_cards, 0, 1)]);
                session(['ply_name_cards_' . $half_time . '_' . $team_id => array_slice ($ply_name_cards, 0, 1)]);
                session(['ply_time_cards_' . $half_time . '_' . $team_id => array_slice ($times, 0, 1)]);
                session(['red_cards_' . $half_time . '_' . $team_id => array_slice ($red_cards, 0, 1)]);
                session(['isViewed_' . $half_time . '_' . $team_id => array_slice ($isViewed, 0, 1)]);
            }
            session(['card_ids_' . $team_id => $ply_cards]);
            session(['ply_name_cards_' . $team_id => $ply_name_cards]);
            session(['ply_time_cards_' . $team_id => $times]);
            session(['red_cards_' . $team_id => $red_cards]);
            session(['isViewed_' . $team_id => $isViewed]);
        } else {
            $ply_cards = session('card_ids_' . $team_id);
            $ply_name_cards = session('ply_name_cards_' . $team_id);
            $ply_time_cards = session('ply_time_cards_' . $team_id);
            $red_cards = session('red_cards_' . $team_id);
            $isViewed = session('isViewed_' . $team_id);

            for ($i = 0; $i < count($ply_cards); $i++) {
                if ($isViewed[$i] <= 65) {
                    DB::insert('INSERT INTO t_card (USER_ID, MATCH_ID, PLY_ID, CARD_TIME, updated_at) VALUES (?, ?, ?, ?, ?)',
                        array($userId, $match_id, $ply_cards[$i], $ply_time_cards[$i], date('Y-m-d H:i:s'))
                    );
                }
            }

            $res = array($ply_name_cards, $ply_time_cards, $red_cards, $isViewed);
        }

        return $res;
    }
}