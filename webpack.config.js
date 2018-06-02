const path = require('path');
const webpack = require('webpack');
const CleanWebpackPlugin = require('clean-webpack-plugin');

module.exports = {
    mode: process.env.NODE_ENV,
    entry: {
        general: './resources/assets/js/general.js',
        dashboard: './resources/assets/js/dashboard.js',
        home: './resources/assets/js/home.js',
        settings: './resources/assets/js/settings.js',
    },
    output: {
        path: path.join(__dirname, '/public'),
        filename: './assets/[name].min.js',
    },
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm.js',
        },
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                use: [ 'babel-loader' ],
                exclude: /node_modules/,
            },
            {
                test: /\.vue$/,
                use: [ 'vue-loader' ],
            },
            {
                test: /\.(js|vue)$/,
                use: [ 'eslint-loader' ],
                exclude: /node_modules/,
                enforce: 'pre',
            },
            {
                test: /\.scss$/,
                use: [ 'style-loader', 'css-loader', 'postcss-loader', 'sass-loader' ],
            },
            {
                test: /\.(woff2?|ttf|eot|svg|png)(\?v=[\d.]+|\?[\s\S]+)?$/,
                use: [ 'file-loader?name=/assets/[name].[ext]' ],
            },
        ],
    },
    optimization: {
        splitChunks: {
            cacheGroups: {
                general: {
                    name: 'vendor',
                    chunks: 'initial',
                    minChunks: 2,
                },
            },
        },
    },
    plugins: [
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery',
        }),
        new CleanWebpackPlugin([
            path.join(__dirname, '/public/assets/*'),
        ]),
    ],
};
