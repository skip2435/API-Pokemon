import './bootstrap';
import { createApp } from 'vue';

const app = createApp({});

import MovesComponent from './components/MovesComponent.vue';
app.component('moves-component', MovesComponent);

import SearchComponent from './components/SearchComponent.vue';
app.component('search-component', SearchComponent);

import EncounterComponent from './components/EncounterComponent.vue';
app.component('encounter-component', EncounterComponent);

import LocationComponent from './components/LocationComponent.vue';
app.component('location-component', LocationComponent);

// Importing the LocationSelection Component
import LocationSelection from './components/LocationSelection.vue';
app.component('location-selection', LocationSelection);

// Importing the GameComponent
import GameComponent from './components/GameComponent.vue';
app.component('game-component', GameComponent);

app.mount('#app');

