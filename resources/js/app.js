import './bootstrap';
import Vue from 'vue';
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import locale from 'element-ui/lib/locale/lang/ru-RU'
import HouseSearch from './components/HouseSearch.vue';

Vue.use(ElementUI, { locale });

new Vue({
    el: '#app',
    components: {
        'house-search': HouseSearch
    }
});
