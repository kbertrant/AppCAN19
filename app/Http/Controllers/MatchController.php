<?php

namespace App\Http\Controllers;

use App\Utils\CardBoardManagement;
use App\Utils\KeysFile\CountryName;
use App\Utils\KeysFile\CanPhase;
use App\Utils\EightFinalPhase;
use App\Utils\FinalPhase;
use App\Utils\KeysFile\DaysList;
use App\Utils\KeysFile\GroupPhase;
use App\Utils\KeysFile\Stadium;
use App\Utils\QuarterFinalPhase;
use App\Utils\SemiFinalPhase;
use App\User;
use View;
use App\Utils\Utils;
use App\helpers;
use App\Http\Controllers\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MatchController extends Controller
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


    static function sort_by_grp($a, $b)
    {
        return strcmp($a->GRP_NAME, $b->GRP_NAME);
    }

    public function match_result()
    {
        $userId = Auth::user()->id;
        $round = Auth::user()->round;

        $step = 0;

        if ($round <= 4) {
            $match_results = DB::select("SELECT USER_ID, match_can_phase, match_grp_phase, match_code, match_score, GRP_ID, GRP_NAME FROM t_match, t_group ".
                "WHERE t_match.MATCH_GRP = t_group.GRP_ID and USER_ID = " . $userId . " and match_can_phase = " . CanPhase::$GROUP_PHASE . " " .
                "GROUP BY match_can_phase, match_grp_phase, USER_ID, match_code, match_score, GRP_ID, GRP_NAME ORDER BY match_grp_phase ASC;");
            usort($match_results, array(self::class,'sort_by_grp'));
        } else {
            $match_results = DB::select("SELECT USER_ID, match_can_phase, match_grp_phase, match_code, match_score FROM t_match ".
                "WHERE USER_ID = " . $userId . " and match_can_phase <> ".CanPhase::$GROUP_PHASE." GROUP BY match_can_phase, match_grp_phase, USER_ID, match_code, match_score ORDER BY match_grp_phase ASC;");
            $step = 1;
        }

        $team = DB::table('t_team')->where('TEAM_ID', Auth::user()->TEAM_ID)->value('TEAM_NAME');

        $can_phase = CanPhase::getPhase();
        $days_phase = DaysList::getPhase();

        $display_data = array(
            $can_phase[CanPhase::$FINAL]['PHASE'] => array(),
            $can_phase[CanPhase::$THIRD_PLACE_FINAL]['PHASE'] => array(),
            $can_phase[CanPhase::$SEMI_FINAL]['PHASE'] => array(),
            $can_phase[CanPhase::$QUARTER_FINAL]['PHASE'] => array(),
            $can_phase[CanPhase::$EIGHT_FINAL]['PHASE'] => array(),
            $days_phase[DaysList::$DAY_THREE]['DAY'] => array(GroupPhase::$GROUP_A => [], GroupPhase::$GROUP_B => [], GroupPhase::$GROUP_C => [], GroupPhase::$GROUP_D => [], GroupPhase::$GROUP_E => [], GroupPhase::$GROUP_F => []),
            $days_phase[DaysList::$DAY_TWO]['DAY'] => array(GroupPhase::$GROUP_A => [], GroupPhase::$GROUP_B => [], GroupPhase::$GROUP_C => [], GroupPhase::$GROUP_D => [], GroupPhase::$GROUP_E => [], GroupPhase::$GROUP_F => []),
            $days_phase[DaysList::$DAY_ONE]['DAY'] => array(GroupPhase::$GROUP_A => [], GroupPhase::$GROUP_B => [], GroupPhase::$GROUP_C => [], GroupPhase::$GROUP_D => [], GroupPhase::$GROUP_E => [], GroupPhase::$GROUP_F => [])
        );

        foreach ($match_results as $match_result) {
            $matchCore = explode(" - ", $match_result->match_score);    // match_score
            $score1 = $matchCore[0];
            $score2 = $matchCore[1];
            $temp = explode(" ", $score1);
            $team1 = implode(" ", array_slice ($temp, 0, count($temp)-1));
            $but1 = $temp[count($temp)-1];
            $temp = explode(" ", $score2);
            $team2 = implode(" ", array_slice ($temp, 1, count($temp)-1));
            $but2 = $temp[0];

            if ($match_result->match_can_phase == CanPhase::$GROUP_PHASE) {
                $temp = array('T1'=> $team1, 'B1'=> $but1, 'T2'=> $team2, 'B2'=> $but2);
                array_push($display_data[$match_result->match_code][$match_result->GRP_NAME], $temp);
            } else {
                $temp = array('T1'=> $team1, 'B1'=> $but1, 'T2'=> $team2, 'B2'=> $but2);
                array_push($display_data[$can_phase[$match_result->match_can_phase]['PHASE']], $temp);
            }
        }

        return view('match.match_result',['display_data' => $display_data, 'step' => $step, 'team' => $team]);
    }

    public function match_load()
    {
        /** @var User $user */
        $user = Auth::user();
        return view('match.match_load');
    }





    public function next_match()
    {

        $can_phase = CanPhase::getPhase();
        $team_name = Utils::get_team_name();
        $rr = Utils::get_opponent();
        $grp_name = $rr[0]->GRP_NAME;
        //$grp_stadium = $rr[0]->GRP_STADIUM1;
        //dd($grp_stadium);
        $round = Auth::user()->round;

        if ($round == 1) {
            $id_team_group = Utils::get_opponent();
            $grp_stadium = $id_team_group[0]->GRP_STADIUM1;
            $team_away = Utils::get_team_details($id_team_group[0]->TEAM_ID);
        } elseif($round == 2) {
            $id_team_group = Utils::get_opponent();
            $grp_stadium = $id_team_group[0]->GRP_STADIUM2;
            $team_away = Utils::get_team_details($id_team_group[1]->TEAM_ID);

        } elseif($round == 3) {
            $id_team_group = Utils::get_opponent();
            $grp_stadium = $id_team_group[0]->GRP_STADIUM1;
            $team_away = Utils::get_team_details($id_team_group[2]->TEAM_ID);

        } elseif($round == 4) {     //qualified teams for eight stage
            if (EightFinalPhase::isQualified(Auth::user()->TEAM_ID)) {
                $phase = request('phase');
                if ($phase != null) {
                    $grp_stadium = Stadium::$GAROUA_STADIUM;
                    $grp_name = $can_phase[CanPhase::$EIGHT_FINAL]['PHASE'];
                    $team_adversary = EightFinalPhase::adversary(Auth::user()->TEAM_ID);
                    $team_away = [(object) [ 'TEAM_NAME'=> $team_adversary->TEAM_NAME ]];
                } else {
                    return view::make('team.eight_stage', ['list_teams' => EightFinalPhase::teams_qualified()]);
                }
            } else {
                return redirect()->route('player.player_loose');
            }
        } elseif($round == 5) {     //qualified teams for quarter stage
            if (QuarterFinalPhase::isQualified(Auth::user()->TEAM_ID)) {
                $phase = request('phase');
                if ($phase != null) {
                    $grp_stadium = Stadium::$BAFOUSSAM_STADIUM;
                    $grp_name = $can_phase[CanPhase::$QUARTER_FINAL]['PHASE'];
                    $team_adversary = QuarterFinalPhase::adversary(Auth::user()->TEAM_ID);
                    $team_away = [(object) [ 'TEAM_NAME'=> $team_adversary->TEAM_NAME ]];
                } else {
                    return view::make('team.quarter_stage', ['list_teams' => QuarterFinalPhase::teams_qualified()]);
                }
            } else {
                return redirect()->route('player.player_loose');
            }
        } elseif($round == 6) {     //qualified teams for semi stage
            if (SemiFinalPhase::isQualified(Auth::user()->TEAM_ID)) {
                $phase = request('phase');
                if ($phase != null) {
                    $grp_stadium = Stadium::$DOUALA_STADIUM;
                    $grp_name = $can_phase[CanPhase::$SEMI_FINAL]['PHASE'];
                    $team_adversary = SemiFinalPhase::adversary(Auth::user()->TEAM_ID);
                    $team_away = [(object) [ 'TEAM_NAME'=> $team_adversary->TEAM_NAME ]];
                } else {
                    return view::make('team.semi_stage', ['list_teams' => SemiFinalPhase::teams_qualified()]);
                }
            } else {
                return redirect()->route('player.player_loose');
            }
        } elseif($round == 7) {     //qualified teams for final stage
            if (FinalPhase::isQualified(Auth::user()->TEAM_ID)) {
                $phase = request('phase');
                if ($phase != null) {
                    $grp_stadium = Stadium::$YAOUNDE_STADIUM;
                    $grp_name = $can_phase[CanPhase::$FINAL]['PHASE'];
                    $team_adversary = FinalPhase::adversary(Auth::user()->TEAM_ID);
                    $team_away = [(object) [ 'TEAM_NAME'=> $team_adversary->TEAM_NAME ]];
                } else {
                    return view::make('team.final_stage', ['list_teams' => FinalPhase::teams_qualified()]);
                }
            } else {
                return redirect()->route('player.player_loose');
            }
        }else {
            if (FinalPhase::isWinner()) {
                return redirect()->route('match.match_load');
            } else {
                return redirect()->route('player.player_loose');
            }
        }

         /** @var User $user */
        $user = Auth::user();

        return view::make('match.next_match',['grp_stadium'=>$grp_stadium,'team_name'=>$team_name,'team_away'=>$team_away,'grp_name'=>$grp_name, 'countries'=>CountryName::getCountries()]);
    }


    public function match_process()
    {
        $can_phase = CanPhase::getPhase();
        $team_name_pro = Utils::get_team_name();
        $rr = Utils::get_opponent();
        $grp_name = $rr[0]->GRP_NAME;

        //--- $team_away_pro = [(object) [ 'TEAM_NAME'=> 'DR CONGO' ]];

        $round = Auth::user()->round;
        if($round == 1){
            $id_team_group = Utils::get_opponent();
            $grp_stadium = $id_team_group[0]->GRP_STADIUM1;
            $team_away_pro = Utils::get_team_details($id_team_group[0]->TEAM_ID);
        }elseif($round == 2){
            $id_team_group = Utils::get_opponent();
            $grp_stadium = $id_team_group[0]->GRP_STADIUM2;
            $team_away_pro = Utils::get_team_details($id_team_group[1]->TEAM_ID);
        }elseif($round == 3){
            $id_team_group = Utils::get_opponent();
            $grp_stadium = $id_team_group[0]->GRP_STADIUM1;
            $team_away_pro = Utils::get_team_details($id_team_group[2]->TEAM_ID);
        }elseif($round == 4){
            $grp_stadium = Stadium::$GAROUA_STADIUM;
            $grp_name = $can_phase[CanPhase::$EIGHT_FINAL]['PHASE'];
            $team_adversary = EightFinalPhase::adversary(Auth::user()->TEAM_ID);
            $team_away_pro = [(object) [ 'TEAM_NAME'=> $team_adversary->TEAM_NAME ]];
        }elseif($round == 5){
            $grp_stadium = Stadium::$BAFOUSSAM_STADIUM;
            $grp_name = $can_phase[CanPhase::$QUARTER_FINAL]['PHASE'];
            $team_adversary = QuarterFinalPhase::adversary(Auth::user()->TEAM_ID);
            $team_away_pro = [(object) [ 'TEAM_NAME'=> $team_adversary->TEAM_NAME ]];
        }elseif($round == 6){
            $grp_stadium = Stadium::$DOUALA_STADIUM;
            $grp_name = $can_phase[CanPhase::$SEMI_FINAL]['PHASE'];
            $team_adversary = SemiFinalPhase::adversary(Auth::user()->TEAM_ID);
            $team_away_pro = [(object) [ 'TEAM_NAME'=> $team_adversary->TEAM_NAME ]];
        }elseif($round == 7){
            $grp_stadium = Stadium::$YAOUNDE_STADIUM;
            $grp_name = $can_phase[CanPhase::$FINAL]['PHASE'];
            $team_adversary = FinalPhase::adversary(Auth::user()->TEAM_ID);
            $team_away_pro = [(object) [ 'TEAM_NAME'=> $team_adversary->TEAM_NAME ]];
        }

        /** @var User $user */
        $user = Auth::user();
        return view('match.match_process',['grp_stadium'=>$grp_stadium,'team_away_pro'=>$team_away_pro,'team_name_pro'=>$team_name_pro,'grp_name'=>$grp_name, 'countries'=>CountryName::getCountries()]);
    }

    public function half_time()
    {
        //get user ID
        $id = Auth::user()->TEAM_ID;
        $idAdversary = 0;
        $team_name_ht = Utils::get_team_name();
        $rr = Utils::get_opponent();
        $grp_name = $rr[0]->GRP_NAME;

        $but_half1 = Utils::but_half($id);  //get goals
        $injuries_half1 = 0; //get players injuries (blessures)

        Utils::players_goals($but_half1, $id, 1);
        CardBoardManagement::players_cards($id, null);

        $can_phase = CanPhase::getPhase();
        $round = Auth::user()->round;
        if($round == 1){
            $id_team_group = Utils::get_opponent();
            $grp_stadium = $id_team_group[0]->GRP_STADIUM1;
            $team_away_ht = Utils::get_team_details($id_team_group[0]->TEAM_ID);
            //get goals
            $but_half2 = Utils::but_half($id_team_group[0]->TEAM_ID);
            Utils::players_goals($but_half2, $id_team_group[0]->TEAM_ID, 1);
            CardBoardManagement::players_cards($id_team_group[0]->TEAM_ID, null);
            $idAdversary = $id_team_group[0]->TEAM_ID;
            //get attempts
            $injuries_half2 = 0;

        }elseif($round == 2){
            $id_team_group = Utils::get_opponent();
            $grp_stadium = $id_team_group[0]->GRP_STADIUM2;
            $team_away_ht = Utils::get_team_details($id_team_group[1]->TEAM_ID);
            //get goals
            $but_half2 = Utils::but_half($id_team_group[1]->TEAM_ID);
            Utils::players_goals($but_half2, $id_team_group[1]->TEAM_ID, 1);
            CardBoardManagement::players_cards($id_team_group[1]->TEAM_ID, null);
            $idAdversary = $id_team_group[1]->TEAM_ID;
            //get attempts
            $injuries_half2 = 0;

        }elseif($round == 3){
            $id_team_group = Utils::get_opponent();
            $grp_stadium = $id_team_group[0]->GRP_STADIUM1;
            $team_away_ht = Utils::get_team_details($id_team_group[2]->TEAM_ID);
            //get goals
            $but_half2 = Utils::but_half($id_team_group[2]->TEAM_ID);
            Utils::players_goals($but_half2, $id_team_group[2]->TEAM_ID, 1);
            CardBoardManagement::players_cards($id_team_group[2]->TEAM_ID, null);
            $idAdversary = $id_team_group[2]->TEAM_ID;
            //get attempts
            $injuries_half2 = 0;
        }elseif($round == 4){
            $grp_stadium = Stadium::$GAROUA_STADIUM;
            $grp_name = $can_phase[CanPhase::$EIGHT_FINAL]['PHASE'];
            $team_away_ht = [EightFinalPhase::adversary($id)];
            $but_half2 = Utils::but_half($team_away_ht[0]->TEAM_ID);
            Utils::players_goals($but_half2, $team_away_ht[0]->TEAM_ID, 1);
            CardBoardManagement::players_cards($team_away_ht[0]->TEAM_ID, null);
            $idAdversary = $team_away_ht[0]->TEAM_ID;
            $injuries_half2 = 0;
        }elseif($round == 5){
            $grp_stadium = Stadium::$BAFOUSSAM_STADIUM;
            $grp_name = $can_phase[CanPhase::$QUARTER_FINAL]['PHASE'];
            $team_away_ht = [QuarterFinalPhase::adversary($id)];
            $but_half2 = Utils::but_half($team_away_ht[0]->TEAM_ID);
            Utils::players_goals($but_half2, $team_away_ht[0]->TEAM_ID, 1);
            CardBoardManagement::players_cards($team_away_ht[0]->TEAM_ID, null);
            $idAdversary = $team_away_ht[0]->TEAM_ID;
            $injuries_half2 = 0;
        }elseif($round == 6){
            $grp_stadium = Stadium::$DOUALA_STADIUM;
            $grp_name = $can_phase[CanPhase::$SEMI_FINAL]['PHASE'];
            $team_away_ht = [SemiFinalPhase::adversary($id)];
            $but_half2 = Utils::but_half($team_away_ht[0]->TEAM_ID);
            Utils::players_goals($but_half2, $team_away_ht[0]->TEAM_ID, 1);
            CardBoardManagement::players_cards($team_away_ht[0]->TEAM_ID, null);
            $idAdversary = $team_away_ht[0]->TEAM_ID;
            $injuries_half2 = 0;
        }elseif($round == 7){
            $grp_stadium = Stadium::$YAOUNDE_STADIUM;
            $grp_name = $can_phase[CanPhase::$FINAL]['PHASE'];
            $team_away_ht = [FinalPhase::adversary($id)];
            $but_half2 = Utils::but_half($team_away_ht[0]->TEAM_ID);
            Utils::players_goals($but_half2, $team_away_ht[0]->TEAM_ID, 1);
            CardBoardManagement::players_cards($team_away_ht[0]->TEAM_ID, null);
            $idAdversary = $team_away_ht[0]->TEAM_ID;
            $injuries_half2 = 0;
        }

        session(['but_half1'=> $but_half1]);
        session(['but_half2'=> $but_half2]);
        session(['injuries_half1'=>$injuries_half1]);
        session(['injuries_half2'=>$injuries_half2]);

        $players_1 = session('ply_names_1_'.$id);
        $players_2 = session('ply_names_1_'.$idAdversary);
        $times_1 = session('times_1_'.$id);
        $times_2 = session('times_1_'.$idAdversary);
        $cards_1 = session('ply_name_cards_1_' . $id);
        $cards_2 = session('ply_name_cards_1_' . $idAdversary);
        $cards_time_1 = session('ply_time_cards_1_' . $id);
        $cards_time_2 = session('ply_time_cards_1_' . $idAdversary);
        $red_cards_1 = session('red_cards_1_' . $id);
        $red_cards_2 = session('red_cards_1_' . $idAdversary);
        $isViewed_1 = session('isViewed_1_' . $id);
        $isViewed_2 = session('isViewed_1_' . $idAdversary);

        /** @var User $user */
        $user = Auth::user();
        return view('match.half_time',
            ['grp_stadium'=>$grp_stadium,'but_half1'=>$but_half1,'but_half2'=>$but_half2, 'players_1'=>$players_1, 'players_2'=>$players_2, 'times_1'=>$times_1,
            'times_2'=>$times_2, 'injuries_half1'=>$injuries_half1,'injuries_half2'=>$injuries_half2,'team_away_ht'=>$team_away_ht, 'team_name_ht'=>$team_name_ht,
            'grp_name'=>$grp_name, 'cards_1'=>$cards_1, 'cards_2'=>$cards_2, 'cards_time_1'=>$cards_time_1, 'cards_time_2'=>$cards_time_2,
            'red_cards_1'=>$red_cards_1, 'red_cards_2'=>$red_cards_2,'isViewed_1'=>$isViewed_1, 'isViewed_2'=>$isViewed_2,'countries'=>CountryName::getCountries()]
        );
    }

    public function match_process2()
    {
        $team_name_pro2 = Utils::get_team_name();
        $rr = Utils::get_opponent();
        $grp_name = $rr[0]->GRP_NAME;

        $can_phase = CanPhase::getPhase();
        $round = Auth::user()->round;
        $id = Auth::user()->TEAM_ID;

        $idAdversary = 0;

        if($round == 1){
            $id_team_group = Utils::get_opponent();
            $grp_stadium = $id_team_group[0]->GRP_STADIUM1;
            $team_away_pro2 = Utils::get_team_details($id_team_group[0]->TEAM_ID);
            $idAdversary = $id_team_group[0]->TEAM_ID;
        }elseif($round == 2){
            $id_team_group = Utils::get_opponent();
            $grp_stadium = $id_team_group[0]->GRP_STADIUM2;
            $team_away_pro2 = Utils::get_team_details($id_team_group[1]->TEAM_ID);
            $idAdversary = $id_team_group[1]->TEAM_ID;
        }elseif($round == 3){
            $id_team_group = Utils::get_opponent();
            $grp_stadium = $id_team_group[0]->GRP_STADIUM1;
            $team_away_pro2 = Utils::get_team_details($id_team_group[2]->TEAM_ID);
            $idAdversary = $id_team_group[2]->TEAM_ID;
        }elseif($round == 4){
            $grp_stadium = Stadium::$GAROUA_STADIUM;
            $grp_name = $can_phase[CanPhase::$EIGHT_FINAL]['PHASE'];
            $team_away_pro2 = [(object) [ 'TEAM_NAME'=> EightFinalPhase::adversary(Auth::user()->TEAM_ID)->TEAM_NAME ]];
            $idAdversary = EightFinalPhase::adversary(Auth::user()->TEAM_ID)->TEAM_ID;
        }elseif($round == 5){
            $grp_stadium = Stadium::$BAFOUSSAM_STADIUM;
            $grp_name = $can_phase[CanPhase::$QUARTER_FINAL]['PHASE'];
            $team_away_pro2 = [(object) [ 'TEAM_NAME'=> QuarterFinalPhase::adversary(Auth::user()->TEAM_ID)->TEAM_NAME ]];
            $idAdversary = QuarterFinalPhase::adversary(Auth::user()->TEAM_ID)->TEAM_ID;
        }elseif($round == 6){
            $grp_stadium = Stadium::$DOUALA_STADIUM;
            $grp_name = $can_phase[CanPhase::$SEMI_FINAL]['PHASE'];
            $team_away_pro2 = [(object) [ 'TEAM_NAME'=> SemiFinalPhase::adversary(Auth::user()->TEAM_ID)->TEAM_NAME ]];
            $idAdversary = SemiFinalPhase::adversary(Auth::user()->TEAM_ID)->TEAM_ID;
        }elseif($round == 7){
            $grp_stadium = Stadium::$YAOUNDE_STADIUM;
            $grp_name = $can_phase[CanPhase::$FINAL]['PHASE'];
            $team_away_pro2 = [(object) [ 'TEAM_NAME'=> FinalPhase::adversary(Auth::user()->TEAM_ID)->TEAM_NAME ]];
            $idAdversary = FinalPhase::adversary(Auth::user()->TEAM_ID)->TEAM_ID;
        }
        $but_pro1 = session('but_half1');
        $but_pro2 = session('but_half2');
        $injuries_pro1 = session('injuries_half1');
        $injuries_pro2 = session('injuries_half2');

        $players_1 = session('ply_names_1_'.$id);
        $players_2 = session('ply_names_1_'.$idAdversary);
        $times_1 = session('times_1_'.$id);
        $times_2 = session('times_1_'.$idAdversary);
        $cards_1 = session('ply_name_cards_1_' . $id);
        $cards_2 = session('ply_name_cards_1_' . $idAdversary);
        $cards_time_1 = session('ply_time_cards_1_' . $id);
        $cards_time_2 = session('ply_time_cards_1_' . $idAdversary);
        $red_cards_1 = session('red_cards_1_' . $id);
        $red_cards_2 = session('red_cards_1_' . $idAdversary);
        $isViewed_1 = session('isViewed_1_' . $id);
        $isViewed_2 = session('isViewed_1_' . $idAdversary);

        /** @var User $user */
        $user = Auth::user();
        return view('match.match_process2',
            ['grp_stadium'=>$grp_stadium,'but_pro1'=>$but_pro1,'but_pro2'=>$but_pro2, 'players_1'=>$players_1, 'players_2'=>$players_2, 'times_1'=>$times_1,
            'times_2'=>$times_2,'injuries_pro1'=>$injuries_pro1,'injuries_pro2'=>$injuries_pro2,'team_away_pro2'=>$team_away_pro2,'team_name_pro2'=>$team_name_pro2,
            'grp_name'=>$grp_name,'cards_1'=>$cards_1, 'cards_2'=>$cards_2, 'cards_time_1'=>$cards_time_1, 'cards_time_2'=>$cards_time_2,
            'red_cards_1'=>$red_cards_1, 'red_cards_2'=>$red_cards_2,'isViewed_1'=>$isViewed_1, 'isViewed_2'=>$isViewed_2, 'countries'=>CountryName::getCountries()]
        );
    }


    public function match_end()
    {
        //get user ID
        $id = Auth::user()->id;

        $grp_id = Utils::get_group_id();
        $id_team = Auth::user()->TEAM_ID;
        $team_name_end = Utils::get_team_name();

        $rr = Utils::get_opponent(); //get group name of the team
        $grp_name = $rr[0]->GRP_NAME; // display group name
        $idAdversary = 0;

        $can_phase = CanPhase::getPhase();
        $round = Auth::user()->round;

        // DB::delete('DELETE FROM t_card WHERE USER_ID = ' . $id);
        DB::table('t_card')->where('USER_ID', $id)->delete();

        if($round == 1){        // Playing all the match of the group of the current round

            $id_team_group = Utils::get_opponent();
            $grp_stadium = $id_team_group[0]->GRP_STADIUM1;
            $team_away_end = Utils::get_team_details($id_team_group[0]->TEAM_ID);

            Utils::play_round1();

            $note_equip_home = Utils::note_equipe($id_team);
            $nbre_occasions_home = Utils::nbre_occasions_half($note_equip_home);
            $but_home = Utils::occasions($nbre_occasions_home,50);
            Utils::players_goals($but_home, $id_team, 2);

            $idAdversary = $id_team_group[0]->TEAM_ID;
            $id0 = $id_team_group[0]->TEAM_ID;
            $note_equip_away = Utils::note_equipe($id0);
            $nbre_occasions_away = Utils::nbre_occasions_half($note_equip_away);
            $but_away = Utils::occasions($nbre_occasions_away,50);
            Utils::players_goals($but_away, $id0, 2);

            $id1 = $id_team_group[1]->TEAM_ID;
            $note_equip_a2 = Utils::note_equipe($id1);
            $nbre_occasions_a2 = Utils::nbre_occasions($note_equip_a2);
            $but_autre2 = Utils::occasions($nbre_occasions_a2,50);
            Utils::players_goals($but_autre2, $id1, 2);

            $id2 = $id_team_group[2]->TEAM_ID;
            $note_equip_a1 = Utils::note_equipe($id2);
            $nbre_occasions_a1 = Utils::nbre_occasions($note_equip_a1);
            $but_autre1 = Utils::occasions($nbre_occasions_a1,50);
            Utils::players_goals($but_autre1, $id2, 2);

            $but_end1 = session('but_half1');
            $but_end2 = session('but_half2');
            $injuries_end1 = session('injuries_half1');
            $injuries_end2 = session('injuries_half2');

            $but_home = $but_end1 + $but_home;
            $but_away = $but_end2 + $but_away;

            $injuries_end1 =  0;
            $injuries_end2 =  0;

            $group_days = DaysList::getPhase();


            $players_1 = Utils::array_merge_custom(session('ply_ids_1_'.$id_team), session('ply_ids_2_'.$id_team));
            $pass_ids_1 = Utils::array_merge_custom(session('pass_ids_1_'.$id_team), session('pass_ids_2_'.$id_team));
            $players_names_1 = Utils::array_merge_custom(session('ply_names_1_'.$id_team), session('ply_names_2_'.$id_team));
            $times_1 = Utils::array_merge_custom(session('times_1_'.$id_team), session('times_2_'.$id_team));
            $players_2 = Utils::array_merge_custom(session('ply_ids_1_'.$id0), session('ply_ids_2_'.$id0));
            $pass_ids_2 = Utils::array_merge_custom(session('pass_ids_1_'.$id0), session('pass_ids_2_'.$id0));
            $players_names_2 = Utils::array_merge_custom(session('ply_names_1_'.$id0), session('ply_names_2_'.$id0));
            $times_2 = Utils::array_merge_custom(session('times_1_'.$id0), session('times_2_'.$id0));
            $players_3 = session('ply_ids_2_'.$id2);
            $pass_ids_3 = session('pass_ids_2_'.$id2);
            $times_3 = session('times_2_'.$id2);
            $players_4 = session('ply_ids_2_'.$id1);
            $pass_ids_4 = session('pass_ids_2_'.$id1);
            $times_4 = session('times_2_'.$id1);

            // save match
            Utils::save_match_infos(0,0,$id_team,$id_team_group[0]->TEAM_ID,$grp_id[0]->GRP_ID,$group_days[DaysList::$DAY_ONE]['DAY'],
            $team_name_end[0]->TEAM_NAME." ".$but_home." - ".$but_away." ".$team_away_end[0]->TEAM_NAME,$id,CanPhase::$GROUP_PHASE,DaysList::$DAY_ONE);
            Utils::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_team));
            Utils::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($id0));
            //update TEAM_STA
            Utils::match_decision($id_team,$but_home,$id_team_group[0]->TEAM_ID,$but_away);

            Utils::save_match_infos(0,0,$id_team_group[2]->TEAM_ID,$id_team_group[1]->TEAM_ID,$grp_id[0]->GRP_ID,$group_days[DaysList::$DAY_ONE]['DAY'],
            $id_team_group[2]->TEAM_NAME." ".$but_autre1." - ".$but_autre2." ".$id_team_group[1]->TEAM_NAME,$id,CanPhase::$GROUP_PHASE,DaysList::$DAY_ONE);
            Utils::save_match_goals($players_3, $pass_ids_3, $times_3, Utils::get_match_infos($id2));
            Utils::save_match_goals($players_4, $pass_ids_4, $times_4, Utils::get_match_infos($id1));
            Utils::match_decision($id_team_group[2]->TEAM_ID,$but_autre1,$id_team_group[1]->TEAM_ID,$but_autre2);

            DB::table('users')->where('id',$id)->update(array('round'=>2));

        }elseif($round == 2){

            Utils::play_round2();

            // match for user group
            $id_team_group = Utils::get_opponent();
            $grp_stadium = $id_team_group[1]->GRP_STADIUM2;
            $team_away_end = Utils::get_team_details($id_team_group[1]->TEAM_ID);

            $note_equip_home = Utils::note_equipe($id_team);
            $nbre_occasions_home = Utils::nbre_occasions_half($note_equip_home);
            $but_home = Utils::occasions($nbre_occasions_home,50);
            Utils::players_goals($but_home, $id_team, 2);


            $note_equip_away = Utils::note_equipe($id_team_group[1]->TEAM_ID);
            $nbre_occasions_away = Utils::nbre_occasions_half($note_equip_away);
            $but_away = Utils::occasions($nbre_occasions_away,50);
            Utils::players_goals($but_away, $id_team_group[1]->TEAM_ID, 2);
            $idAdversary = $id_team_group[1]->TEAM_ID;

            $note_equip_a1 = Utils::note_equipe($id_team_group[0]->TEAM_ID);
            $nbre_occasions_a1 = Utils::nbre_occasions($note_equip_a1);
            $but_autre1 = Utils::occasions($nbre_occasions_a1,50);
            Utils::players_goals($but_autre1, $id_team_group[0]->TEAM_ID, 2);

            $note_equip_a2 = Utils::note_equipe($id_team_group[2]->TEAM_ID);
            $nbre_occasions_a2 = Utils::nbre_occasions($note_equip_a2);
            $but_autre2 = Utils::occasions($nbre_occasions_a2,50);
            Utils::players_goals($but_autre2, $id_team_group[2]->TEAM_ID, 2);

            $but_end1 = session('but_half1');
            $but_end2 = session('but_half2');
            $injuries_end1 = session('injuries_half1');
            $injuries_end2 = session('injuries_half2');

            $but_home = $but_end1 + $but_home;
            $but_away = $but_end2 + $but_away;

            $injuries_end1 = 0 + $injuries_end1;
            $injuries_end2 = 0 + $injuries_end2;

            $group_days = DaysList::getPhase();

            $players_1 = Utils::array_merge_custom(session('ply_ids_1_'.$id_team), session('ply_ids_2_'.$id_team));
            $pass_ids_1 = Utils::array_merge_custom(session('pass_ids_1_'.$id_team), session('pass_ids_2_'.$id_team));
            $players_names_1 = Utils::array_merge_custom(session('ply_names_1_'.$id_team), session('ply_names_2_'.$id_team));
            $times_1 = Utils::array_merge_custom(session('times_1_'.$id_team), session('times_2_'.$id_team));
            $players_2 = Utils::array_merge_custom(session('ply_ids_1_'.$idAdversary), session('ply_ids_2_'.$idAdversary));
            $pass_ids_2 = Utils::array_merge_custom(session('pass_ids_1_'.$idAdversary), session('pass_ids_2_'.$idAdversary));
            $players_names_2 = Utils::array_merge_custom(session('ply_names_1_'.$idAdversary), session('ply_names_2_'.$idAdversary));
            $times_2 = Utils::array_merge_custom(session('times_1_'.$idAdversary), session('times_2_'.$idAdversary));
            $players_3 = session('ply_ids_2_'.$id_team_group[0]->TEAM_ID);
            $pass_ids_3 = session('pass_ids_2_'.$id_team_group[0]->TEAM_ID);
            $times_3 = session('times_2_'.$id_team_group[0]->TEAM_ID);
            $players_4 = session('ply_ids_2_'.$id_team_group[2]->TEAM_ID);
            $pass_ids_4 = session('pass_ids_2_'.$id_team_group[2]->TEAM_ID);
            $times_4 = session('times_2_'.$id_team_group[2]->TEAM_ID);

            Utils::save_match_infos(0,0,$id_team,$id_team_group[1]->TEAM_ID,$grp_id[0]->GRP_ID,$group_days[DaysList::$DAY_TWO]['DAY'],
            $team_name_end[0]->TEAM_NAME." ".$but_home." - ".$but_away." ".$team_away_end[0]->TEAM_NAME,$id,CanPhase::$GROUP_PHASE,DaysList::$DAY_TWO);
            Utils::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_team));
            Utils::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($idAdversary));
            Utils::match_decision($id_team,$but_home,$id_team_group[1]->TEAM_ID,$but_away);

            //save current match 2
            Utils::save_match_infos(0,0,$id_team_group[0]->TEAM_ID,$id_team_group[2]->TEAM_ID,$grp_id[0]->GRP_ID,$group_days[DaysList::$DAY_TWO]['DAY'],
            $id_team_group[0]->TEAM_NAME." ".$but_autre1." - ".$but_autre2." ".$id_team_group[2]->TEAM_NAME,$id,CanPhase::$GROUP_PHASE,DaysList::$DAY_TWO);
            Utils::save_match_goals($players_3, $pass_ids_3, $times_3, Utils::get_match_infos($id_team_group[0]->TEAM_ID));
            Utils::save_match_goals($players_4, $pass_ids_4, $times_4, Utils::get_match_infos($id_team_group[2]->TEAM_ID));
            Utils::match_decision($id_team_group[0]->TEAM_ID,$but_autre1,$id_team_group[2]->TEAM_ID,$but_autre2);

            DB::table('users')->where('id',$id)->update(array('round'=>3));

        }elseif($round == 3){

            Utils::play_round3();

            $id_team_group = Utils::get_opponent();
            $grp_stadium = $id_team_group[0]->GRP_STADIUM1;
            $team_away_end = Utils::get_team_details($id_team_group[2]->TEAM_ID);

            $note_equip_home = Utils::note_equipe($id_team);
            $nbre_occasions_home = Utils::nbre_occasions_half($note_equip_home);
            $but_home = Utils::occasions($nbre_occasions_home,50);
            Utils::players_goals($but_home, $id_team, 2);

            $note_equip_away = Utils::note_equipe($id_team_group[2]->TEAM_ID);
            $nbre_occasions_away = Utils::nbre_occasions_half($note_equip_away);
            $but_away = Utils::occasions($nbre_occasions_away,50);
            Utils::players_goals($but_away, $id_team_group[2]->TEAM_ID, 2);
            $idAdversary = $id_team_group[2]->TEAM_ID;

            $note_equip_a1 = Utils::note_equipe($id_team_group[0]->TEAM_ID);
            $nbre_occasions_a1 = Utils::nbre_occasions($note_equip_a1);
            $but_autre1 = Utils::occasions($nbre_occasions_a1,50);
            Utils::players_goals($but_autre1, $id_team_group[0]->TEAM_ID, 2);

            $note_equip_a2 = Utils::note_equipe($id_team_group[1]->TEAM_ID);
            $nbre_occasions_a2 = Utils::nbre_occasions($note_equip_a2);
            $but_autre2 = Utils::occasions($nbre_occasions_a2,50);
            Utils::players_goals($but_autre2, $id_team_group[1]->TEAM_ID, 2);

            $but_end1 = session('but_half1');
            $but_end2 = session('but_half2');
            $injuries_end1 = session('injuries_half1');
            $injuries_end2 = session('injuries_half2');

            $but_home = $but_end1 + $but_home;
            $but_away = $but_end2 + $but_away;

            $injuries_end1 = 0;
            $injuries_end2 = 0;

            $group_days = DaysList::getPhase();

            $players_1 = Utils::array_merge_custom(session('ply_ids_1_'.$id_team), session('ply_ids_2_'.$id_team));
            $pass_ids_1 = Utils::array_merge_custom(session('pass_ids_1_'.$id_team), session('pass_ids_2_'.$id_team));
            $players_names_1 = Utils::array_merge_custom(session('ply_names_1_'.$id_team), session('ply_names_2_'.$id_team));
            $times_1 = Utils::array_merge_custom(session('times_1_'.$id_team), session('times_2_'.$id_team));
            $players_2 = Utils::array_merge_custom(session('ply_ids_1_'.$idAdversary), session('ply_ids_2_'.$idAdversary));
            $pass_ids_2 = Utils::array_merge_custom(session('pass_ids_1_'.$idAdversary), session('pass_ids_2_'.$idAdversary));
            $players_names_2 = Utils::array_merge_custom(session('ply_names_1_'.$idAdversary), session('ply_names_2_'.$idAdversary));
            $times_2 = Utils::array_merge_custom(session('times_1_'.$idAdversary), session('times_2_'.$idAdversary));
            $players_3 = session('ply_ids_2_'.$id_team_group[0]->TEAM_ID);
            $players_4 = session('ply_ids_2_'.$id_team_group[1]->TEAM_ID);
            $times_3 = session('times_2_'.$id_team_group[0]->TEAM_ID);
            $times_4 = session('times_2_'.$id_team_group[1]->TEAM_ID);
            $pass_ids_3 = session('pass_ids_2_'.$id_team_group[0]->TEAM_ID);
            $pass_ids_4 = session('pass_ids_2_'.$id_team_group[1]->TEAM_ID);

            Utils::save_match_infos(0,0,$id_team,$id_team_group[2]->TEAM_ID,$grp_id[0]->GRP_ID,$group_days[DaysList::$DAY_THREE]['DAY'],
                $team_name_end[0]->TEAM_NAME." ".$but_home." - ".$but_away." ".$team_away_end[0]->TEAM_NAME,$id,CanPhase::$GROUP_PHASE,DaysList::$DAY_THREE);
            Utils::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_team));
            Utils::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($idAdversary));
            Utils::match_decision($id_team,$but_home,$id_team_group[2]->TEAM_ID,$but_away);

            Utils::save_match_infos(0,0,$id_team_group[0]->TEAM_ID,$id_team_group[1]->TEAM_ID,$grp_id[0]->GRP_ID,$group_days[DaysList::$DAY_THREE]['DAY'],
             $id_team_group[0]->TEAM_NAME." ".$but_autre1." - ".$but_autre2." ".$id_team_group[1]->TEAM_NAME,$id,CanPhase::$GROUP_PHASE,DaysList::$DAY_THREE);
            Utils::save_match_goals($players_3, $pass_ids_3, $times_3, Utils::get_match_infos($id_team_group[0]->TEAM_ID));
            Utils::save_match_goals($players_4, $pass_ids_4, $times_4, Utils::get_match_infos($id_team_group[1]->TEAM_ID));
            Utils::match_decision($id_team_group[0]->TEAM_ID,$but_autre1,$id_team_group[1]->TEAM_ID,$but_autre2);

            DB::table('users')->where('id',$id)->update(array('round'=>4));

        } elseif($round == 4) {
            $grp_stadium = Stadium::$GAROUA_STADIUM;
            $grp_name = $can_phase[CanPhase::$EIGHT_FINAL]['PHASE'];

            $team_adversary = EightFinalPhase::adversary(Auth::user()->TEAM_ID);
            $team_away_end = [(object) [ 'TEAM_NAME'=> $team_adversary->TEAM_NAME ]];

            $note_equip_home = Utils::note_equipe($id_team);
            $nbre_occasions_home = Utils::nbre_occasions_half($note_equip_home);
            $but_home = Utils::occasions($nbre_occasions_home,50);
            Utils::players_goals($but_home, $id_team, 2);

            $idAdversary = $team_adversary->TEAM_ID;
            $note_equip_away = Utils::note_equipe($idAdversary);
            $nbre_occasions_away = Utils::nbre_occasions_half($note_equip_away);
            $but_away = Utils::occasions($nbre_occasions_away,50);
            Utils::players_goals($but_away, $idAdversary, 2);

            EightFinalPhase::play_round();

            $but_end1 = session('but_half1');
            $but_end2 = session('but_half2');
            $injuries_end1 = session('injuries_half1');
            $injuries_end2 = session('injuries_half2');

            while ($but_end1 + $but_home == $but_end2 + $but_away) {
                $but_home = Utils::occasions($nbre_occasions_home,50);
                $but_away = Utils::occasions($nbre_occasions_away,50);
            }
            $but_home = $but_end1 + $but_home;
            $but_away = $but_end2 + $but_away;

            $injuries_end1 = 0;
            $injuries_end2 = 0;

            $group_days = DaysList::getPhase();

            $players_1 = Utils::array_merge_custom(session('ply_ids_1_'.$id_team), session('ply_ids_2_'.$id_team));
            $pass_ids_1 = Utils::array_merge_custom(session('pass_ids_1_'.$id_team), session('pass_ids_2_'.$id_team));
            $players_names_1 = Utils::array_merge_custom(session('ply_names_1_'.$id_team), session('ply_names_2_'.$id_team));
            $players_2 = Utils::array_merge_custom(session('ply_ids_1_'.$idAdversary), session('ply_ids_2_'.$idAdversary));
            $pass_ids_2 = Utils::array_merge_custom(session('pass_ids_1_'.$idAdversary), session('pass_ids_2_'.$idAdversary));
            $players_names_2 = Utils::array_merge_custom(session('ply_names_1_'.$idAdversary), session('ply_names_2_'.$idAdversary));
            $times_1 = Utils::array_merge_custom(session('times_1_'.$id_team), session('times_2_'.$id_team));
            $times_2 = Utils::array_merge_custom(session('times_1_'.$idAdversary), session('times_2_'.$idAdversary));

            Utils::save_match_infos(0,0,$id_team,$idAdversary,$grp_id[0]->GRP_ID,$group_days[DaysList::$UNKNOWN]['DAY'],
                $team_name_end[0]->TEAM_NAME." ".$but_home." - ".$but_away." ".$team_adversary->TEAM_NAME,$id,CanPhase::$EIGHT_FINAL,DaysList::$UNKNOWN);
            Utils::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_team));
            Utils::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($idAdversary));

            DB::table('users')->where('id',$id)->update(array('round'=> 5));
        } elseif($round == 5) {
            $grp_stadium = Stadium::$BAFOUSSAM_STADIUM;
            $grp_name = $can_phase[CanPhase::$QUARTER_FINAL]['PHASE'];

            $team_adversary = QuarterFinalPhase::adversary(Auth::user()->TEAM_ID);
            $team_away_end = [(object) [ 'TEAM_NAME'=> $team_adversary->TEAM_NAME ]];

            $note_equip_home = Utils::note_equipe($id_team);
            $nbre_occasions_home = Utils::nbre_occasions_half($note_equip_home);
            $but_home = Utils::occasions($nbre_occasions_home,50);
            Utils::players_goals($but_home, $id_team, 2);

            $idAdversary = $team_adversary->TEAM_ID;
            $note_equip_away = Utils::note_equipe($idAdversary);
            $nbre_occasions_away = Utils::nbre_occasions_half($note_equip_away);
            $but_away = Utils::occasions($nbre_occasions_away,50);
            Utils::players_goals($but_away, $idAdversary, 2);

            QuarterFinalPhase::play_round();

            $but_end1 = session('but_half1');
            $but_end2 = session('but_half2');
            $injuries_end1 = session('injuries_half1');
            $injuries_end2 = session('injuries_half2');

            while ($but_end1 + $but_home == $but_end2 + $but_away) {
                $but_home = Utils::occasions($nbre_occasions_home,50);
                $but_away = Utils::occasions($nbre_occasions_away,50);
            }
            $but_home = $but_end1 + $but_home;
            $but_away = $but_end2 + $but_away;

            $attempts_end1 = 0;
            $attempts_end2 = 0;

            $group_days = DaysList::getPhase();

            $players_1 = Utils::array_merge_custom(session('ply_ids_1_'.$id_team), session('ply_ids_2_'.$id_team));
            $pass_ids_1 = Utils::array_merge_custom(session('pass_ids_1_'.$id_team), session('pass_ids_2_'.$id_team));
            $players_names_1 = Utils::array_merge_custom(session('ply_names_1_'.$id_team), session('ply_names_2_'.$id_team));
            $players_2 = Utils::array_merge_custom(session('ply_ids_1_'.$idAdversary), session('ply_ids_2_'.$idAdversary));
            $pass_ids_2 = Utils::array_merge_custom(session('pass_ids_1_'.$idAdversary), session('pass_ids_2_'.$idAdversary));
            $players_names_2 = Utils::array_merge_custom(session('ply_names_1_'.$idAdversary), session('ply_names_2_'.$idAdversary));
            $times_1 = Utils::array_merge_custom(session('times_1_'.$id_team), session('times_2_'.$id_team));
            $times_2 = Utils::array_merge_custom(session('times_1_'.$idAdversary), session('times_2_'.$idAdversary));

            Utils::save_match_infos(0,0,$id_team,$idAdversary,$grp_id[0]->GRP_ID,$group_days[DaysList::$UNKNOWN]['DAY'],
                $team_name_end[0]->TEAM_NAME." ".$but_home." - ".$but_away." ".$team_adversary->TEAM_NAME,$id,CanPhase::$QUARTER_FINAL,DaysList::$UNKNOWN);
            Utils::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_team));
            Utils::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($idAdversary));

            DB::table('users')->where('id',$id)->update(array('round'=> 6));
        } elseif($round == 6) {
            $grp_stadium = Stadium::$DOUALA_STADIUM;
            $grp_name = $can_phase[CanPhase::$SEMI_FINAL]['PHASE'];

            $team_adversary = SemiFinalPhase::adversary(Auth::user()->TEAM_ID);
            $team_away_end = [(object) [ 'TEAM_NAME'=> $team_adversary->TEAM_NAME ]];

            $note_equip_home = Utils::note_equipe($id_team);
            $nbre_occasions_home = Utils::nbre_occasions_half($note_equip_home);
            $but_home = Utils::occasions($nbre_occasions_home,50);
            Utils::players_goals($but_home, $id_team, 2);

            $idAdversary = $team_adversary->TEAM_ID;
            $note_equip_away = Utils::note_equipe($idAdversary);
            $nbre_occasions_away = Utils::nbre_occasions_half($note_equip_away);
            $but_away = Utils::occasions($nbre_occasions_away,50);
            Utils::players_goals($but_away, $idAdversary, 2);

            SemiFinalPhase::play_round();

            $but_end1 = session('but_half1');
            $but_end2 = session('but_half2');
            $injuries_end1 = session('injuries_half1');
            $injuries_end2 = session('injuries_half2');

            while ($but_end1 + $but_home == $but_end2 + $but_away) {
                $but_home = Utils::occasions($nbre_occasions_home,50);
                $but_away = Utils::occasions($nbre_occasions_away,50);
            }
            $but_home = $but_end1 + $but_home;
            $but_away = $but_end2 + $but_away;

            $injuries_end1 = 0;
            $injuries_end2 = 0;

            $group_days = DaysList::getPhase();

            $players_1 = Utils::array_merge_custom(session('ply_ids_1_'.$id_team), session('ply_ids_2_'.$id_team));
            $pass_ids_1 = Utils::array_merge_custom(session('pass_ids_1_'.$id_team), session('pass_ids_2_'.$id_team));
            $players_names_1 = Utils::array_merge_custom(session('ply_names_1_'.$id_team), session('ply_names_2_'.$id_team));
            $players_2 = Utils::array_merge_custom(session('ply_ids_1_'.$idAdversary), session('ply_ids_2_'.$idAdversary));
            $pass_ids_2 = Utils::array_merge_custom(session('pass_ids_1_'.$idAdversary), session('pass_ids_2_'.$idAdversary));
            $players_names_2 = Utils::array_merge_custom(session('ply_names_1_'.$idAdversary), session('ply_names_2_'.$idAdversary));
            $times_1 = Utils::array_merge_custom(session('times_1_'.$id_team), session('times_2_'.$id_team));
            $times_2 = Utils::array_merge_custom(session('times_1_'.$idAdversary), session('times_2_'.$idAdversary));

            Utils::save_match_infos(0,0,$id_team,$idAdversary,$grp_id[0]->GRP_ID,$group_days[DaysList::$UNKNOWN]['DAY'],
                $team_name_end[0]->TEAM_NAME." ".$but_home." - ".$but_away." ".$team_adversary->TEAM_NAME,$id,CanPhase::$SEMI_FINAL,DaysList::$UNKNOWN);
            Utils::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_team));
            Utils::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($idAdversary));

            DB::table('users')->where('id',$id)->update(array('round'=> 7));
        } elseif($round == 7) {
            $grp_stadium = Stadium::$YAOUNDE_STADIUM;
            $grp_name = $can_phase[CanPhase::$FINAL]['PHASE'];

            FinalPhase::play_third_place();

            $team_adversary = FinalPhase::adversary(Auth::user()->TEAM_ID);
            $team_away_end = [(object) [ 'TEAM_NAME'=> $team_adversary->TEAM_NAME ]];

            $note_equip_home = Utils::note_equipe($id_team);
            $nbre_occasions_home = Utils::nbre_occasions_half($note_equip_home);
            $but_home = Utils::occasions($nbre_occasions_home,50);
            Utils::players_goals($but_home, $id_team, 2);

            $idAdversary = $team_adversary->TEAM_ID;
            $note_equip_away = Utils::note_equipe($idAdversary);
            $nbre_occasions_away = Utils::nbre_occasions_half($note_equip_away);
            $but_away = Utils::occasions($nbre_occasions_away,50);
            Utils::players_goals($but_away, $idAdversary, 2);

            $but_end1 = session('but_half1');
            $but_end2 = session('but_half2');
            $injuries_end1 = session('injuries_half1');
            $injuries_end2 = session('injuries_half2');

            while ($but_end1 + $but_home == $but_end2 + $but_away) {
                $but_home = Utils::occasions($nbre_occasions_home,50);
                $but_away = Utils::occasions($nbre_occasions_away,50);
            }
            $but_home = $but_end1 + $but_home;
            $but_away = $but_end2 + $but_away;

            $injuries_end1 = 0;
            $injuries_end2 = 0;

            $group_days = DaysList::getPhase();

            $players_1 = Utils::array_merge_custom(session('ply_ids_1_'.$id_team), session('ply_ids_2_'.$id_team));
            $pass_ids_1 = Utils::array_merge_custom(session('pass_ids_1_'.$id_team), session('pass_ids_2_'.$id_team));
            $players_names_1 = Utils::array_merge_custom(session('ply_names_1_'.$id_team), session('ply_names_2_'.$id_team));
            $players_2 = Utils::array_merge_custom(session('ply_ids_1_'.$idAdversary), session('ply_ids_2_'.$idAdversary));
            $pass_ids_2 = Utils::array_merge_custom(session('pass_ids_1_'.$idAdversary), session('pass_ids_2_'.$idAdversary));
            $players_names_2 = Utils::array_merge_custom(session('ply_names_1_'.$idAdversary), session('ply_names_2_'.$idAdversary));
            $times_1 = Utils::array_merge_custom(session('times_1_'.$id_team), session('times_2_'.$id_team));
            $times_2 = Utils::array_merge_custom(session('times_1_'.$idAdversary), session('times_2_'.$idAdversary));

            Utils::save_match_infos(0,0,$id_team,$idAdversary,$grp_id[0]->GRP_ID,$group_days[DaysList::$UNKNOWN]['DAY'],
                $team_name_end[0]->TEAM_NAME." ".$but_home." - ".$but_away." ".$team_adversary->TEAM_NAME,$id,CanPhase::$FINAL,DaysList::$UNKNOWN);
            Utils::save_match_goals($players_1, $pass_ids_1, $times_1, Utils::get_match_infos($id_team));
            Utils::save_match_goals($players_2, $pass_ids_2, $times_2, Utils::get_match_infos($idAdversary));

            DB::table('users')->where('id',$id)->update(array('round'=> 8));
        }

        $cards_team = CardBoardManagement::players_cards($id_team, Utils::get_match_infos($id_team), 2);
        $cards_players_1 = $cards_team[0];
        $cards_time_1 = $cards_team[1];
        $red_cards_1 = $cards_team[2];
        $isViewed_1 = $cards_team[3];
        $card_adversary = CardBoardManagement::players_cards($idAdversary, Utils::get_match_infos($idAdversary), 2);
        $cards_players_2 = $card_adversary[0];
        $cards_time_2 = $card_adversary[1];
        $red_cards_2 = $card_adversary[2];
        $isViewed_2 = $card_adversary[3];

        /** @var User $user */
        $user = Auth::user();
        return view('match.match_end',
            ['grp_stadium'=>$grp_stadium,'injuries_end1'=>$injuries_end1,'injuries_end2'=>$injuries_end2,'team_away_end'=>$team_away_end,'team_name_end'=>$team_name_end,
            'but_away'=>$but_away,'but_home'=>$but_home,'grp_name'=>$grp_name,'players_1'=>$players_names_1,'players_2'=>$players_names_2,'times_1'=>$times_1,
            'times_2'=>$times_2,'cards_1'=>$cards_players_1, 'cards_2'=>$cards_players_2, 'cards_time_1'=>$cards_time_1,'cards_time_2'=>$cards_time_2,
            'red_cards_1'=>$red_cards_1,'red_cards_2'=>$red_cards_2,'isViewed_1'=>$isViewed_1, 'isViewed_2'=>$isViewed_2, 'countries'=>CountryName::getCountries()]
        );
    }


}
