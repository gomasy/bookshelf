const webpack = require('webpack');

module.exports = {
    entry: './public/js/app.js',
    output: {
        filename: 'bundle.js',
        path: __dirname + '/public/js',
    },
    plugins: [
        new webpack.optimize.UglifyJsPlugin(),
        new webpack.ProvidePlugin({
            $: 'jquery',
            jquery: 'jquery',
            jQuery: 'jquery',
            'window.jQuery': 'jquery'
        })
    ]
};
