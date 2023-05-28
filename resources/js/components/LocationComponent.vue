<template>
  <div class="min-h-screen bg-red-100 flex flex-col pokemon-font">
  <!-- Navigation bar -->
    <nav class="bg-red-500 text-white p-3">
    <!-- Navigation links -->
      <ul class="flex justify-around">
        <li class="neon hover:bg-red-400 transition-colors duration-200 rounded px-2 py-1"><a href="/" class="text-xl font-semibold">Moves</a></li>
        <li class="neon hover:bg-red-400 transition-colors duration-200 rounded px-2 py-1"><a href="/encounters" class="text-xl font-semibold">Pokemon</a></li>
        <li class="neon hover:bg-red-400 transition-colors duration-200 rounded px-2 py-1"><a href="/location-search" class="text-xl font-semibold">Locations</a></li>
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
      <h1 class="text-4xl font-bold mb-4 text-red-600">Location Pokémon Encounters</h1>
      <div class="relative w-full sm:w-auto">
      <!-- Search input for location -->
        <input type="text" v-model="selectedLocation" @input="fetchLocationList" placeholder="Enter location..." class="px-4 py-2 rounded-lg border-0 shadow-lg mb-4 text-lg bg-white text-gray-700 placeholder-gray-500 w-full">
        <!-- Location suggestions dropdown -->
        <div class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg shadow-lg" v-if="locationSuggestions.length && selectedLocation">
           <!-- Individual location suggestion -->
          <div class="px-4 py-2 hover:bg-gray-200 cursor-pointer" v-for="suggestion in locationSuggestions" :key="suggestion.name" @click="selectLocation(suggestion.name)">
            {{ suggestion.name }}
          </div>
        </div>
      </div>
      <transition name="fade">
                <!-- Location data display -->
        <div v-if="selectedLocation && locationData" class="w-full max-w-lg bg-white shadow-lg rounded-lg overflow-hidden mx-auto my-4">
          <div class="py-4 px-6">
            <div class="flex items-center justify-between">
              <div>
              <!-- Display location data -->
                <h2 class="text-2xl font-bold text-red-500">{{ locationData.name }}</h2>
                <h3>Region: {{ locationData.region.name }}</h3>
                <!-- Game indices -->
                <div v-for="gameIndex in locationData.game_indices" :key="gameIndex.game_index">
                  <p>Game Index: {{ gameIndex.game_index }}</p>
                </div>
                <ul>
                  <li v-for="area in locationData.areas" :key="area.name">
                    <p>Area: {{ area.name }}</p>
                  </li>
                </ul>
                <div v-if="locationAreaData">
                  <div v-for="areaData in locationAreaData" :key="areaData.id">
                  <h3 class="text-lg font-bold mb-2">{{ areaData.name }}</h3>
                  <div v-for="encounter in areaData.pokemon_encounters" :key="encounter.pokemon.name" class="border border-gray-200 rounded p-2 mb-2">
                    <h4 class="font-bold mb-1">{{ encounter.pokemon.name }}</h4>
                    <img :src="encounter.pokemon.sprites.front_default" alt="">
                    <p>Game Version: {{ encounter.version_details[0].version.name }}</p>
                    <p>Encounter Method: {{ encounter.version_details[0].encounter_details[0].method.name }}</p>
                    <p>Encounter Chance: {{ encounter.version_details[0].encounter_details[0].chance }}%</p>
                    <p>Encounter Level: 
                    <!-- Encounter levels (displays only max level if min and max are the same) -->
                    {{ encounter.version_details[0].encounter_details[0].min_level == encounter.version_details[0].encounter_details[0].max_level 
                      ? encounter.version_details[0].encounter_details[0].max_level 
                      : encounter.version_details[0].encounter_details[0].min_level + ' - ' + encounter.version_details[0].encounter_details[0].max_level
                    }}
                  </p>
                  </div>
                </div>
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
                     // Data properties
                        selectedLocation: '', // The location input by the user
                        locationSuggestions: [],   // The list of location suggestions that match the user's input
                        locationData: null,      // The data for the selected location
                        locationAreaData: [],    // The area data for the selected location
                        errorMessage: '',     // Error message to be displayed if any error occurs
                        randomPokemonImages: []    // List of random Pokemon images displayed at the top of the page
                      }
                    },
                    watch: {
                      selectedLocation(newValue) {
                         // When the user inputs a location, fetch the location list that matches the user's input
                        if (newValue && !this.locationData) {
                          this.fetchLocationList(newValue);
                        } else {
                          this.locationSuggestions = [];
                        }
                      }
                    },
                    created() {
                       // Fetch random Pokemon images when the component is created
                      this.fetchRandomPokemonImages();
                    },
                    methods: {
                       // Fetches a list of locations that match the user's input
                      async fetchLocationList() {
                        if (this.selectedLocation.trim() === '') {
                          this.locationSuggestions = [];
                        } else {
                          try {
                            // API call to fetch the list of locations
                            const response = await axios.get('https://pokeapi.co/api/v2/location?limit=1000');
                            this.locationSuggestions = response.data.results.filter(location => location.name.startsWith(this.selectedLocation));
                          } catch (error) {
                            console.error(error);
                            this.errorMessage = "An error occurred while fetching the Location list";
                          }
                        }
                      },                      
                      async fetchLocationData(name) {
                        try {
                          const locationResponse = await axios.get(`https://pokeapi.co/api/v2/location/${name}`);
                          this.locationData = locationResponse.data;
                          this.locationAreaData = await Promise.all(this.locationData.areas.map(area => this.fetchAreaData(area.url)));
                        } catch (error) {
                          console.error(error);
                          this.errorMessage = "An error occurred while fetching the Location Data";
                        }
                      },
                      async fetchAreaData(url) {
                        try {
                          const response = await axios.get(url);
                          const areaData = response.data;
                      
                          // Fetch Pokemon data
                          for (let encounter of areaData.pokemon_encounters) {
                            const pokemonResponse = await axios.get(encounter.pokemon.url);
                            encounter.pokemon = pokemonResponse.data;  // This will overwrite the 'pokemon' field with complete data, including 'sprites'
                          }
                      
                          return areaData;
                        } catch (error) {
                          console.error(error);
                          this.errorMessage = "An error occurred while fetching the Area Data";
                        }
                      },
                      selectLocation(name) {
                        this.fetchLocationData(name);
                        this.locationSuggestions = [];
                      },
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
                  </style>
