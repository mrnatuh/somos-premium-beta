import 'flowbite';
import { initFlowbite } from 'flowbite';
import anime from 'animejs';

function alerts()
{
    var alerts = document.querySelectorAll('.alert');

    console.log('alerts', alerts.length);

    if (alerts.length) {
        anime({
            targets: '.alert',
            translateY: -10,
            duration: 500,
            complete: function (anim) {
                anime({
                    targets: '.alert',
                    translateY: 0,
                    duration: 500,
                    complete: function (anim) {
                        document.querySelector('.alert').remove();
                    },
                    delay: 3000
                });
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    initFlowbite();

    alerts();
});

document.addEventListener('livewire:navigate', () => {
    initFlowbite();

    alerts();
});
