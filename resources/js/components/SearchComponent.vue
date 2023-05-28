<template>
<div class="min-h-screen bg-red-100 flex flex-col pokemon-font">
  <nav class="bg-red-500 text-white p-3">
    <ul class="flex justify-around">
      <li class="neon hover:bg-red-400 transition-colors duration-200 rounded px-2 py-1"><a href="/" class="text-xl font-semibold">Moves</a></li>
      <li class="neon hover:bg-red-400 transition-colors duration-200 rounded px-2 py-1"><a href="/encounters" class="text-xl font-semibold">Pokemon</a></li>
      <li class="neon hover:bg-red-400 transition-colors duration-200 rounded px-2 py-1"><a href="/location-search" class="text-xl font-semibold">Locations</a></li>
      <li class="neon hover:bg-red-400 transition-colors duration-200 rounded px-2 py-1"><a href="/poke-search" class="text-xl font-semibold">Poke Search</a></li>
    </ul>
  </nav>

  <div class="flex flex-col items-center justify-center flex-grow px-4 sm:px-0">
    <h1 class="text-4xl font-bold mb-4 text-red-600">Pokemon Search</h1>
    <div class="relative w-full sm:w-auto">
      <input 
        type="text" 
        v-model="pokemonName" 
        @input="fetchPokemonList" 
        placeholder="Lowercase letters..."
        class="px-4 py-2 rounded-lg border-0 shadow-lg mb-4 text-lg bg-white text-gray-700 placeholder-gray-500 w-full"
      >
      <div class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg shadow-lg" v-if="pokemonSuggestions.length">
        <div 
          class="px-4 py-2 hover:bg-gray-200 cursor-pointer" 
          v-for="suggestion in pokemonSuggestions" 
          :key="suggestion.name"
          @click="selectPokemon(suggestion.name)"
        >
          {{ suggestion.name }}
        </div>
      </div>
    </div>
    <transition name="fade">
      <div v-if="pokemonData.name" class="w-full max-w-lg bg-white shadow-lg rounded-lg overflow-hidden mx-auto mb-4">
        <div class="py-4 px-6">
          <div class="flex flex-col justify-center items-center">
            <h2 class="text-2xl font-bold text-red-500">{{ pokemonData.name }}</h2>
            <img :src="pokemonData.sprites.front_default" alt="Pokemon image" class="h-30 w-30 object-cover">
          </div>
          <div class="grid grid-cols-2 gap-4 mt-4 border border-red-500 p-4 rounded-md">
            <div class="flex justify-between items-center">
              <h4 class="font-semibold text-red-600">HP:</h4>
              <h4 class="font-semibold text-gray-700">{{ pokemonData.stats[0].base_stat }}</h4>
            </div>
            <div class="flex justify-between items-center">
              <h4 class="font-semibold text-red-600">Special Attack:</h4>
              <h4 class="font-semibold text-gray-700">{{ pokemonData.stats[2].base_stat }}</h4>
            </div>
            <div class="flex justify-between items-center">
              <h4 class="font-semibold text-red-600">Attack:</h4>
              <h4 class="font-semibold text-gray-700">{{ pokemonData.stats[1].base_stat }}</h4>
            </div>
            <div class="flex justify-between items-center">
              <h4 class="font-semibold text-red-600">Special Defense:</h4>
              <h4 class="font-semibold text-gray-700">{{ pokemonData.stats[3].base_stat }}</h4>
            </div>
            <div class="flex justify-between items-center">
              <h4 class="font-semibold text-red-600">Defense:</h4>
              <h4 class="font-semibold text-gray-700">{{ pokemonData.stats[4].base_stat }}</h4>
            </div>
            <div class="flex justify-between items-center">
              <h4 class="font-semibold text-red-600">Speed:</h4>
              <h4 class="font-semibold text-gray-700">{{ pokemonData.stats[5].base_stat }}</h4>
            </div>
          </div>
          <div v-if="pokedexEntry.flavor_text_entries" class="mt-4">
            <h3 class="text-lg font-semibold text-red-600">Pokedex Entry:</h3>
            <p class="text-gray-700">{{ pokedexEntry.flavor_text_entries[1].flavor_text }}</p>
          </div>
          <div class="mt-4">
            <h3 class="text-lg font-semibold text-red-600">Abilities:</h3>
            <ul class="space-y-1">
              <li v-for="ability in pokemonData.abilities" :key="ability.ability.name" class="text-gray-700 shadow p-1 rounded">
                {{ ability.ability.name }}
              </li>
            </ul>
          </div>
          <div v-if="pokemonData.moves.length > 0" class="mt-4">
            <h3 class="text-lg font-semibold text-red-600">Moves:</h3>
            <h4 class="text-md font-semibold text-red-600">Learned by leveling up:</h4>
            <ul class="space-y-1">
              <li v-for="move in levelUpMoves" :key="move.name" class="text-gray-700 shadow p-1 rounded">
                {{ move.name }} (Level: {{ move.level }})
              </li>
            </ul>
            <h4 class="text-md font-semibold text-red-600">Not learned by leveling up:</h4>
            <ul class="space-y-1">
              <li v-for="move in otherMoves" :key="move.move.name" class="text-gray-700 shadow p-1 rounded">
                {{ move.move.name }}
              </li>
            </ul>
          </div>
        </div>
      </div>
    </transition>
    <div v-if="errorMessage" class="text-red-600 text-lg font-semibold">{{ errorMessage }}</div>
  </div>
