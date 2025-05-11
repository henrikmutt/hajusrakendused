<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ClassmateApiController extends Controller
{
    public function fetch()
    {
        return Cache::remember('classmate_api_data', 60, function () {
            $response = Http::get('https://hajus.tak23mand.itmajakas.ee/api/favourite/07b4cc44-9042-4944-b1dc-56eac757ca4f');

            if ($response->successful()) {
                return $response->json();
            }

            return response()->json(['error' => 'Unable to fetch'], 500);
        });
    }
}


