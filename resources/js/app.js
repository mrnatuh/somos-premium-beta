import Alpine from 'alpinejs'
import mask from '@alpinejs/mask'
import Axios from 'axios'

import { createApp } from 'vue';
import CategoryMo from './components/CategoryMo.vue';

Alpine.plugin(mask)

const axios = Axios.create({
    baseURL: location.origin,
    timeout: 60000,
    withCredentials: true,
    xsrfCookieName: 'XSRF-TOKEN',
    // xsrfHeaderName: 'X-SRF-TOKEN',
});

// hack para funcionar na navegação ajax
if (location.href.indexOf('=mo') > -1) {
    document.addEventListener('livewire:navigated', () => {
        createApp({})
            .component('CategoryMo', CategoryMo)
            .mount('#app');
    });
}
