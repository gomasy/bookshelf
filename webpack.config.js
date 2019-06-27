const webpack = require('webpack');
const path = require('path');
const entries = {};

const { CleanWebpackPlugin } = require('clean-webpack-plugin');

require('glob').sync('./resources/assets/js/*.js').map(entry => {
    const name = entry.match('^.+/(.+?)\\.js$')[1];
    entries[name] = entry;
});

module.exports = {
    entry: entries,
    output: {
        path: path.join(__dirname, '/public'),
        filename: './assets/[name].[hash].js',
        hashDigestLength: 8,
    },
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm.js',
        },
        extensions: [ '.js', '.vue' ],
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
                test: /\.s?css$/,
                use: [ 'style-loader', 'css-loader', 'postcss-loader', 'sass-loader' ],
            },
            {
                test: /\.(woff2?|ttf|eot|svg|png)(\?v=[\d.]+|\?[\s\S]+)?$/,
                use: [ 'file-loader?name=/assets/[name].[ext]' ],
            },
        ],
    },
    optimization: {
        runtimeChunk: {
            name: 'vendor',
        },
        splitChunks: {
            name: 'vendor',
            chunks: 'initial',
        },
    },
    performance: {
        maxEntrypointSize: 1024000,
        maxAssetSize: 1024000,
    },
    plugins: [
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery',
        }),
        new webpack.ProgressPlugin(percentage => {
            if (percentage == 1) {
                require('child_process').exec('./artisan view:clear');
            }
        }),
        new CleanWebpackPlugin({
            cleanOnceBeforeBuildPatterns: [
                'assets/*.{ttf,eot,woff*,js,css,jp*g,png,gif}',
            ],
            verbose: true,
        }),
        new (require('browser-sync-webpack-plugin'))({
            proxy: process.env.PROXY_HOST || 'localhost',
            files: './public/assets/*',
            ghostMode: {
                clicks: false,
                scroll: false,
                location: false,
                forms: false,
            },
            open: false,
            notify: false,
        }),
        new (require('vue-loader/lib/plugin'))(),
    ],
};
