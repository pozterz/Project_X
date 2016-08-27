<?php

namespace App\Helpers;
use DateTime;
class DateHelper {

    public static function thformat($date) {
    	$THmonth = array('ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค');
        if ($date) {
            $dt = new DateTime($date);
            $d = $dt->format("d/m/Y");
            $d = explode("/", $d); 
            $t = $dt->format("H:i:s");
            $str = $d[0] . " " . $THmonth[intval($d[1])-1] . " " . ($d[2]+543) . " " . $t;
        return $str;
      }
   }
}
