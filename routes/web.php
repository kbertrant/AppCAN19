<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/profile', 'HomeController@profile')->name('profile');
Route::post('/profile',  ['as' => 'users.update', 'uses' => 'HomeController@update']);
//Route::post('postprofile', 'HomeController@postprofile')->name('postprofile');
Route::get('team/view_group', 'TeamController@view_group')->name('team.view_group');
Route::get('team/team_sta', 'TeamController@team_sta')->name('team.team_sta');
Route::post('/team/list_team', 'TeamController@list_team')->name('team.list_team');;
Route::get('team/list_team', 'TeamController@list_team')->name('team.list_team');
Route::get('team/team_sta', 'TeamController@team_sta')->name('team.team_sta');

Route::get('team/eight_stage', 'TeamController@eight_stage')->name('team.eight_stage');
Route::get('team/eight_crossing', 'TeamController@eight_crossing')->name('team.eight_crossing');
Route::get('team/quarter_stage', 'TeamController@quarter_stage')->name('team.quarter_stage');
Route::get('team/quarter_crossing', 'TeamController@quarter_crossing')->name('team.quarter_crossing');
Route::get('team/semi_stage', 'TeamController@semi_stage')->name('team.semi_stage');
Route::get('team/semi_crossing', 'TeamController@semi_crossing')->name('team.semi_crossing');
Route::get('team/final_stage', 'TeamController@final_stage')->name('team.final_stage');
Route::get('team/final_crossing', 'TeamController@final_crossing')->name('team.final_crossing');

Route::get('player/player_loose', 'PlayerController@player_loose')->name('player.player_loose');
Route::get('player/player_sta', 'PlayerController@player_sta')->name('player.player_sta');
Route::get('player/player_sta/{id}', 'PlayerController@player_sta')->name('player.player_sta');
Route::get('player/list_player', 'PlayerController@list_player')->name('player.list_player');
Route::get('player/stats_player', 'PlayerController@stats_player')->name('player.stats_player');

Route::get('match/match_result', 'MatchController@match_result')->name('match.match_result');
Route::get('match/next_match', 'MatchController@next_match')->name('match.next_match');
Route::get('match/match_process', 'MatchController@match_process')->name('match.match_process');
Route::get('match/match_process2', 'MatchController@match_process2')->name('match.match_process2');
Route::get('match/half_time', 'MatchController@half_time')->name('match.half_time');
Route::get('match/match_end', 'MatchController@match_end')->name('match.match_end');
Route::get('match/match_load', 'MatchController@match_load')->name('match.match_load');

Route::get('/credit', 'HomeController@credit')->name('credit');
Route::post('/credit','HomeController@payment')->name('payment');

Route::get('tactic', 'TacticController@tactic')->name('tactic');

Route::get('rank/view_ranking', 'RankController@view_ranking')->name('rank.view_ranking');

Route::get('user/forgot_password', 'HomeController@forgot_password')->name('user.forgot_password');

Route::get('menu_main', 'HomeController@menu_main')->name('menu_main');

Route::get('disconnect', 'PlayerController@disconnect')->name('disconnect');

// URL::forceScheme('https');
