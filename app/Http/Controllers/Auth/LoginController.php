<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Utils\Utils;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function redirectTo() {

        $credit = DB::table('t_credit')->where('CRED_USER_ID', Auth::user()->id)->value('CRED_BALANCE');
        /*$t_credit = DB::select("SELECT CRED_BALANCE FROM t_credit WHERE CRED_USER_ID = " . Auth::user()->id);
        $credit = $t_credit[0]->CRED_BALANCE;*/

        // self::run_script_table();
        Utils::deleteUserData();

        if ($credit > 0 ) {
            DB::update("UPDATE t_credit SET CRED_BALANCE = " . ($credit - 1) . " WHERE CRED_USER_ID = " . Auth::user()->id);
            return '/home';
        } else {
            return '/credit';
        }
    }

    public function logout(Request $request)
    {
        Utils::deleteUserData();

        $this->guard()->logout();

        session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/');
    }

    public function run_script_table() {

        Schema::create('t_team_sta', function (Blueprint $table) {
            $table->increments('TEAM_STA_ID');
            $table->integer('TEAM_ID')->unsigned();
            $table->integer('RNK_ID')->unsigned();
            $table->integer('GRP_ID')->unsigned();
            $table->integer('TEAM_WIN')->unsigned();
            $table->integer('TEAM_LOS')->unsigned();
            $table->integer('TEAM_DRAW')->unsigned();
            $table->integer('TEAM_PTS')->unsigned();
            $table->integer('TEAM_SCO')->unsigned();
            $table->integer('TEAM_CON')->unsigned();
            $table->integer('TEAM_AVG')->unsigned();
            $table->timestamps();
            $table->foreign('TEAM_ID')->references('TEAM_ID')->on('t_team')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('RNK_ID')->references('RNK_ID')->on('t_rank')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('GRP_ID')->references('GRP_ID')->on('t_group')->onUpdate('cascade')->onDelete('cascade');
            $table->temporary();
        });

        Schema::create('t_team_tac', function (Blueprint $table) {
            $table->integer('TAC_ID')->unsigned();
            $table->integer('TEAM_ID')->unsigned();
            $table->integer('USER_ID')->unsigned();
            $table->timestamps();
            $table->foreign('TAC_ID')->references('TAC_ID')->on('t_tactic')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('TEAM_ID')->references('TEAM_ID')->on('t_team')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('USER_ID')->references('is')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->temporary();
        });

        /*
        $dropTempTables = DB::unprepared(
            DB::raw("
                DROP TABLE IF EXISTS t_card;
                DROP TABLE IF EXISTS t_goal;
                DROP TABLE IF EXISTS t_match;
                DROP TABLE IF EXISTS t_player_sta;
                DROP TABLE IF EXISTS t_player_tac_pos;
                DROP TABLE IF EXISTS t_rank;
                DROP TABLE IF EXISTS t_team_sta;
                DROP TABLE IF EXISTS t_team_tac;
                
                CREATE TEMPORARY TABLE IF NOT EXISTS t_card (
                    CRD_ID int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                    MATCH_ID int(10) UNSIGNED NOT NULL,
                    PLY_ID int(10) UNSIGNED NOT NULL,
                    T_P_PLY_ID int(10) UNSIGNED NOT NULL,
                    created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    updated_at timestamp NULL,
                    PRIMARY KEY (CRD_ID),
                    KEY t_card_match_id_foreign (MATCH_ID),
                    KEY t_card_ply_id_foreign (PLY_ID),
                    KEY t_card_t_p_ply_id_foreign (T_P_PLY_ID)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                
                CREATE TEMPORARY TABLE IF NOT EXISTS t_goal (
                    GOAL_ID int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                    MATCH_ID int(10) UNSIGNED NOT NULL,
                    PLY_ID int(10) UNSIGNED NOT NULL,
                    T_P_PLY_ID int(10) UNSIGNED NOT NULL,
                    GOAL_TIME INT(2) NOT NULL,
                    created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    updated_at timestamp NULL,
                    PRIMARY KEY (GOAL_ID),
                    KEY t_goal_match_id_foreign (MATCH_ID),
                    KEY t_goal_ply_id_foreign (PLY_ID),
                    KEY t_goal_t_p_ply_id_foreign (T_P_PLY_ID)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                
                CREATE TEMPORARY TABLE IF NOT EXISTS t_match (
                    MATCH_ID int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                    MATCH_T1_TAC int(10) UNSIGNED NOT NULL,
                    MATCH_T2_TAC int(10) UNSIGNED NOT NULL,
                    MATCH_T1 int(10) UNSIGNED NOT NULL,
                    MATCH_T2 int(10) UNSIGNED NOT NULL,
                    MATCH_GRP int(10) UNSIGNED NOT NULL,
                    MATCH_CAN_PHASE INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Differents phases of ANC',
                    MATCH_GRP_PHASE INT(10) UNSIGNED NOT NULL DEFAULT '1' COMMENT 'Group phase',
                    MATCH_CODE varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                    MATCH_T1_VAL int(11) DEFAULT NULL,
                    MATCH_T1_BON int(11) DEFAULT NULL,
                    MATCH_T1_APT int(11) DEFAULT NULL,
                    MATCH_T1_SCO int(11) DEFAULT NULL,
                    MATCH_T1_ATT_BON int(11) DEFAULT NULL,
                    MATCH_T1_MID_BON int(11) DEFAULT NULL,
                    MATCH_T1_DEF_BON int(11) DEFAULT NULL,
                    MATCH_T2_VAL int(11) DEFAULT NULL,
                    MATCH_T2_BON int(11) DEFAULT NULL,
                    MATCH_T2_APT int(11) DEFAULT NULL,
                    MATCH_T2_SCO int(11) DEFAULT NULL,
                    MATCH_T2_ATT_BON int(11) DEFAULT NULL,
                    MATCH_T2_MID_BON int(11) DEFAULT NULL,
                    MATCH_T2_DEF_BON int(11) DEFAULT NULL,
                    MATCH_SCORE varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                    MATCH_WINNER int(11) DEFAULT NULL,
                    id int(11) DEFAULT NULL,
                    created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    updated_at timestamp NULL,
                    PRIMARY KEY (MATCH_ID),
                    KEY t_match_match_t1_tac_foreign (MATCH_T1_TAC),
                    KEY t_match_match_t2_tac_foreign (MATCH_T2_TAC),
                    KEY t_match_match_t1_foreign (MATCH_T1),
                    KEY t_match_match_t2_foreign (MATCH_T2),
                    KEY t_match_match_grp_foreign (MATCH_GRP)
                ) ENGINE=MyISAM AUTO_INCREMENT=1148 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                
                CREATE TEMPORARY TABLE IF NOT EXISTS t_player_sta (
                    PLY_ID_STA int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                    PLY_ID int(10) UNSIGNED NOT NULL,
                    PLY_TIT int(11) NOT NULL,
                    PLY_SUB int(11) NOT NULL,
                    PLY_SHP int(11) NOT NULL,
                    PLY_INJ int(11) NOT NULL,
                    PLY_STA int(11) NOT NULL,
                    PLY_CRD int(11) NOT NULL,
                    PLY_DSQ int(11) NOT NULL,
                    PLY_SCO int(11) NOT NULL,
                    PLY_ASS int(11) NOT NULL,
                    created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    updated_at timestamp NULL,
                    PRIMARY KEY (PLY_ID_STA),
                    KEY t_player_sta_ply_id_foreign (PLY_ID)
                ) ENGINE=MyISAM AUTO_INCREMENT=553 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                
                CREATE TEMPORARY TABLE IF NOT EXISTS t_player_tac_pos (
                    TAC_ID_POS int(10) UNSIGNED NOT NULL,
                    PLY_ID int(10) UNSIGNED NOT NULL,
                    created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    updated_at timestamp NULL,
                    KEY t_player_tac_pos_ply_id_foreign (PLY_ID),
                    KEY t_player_tac_pos_tac_id_pos_foreign (TAC_ID_POS)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                
                CREATE TEMPORARY TABLE IF NOT EXISTS t_rank (
                    RNK_ID int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                    RNK_NAME varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
                    created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    updated_at timestamp NULL,
                    PRIMARY KEY (RNK_ID)
                ) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                
                CREATE TEMPORARY TABLE IF NOT EXISTS t_team_sta (
                    TEAM_STA_ID int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                    TEAM_ID int(10) UNSIGNED NOT NULL,
                    RNK_ID int(10) UNSIGNED NOT NULL,
                    GRP_ID int(10) UNSIGNED NOT NULL,
                    TEAM_WIN int(11) NOT NULL,
                    TEAM_LOS int(11) NOT NULL,
                    TEAM_DRAW int(11) NOT NULL,
                    TEAM_PTS int(11) NOT NULL,
                    TEAM_SCO int(11) NOT NULL,
                    TEAM_CON int(11) NOT NULL,
                    TEAM_AVG int(11) NOT NULL,
                    created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    updated_at timestamp NULL,
                    PRIMARY KEY (TEAM_STA_ID),
                    KEY t_team_sta_team_id_foreign (TEAM_ID),
                    KEY t_team_sta_rnk_id_foreign (RNK_ID),
                    KEY t_team_sta_grp_id_foreign (GRP_ID)
                ) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                
                CREATE TEMPORARY TABLE IF NOT EXISTS t_team_tac (
                    TAC_ID int(10) UNSIGNED NOT NULL,
                    TEAM_ID int(10) UNSIGNED NOT NULL,
                    USER_ID int(10) UNSIGNED NOT NULL,
                    created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    updated_at timestamp NULL,
                    KEY t_team_tac_tac_id_foreign (TAC_ID),
                    KEY t_team_tac_team_id_foreign (TEAM_ID),
                    KEY t_team_tac_user_id_foreign (USER_ID)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        
            ")
        );*/

        // DB::unprepared(File::get('path/to/SQL/file'));

        /*echo "<script>console.debug( \"1 ---------------------------------- PHP DEBUG ------------------------\" );</script>";
        $sql = base_path('temp_tables.sql');
        DB::unprepared(file_get_contents($sql));*/

        $dsn = '';
        $user = '';
        $password = '';

        /*
        DB_CONNECTION=mysql
        DB_HOST=db770926078.hosting-data.io
        DB_PORT=3306
        DB_DATABASE=db770926078
        DB_USERNAME=dbo770926078
        DB_PASSWORD=BD_appcan19

        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=appcan
        DB_USERNAME=emmaus
        DB_PASSWORD=83emmaus06
        */

        /*$environment = App::environment();
        if (App::environment(['local', 'staging'])) {
            // The environment is either local OR staging...
        }*/

        /*if (App::environment() === 'local') {
            // The environment is local
            $dsn = 'mysql:dbname=appcan;host=127.0.0.1';
            $user = 'emmaus';
            $password = '83emmaus06';
            echo "<script>console.debug( \"1 - PHP DEBUG\" );</script>";
        } else {
            $dsn = 'mysql:dbname=db770926078;host=db770926078.hosting-data.io';
            $user = 'dbo770926078';
            $password = 'BD_appcan19';
            echo "<script>console.debug( \"2 - PHP DEBUG\" );</script>";
        }


        $db = new PDO($dsn, $user, $password);
        $sql = file_get_contents($sql);
        $db->exec($sql);*/

        // DB::unprepared(file_get_contents(‘temp_tables.sql’));

        /*$command = "mysql --user={$vals['db_user']} --password='{$vals['db_pass']}' "
            . "-h {$vals['db_host']} -D {$vals['db_name']} < {$script_path}";

        $output = shell_exec($command . '/shellexec.sql');*/
        /*$command = "mysql --user={emmaus} --password='{83emmaus06}' "
            . "-h {127.0.0.1} -D {appcan} < {''}";

        $output = shell_exec($command . '/temp_tables.sql');*/


        /*$command = 'mysql'
            . ' --host=' . 'localhost'
            . ' --user=' . 'myuser'
            . ' --password=' . 'mypass'
            . ' --database=' . 'dbname'
            . ' --execute="SOURCE ' . $site_path;

        $output = shell_exec($command . 'backup.sql"');*/
    }

    public function forgot_password(){
        return view('user.forgot_password');
    }

    
}
