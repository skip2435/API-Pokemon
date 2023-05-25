<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class PokemonController extends Controller
{
    public function index()
    {
        // Fetch the generations.
        $response = Http::get('https://pokeapi.co/api/v2/generation/');

        if ($response->successful()) {
            $data = $response->json();
            $generations = array_map(function ($generation) {
                return $generation['name'];
            }, $data['results']);

            return view('pokemon', [
                'generations' => $generations,
            ]);
        } else {
            return view('error', ['message' => 'Error fetching generations']);
        }
    }

    public function fetchRegion($generation)
    {
        // Fetch the region of the generation.
        $response = Http::get('https://pokeapi.co/api/v2/generation/'.$generation);

        if ($response->successful()) {
            $data = $response->json();
            $region = $data['main_region']['url'];

            return redirect()->action([PokemonController::class, 'fetchLocations'], ['region' => $region]);
        } else {
            return view('error', ['message' => 'Error fetching region']);
        }
    }

    public function fetchLocations($region)
    {
        // Fetch the locations of the region.
        $response = Http::get($region);

        if ($response->successful()) {
            $data = $response->json();
            $locations = array_map(function ($location) {
                return $location['url'];
            }, $data['locations']);

            return view('pokemon', [
                'locations' => $locations,
            ]);
        } else {
            return view('error', ['message' => 'Error fetching locations']);
        }
    }

    public function fetchLocationAreas($location)
    {
        // Fetch the location areas of the location.
        $response = Http::get($location);

        if ($response->successful()) {
            $data = $response->json();
            $locationAreas = array_map(function ($locationArea) {
                return $locationArea['url'];
            }, $data['areas']);

            return view('pokemon', [
                'locationAreas' => $locationAreas,
            ]);
        } else {
            return view('error', ['message' => 'Error fetching location areas']);
        }
    }

    public function fetchEncounters($locationArea)
    {
        // Fetch the encounter rates of the location area.
        $response = Http::get($locationArea);

        if ($response->successful()) {
            $data = $response->json();
            $encounters = $data['pokemon_encounters'];

            return view('pokemon', [
                'encounters' => $encounters,
            ]);
        } else {
            return view('error', ['message' => 'Error fetching encounters']);
        }
    }
}
