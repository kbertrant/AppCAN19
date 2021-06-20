<?php
/**
 * Created by PhpStorm.
 * User: emmaus
 * Date: 08/03/19
 * Time: 10:25
 */

namespace App\Utils\KeysFile;


class CountryName
{
    public static $ALGERIA = 'ALGERIA';
    public static $ANGOLA = 'ANGOLA';
    public static $BENIN = 'BENIN';
    public static $BISSAU_GUINEA = 'BISSAU GUINEA';
    public static $BURUNDI = 'BURUNDI';
    public static $CAMEROON = 'CAMEROON';
    public static $RD_CONGO = 'DR CONGO';
    public static $GUINEA = 'GUINEA';
    public static $EGYPT = 'EGYPT';
    public static $KENYA = 'KENYA';
    public static $MADAGASCAR = 'MADAGASCAR';
    public static $GHANA = 'GHANA';
    public static $IVORY_COAST = 'IVORY COAST';
    public static $MAURITANIA = 'MAURITANIA';
    public static $NAMIBIA = 'NAMIBIA';
    public static $MALI = 'MALI';
    public static $MOROCCO = 'MOROCCO';
    public static $NIGERIA = 'NIGERIA';
    public static $SENEGAL = 'SENEGAL';
    public static $SOUTH_AFRICA = 'SOUTH AFRICA';
    public static $TANZANIA = 'TANZANIA';
    public static $TUNISIA = 'TUNISIA';
    public static $UGANDA = 'UGANDA';
    public static $ZIMBABWE = 'ZIMBABWE';

    public static function getCountries() {
        $countries = [
            CountryName::$ALGERIA => ['NAME' => CountryName::$ALGERIA, 'IMG' => 'img/eq_algerie.png'],
            CountryName::$ANGOLA => ['NAME' => CountryName::$ANGOLA, 'IMG' => 'img/eq_angola.png'],
            CountryName::$BENIN => ['NAME' => CountryName::$BENIN, 'IMG' => 'img/eq_benin.png'],
            CountryName::$BISSAU_GUINEA => ['NAME' => CountryName::$BISSAU_GUINEA, 'IMG' => 'img/eq_gui_bissau.png'],
            CountryName::$BURUNDI => ['NAME' => CountryName::$BURUNDI, 'IMG' => 'img/eq_burundi.png'],
            CountryName::$CAMEROON => ['NAME' => CountryName::$CAMEROON, 'IMG' => 'img/eq_cmr.png'],
            CountryName::$GHANA => ['NAME' => CountryName::$GHANA, 'IMG' => 'img/eq_gha.png'],
            CountryName::$GUINEA => ['NAME' => CountryName::$GUINEA, 'IMG' => 'img/eq_guinea.png'],
            CountryName::$IVORY_COAST => ['NAME' => CountryName::$IVORY_COAST, 'IMG' => 'img/eq_civ.png'],
            CountryName::$EGYPT => ['NAME' => CountryName::$EGYPT, 'IMG' => 'img/eq_egypt.png'],
            CountryName::$KENYA => ['NAME' => CountryName::$KENYA, 'IMG' => 'img/eq_kenya.png'],
            CountryName::$MADAGASCAR => ['NAME' => CountryName::$MADAGASCAR, 'IMG' => 'img/eq_madagascar.png'],
            CountryName::$MALI => ['NAME' => CountryName::$MALI, 'IMG' => 'img/eq_mali.png'],
            CountryName::$MAURITANIA => ['NAME' => CountryName::$MAURITANIA, 'IMG' => 'img/eq_mauritania.png'],
            CountryName::$MOROCCO => ['NAME' => CountryName::$MOROCCO, 'IMG' => 'img/eq_maroc.png'],
            CountryName::$NAMIBIA => ['NAME' => CountryName::$NAMIBIA, 'IMG' => 'img/eq_namibia.png'],
            CountryName::$NIGERIA => ['NAME' => CountryName::$NIGERIA, 'IMG' => 'img/eq_nigeria.png'],
            CountryName::$TANZANIA => ['NAME' => CountryName::$TANZANIA, 'IMG' => 'img/eq_tanzania.png'],
            CountryName::$RD_CONGO => ['NAME' => CountryName::$RD_CONGO, 'IMG' => 'img/eq_rdc.jpg'],
            CountryName::$SENEGAL => ['NAME' => CountryName::$SENEGAL, 'IMG' => 'img/eq_senegal.png'],
            CountryName::$SOUTH_AFRICA => ['NAME' => CountryName::$SOUTH_AFRICA, 'IMG' => 'img/eq_rsa.png'],
            CountryName::$TUNISIA => ['NAME' => CountryName::$TUNISIA, 'IMG' => 'img/eq_tunisie.png'],
            CountryName::$UGANDA => ['NAME' => CountryName::$UGANDA, 'IMG' => 'img/eq_uganda.png'],
            CountryName::$ZIMBABWE => ['NAME' => CountryName::$ZIMBABWE, 'IMG' => 'img/eq_zimbabwe.png']
        ];

        return $countries;
    }

}
