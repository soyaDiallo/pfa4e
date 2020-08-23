/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../scss/global.scss';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
import $ from 'jquery';

// Select
require('select2');

var Sticky = require('sticky-js');
var sticky = new Sticky('.selector');

require('bootstrap');
require('popper.js'); 

// Feather Icons
const feather = require('feather-icons');
$(document).ready(function() {
	"use-strict";
  feather.replace();
});

require('datatables.net-bs4');
require('datatables.net-responsive-bs4')();

import './jquery.sticky';
import './jquery.easing.1.3'; 
import './header';
import './script';