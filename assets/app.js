/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';
import './bootstrap';

import 'flowbite/dist/flowbite.turbo.js';

import 'animate.css';


document.addEventListener('turbo:submit-start', (event) => {
    event.detail.formSubmission.submitter.toggleAttribute('disabled', true);
})


document.addEventListener("DOMContentLoaded", function() {
    var button = document.getElementById('back-to-top');

    button.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
});

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
