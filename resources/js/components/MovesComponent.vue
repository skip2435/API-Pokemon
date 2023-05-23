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
      <h1 class="text-4xl font-bold mb-4 text-red-600">Pokemon Generations</h1>
      <select 
        v-model="selectedGeneration" 
        @change="fetchPokemon" 
        class="px-4 py-2 rounded-lg border-0 shadow-lg mb-4 text-lg bg-white text-gray-700 placeholder-gray-500"
      >
        <option v-for="generation in generations" :key="generation" :value="generation">
          Generation {{ generation }}
        </option>
      </select>

      <div v-if="pokemonData.region" class="w-full max-w-lg bg-white shadow-lg rounded-lg overflow-hidden mx-auto mb-4">
        <div class="py-4 px-6">
          <h2 class="text-2xl font-bold text-red-500">Region: {{ pokemonData.region }}</h2>
          <div class="mt-4">
            <h3 class="text-lg font-semibold text-red-600">Moves:</h3>
            <ul>
              <li v-for="move in pokemonData.moves" :key="move" class="text-gray-700">
                {{ move }}
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    region: String,
    moves: Array
  },
  data() {
    return {
      generations: [1, 2, 3, 4, 5, 6, 7, 8],
      selectedGeneration: 1,
      pokemonData: {
        region: this.region || '',
        moves: this.moves || []
      }
    }
  },
  methods: {
    async fetchPokemon() {
      try {
        const response = await axios.get(`https://pokeapi.co/api/v2/generation/${this.selectedGeneration}`);
        this.pokemonData.region = response.data.main_region.name;
        this.pokemonData.moves = response.data.moves.map(move => move.name);
      } catch (error) {
        console.error(error);
      }
    }
  },
  created() {
    if (!this.region || !this.moves.length) {
      this.fetchPokemon();
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