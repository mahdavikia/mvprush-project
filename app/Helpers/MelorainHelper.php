<?php

use App\Models\Permission;
use App\Models\User;
use App\Models\UserLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Melorain {

    public static function hash($string){
        $h = md5($string);
        return $h;
    }
    public static function getVerTypeName(string $ver_type = null,$humanity = 0){
        if(is_null($ver_type)) return '';
        if($humanity == 0){
            return match ($ver_type){
                '0' => 'a',
                '1' => 'b',
                '2' => 'r'
            };
        } else {
            return match ($ver_type){
                '0' => 'Alpha',
                '1' => 'Beta',
                '2' => 'Release'
            };
        }
    }
    public static function userLog($action):void{
        UserLog::create([
            'user_id' => Auth::user()->id,
            'action_name' => $action
        ]);
    }

    public static function jalali_to_gregorian($jalali_date = null,$_3_hourses = null){
        if(is_null($jalali_date)) return null;
        $jalali_time_arr = explode(':',substr($jalali_date, -8));
        $h = intval($jalali_time_arr[0]);
        $m = intval($jalali_time_arr[1]);
        $s = intval($jalali_time_arr[2]);
        $jalali_date_stand = substr($jalali_date,0,10);
        $expire_at_persian_arr = explode('/',$jalali_date_stand);
        $jmktime = jmktime($h,$m,$s,$expire_at_persian_arr[1],$expire_at_persian_arr[2],$expire_at_persian_arr[0],'','Asia/Tehran');
        if($_3_hourses){
            $jmktime -= 12600;
        }

        return date('Y-m-d H:i:s',$jmktime);
    }

    public static function url_title($string){
        $separator = '-';
        $wordLimit=0;

        if($wordLimit != 0){
            $wordArr = explode(' ', $string);
            $string = implode(' ', array_slice($wordArr, 0, $wordLimit));
        }

        $quoteSeparator = preg_quote($separator, '#');

        $trans = array(
            '&.+?;'                 => '',
            '[^\w\d _-]'            => '',
            '\s+'                   => $separator,
            '('.$quoteSeparator.')+'=> $separator
        );

        $string = strip_tags($string);
        foreach ($trans as $key => $val){
            $string = preg_replace('#'.$key.'#iu', $val, $string);
        }

        $string = strtolower($string);

        return trim(trim($string, $separator));
    }

    public static function getParameter($name){
        return Session::get('parameter.'.$name) ?? null;
    }

    public static function gregorian_to_jalali($g_date = null, $show_time = true){
        if(is_null($g_date)) return null;
        $expire_at_ger_arr = explode('-',substr($g_date,0,10));

        $j_arr = gregorian_to_jalali($expire_at_ger_arr[0],$expire_at_ger_arr[1],$expire_at_ger_arr[2]);
        if($show_time === true) {
            if (strlen($j_arr[1]) < 2) $j_arr[1] = '0' . $j_arr[1];
            if (strlen($j_arr[2]) < 2) $j_arr[2] = '0' . $j_arr[2];
            return implode('/', $j_arr) . ' ' . substr($g_date, -8);
        }
        else
            return implode('/',$j_arr);
    }
    public static function jalali_to_timestamp($date){
        $date = substr($date,0,10);
        $splits = explode('-',$date);
        $ts = jmktime(0,0,0,$splits[1],$splits[2],$splits[0]);
        return $ts;
    }
    public static function niceDate($format,$date_main){
        $date = substr($date_main,0,10);
        $time = substr($date_main,11,8);
        $splits = explode('-',$date);
        $times = explode(':',$time);
        return jdate($format,mktime($times[0],$times[1],$times[2],intval($splits[1]),intval($splits[2]),intval($splits[0])),'','Asia/Tehran','en');
    }
    public static function substr($str,$len){
        return mb_substr(strip_tags($str),0,$len).'...';
    }

    public static function getAccess($permission_name): bool
    {
        if(session('get_'.Auth::user()->id.'_'.$permission_name) === true) {
            return true;
        }else{

            $user_id = Auth::user()->id;
            $current_user_role = User::find($user_id);
            $curret_user_permissions = Permission::where(['role_id' => $current_user_role->role->id])->get();

            foreach($curret_user_permissions as $per){
                if($permission_name == $per->route){
                    session()->put('get_'.Auth::user()->id.'_'.$permission_name,true);
                    session()->save();
                    return true;
                }
            }
        }
        return false;
    }

    public static function ta_persian_num($string) {
        //arrays of persian and latin numbers
        $persian_num = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
        $latin_num = range(0, 9);

        $string = str_replace($latin_num, $persian_num, $string);

        return $string;
    }
    public static function ta_latin_num($string)
    {
        //arrays of persian and latin numbers
        $persian_num = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
        $latin_num = range(0, 9);

        $string = str_replace($persian_num, $latin_num, $string);

        return $string;
    }

}
