const path = require('path');
const webpack = require('webpack');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const ManifestPlugin = require('webpack-manifest-plugin');
const ExtractTextPlugin = require('extract-text-webpack-plugin');

module.exports = {
    entry: {
        core: './resources/assets/js/core.js',
        dashboard: './resources/assets/js/dashboard.js',
        home: './resources/assets/js/home.js',
    },
    output: {
        filename: './assets/[name].[hash].js',
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
                use: ExtractTextPlugin.extract({
                    fallback: 'style-loader',
                    use: [ 'css-loader', 'sass-loader' ],
                }),
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
        new CleanWebpackPlugin([
            path.join(__dirname, '/public/assets/*'),
        ]),
        new ManifestPlugin({
            fileName: './assets/manifest.json',
        }),
        new ExtractTextPlugin({
            filename: './assets/[name].[hash].css',
        }),
    ],
};
