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

// hack para setas
if (location.href.indexOf('/previa') > -1) {
    document.addEventListener('livewire:initialized', () => {
        axios.get('/orcamentos').then(function(response) {
            var json = response.data;
            var groups = document.querySelectorAll('[data-group]');

            console.log(json);

            groups.forEach(group => {
                var cc = group.getAttribute('data-cc');
                var month_ref = group.getAttribute('data-month_ref');
                var code = group.getAttribute('data-group');
                var value = group.getAttribute('data-value');

                var filtered = json.filter(item => item.cc == cc && item.month_ref == month_ref && item.group == code);

                var arrow = "";

                if (filtered.length) {
                    filtered = filtered[0];

                    console.log(filtered);

                    if (code === '0001') {
                        if (value > 0 && value > filtered.value) {
                            arrow = "green";
                        } else if (value > 0 && value < filtered.value) {
                            arrow = "red"
                        }
                    }

                    if (code == '0003' || code == '0004' || code == '0005' || code == '0006') {
                        if (value > 0 && value > filtered.value) {
                            arrow = "red";
                        } else if (value > 0 && value < filtered.value) {
                            arrow = "green"
                        }
                    }
                }

                if (arrow) {
                    group.innerHTML = '<img src="/img/' + arrow + '.svg" />';
                }
            });
        });
    });
}
