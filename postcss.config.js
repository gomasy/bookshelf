const autoprefixer = require('autoprefixer');

module.exports = {
    plugins: [
        new autoprefixer({
            browsers: [
                '> 1% in JP',
            ],
            grid: true,
        }),
    ],
};
