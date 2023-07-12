<?php

namespace common\models;

use common\models\lower_management\Country as ModelsLower_managementCountry;
use common\models\wikiinvestment\Country;
use Exception;
use frontend\models\lower_management\Country as Lower_managementCountry;
use Yii;

class ModelMaster extends \yii\db\ActiveRecord
{

    const DATE_THAI_TYPE_FULL = 1;
    const DATE_THAI_TYPE_SHORT = 2;
    const TAB_TYPE_PHOTO = 1;
    const TAB_TYPE_DETAIL = 2;
    const TAB_TYPE_AMENITY = 3;
    const TAB_TYPE_MAP = 4;
    const TAB_TYPE_STREET_VIEW = 5;
    const USER_ASSET_TYPE_OWNER = 1;
    const USER_ASSET_TYPE_AGENCY = 2;
    const STATUS_ACTIVE = 0x1;
    const STATUS_INACTIVE = 0x2;

    public $searchText;

    public $monthFull = [
        1 => 'มกราคม',
        'กุมภาพันธ์',
        'มีนาคม',
        'เมษายน',
        'พฤษภาคม',
        'มิถุนายน',
        'กรกฎาคม',
        'สิงหาคม',
        'กันยายน',
        'ตุลาคม',
        'พฤศจิกายน',
        'ธันวาคม',
    ];
    public $monthShort = [
        1 => 'ม.ค.',
        'ก.พ.',
        'มี.ค.',
        'เม.ย.',
        'พ.ค.',
        'มิ.ย.',
        'ก.ค.',
        'ส.ค.',
        'ก.ย.',
        'ต.ค.',
        'พ.ย.',
        'ธ.ค.',
    ];

    public function writeToFile($fileName, $text, $mode = 'w+')
    {
        $handle = fopen($fileName, $mode);
        fwrite($handle, $text);
        fclose($handle);
    }
    public function thaiDate($date, $type = self::DATE_THAI_TYPE_FULL)
    {
        $d = explode('-', $date);
        $year = $d[0] + 543;
        $month = ($type == self::DATE_THAI_TYPE_FULL) ? $this->monthFull[(int) $d[1]] : $this->monthShort[(int) $d[1]];
        $date = (int) $d[2];

        return $date . ' ' . $month . ' ' . $year;
    }
    public static function engDate($date, $type = self::DATE_THAI_TYPE_FULL)
    {
        $monthFullEng = [
            1 => 'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December',
        ];
        $monthShortEng = [
            1 => 'Jan',
            'Feb',
            'Mar',
            'Apr',
            'May',
            'Jun',
            'Jul',
            'Aug',
            'Sep',
            'Oct',
            'Nov',
            'Dec',
        ];

        $d = explode('-', $date);
        if (count($d) >= 3) {
            $year = $d[0];
            $month = ($type == self::DATE_THAI_TYPE_FULL) ? $monthFullEng[(int) $d[1]] : $monthShortEng[(int) $d[1]];
            $date = (int) $d[2];
            return $date . ' ' . $month . ' ' . $year;
        } else {
            return null;
        }
    }
    public static function dateNumber($dateFull)
    {
        $d = explode(' ', $dateFull);
        $days = $d[0];
        $day = explode('-', $days);
        $year = $day[0];
        $month = $day[1];
        $date = $day[2];
        return $date . '/' . $month . '/' . $year;
    }
    public static function dateExcel($dateFull)
    {
        if ($dateFull != '' && $dateFull != null) {
            $d = explode(' ', $dateFull);
            $days = $d[0];
            $day = explode('-', $days);
            $year = $day[0];
            $month = $day[1];
            $date = $day[2];
            return $year . '/' . $month . '/' . $date;
        } else {
            return null;
        }
    }
    public static function dateNumberShort($dateFull)
    {
        $d = explode(' ', $dateFull);
        $days = $d[0];
        $day = explode('-', $days);
        $year = substr($day[0], -2);
        $month = $day[1];
        $date = $day[2];
        return $date . '/' . $month . '/' . $year;
    }
    public static function monthEng($month, $type = self::DATE_THAI_TYPE_FULL)
    {
        $monthFullEng = [
            1 => 'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December',
        ];
        $monthShortEng = [
            1 => 'Jan',
            'Feb',
            'Mar',
            'Apr',
            'May',
            'Jun',
            'Jul',
            'Aug',
            'Sep',
            'Oct',
            'Nov',
            'Dec',
        ];
        $month = ($type == self::DATE_THAI_TYPE_FULL) ? $monthFullEng[(int) $month] : $monthShortEng[(int) $month];
        return $month;
    }
    public function getMonthText($month, $type = 1)
    {
        return ($type == 1) ? $this->monthFull[$month] : $this->monthShort[$month];
    }
    public static function encodeParams($params)
    {
        //        return urlencode(base64_encode(base64_encode(Yii::$app->getSecurity()->encryptByPassword(json_encode($params), Yii::$app->params['secureKey']))));

        $text = json_encode($params);
        //        $enc = mcrypt_encrypt(MCRYPT_BLOWFISH, Yii::$app->params['secureKey'], $text, MCRYPT_MODE_ECB, Yii::$app->params['secureVi']);
        $enc = openssl_encrypt($text, "aes-256-cbc", Yii::$app->params['secureKey'], OPENSSL_RAW_DATA, substr(Yii::$app->params['secureKey'], 0, 16));
        $enc = str_replace(array('+', '/'), array('-', '_'), base64_encode($enc));
        return rawurlencode($enc);
    }

