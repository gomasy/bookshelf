import './general.js';

import Vue from 'vue';
import cameraModal from './components/cameraModal.vue';

new Vue({
    el: '#content',
    components: { cameraModal },
    template: '<cameraModal />',
});
