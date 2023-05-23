import './bootstrap';
import { createApp } from 'vue';

const app = createApp({});

import MovesComponent from './components/MovesComponent.vue';
app.component('moves-component', MovesComponent);

import SearchComponent from './components/SearchComponent.vue';
app.component('search-component', SearchComponent);

app.mount('#app');
