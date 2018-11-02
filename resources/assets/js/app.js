import 'bootstrap-sass';
import '../scss/vendor/_bootstrap.scss';
import 'font-awesome/scss/font-awesome.scss';

// polyfill
import 'babel-polyfill';

// assets
import '../icon.png';
import '../scss/app.scss';
import '../scss/dashboard.scss';
import '../scss/home.scss';
import '../scss/scanner.scss';
import '../scss/settings.scss';

// js
import dashboard from './dashboard.js';

const options = {
    ajax: {
        'Content-Type': 'application/json',
        'X-Csrf-Token': document.head.querySelector('meta[name="csrf-token"]').content,
        'X-Requested-With': 'XMLHttpRequest',
    },
};

switch (window.location.pathname) {
case '/':
    dashboard(options);
    break;
}
