<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class PokemonController extends Controller
{
    public function index($generation)
    {
        $response = Http::get('https://pokeapi.co/api/v2/generation/'.$generation);

        if ($response->successful()) {
            $data = $response->json();
            $region = $data['main_region']['name'];
            $moves = array_map(function ($move) {
                return $move['name'];
            }, $data['moves']);

            // Instead of returning a JSON response, return a view and pass the data to the view
            return view('pokemon', [
                'region' => $region,
                'moves' => $moves,
            ]);
        } else {
            return view('error', ['message' => 'Error fetching Pokemon']);
        }
    }
    public function show($pokemon)
    {
        $response = Http::get('https://pokeapi.co/api/v2/pokemon/'.$pokemon);

        if ($response->successful()) {
            $data = $response->json();
            return view('pokemon-search', [
                'pokemonData' => $data,
            ]);
        } else {
            return view('error', ['message' => 'Error fetching Pokemon']);
        }
    }

}
