<template>
  <div class="min-h-screen bg-red-100 flex flex-col pokemon-font">
    <nav class="bg-red-500 text-white p-3">
      <ul class="flex justify-around">
        <li class="neon hover:bg-red-400 transition-colors duration-200 rounded px-2 py-1"><a href="/" class="text-xl font-semibold">Moves</a></li>
        <li class="neon hover:bg-red-400 transition-colors duration-200 rounded px-2 py-1"><a href="/encounters" class="text-xl font-semibold">Pokemon</a></li>
        <li class="neon hover:bg-red-400 transition-colors duration-200 rounded px-2 py-1"><a href="/poke-search" class="text-xl font-semibold">Poke Search</a></li>
      </ul>
    </nav>

    <div class="flex flex-col items-center justify-center flex-grow px-4 sm:px-0">
      <h1 class="text-4xl font-bold mb-4 text-red-600">Pokemon Encounters</h1>
      <div class="relative w-full sm:w-auto">
        <input 
          type="text" 
          v-model="selectedPokemon" 
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
        <div v-if="selectedPokemonDetails && encounters.length > 0" class="w-full max-w-lg bg-white shadow-lg rounded-lg overflow-hidden mx-auto mb-4">
          <div class="py-4 px-6">
            <div class="flex items-center justify-between">
              <div>
                <h2 class="text-2xl font-bold text-red-500">{{ selectedPokemonDetails.name }}</h2>
                <img :src="selectedPokemonDetails.sprites.other['official-artwork']['front_default']" alt="" class="w-24 h-24 object-cover rounded-full shadow-xl">
              </div>
            </div>
            <div class="mt-4" v-for="(encounter, index) in encounters" :key="index">
              <h3 class="text-lg font-semibold text-red-600">Location: {{ encounter.location_area.name }}</h3>
              <div class="mt-2" v-for="(detail, i) in encounter.version_details" :key="i">
                <h4 class="text-md font-semibold text-blue-600">Version: {{ detail.version.name }}</h4>
                <div v-for="(encounterDetail, j) in detail.encounter_details" :key="j">
                  <p><b>Chance:</b> {{ encounterDetail.chance }}%</p>
                  <p><b>Method:</b> {{ encounterDetail.method.name }}</p>
                  <p><b>Min level:</b> {{ encounterDetail.min_level }}</p>
                  <p><b>Max level:</b> {{ encounterDetail.max_level }}</p>
                  <p><b>Condition:</b> {{ encounterDetail.condition_values.length > 0 ? encounterDetail.condition_values[0].name : 'None' }}</p>
                  </div>
                </div>
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
        selectedPokemon: '',
        pokemonSuggestions: [],
        encounters: [],
        selectedPokemonDetails: null,
        errorMessage: ''
      }
    },
    watch: {
      selectedPokemon(newValue) {
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
          this.pokemonSuggestions = response.data.results.filter(pokemon => pokemon.name.startsWith(this.selectedPokemon));
        } catch (error) {
          console.error(error);
          this.errorMessage = "An error occurred while fetching the Pokemon list";
        }
      },
      selectPokemon(name) {
        this.fetchEncounters(name);
        this.fetchPokemonDetails(name);
        this.selectedPokemon = '';
        this.pokemonSuggestions = [];
      },
      async fetchEncounters(name) {
        try {
          const response = await axios.get(`https://pokeapi.co/api/v2/pokemon/${name}/encounters`);
          this.encounters = response.data;
        } catch (error) {
          console.error(error);
          this.errorMessage = "An error occurred while fetching Pokemon encounters";
        }
      },
      async fetchPokemonDetails(name) {
        try {
          const response = await axios.get(`https://pokeapi.co/api/v2/pokemon/${name}`);
          this.selectedPokemonDetails = response.data;
        } catch (error) {
          console.error(error);
          this.errorMessage = "An error occurred while fetching Pokemon details";
        }
      },
    }
  };
  </script>         


<style scoped>
@font-face {
  font-family: 'Pokemon Solid';
  src: url('/fonts/Pokemon-Solid.ttf') format('truetype');
  font-weight: normal;
  font-style: normal;
}

@keyframes neon {
  from {
    text-shadow: 0 0 10px #fff, 0 0 20px #fff, 0 0 30px #fff, 0 0 40px #ff42a1, 0 0 70px #ff42a1, 0 0 90px #ff42a1, 0 0 100px #ff42a1, 0 0 150px #ff42a1;
  }
  to {
    text-shadow: 0 0 5px #fff, 0 0 10px #fff, 0 0 15px #fff, 0 0 20px #ff42a1, 0 0 35px #ff42a1, 0 0 40px #ff42a1, 0 0 50px #ff42a1, 0 0 75px #ff42a1;
  }
}

.neon {
  animation: neon 1s ease-in-out infinite alternate;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s;
}

.fade-enter, .fade-leave-to {
  opacity: 0;
}

.pokemon-font {
  font-family: 'Pokemon Solid', sans-serif;
}

</style>

