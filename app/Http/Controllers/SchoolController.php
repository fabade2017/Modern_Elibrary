<?php

namespace App\Http\Controllers;

use App\Services\CommandSchoolApi;

class SchoolController extends Controller
{
    public function domains(CommandSchoolApi $api)
    {
        $domains = $api->getSchoolDomains();
        return response()->json($domains);
    }

    public function students(CommandSchoolApi $api, $subdomain)
    {
        $students = $api->getStudentsBySchool($subdomain, request('page', 1), request('per_page', 50), request('search'));
        return response()->json($students);
    }
}