</div>
</template>


<script>
import axios from 'axios';

export default {
  data() {
    return {
      pokemonName: '',
      pokemonData: {},
      pokedexEntry: {},
      errorMessage: '',
      pokemonSuggestions: []
    }
  },
  computed: {
    levelUpMoves() {
      if (this.pokemonData.moves) {
        return this.pokemonData.moves
          .filter(move => move.version_group_details.some(detail => detail.move_learn_method.name === 'level-up' && detail.level_learned_at !== 0))
          .map(move => ({ 
            name: move.move.name, 
            level: move.version_group_details.find(detail => detail.move_learn_method.name === 'level-up' && detail.level_learned_at !== 0).level_learned_at 
          }))
          .sort((a, b) => a.level - b.level);
      }
      return [];
    },
    otherMoves() {
      if (this.pokemonData.moves) {
        return this.pokemonData.moves.filter(move => move.version_group_details.every(detail => detail.move_learn_method.name !== 'level-up' || detail.level_learned_at === 0));
      }
      return [];
    }
  },
  watch: {
    pokemonName(newValue) {
      if (newValue) {
        this.fetchPokemonList(newValue);
      } else {
        this.pokemonSuggestions = [];
      }
    }
  },
  methods: {
    async fetchPokemonList() {
      try {
        const response = await axios.get('https://pokeapi.co/api/v2/pokemon?limit=1000');
        this.pokemonSuggestions = response.data.results.filter(pokemon => pokemon.name.startsWith(this.pokemonName));
      } catch (error) {
        console.error(error);
      }
    },
    selectPokemon(name) {
      this.fetchPokemon(name);
      this.pokemonName = '';
      this.pokemonSuggestions = [];
    },
    async fetchPokemon(name) {
      try {
        const pokemonResponse = await axios.get(`https://pokeapi.co/api/v2/pokemon/${name}`);
        this.pokemonData = pokemonResponse.data;
        
        const speciesResponse = await axios.get(`https://pokeapi.co/api/v2/pokemon-species/${name}`);
        this.pokedexEntry = speciesResponse.data;

        this.errorMessage = '';
      } catch (error) {
        this.errorMessage = 'Pokemon not found';
        console.error(error);
      }
    }
  }
};
</script>


<style scoped>
@font-face {
  font-family: 'PokemonSolid';
  src: url('/fonts/pokemon-solid.ttf') format('truetype');
}

@keyframes neon {
  from {
    text-shadow: 0 0 10px #fff, 0 0 20px #fff, 0 0 30px #fff, 0 0 40px #ff42a1, 0 0 70px #ff42a1, 0 0 90px #ff42a1, 0 0 100px #ff42a1, 0 0 150px #ff42a1;
  }
  to {
    text-shadow: 0 0 10px #fff, 0 0 20px #fff, 0 0 30px #fff, 0 0 40px #ff42a1, 0 0 70px #ff42a1, 0 0 90px #ff42a1, 0 0 100px #ff42a1, 0 0 150px #ff42a1;
  } 
}

.neon {
  animation: neon 1s ease-in-out infinite alternate;
}

.pokemon-font {
  font-family: 'PokemonSolid', sans-serif;
}
</style>