    public static function decodeParams($hash)
    {
        //	    return json_decode(Yii::$app->getSecurity()->decryptByPassword(base64_decode(base64_decode(urldecode($hash))), Yii::$app->params['secureKey']), true);
        $hash = str_replace(array('-', '_'), array('+', '/'), $hash);
        $enc = base64_decode($hash);
        //        $enc = mcrypt_decrypt(MCRYPT_BLOWFISH, Yii::$app->params['secureKey'], $enc, MCRYPT_MODE_ECB, Yii::$app->params['secureVi']);
        $enc = openssl_decrypt($enc, "aes-256-cbc", Yii::$app->params['secureKey'], OPENSSL_RAW_DATA, substr(Yii::$app->params['secureKey'], 0, 16));
        return json_decode(trim($enc), true);
    }

    public static function encodeParamsBrand($params)
    {
        //        return urlencode(base64_encode(base64_encode(Yii::$app->getSecurity()->encryptByPassword(json_encode($params), Yii::$app->params['secureKey']))));

        $text = json_encode($params);
        $enc = openssl_encrypt($text, "aes-256-cbc", Yii::$app->params['secureKey'], OPENSSL_RAW_DATA, substr(Yii::$app->params['secureKey'], 0, 16));
        $enc = str_replace(array('+', '/'), array('-', '_'), base64_encode($enc));
        //$enc = str_replace('-', '_', base64_encode(str_replace(array('+', '/'), array('-', ''), $enc)));
        return rawurlencode($enc);
    }

    public static function month()
    {
        $month["01"] = "Jan";
        $month["02"] = "Feb";
        $month["03"] = "Mar";
        $month["04"] = "Apr";
        $month["05"] = "May";
        $month["06"] = "Jun";
        $month["07"] = "Jul";
        $month["08"] = "Aug";
        $month["09"] = "Sep";
        $month["10"] = "Oct";
        $month["11"] = "Nov";
        $month["12"] = "Dec";
        return $month;
    }
    public static function shotMonthValue($monthText)
    {
        switch ($monthText) {
            case "Jan":
                $month = 1;
                break;
            case "Feb":
                $month = 2;
                break;
            case "Mar":
                $month = 3;
                break;
            case "Apr":
                $month = 4;
                break;
            case "May":
                $month = 5;
                break;
            case "Jun":
                $month = 6;
                break;
            case "Jul":
                $month = 7;
                break;
            case "Aug":
                $month = 8;
                break;
            case "Sep":
                $month = 9;
                break;
            case "Oct":
                $month = 10;
                break;
            case "Nov":
                $month = 11;
                break;
            case "Dec":
                $month = 12;
                break;
            default:
                $month = null;
        }
        return $month;
    }
    public static function shotMonthText($monthValue)
    {
        switch ($monthValue) {
            case 1:
                $month = "Jan";
                break;
            case 2:
                $month = "Feb";
                break;
            case 3:
                $month = "Mar";
                break;
            case 4:
                $month = "Apr";
                break;
            case 5:
                $month = "May";
                break;
            case 6:
                $month = "Jun";
                break;
            case 7:
                $month = "Jul";
                break;
            case 8:
                $month = "Aug";
                break;
            case 9:
                $month = "Sep";
                break;
            case 10:
                $month = "Oct";
                break;
            case 11:
                $month = "Nov";
                break;
            case 12:
                $month = "Dec";
                break;
            default:
                $month = null;
        }
        return $month;
    }
}
