const webpack = require('webpack');
const path = require('path');

module.exports = {
    entry: {
        'bundle.js': './resources/assets/js/app.js',
        'icon.png': './resources/assets/icon.png',
        'messages.json': './resources/assets/messages.json',
    },
    output: {
        filename: './assets/[name]',
        path: path.join(__dirname, '/public'),
    },
    module: {
        rules: [
            {
                test: /\.scss$/,
                use: [ 'style-loader', 'css-loader', 'sass-loader' ],
            },
            {
                test: /\.(woff2?|ttf|eot|svg|png|json)(\?v=[\d.]+|\?[\s\S]+)?$/,
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
