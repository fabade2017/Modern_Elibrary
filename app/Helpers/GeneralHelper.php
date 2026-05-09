<?php

if (! function_exists('getSchoolByAdmin')) {
    function getSchoolByAdmin($adminId) {
        return DB::table('school_admin_mappers')
            ->join('schools', 'school_admin_mappers.school', '=', 'schools.id')
            ->where('school_admin_mappers.adminemail', $adminId)
            ->select('schools.*')
            ->get();
    }
}
if (!function_exists('label')) {
    function label($text)
    {
        return str_ireplace(['Group', 'Groups'], ['Arm', 'Arms'], $text);
    }
}