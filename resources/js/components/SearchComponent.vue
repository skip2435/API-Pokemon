<template>
  <div class="min-h-screen bg-red-100 flex flex-col">
    <nav class="bg-red-500 text-white p-3">
      <ul class="flex justify-around">
        <li class="neon hover:bg-red-400 transition-colors duration-200 rounded px-2 py-1"><a href="/">Moves</a></li>
        <li class="neon hover:bg-red-400 transition-colors duration-200 rounded px-2 py-1"><a href="#">Pokemon</a></li>
        <li class="neon hover:bg-red-400 transition-colors duration-200 rounded px-2 py-1"><a href="/poke-search">Poke Search</a></li>
      </ul>
    </nav>

    <div class="flex flex-col items-center justify-center flex-grow">
      <h1 class="text-4xl font-bold mb-4 text-red-600">Pokemon Search</h1>
      <div class="relative">
        <input 
          type="text" 
          v-model="pokemonName" 
          @input="fetchPokemonList" 
          placeholder="Lowercase letters only..."
          class="px-4 py-2 rounded-lg border-0 shadow-lg mb-4 text-lg bg-white text-gray-700 placeholder-gray-500"
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
      <div v-if="pokemonData.name" class="w-full max-w-lg bg-white shadow-lg rounded-lg overflow-hidden mx-auto mb-4">
        <div class="py-4 px-6">
          <h2 class="text-2xl font-bold text-red-500">{{ pokemonData.name }}</h2>
          <div class="mt-4">
            <h3 class="text-lg font-semibold text-red-600">Abilities:</h3>
            <ul>
              <li v-for="ability in pokemonData.abilities" :key="ability.ability.name" class="text-gray-700">
                {{ ability.ability.name }}
              </li>
            </ul>
          </div>
          <div v-if="pokemonData.moves.length > 0" class="mt-4">
            <h3 class="text-lg font-semibold text-red-600">Moves:</h3>
            <h4 class="text-md font-semibold text-red-600">Learned by leveling up:</h4>
            <ul>
              <li v-for="move in levelUpMoves" :key="move.name" class="text-gray-700">
                {{ move.name }} (Level: {{ move.level }})
              </li>
            </ul>
            <h4 class="text-md font-semibold text-red-600">Not learned by leveling up:</h4>
            <ul>
              <li v-for="move in otherMoves" :key="move.move.name" class="text-gray-700">
                {{ move.move.name }}
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div v-if="errorMessage" class="text-red-600">{{ errorMessage }}</div>
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
        const response = await axios.get(`https://pokeapi.co/api/v2/pokemon/${name}`);
        this.pokemonData = response.data;
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
</style>

