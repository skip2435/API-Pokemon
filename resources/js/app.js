import './bootstrap';
import { createApp } from 'vue';

const app = createApp({});

import MovesComponent from './components/MovesComponent.vue';
app.component('moves-component', MovesComponent);

import SearchComponent from './components/SearchComponent.vue';
app.component('search-component', SearchComponent);

// New component for fetching and displaying regions, locations, location areas, and encounter rates.
import EncounterComponent from './components/EncounterComponent.vue';
app.component('encounter-component', EncounterComponent);

app.mount('#app');
