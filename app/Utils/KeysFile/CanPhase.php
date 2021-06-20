<?php
/**
 * Created by PhpStorm.
 * User: emmaus
 * Date: 11/03/19
 * Time: 12:17
 */

namespace App\Utils\KeysFile;


class CanPhase { // extends SplEnum {

    /*const __default = self::GROUP_PHASE;

    const GROUP_PHASE = 0;
    const EIGHT_FINAL = 1;
    const QUARTER_FINAL = 2;
    const SEMI_FINAL = 3;
    const THIRD_PLACE_FINAL = 4;
    const FINAL = 5;

    // --- SplEnum::getConstList method
    public function getValue($key) {
        $declaredElems = $this->getConstList();
        if(array_key_exists($key, $declaredElems)){
            $r = new \ReflectionClass($this);
            return $r->getConstant($key);
        }else{
            return self::__default;
        }
    }*/

    public static $GROUP_PHASE = 0;
    public static $EIGHT_FINAL = 1;
    public static $QUARTER_FINAL = 2;
    public static $SEMI_FINAL = 3;
    public static $THIRD_PLACE_FINAL = 4;
    public static $FINAL = 5;

    public static function getPhase() {
        $can_phase = [
            CanPhase::$GROUP_PHASE => ['KEY' => CanPhase::$GROUP_PHASE, 'PHASE' => 'GROUP PHASE'],
            CanPhase::$EIGHT_FINAL => ['KEY' => CanPhase::$EIGHT_FINAL, 'PHASE' => 'EIGHT FINAL'],
            CanPhase::$QUARTER_FINAL => ['KEY' => CanPhase::$QUARTER_FINAL, 'PHASE' => 'QUARTER FINAL'],
            CanPhase::$SEMI_FINAL => ['KEY' => CanPhase::$SEMI_FINAL, 'PHASE' => 'SEMI FINAL'],
            CanPhase::$THIRD_PLACE_FINAL => ['KEY' => CanPhase::$THIRD_PLACE_FINAL, 'PHASE' => 'THIRD PLACE FINAL'],
            CanPhase::$FINAL => ['KEY' => CanPhase::$FINAL, 'PHASE' => 'FINAL']
        ];

        return $can_phase;
    }
}

?>