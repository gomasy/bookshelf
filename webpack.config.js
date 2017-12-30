const path = require('path');
const webpack = require('webpack');
const CleanWebpackPlugin = require('clean-webpack-plugin');

module.exports = {
    entry: {
        core: './resources/assets/js/core.js',
        dashboard: './resources/assets/js/dashboard.js',
        home: './resources/assets/js/home.js',
    },
    output: {
        filename: './assets/[name].min.js',
        path: path.join(__dirname, '/public'),
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: [ 'babel-loader' ],
            },
            {
                test: /\.scss$/,
                use: [ 'style-loader', 'css-loader', 'sass-loader' ],
            },
            {
                test: /\.(woff2?|ttf|eot|svg|png)(\?v=[\d.]+|\?[\s\S]+)?$/,
                use: [ 'file-loader?name=/assets/[name].[ext]' ],
            },
        ],
    },
    plugins: [
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery',
        }),
        new webpack.optimize.CommonsChunkPlugin({
            name: 'core',
            chunks: [ 'core', 'dashboard', 'home' ],
        }),
        new CleanWebpackPlugin([
            path.join(__dirname, '/public/assets/*'),
        ]),
    ],
};
