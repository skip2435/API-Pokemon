<template>
  <div class="min-h-screen bg-red-100 flex flex-col pokemon-font">
    <nav class="bg-red-500 text-white p-3">
      <ul class="flex justify-around">
        <li class="neon hover:bg-red-400 transition-colors duration-200 rounded px-2 py-1"><a href="/" class="text-xl font-semibold">Moves</a></li>
        <li class="neon hover:bg-red-400 transition-colors duration-200 rounded px-2 py-1"><a href="/encounters" class="text-xl font-semibold">Pokemon</a></li>
        <li class="neon hover:bg-red-400 transition-colors duration-200 rounded px-2 py-1"><a href="/location-selection" class="text-xl font-semibold">Locations</a></li>
        <li class="neon hover:bg-red-400 transition-colors duration-200 rounded px-2 py-1"><a href="/poke-search" class="text-xl font-semibold">Poke Search</a></li>
      </ul>
    </nav>

    <div class="flex flex-col items-center justify-center flex-grow px-4 sm:px-0">
      <h1 class="text-4xl font-bold mb-4 text-red-600">Search Pokemon Moves</h1>

      <div class="relative w-full max-w-md">
        <input 
          v-model="searchQuery" 
          @input="fetchMoves" 
          class="px-4 py-2 rounded-lg border-0 shadow-lg mb-4 text-lg bg-white text-gray-700 placeholder-gray-500 w-full sm:w-auto"
          placeholder="Search a move..."
        >
        <div v-if="moveSuggestions.length" class="absolute w-full bg-white shadow-lg rounded-lg z-10">
          <div 
            v-for="move in moveSuggestions" 
            :key="move.name" 
            @click="selectMove(move.name)" 
            class="cursor-pointer hover:bg-red-100 p-2"
          >
            {{ move.name }}
          </div>
        </div>
      </div>

      <transition name="fade">
        <div v-if="selectedMove.name" class="w-full max-w-lg bg-white shadow-lg rounded-lg overflow-hidden mx-auto mb-4">
          <div class="py-4 px-6">
            <h2 class="text-2xl font-bold text-red-500">Move: {{ selectedMove.name }}</h2>
            <div class="mt-4">
              <ul class="space-y-1">
                <li class="text-gray-700 shadow p-1 rounded">
                  Power: {{ selectedMove.power }}<br>
                  Type: {{ selectedMove.type }}<br>
                  Accuracy: {{ selectedMove.accuracy }}<br>
                  PP: {{ selectedMove.pp }}<br>
                  Category: {{ selectedMove.category }}
                </li>
              </ul>
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
      searchQuery: '',
      moveSuggestions: [],
      selectedMove: {},
      moveCache: {}
    }
  },
  methods: {
    async fetchMoves() {
      if (!this.searchQuery) {
        this.moveSuggestions = [];
        return;
      }

      try {
        const response = await axios.get(`https://pokeapi.co/api/v2/move?limit=500`);
        // Filter and return up to 5 move suggestions based on the user's search query
        this.moveSuggestions = response.data.results.filter(move => move.name.includes(this.searchQuery.toLowerCase())).slice(0, 5);
      } catch (error) {
        console.error(error);
      }
    },
    async selectMove(name) {
      if (!name) {
        return;
      }

      if (this.moveCache[name]) {
        this.selectedMove = this.moveCache[name];
      } else {
        try {
          const response = await axios.get(`https://pokeapi.co/api/v2/move/${name}`);
          const moveData = {
            name: response.data.name,
            power: response.data.power,
            type: response.data.type.name,
            accuracy: response.data.accuracy,
            pp: response.data.pp,
            category: response.data.damage_class.name
          };

          this.moveCache[name] = moveData;
          this.selectedMove = moveData;
        } catch (error) {
          console.error(error);
        }
      }
      // Clear move suggestions after a move has been selected
      this.moveSuggestions = [];
    }
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
        
