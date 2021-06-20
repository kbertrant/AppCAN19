<?php 
$match_m01 = array('3','7');
$match_m02 = array('12','21');
$match_m03 = array('3','12');
$match_m04 = array('7','21');
$match_m05 = array('3','21');
$match_m06 = array('12','7');

$match_m11 = array('0','8');
$match_m12 = array('13','15');
$match_m13 = array('0','13');
$match_m14 = array('8','15');
$match_m15 = array('0','15');
$match_m16 = array('8','13');

$match_m21 = array('1','9');
$match_m22 = array('17','23');
$match_m23 = array('1','17');
$match_m24 = array('9','23');
$match_m25 = array('1','23');
$match_m26 = array('17','9');

$match_m31 = array('2','5');
$match_m32 = array('16','20');
$match_m33 = array('2','16');
$match_m34 = array('5','20');
$match_m35 = array('2','20');
$match_m36 = array('16','5');

$match_m41 = array('4','10');
$match_m42 = array('18','19');
$match_m43 = array('4','18');
$match_m44 = array('10','19');
$match_m45 = array('4','19');
$match_m46 = array('18','10');

$match_m51 = array('6','11');
$match_m52 = array('14','22');
$match_m51 = array('6','14');
$match_m52 = array('11','22');
$match_m51 = array('6','22');
$match_m52 = array('14','11');

$match_phase = array('1');

function team_val($team_id){
    $team_val = DB::select("SELECT COUNT(PLY_VAL) FROM t_player WHERE PLY_ID = (".$team_id.")");
    $somme = $team_val/11;
    return $somme;
}

