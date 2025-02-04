import './bootstrap';
import { createApp } from 'vue';
import ElementPlus from 'element-plus';
import 'element-plus/dist/index.css';
import HouseSearch from './components/HouseSearch.vue';

const app = createApp({});
app.use(ElementPlus);
app.component('house-search', HouseSearch);
app.mount('#app');
