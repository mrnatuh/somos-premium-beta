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
});

// hack para funcionar na navegação ajax
if (location.href.indexOf('=mo') > -1) {
	document.addEventListener('livewire:navigated', () => {
		createApp({})
			.component('CategoryMo', CategoryMo)
			.mount('#app');
	});
}

function load_orcamento() {
	if (
		location.href.indexOf('/dashboard') > -1 || 
		location.href.indexOf('/previa') > -1 ||
		location.href.indexOf('/categoria') > -1 ||
		location.href.indexOf('/realizado') > -1
	) {
		console.log('[orcamentos]');

		var is_preview = location.href.indexOf('/previa') > -1;
		var is_category = location.href.indexOf('/categoria') > -1;
		var is_donepreview = location.href.indexOf('/realizado') > -1;
		var is_dashboard = location.href.indexOf('/dashboard') > -1;

		axios.get('/v1/orcamento').then(function (response) {
			var json = response.data;
			var groups_table = document.querySelectorAll('[data-table]');
			var bar_total = document.querySelectorAll('[data-bar-total]');
			var bar_arrow = document.querySelectorAll('[data-bar-arrow]');

			groups_table.forEach(group => {
				console.log(group);

				try {
					var data = JSON.parse(group.getAttribute('data-table'));

					var filtered = json?.[data.cc]?.[data.month_ref]?.[data.group] ?? null;
					
					console.log(data, filtered);

					var arrow = "";

					if (filtered) {
						if (data.group == '0001') {
							if (data.total > filtered.total) {
								arrow = "green";
							} else if (data.total < filtered.total) {
								arrow = "red"
							}
						}

						if (
							data.group == '0003' ||
							data.group == '0004' ||
							data.group == '0005' ||
							data.group == '0006'
						) {
							if (data.total > filtered.total) {
								arrow = "red";
							} else if (data.total < filtered.total) {
								arrow = "green"
							}
						}
					}

					if ((is_preview || is_donepreview) && arrow) {
						group.innerHTML = '<img src="/img/' + arrow + '.svg" />';
					} else {
						group.innerHTML = '';
					}
				} catch (e) {
					console.log(e);
				}
			});

			bar_total.forEach(group => {
				try {
					var data = JSON.parse(group.getAttribute('data-bar-total'));
					var filtered = json?.[data.cc]?.[data.month_ref]?.[data.group] ?? null;
					if (filtered) {
						var value = new Intl.NumberFormat('pt-BR', {
							style: 'currency', currency: 'BRL'
						}).format(filtered.total);

						group.innerHTML = "(" + value + ")";
					} else {
						group.innerHTML = '(R$ 0,00)';
					}
				} catch (e) {
					console.log(e);
				}
			});

			bar_arrow.forEach(group => {
				try {
					var data = JSON.parse(group.getAttribute('data-bar-arrow'));
					var filtered = json?.[data.cc]?.[data.month_ref]?.[data.group] ?? null;
					
					console.log(filtered);

					var arrow = "";
					if (filtered) {
						if (data.group == '0001') {
							if (data.total > filtered.total) {
								arrow = "green";
							} else if (data.total < filtered.total) {
								arrow = "red"
							}
						}

						if (
							data.group == '0003' ||
							data.group == '0004' ||
							data.group == '0005' ||
							data.group == '0006'
						) {
							if (data.total > filtered.total) {
								arrow = "red";
							} else if (data.total < filtered.total) {
								arrow = "green"
							}
						}

						if (arrow) {
							group.innerHTML = '<img src="/img/' + arrow + '.svg" />';
						}
					}

					console.log(arrow);
				} catch (e) {
					console.log(e);
				}
			});
		});
	}
}


