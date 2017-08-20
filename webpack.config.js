const webpack = require('webpack');
const path = require('path');

module.exports = {
    entry: './resources/assets/app.js',
    output: {
        filename: './assets/bundle.js',
        path: path.join(__dirname, '/public'),
    },
    module: {
        rules: [
            {
                test: /\.scss$/,
                use: [ 'style-loader', 'css-loader', 'sass-loader' ],
            },
            {
                test: /\.(woff2?|ttf|eot|svg)(\?v=[\d.]+|\?[\s\S]+)?$/,
                use: [
                    { loader: 'file-loader?name=/assets/[name].[ext]' },
                ],
            },
        ],
    },
    plugins: [
        new webpack.optimize.UglifyJsPlugin(),
        new webpack.ProvidePlugin({
            $: 'jquery',
            jquery: 'jquery',
            jQuery: 'jquery',
            'window.jQuery': 'jquery',
        }),
    ],
};
