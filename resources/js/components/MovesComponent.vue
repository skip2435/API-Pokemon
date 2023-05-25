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
      <h1 class="text-4xl font-bold mb-4 text-red-600">Pokemon Generations</h1>
      <select 
        v-if="generations"
        v-model="selectedGeneration" 
        @change="fetchPokemon" 
        class="px-4 py-2 rounded-lg border-0 shadow-lg mb-4 text-lg bg-white text-gray-700 placeholder-gray-500 w-full sm:w-auto"
      >
        <option v-for="generation in generations" :key="generation" :value="generation">
            Generation {{ generation }}
        </option>
      </select>

      <transition name="fade">
        <div v-if="pokemonData.region" class="w-full max-w-lg bg-white shadow-lg rounded-lg overflow-hidden mx-auto mb-4">
          <div class="py-4 px-6">
            <h2 class="text-2xl font-bold text-red-500">Region: {{ pokemonData.region }}</h2>
            <div class="mt-4">
              <div v-for="(moves, type) in categorizedMoves" :key="type">
                <h3 class="text-lg font-semibold text-red-600">{{ type.charAt(0).toUpperCase() + type.slice(1) }} Moves:</h3>
                <ul class="space-y-1">
                  <li v-for="move in moves" :key="move.name" class="text-gray-700 shadow p-1 rounded">
                    Name: {{ move.name }}<br>
                    Power: {{ move.power }}<br>
                    Type: {{ move.type }}<br>
                    Accuracy: {{ move.accuracy }}<br>
                    PP: {{ move.pp }}<br>
                    Category: {{ move.category }}
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      generations: [],
      selectedGeneration: 1,
      pokemonData: {
        region: '',
        moves: []
      },
      moveCache: {}
    }
  },
  computed: {
    categorizedMoves() {
      return this.pokemonData.moves.reduce((accumulator, move) => {
        if (!accumulator[move.type]) {
          accumulator[move.type] = [];
        }
        accumulator[move.type].push(move);
        return accumulator;
      }, {});
    }
  },
  methods: {
    async fetchPokemon() {
      try {
        const response = await axios.get(`https://pokeapi.co/api/v2/generation/${this.selectedGeneration}`);
        this.pokemonData.region = response.data.main_region.name;
        this.pokemonData.moves = await Promise.all(response.data.moves.map(async (move) => {
          if (!move) {
            return null;
          }
          
          if (this.moveCache[move.name]) {
            return this.moveCache[move.name];
          } else {
            const moveResponse = await axios.get(move.url);
            const moveData = {
              name: moveResponse.data.name,
              power: moveResponse.data.power,
              type: moveResponse.data.type.name,
              accuracy: moveResponse.data.accuracy,
              pp: moveResponse.data.pp,
              category: moveResponse.data.damage_class.name
            };
            this.moveCache[move.name] = moveData;
            return moveData;
          }
        }));

        // Filter out null values.
        this.pokemonData.moves = this.pokemonData.moves.filter(move => move);
      } catch (error) {
        console.error(error);
      }
    }
  },
  created() {
    this.generations = [1, 2, 3, 4, 5, 6, 7, 8, 9];
    this.fetchPokemon();
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

