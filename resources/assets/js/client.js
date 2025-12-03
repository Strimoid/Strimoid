// Expose jQuery globally (replaces Webpack's expose-loader and ProvidePlugin)
import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;

// Expose React globally (replaces Webpack's ProvidePlugin)
import React from 'react';
import ReactDOM from 'react-dom';
window.React = React;
window.ReactDOM = ReactDOM;

import './lara';
