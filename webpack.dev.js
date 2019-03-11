const merge = require('webpack-merge')
const common = require('./webpack.config.js')

module.exports = merge(common, {
    entry: {
        client: [
            'react-hot-loader/patch',
            'webpack-dev-server/client?http://localhost:8080',
            'webpack/hot/only-dev-server',
        ].concat(common.entry.client),
    },
    mode: 'development',
    devtool: 'inline-source-map',
    devServer: {
        hot: true,
        contentBase: false,
        publicPath: '/assets/',
        proxy: {
            "/": "http://localhost:8000"
        }
    },
})
