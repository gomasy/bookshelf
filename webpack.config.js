const path = require('path');
const webpack = require('webpack');
const CleanWebpackPlugin = require('clean-webpack-plugin');

module.exports = {
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm.js',
        },
    },
    entry: {
        core: './resources/assets/js/core.js',
        dashboard: './resources/assets/js/dashboard.js',
        home: './resources/assets/js/home.js',
        settings: './resources/assets/js/settings.js',
    },
    output: {
        filename: './assets/[name].min.js',
        path: path.join(__dirname, '/public'),
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
                use: [ 'style-loader', 'css-loader', 'sass-loader' ],
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
                core: {
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
