<template>
    <div class="modal fade" id="camera-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div id="cameraWindow"></div>
                <div class="modal-footer">
                    <h4>バーコードを近づけて下さい</h4>
                    <input type="checkbox" v-model="isConfirm"> 確認する
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Quagga from 'quagga';

export default {
    data: () => ({
        qgParams: {
            inputStream: {
                name: 'Live',
                type: 'LiveStream',
                target: '#cameraWindow',
                size: 640,
                singleChannel: false,
            },
            locator: {
                patchSize: 'medium',
                halfSample: true,
            },
            decoder: {
                readers: [ 'ean_reader' ],
            },
            locate: true,
            interval: null,
        },
        isConfirm: false,
    }),
    methods: {
        start() {
            Quagga.init(this.qgParams, el => {
                if (el) return;

                Quagga.start();
                Quagga.onProcessed(r => this.processed(r));
                Quagga.onDetected(r => this.detected(r));

                this.interval = setInterval(() => {
                    if (!$('#camera-modal').hasClass('in')) {
                        this.stop();
                    }
                }, 100);
            });
        },
        stop() {
            // :thinking_face:
            clearInterval(this.interval);
            Quagga.stop();
        },
        processed(result) {
            if (!result) return;

            const ctx = Quagga.canvas.ctx.overlay;
            const canvas = Quagga.canvas.dom.overlay;

            if (result.boxes) {
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                const hasNotRead = box => box !== result.box;
                result.boxes.filter(hasNotRead).forEach(box => {
                    Quagga.ImageDebug.drawPath(box, { x: 0, y: 1 }, ctx, { color: 'green', lineWidth: 2 });
                });
            }

            if (result.box) {
                Quagga.ImageDebug.drawPath(result.box, { x: 0, y: 1 }, ctx, { color: 'blue', lineWidth: 2 });
            }

            if (result.codeResult && result.codeResult.code) {
                Quagga.ImageDebug.drawPath(result.line, {x: 'x', y: 'y'}, ctx, {color: 'red', lineWidth: 3});
            }
        },
        detected(result) {
            const isbn = result.codeResult.code;
            if (this.validation(isbn)) {
                $('#camera-modal').modal('hide');
                this.stop();
                this.create(isbn);
            }
        },
        create(code) {
            this.$parent.before_create(r => this.$parent.create(r), code, !this.isConfirm);
        },
        validation(code) {
            return code.match(/^978/);
        },
    },
};
</script>
