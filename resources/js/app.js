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


document.addEventListener('livewire:initialized', () => {

    // hack para setas
    if (location.href.indexOf('/previa') > -1) {
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
    }

    // approve
    var el_approve_area = document.getElementById('approve_area');

    var btn_approve = document.getElementById('btn_approve');

    var btn_status_reprove = document.getElementById('btn_status_reprove');
    var btn_status_approve = document.getElementById('btn_status_approve');

    var el_logs_area = document.getElementById('logs_area');
    var btn_show_logs = document.getElementById('btn_show_logs');

    if (el_approve_area) {
        window.preview = {
            cc: null,
            weekref: null,
        };

        console.log('[approve-area]');

        if (btn_approve) {
            console.log('[btn-approve]');
            var approve_modal = document.getElementById('approve-modal');
            var approve_modal_close = document.getElementById('approve-modal-close');
            var approve_modal_cancel = document.getElementById('approve-modal-cancel');
            var approve_modal_confirm = document.getElementById('approve-modal-confirm');

            btn_approve.addEventListener('click', function(e){
                console.log('[btn-approve] clicked');

                approve_modal.classList.add('flex');
                approve_modal.classList.remove('hidden');

                window.preview = {
                    cc: e.target.dataset.cc,
                    weekref: e.target.dataset.weekref,
                };
            });

            approve_modal_cancel.addEventListener('click', function() {
                approve_modal.classList.add('hidden');
                approve_modal.classList.remove('flex');

                window.preview = {
                    cc: null,
                    weekref: null,
                };
            });

            approve_modal_close.addEventListener('click', function() {
                approve_modal.classList.add('hidden');
                approve_modal.classList.remove('flex');

                window.preview = {
                    cc: null,
                    weekref: null,
                };
            });

            approve_modal_confirm.addEventListener('click', function() {
                approve_modal_close.setAttribute('disabled', true);
                approve_modal_cancel.setAttribute('disabled', true);
                approve_modal_confirm.setAttribute('disabled', true);

                axios.post('/previa/status', {
                    cc: window.preview.cc,
                    weekref: window.preview.weekref,
                    status: 'publish',
                }).then(function(response) {
                    location.href = location.href;
                }).catch(function(error) {
                    console.log(error);
                });
            });
        }

        if (btn_status_approve && btn_status_reprove) {
            var approve_status_modal = document.getElementById('approve-status-modal');

            var approve_status_modal_message = document.getElementById('approve-status-modal-message');

            var approve_status_modal_confirm = document.getElementById('approve-status-modal-confirm');

            var approve_status_modal_cancel = document.getElementById('approve-status-modal-cancel');

            var approve_status_modal_close = document.getElementById('approve-status-modal-close');

            console.log('[btn-cancel-approve][btn-confirm-approve]');

            btn_status_approve.addEventListener('click', function(e) {
                approve_status_modal.classList.add('flex');
                approve_status_modal.classList.remove('hidden');

                approve_status_modal_message.innerHTML = 'Deseja aprovar a prévia?';

                window.preview = {
                    cc: e.target.dataset.cc,
                    weekref: e.target.dataset.weekref,
                    status: 'approve',
                };
            });

            btn_status_reprove.addEventListener('click', function(e) {
                approve_status_modal.classList.add('flex');
                approve_status_modal.classList.remove('hidden');

                approve_status_modal_message.innerHTML = 'Deseja reprovar a prévia?';

                window.preview = {
                    cc: e.target.dataset.cc,
                    weekref: e.target.dataset.weekref,
                    status: 'reprove',
                };
            });

            approve_status_modal_close.addEventListener('click', function(e) {
                approve_status_modal.classList.add('hidden');
                approve_status_modal.classList.remove('flex');

                window.preview = {
                    cc: null,
                    weekref: null,
                    status: null,
                };
            });

            approve_status_modal_cancel.addEventListener('click', function(e) {
                approve_status_modal.classList.add('hidden');
                approve_status_modal.classList.remove('flex');

                window.preview = {
                    cc: null,
                    weekref: null,
                    status: null,
                };
            });

            approve_status_modal_confirm.addEventListener('click', function(e) {
                approve_status_modal_cancel.setAttribute('disabled', true);
                approve_status_modal_close.setAttribute('disabled', true);
                approve_status_modal_confirm.setAttribute('disabled', true);

                axios.post('/previa/status', {
                    cc: window.preview.cc,
                    weekref: window.preview.weekref,
                    status: window.preview.status,
                }).then(function(response) {
                    location.href = location.href;
                }).catch(function(error) {
                    console.log(error);
                });
            });
        }
    }

    if (el_logs_area) {
        btn_show_logs.addEventListener('click', function(e) {
            if(!el_logs_area.classList.contains('flex')) {
                el_logs_area.classList.remove('hidden');
                el_logs_area.classList.add('flex');
            } else {
                el_logs_area.classList.remove('flex');
                el_logs_area.classList.add('hidden');
            }
        });
    }
});

