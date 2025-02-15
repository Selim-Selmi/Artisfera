import './bootstrap.js';
import { Application } from "@hotwired/stimulus";
import { Controller } from "@hotwired/stimulus";

/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

// Crée une instance de l'application Stimulus
const application = Application.start();