function init() {
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

			btn_approve.addEventListener('click', function (e) {
				console.log('[btn-approve] clicked');

				approve_modal.classList.add('flex');
				approve_modal.classList.remove('hidden');

				window.preview = {
					id: e.target.dataset.preview,
					cc: e.target.dataset.cc,
					weekref: e.target.dataset.weekref,
					level: e.target.dataset.level,
				};
			});

			approve_modal_cancel.addEventListener('click', function () {
				approve_modal.classList.add('hidden');
				approve_modal.classList.remove('flex');

				window.preview = {
					id: null,
					cc: null,
					weekref: null,
					level: null,
				};
			});

			approve_modal_close.addEventListener('click', function () {
				approve_modal.classList.add('hidden');
				approve_modal.classList.remove('flex');

				window.preview = {
					id: null,
					cc: null,
					weekref: null,
					level: null,
				};
			});

			approve_modal_confirm.addEventListener('click', function () {
				approve_modal_close.setAttribute('disabled', true);
				approve_modal_cancel.setAttribute('disabled', true);
				approve_modal_confirm.setAttribute('disabled', true);

				axios.post('/previa/' + window.preview.id + '/publish', {
					cc: window.preview.cc,
					weekref: window.preview.weekref,
					status: 'publish',
					level: window.preview.level,
				}).then(function (response) {
					location.href = location.href;
				}).catch(function (error) {
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

			var approve_status_modal_text = document.getElementById('approve-status-modal-text');

			console.log('[btn-cancel-approve][btn-confirm-approve]');

			btn_status_approve.addEventListener('click', function (e) {
				approve_status_modal.classList.add('flex');
				approve_status_modal.classList.remove('hidden');

				approve_status_modal_message.innerHTML = 'Deseja aprovar a prévia?';

				approve_status_modal_text.value = '';
				approve_status_modal_text.classList.remove('flex');
				approve_status_modal_text.classList.add('hidden');

				window.preview = {
					id: e.target.dataset.preview,
					cc: e.target.dataset.cc,
					weekref: e.target.dataset.weekref,
					status: 'approve',
					level: e.target.dataset.level,
					text: '',
				};
			});

			btn_status_reprove.addEventListener('click', function (e) {
				approve_status_modal.classList.add('flex');
				approve_status_modal.classList.remove('hidden');

				approve_status_modal_text.classList.remove('hidden');
				approve_status_modal_text.classList.add('flex');

				approve_status_modal_message.innerHTML = 'Deseja reprovar a prévia?';

				window.preview = {
					id: e.target.dataset.preview,
					cc: e.target.dataset.cc,
					weekref: e.target.dataset.weekref,
					status: 'reprove',
					level: e.target.dataset.level,
				};
			});

			approve_status_modal_close.addEventListener('click', function (e) {
				approve_status_modal.classList.add('hidden');
				approve_status_modal.classList.remove('flex');

				window.preview = {
					id: null,
					cc: null,
					weekref: null,
					status: null,
					level: 1,
					text: '',
				};
			});

			approve_status_modal_cancel.addEventListener('click', function (e) {
				approve_status_modal.classList.add('hidden');
				approve_status_modal.classList.remove('flex');

				window.preview = {
					id: null,
					cc: null,
					weekref: null,
					status: null,
					level: null,
					text: null,
				};
			});

			approve_status_modal_confirm.addEventListener('click', function (e) {
				var obj = {
					id: window.preview.id,
					cc: window.preview.cc,
					weekref: window.preview.weekref,
					status: window.preview.status,
					level: window.preview.level,
				};

				var action = 'approve';

				if (approve_status_modal_text.classList.contains('flex')) {
					action = 'reprove';

					var text = approve_status_modal_text.value.trim();

					if (text.length < 10) {
						approve_status_modal_text.classList.add('border-red-400');
						return;
					} else {
						approve_status_modal_text.classList.remove('border-red-400');
					}

					obj.text = approve_status_modal_text.value;
				}

				approve_status_modal_cancel.setAttribute('disabled', true);
				approve_status_modal_close.setAttribute('disabled', true);
				approve_status_modal_confirm.setAttribute('disabled', true);

				axios.post('/previa/' + window.preview.id + '/' + action + '/' + window.preview.level, obj).then(function (response) {
					console.log(response.data);
					location.href = location.href;
				}).catch(function (error) {
					console.log(error);
				});
			});
		}
	}

	if (el_logs_area) {
		btn_show_logs.addEventListener('click', function (e) {
			if (!el_logs_area.classList.contains('flex')) {
				el_logs_area.classList.remove('hidden');
				el_logs_area.classList.add('flex');
			} else {
				el_logs_area.classList.remove('flex');
				el_logs_area.classList.add('hidden');
			}
		});
	}
}

// init();
document.addEventListener('livewire:initialized', function () {
	init();
});

if (window.Livewire) {
	Livewire.on('render-preview-month', () => {
		load_orcamento();
	});

	Livewire.on('render-bar', () => {
		load_orcamento();
	});
}

if (
	location.href.indexOf('/dashboard') > -1 || 
	location.href.indexOf('/categoria') > -1
) {
	load_orcamento();	
}