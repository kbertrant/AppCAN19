<?php
/**
 * Created by PhpStorm.
 * User: emmaus
 * Date: 11/03/19
 * Time: 12:12
 */

namespace App\Utils\KeysFile;


class DaysList {  // extends SplEnum {

    /*const __default = self::DAY_ONE;

    const DAY_ONE = 1;
    const DAY_TWO = 2;
    const DAY_THREE = 3;

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

    public static $DAY_ONE = 1;
    public static $DAY_TWO = 2;
    public static $DAY_THREE = 3;
    public static $UNKNOWN = 4;

    public static function getPhase() {
        $group_phase = [
            DaysList::$DAY_ONE => ['KEY' => DaysList::$DAY_ONE, 'DAY' => 'DAY ONE'],
            DaysList::$DAY_TWO => ['KEY' => DaysList::$DAY_TWO, 'DAY' => 'DAY TWO'],
            DaysList::$DAY_THREE => ['KEY' => DaysList::$DAY_THREE, 'DAY' => 'DAY THREE'],
            DaysList::$UNKNOWN => ['KEY' => DaysList::$UNKNOWN, 'DAY' => 'UNKNOWN']
        ];

        return $group_phase;
    }
}

?>