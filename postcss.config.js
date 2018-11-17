module.exports = {
    plugins: {
        'autoprefixer': {
            browsers: [
                '> 0.25% in JP',
                'not android <= 4.4.4',
            ],
            grid: true,
        },
        'postcss-custom-media': {},
    },
};
