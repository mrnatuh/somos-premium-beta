import Alpine from 'alpinejs'
import mask from '@alpinejs/mask'

Alpine.plugin(mask)

import { createApp } from 'vue';
import IncrementCounter from './components/IncrementCounter.vue';
import CategoryMo from './components/CategoryMo.vue';

// hack para funcionar na navegação ajax
document.addEventListener('livewire:navigated', () => {
    createApp({})
        .component('IncrementCounter', IncrementCounter)
        .component('CategoryMo', CategoryMo)
        .mount('#app')
});
