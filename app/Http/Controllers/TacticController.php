<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Tactic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class TacticController extends Controller
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

    public function tactic(Request $request)
    {
        $user_tac = -1;
        $userId = Auth::user()->id;
        $idTeam = Auth::user()->TEAM_ID;

        $tactics = DB::select("SELECT * FROM t_tactic ORDER BY TAC_ID ASC");

        if (request('id')) {
            $id = $request->id;
            $bd_tac = DB::select('SELECT * FROM t_team_tac WHERE USER_ID = '.$userId.' ORDER BY USER_ID DESC LIMIT 1');

            if (count($bd_tac) > 0) {
                // update db
                DB::table('t_team_tac')->where('USER_ID', $userId)->update(array('TAC_ID' => $id, 'updated_at' => date('Y-m-d H:i:s')));
            } else {
                // insert db
                DB::insert('INSERT INTO t_team_tac (TAC_ID, TEAM_ID, USER_ID, created_at, updated_at) '.
                                'VALUES (?, ?, ?, ?, ?)', array($id, $idTeam, $userId, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')));
            }

            // reset all the default player tactic position for the new tactic selected
            $list_players = DB::select("SELECT PLY_ID
                            FROM t_player
                            INNER JOIN t_player_line ON t_player_line.LINE_ID = t_player.LINE_ID
                            INNER JOIN (SELECT TAC_ID_POS, USER_ID, PLY_ID AS PL_ID FROM t_player_tac_pos) AS plyTacPos ON plyTacPos.PL_ID = t_player.PLY_ID AND plyTacPos.USER_ID = ".$userId." 
                            WHERE t_player.TEAM_ID = ".$idTeam." ORDER BY t_player.LINE_ID ASC, PLY_NAME ASC");

            $size = count($list_players);
            if ($size > 0) {
                $ids = "";
                for ($i = 0; $i < $size; $i++) {
                   if ($i == 0) {
                       $ids = $list_players[$i]->PLY_ID;
                   } else {
                       $ids .= ",".$list_players[$i]->PLY_ID;
                   }
                }
                DB::update("UPDATE t_player_tac_pos SET TAC_ID_POS = 0 WHERE PLY_ID IN (".$ids.") AND USER_ID = " . $userId);
            }

            $user_tac = $id;
        } else {
            $bd_tac = DB::select('SELECT * FROM t_team_tac WHERE USER_ID = '.$userId.' ORDER BY USER_ID DESC LIMIT 1');
            if (count($bd_tac) > 0) {
                $user_tac = $bd_tac[0]->TAC_ID;
            }
        }

        /** @var User $user */
        $user = Auth::user();
        return view('tactic.tactic',['tactics'=>$tactics, 'user_tac'=>$user_tac]);
    }
}
