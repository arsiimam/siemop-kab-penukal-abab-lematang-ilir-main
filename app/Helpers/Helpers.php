<?php

use App\Models\Announcement;
use App\Models\Setting;

/**
 * function get announcement
 */
function get_announcement()
{
    $user = Auth::user();
    return Announcement::where(function ($q) use ($user) {
        $q->where('institute_id', $user->institute_id)
            ->orWhere('institute_id', null);
    })
        ->where('readable', 0)
        ->count();
}

/** 
 * function setting
 */
function settingByUnique($unique)
{
    $rows = Setting::where('setting_name', $unique)->first();
    return $rows->setting_value;
}

/** 
 * funtion get month
 */
function month_list()
{
    $rows = [
        '01' => 'JANUARI',
        '02' => 'FEBRUARI',
        '03' => 'MARET',
        '04' => 'APRIL',
        '05' => 'MEI',
        '06' => 'JUNI',
        '07' => 'JULI',
        '08' => 'AGUSTUS',
        '09' => 'SEPTEMBER',
        '10' => 'OKTOBER',
        '11' => 'NOVEMBER',
        '12' => 'DESEMBER',
    ];

    return $rows;
}

/**
 * convert month
 */
function convert_month($month)
{
    $month_list = month_list();

    try {
        return $month_list[$month];
    } catch (\Throwable $th) {
        return '-';
    }
}

/**
 * convert format date
 */
function convert_format_date($date)
{
    return date('d-m-Y', strtotime($date));
}

/**
 * this month
 */
function recent_month()
{
    $month_list = month_list();

    return ucfirst(strtolower($month_list[date('m')])) . ' ' . date('Y');
}

/**
 * function year
 */
function year_list()
{
    $start = 2020;
    $end = date('Y');
    $rows = array();

    for ($i = $end; $i >= $start; $i--) {
        $rows[$i] = $i;
    }

    return $rows;
}

/**
 * format date time
 */
function dateTime_format($item)
{
    return date('d-m-Y H:i:s', strtotime($item));
}


/**
 * number format using dot (.)
 */
function custom_number_format($val)
{
    return $val != null || $val != 0 ? number_format($val, 0, ',', '.') : '';
}

/** 
 * update ENV
 */
function setEnvironmentValue(array $values)
{
    $envFile = app()->environmentFilePath();
    $str     = file_get_contents($envFile);
    if (count($values) > 0) {
        foreach ($values as $envKey => $envValue) {
            $keyPosition       = strpos($str, "{$envKey}=");
            $endOfLinePosition = strpos($str, "\n", $keyPosition);
            $oldLine           = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
            // If key does not exist, add it
            if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                $str .= "{$envKey}='{$envValue}'\n";
            } else {
                $str = str_replace($oldLine, "{$envKey}='{$envValue}'", $str);
            }
        }
    }
    $str = substr($str, 0, -1);
    $str .= "\n";
    if (!file_put_contents($envFile, $str)) {
        return false;
    }

    return true;
}
