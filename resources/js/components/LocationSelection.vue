<template>
  <div class="min-h-screen bg-red-100 flex flex-col pokemon-font">
    <!-- Navigation bar -->
    <nav class="bg-red-500 text-white p-3">
      <!-- Navigation links -->
      <ul class="flex justify-around">
        <li class="neon hover:bg-red-400 transition-colors duration-200 rounded px-2 py-1"><a href="/" class="text-xl font-semibold">Moves</a></li>
        <li class="neon hover:bg-red-400 transition-colors duration-200 rounded px-2 py-1"><a href="/encounters" class="text-xl font-semibold">Pokemon</a></li>
        <li class="neon hover:bg-red-400 transition-colors duration-200 rounded px-2 py-1"><a href="/location-selection" class="text-xl font-semibold">Locations</a></li>
        <li class="neon hover:bg-red-400 transition-colors duration-200 rounded px-2 py-1"><a href="/poke-search" class="text-xl font-semibold">Poke Search</a></li>
      </ul>
    </nav>
    <!-- Random Pokemon images -->
    <div class="flex flex-wrap justify-around items-center p-10">
      <img v-for="(image, index) in randomPokemonImages" :key="index" :src="image" class="w-24 h-24 object-cover rounded-full shadow-lg m-2"/>
    </div>
    <!-- Main content section -->
    <div class="flex flex-col items-center justify-center flex-grow px-4 sm:px-0">
      <!-- Page title -->
      <h1 class="text-4xl font-bold mb-6 text-red-600">Connecting Page</h1>
      <div class="relative w-full sm:w-auto">
        <!-- Navigation buttons -->
        <button class="btn px-4 py-2 rounded-lg border-0 shadow-lg mb-12 text-lg bg-red-500 text-white placeholder-gray-500 w-full mt-2" @click="goToLocation">
    Go to Location Search
  </button>
  <button class="btn px-4 py-2 rounded-lg border-0 shadow-lg mb-12 text-lg bg-red-500 text-white placeholder-gray-500 w-full mt-2" @click="goToGame">
  Go to Game Search
  </button>
      </div>
      
      <!-- How to use section -->
      <div class="mt-10 px-4 py-6 bg-white border border-gray-200 rounded-lg shadow-sm text-gray-700">
        <h2 class="text-2xl font-semibold mb-4 text-gray-800">How to Use</h2>
        <p class="mb-4">
          <strong>Location Search:</strong> Click the "Go to Location Search" button to use the location search feature. In this feature, you can enter a location, and it will provide you with the games, region, and Pokémon that can be found in that area, as well as the levels at which they spawn.
        </p>
        <p>
          <strong>Game Search:</strong> Click the "Go to Game Search" button to use the game search feature. In this feature, you enter a Pokémon game, and it will provide you with all the locations in that game, along with the Pokémon that can be found in each area.
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      randomPokemonImages: []    // List of random Pokemon images displayed at the top of the page
    }
  },
  created() {
    // Fetch random Pokemon images when the component is created
    this.fetchRandomPokemonImages();
  },
  methods: {
    // Fetches random Pokemon images
    async fetchRandomPokemonImages() {
      try {
        let randomIds = [];
        for(let i=0; i<5; i++) {
          let randomId = Math.floor(Math.random() * 800) + 1;
          randomIds.push(randomId);
        }
        const responses = await Promise.all(randomIds.map(id => axios.get(`https://pokeapi.co/api/v2/pokemon/${id}`)));
        this.randomPokemonImages = responses.map(response => response.data.sprites.other['official-artwork']['front_default']);
      } catch (error) {
        console.error(error);
      }
    },
    goToLocation() {
      window.location.href = '/location-search';
    },
    goToGame() {
      window.location.href = '/game-search';
    },
},
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
  text-shadow: 0 0 10px #fff, 0 0 20px #fff, 0 0 30px #fff, 0 0 40 px #ff073a, 0 0 70px #ff073a, 0 0 80px #ff073a, 0 0 100px #ff073a, 0 0 150px #ff073a;
}
to {
  text-shadow: 0 0 5px #fff, 0 0 10px #fff, 0 0 15px #fff, 0 0 20px #ff073a, 0 0 30px #ff073a, 0 0 40px #ff073a, 0 0 50px #ff073a, 0 0 75px #ff073a;
}
}

.neon {
animation: neon 1s ease-in-out infinite alternate;
}

.pokemon-font {
font-family: 'Pokemon Solid', sans-serif;
}

.fade-enter-active,
.fade-leave-active {
transition: opacity 0.5s;
}

.fade-enter,
.fade-leave-to {
opacity: 0;
}

.btn {
    transition: all 0.3s ease;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  }

  .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
  }
</style>
