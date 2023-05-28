import './bootstrap';
import { createApp } from 'vue';

const app = createApp({});

import MovesComponent from './components/MovesComponent.vue';
app.component('moves-component', MovesComponent);

import SearchComponent from './components/SearchComponent.vue';
app.component('search-component', SearchComponent);

import EncounterComponent from './components/EncounterComponent.vue';
app.component('encounter-component', EncounterComponent);

// Import the new Vue component
import LocationComponent from './components/LocationComponent.vue';
app.component('location-component', LocationComponent);

app.mount('#app');
