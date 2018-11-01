import Vue from 'vue';
import cameraModal from './components/cameraModal.vue';

export default function() {
    return new Vue({
        el: '#content',
        components: { cameraModal },
        template: '<cameraModal />',
    });
}
