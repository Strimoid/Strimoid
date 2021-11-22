const { resolve } = require('path')
const webpack = require('webpack')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const WebpackAssetsManifest = require('webpack-assets-manifest')
const { values } = require('lodash')

module.exports = {
    devtool: 'source-map',
    entry: {
        client: [
            './sass/app.sass',
            './js/client.js',
        ],
    },
    output: {
        filename: '[name].[hash:8].js',
        chunkFilename: '[id].[chunkhash:8].js',
        assetModuleFilename: '[name].[hash:8][ext][query]',
        path: resolve(__dirname, 'public/assets'),
        publicPath: '/assets/',
    },
    resolve: {
        extensions: ['.js', '.jsx'],
    },
    context: resolve(__dirname, 'resources/assets'),
    module: {
        rules: [
            {
                test: /\.jsx?$/,
                use: ['babel-loader'],
                exclude: /node_modules/,
            },
            {
                test: /\.(sass|scss)$/,
                use: [MiniCssExtractPlugin.loader, 'css-loader', 'sass-loader'],
            },
            {
                test: /\.css$/,
                use: [MiniCssExtractPlugin.loader, 'css-loader?modules', 'postcss-loader'],
            },
            {
                test: /\.html$/,
                loader: 'handlebars-loader',
            },
            {
                test: /\.(ico|jpg|png|gif|eot|otf|webp|svg|ttf|woff|woff2)(\?.*)?$/,
                type: 'asset/resource'
            },
        ],
    },
    plugins: [
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery',
            React: 'react',
        }),
        new MiniCssExtractPlugin({
            filename: '[name].[hash:8].css',
            chunkFilename: '[id].[chunkhash:8].css'
        }),
        new WebpackAssetsManifest({
            output: 'mix-manifest.json',
            customize: ({key, value}) =>  { return { key: `/${key}`, value: `/${value}` } }
        })
    ]
}
