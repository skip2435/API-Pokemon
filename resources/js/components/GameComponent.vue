<template>
  <div class="min-h-screen bg-red-100 flex flex-col pokemon-font">
    <!-- Navigation bar -->
    <nav class="bg-red-500 text-white p-3">
      <ul class="flex justify-around">
        <li class="neon hover:bg-red-400 transition-colors duration-200 rounded px-2 py-1"><a href="/" class="text-xl font-semibold">Moves</a></li>
        <li class="neon hover:bg-red-400 transition-colors duration-200 rounded px-2 py-1"><a href="/encounters" class="text-xl font-semibold">Pokemon</a></li>
        <li class="neon hover:bg-red-400 transition-colors duration-200 rounded px-2 py-1"><a href="/location-selection" class="text-xl font-semibold">Locations</a></li>
        <li class="neon hover:bg-red-400 transition-colors duration-200 rounded px-2 py-1"><a href="/poke-search" class="text-xl font-semibold">Poke Search</a></li>
      </ul>
    </nav>
    <div class="flex flex-wrap justify-around items-center p-10">
      <img v-for="(image, index) in randomPokemonImages" :key="index" :src="image" class="w-24 h-24 object-cover rounded-full shadow-lg m-2"/>
    </div>
    <div class="flex flex-col items-center justify-center flex-grow px-4 sm:px-0">
      <h1 class="text-4xl font-bold mb-4 text-red-600">Region Pokémon Encounters</h1>
      <div class="relative w-full sm:w-auto">
        <select v-model="selectedRegion" @change="fetchRegionData" class="px-4 py-2 rounded-lg border-0 shadow-lg mb-4 text-lg bg-white text-gray-700 w-full">
          <option disabled value="">Please select a region</option>
          <option v-for="region in regionSuggestions" :key="region.name" :value="region.name">
            {{ region.name }}
          </option>
        </select>
      </div>
      <transition name="fade">
        <div v-if="selectedRegion && regionData" class="w-full max-w-lg bg-white shadow-lg rounded-lg overflow-hidden mx-auto my-4">
          <div class="py-4 px-6">
            <div class="flex items-center justify-between">
              <div>
                <h2 class="text-2xl font-bold text-red-500 mb-4">{{ regionData.name }}</h2>

                <!-- Display for Routes -->
                <h3 class="text-xl font-bold">Routes</h3>
                <ul class="border-2 border-red-500 p-4 rounded-lg">
                  <li v-for="location in sortedRoutes" :key="location.name" class="mb-4">
                    <p class="text-xl font-bold">Location: {{ location.name }}</p>
                    <button @click="fetchAreaData(location.url, location.name)" class="bg-red-500 text-white px-2 py-1 rounded-lg mt-2">Show Pokémon</button>
                    <ul v-if="locationAreaData[location.name] && locationAreaData[location.name].length" class="border-2 border-red-500 p-4 rounded-lg mt-2">
                      <li v-for="encounter in locationAreaData[location.name]" :key="encounter.pokemon.name" class="mb-4">
                        <p>Pokemon: {{ encounter.pokemon.name }}</p>
                        <img :src="encounter.pokemon.sprites.front_default" class="w-20 h-20 object-cover rounded-full shadow-lg" />
                        <div v-if="encounter.version_details && encounter.version_details[0]">
                          <p>Game Version: {{ encounter.version_details[0].version.name }}</p>
                          <p>Encounter Method: {{ encounter.version_details[0].encounter_details[0].method.name }}</p>
                          <p>Encounter Chance: {{ encounter.version_details[0].encounter_details[0].chance }}%</p>
                          <p>Encounter Level: {{ encounter.version_details[0].encounter_details[0].min_level }}-{{ encounter.version_details[0].encounter_details[0].max_level }}</p>
                        </div>
                      </li>
                    </ul>
                  </li>
                </ul>

                <!-- Display for Towns and Cities -->
                <h3 class="text-xl font-bold">Towns and Cities</h3>
                <ul class="border-2 border-red-500 p-4 rounded-lg">
                  <li v-for="location in townsAndCities" :key="location.name" class="mb-4">
                    <p class="text-xl font-bold">Location: {{ location.name }}</p>
                    <button @click="fetchAreaData(location.url, location.name)" class="bg-red-500 text-white px-2 py-1 rounded-lg mt-2">Show Pokémon</button>
                    <ul v-if="locationAreaData[location.name] && locationAreaData[location.name].length" class="border-2 border-red-500 p-4 rounded-lg mt-2">
                      <li v-for="encounter in locationAreaData[location.name]" :key="encounter.pokemon.name" class="mb-4">
                        <p>Pokemon: {{ encounter.pokemon.name }}</p>
                        <img :src="encounter.pokemon.sprites.front_default" class="w-20 h-20 object-cover rounded-full shadow-lg" />
                        <div v-if="encounter.version_details && encounter.version_details[0]">
                          <p>Game Version: {{ encounter.version_details[0].version.name }}</p>
                          <p>Encounter Method: {{ encounter.version_details[0].encounter_details[0].method.name }}</p>
                          <p>Encounter Chance: {{ encounter.version_details[0].encounter_details[0].chance }}%</p>
                          <p>Encounter Level: {{ encounter.version_details[0].encounter_details[0].min_level }}-{{ encounter.version_details[0].encounter_details[0].max_level }}</p>
                        </div>
                      </li>
                    </ul>
                  </li>
                </ul>

                <!-- Display for Miscellaneous Locations -->
                <h3 class="text-xl font-bold">Miscellaneous Locations</h3>
                <ul class="border-2 border-red-500 p-4 rounded-lg">
                  <li v-for="location in miscellaneousLocations" :key="location.name" class="mb-4">
                    <p class="text-xl font-bold">Location: {{ location.name }}</p>
                    <button @click="fetchAreaData(location.url, location.name)" class="bg-red-500 text-white px-2 py-1 rounded-lg mt-2">Show Pokémon</button>
                    <ul v-if="locationAreaData[location.name] && locationAreaData[location.name].length" class="border-2 border-red-500 p-4 rounded-lg mt-2">
                      <li v-for="encounter in locationAreaData[location.name]" :key="encounter.pokemon.name" class="mb-4">
                        <p>Pokemon: {{ encounter.pokemon.name }}</p>
                        <img :src="encounter.pokemon.sprites.front_default" class="w-20 h-20 object-cover rounded-full shadow-lg" />
                        <div v-if="encounter.version_details && encounter.version_details[0]">
                          <p>Game Version: {{ encounter.version_details[0].version.name }}</p>
                          <p>Encounter Method: {{ encounter.version_details[0].encounter_details[0].method.name }}</p>
                          <p>Encounter Chance: {{ encounter.version_details[0].encounter_details[0].chance }}%</p>
                          <p>Encounter Level: {{ encounter.version_details[0].encounter_details[0].min_level }}-{{ encounter.version_details[0].encounter_details[0].max_level }}</p>
                        </div>
                      </li>
                    </ul>
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
    selectedRegion: '',
    regionSuggestions: [],
    regionData: null,
    locationAreaData: {},
    randomPokemonImages: [],
  };
},
created() {
  this.fetchRegionSuggestions();
  this.fetchRandomPokemonImages();
},
methods: {
  async fetchRegionSuggestions() {
    const response = await axios.get('https://pokeapi.co/api/v2/region');
    this.regionSuggestions = response.data.results;
  },
  async fetchRegionData() {
    const response = await axios.get(`https://pokeapi.co/api/v2/region/${this.selectedRegion}`);
    this.regionData = {
      name: response.data.name,
      locations: response.data.locations,
    };
  },
  async fetchAreaData(locationUrl, locationName) {
    this.loading = true;
    try {
      const locationResponse = await axios.get(locationUrl);
      const locationData = locationResponse.data;
      let encounterData = [];
  
      for (let area of locationData.areas) {
        const areaResponse = await axios.get(area.url);
        const areaData = areaResponse.data;
  
        for (let encounter of areaData.pokemon_encounters) {
          const pokemonResponse = await axios.get(encounter.pokemon.url);
          encounter.pokemon = {
            ...encounter.pokemon,
            ...pokemonResponse.data
          };
        }
  
        encounterData.push(...areaData.pokemon_encounters);
      }
  
      this.locationAreaData = {
        ...this.locationAreaData,
        [locationName]: encounterData,
      };
    } catch (error) {
      console.error(error);
    }
    this.loading = false;
  },
  async fetchRandomPokemonImages() {
    for (let i = 0; i < 6; i++) {
      const pokemonNumber = Math.floor(Math.random() * 898) + 1;
      const response = await axios.get(`https://pokeapi.co/api/v2/pokemon/${pokemonNumber}`);
      this.randomPokemonImages.push(response.data.sprites.front_default);
    }
  },
},
computed: {
  sortedRoutes() {
    return this.regionData.locations.filter(location => location.name.includes('route')).sort((a, b) => a.name.localeCompare(b.name, undefined, { numeric: true }));
  },
  townsAndCities() {
    return this.regionData.locations.filter(location => location.name.includes('city') || location.name.includes('town')).sort((a, b) => a.name.localeCompare(b.name));
  },
  miscellaneousLocations() {
    return this.regionData.locations.filter(location => !location.name.includes('city') && !location.name.includes('town') && !location.name.includes('route')).sort((a, b) => a.name.localeCompare(b.name));
  },
},
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap');
.pokemon-font {
font-family: 'Press Start 2P', cursive;
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
.loader {
border-top-color: #3498db;
-webkit-animation: spinner 1.5s linear infinite;
animation: spinner 1.5s linear infinite;
}
@-webkit-keyframes spinner {
0% { -webkit-transform: rotate(0deg); }
100% { -webkit-transform: rotate(360deg); }
}
@keyframes spinner {
0% { transform: rotate(0deg); }
100% { transform: rotate(360deg); }
}
.fade-enter-active, .fade-leave-active {
transition: opacity .5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
opacity: 0;
}
</style>