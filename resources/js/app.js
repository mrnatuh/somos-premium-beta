import Alpine from 'alpinejs'
import mask from '@alpinejs/mask'

Alpine.plugin(mask)

import { createApp } from 'vue';
import CategoryMo from './components/CategoryMo.vue';

// hack para funcionar na navegação ajax
if (location.href.indexOf('=mo') > -1) {
    document.addEventListener('livewire:navigated', () => {
        createApp({})
            .component('CategoryMo', CategoryMo)
            .mount('#app');
    });
}
