<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CommandSchoolApi
{
    protected $baseUrl;
    protected $publicKey;
    protected $secretKey;

    public function __construct()
    {
        $this->baseUrl   = config('services.commandschool.base_url');
        $this->publicKey = config('services.commandschool.public_key');
        $this->secretKey = config('services.commandschool.secret_key');
    }

    protected function client()
    {
        return Http::withHeaders([
            'X-Public-Key' => $this->publicKey,
            'X-Secret-Key' => $this->secretKey,
            'Accept'       => 'application/json',
        ]);
    }

    // 1. Get all school domains
    public function getSchoolDomains()
    {
        $response = $this->client()->get("{$this->baseUrl}/api/external/school-domains");
        return $response->json();
    }

    // 2. Get students by school subdomain
    public function getStudentsBySchool($subdomain, $page = 1, $perPage = 50, $search = null)
    {
        $query = [
            'page' => $page,
            'per_page' => $perPage,
        ];

        if ($search) {
            $query['search'] = $search;
        }

        $response = $this->client()->get("{$this->baseUrl}/api/external/schools/{$subdomain}/students", $query);

        return $response->json();
    }
}
