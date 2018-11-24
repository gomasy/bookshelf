module.exports = {
    plugins: {
        'autoprefixer': {
            browsers: [
                'last 2 versions',
                'not dead',
                'not op_mini all',
                'not android <= 4.4.4',
            ],
            grid: true,
        },
        'postcss-custom-media': {},
    },
};
