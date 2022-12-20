const path = require('path');
const glob = require('glob');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;

module.exports = (env) => {

    return {
        entry: {
            app: glob.sync('./ts/**/*.ts*'),
            bundle: glob.sync('./css/**/*.css'),
        },
        output: {
            filename: '[name].js',
            path: path.resolve(__dirname, 'dist'),
            clean: true,
        },
        module: {
            rules: [
                {
                    test: /\.css$/,
                    use: [MiniCssExtractPlugin.loader, "css-loader"],
                },
                {
                    test: /\.(ts)$/,
                    use: ['ts-loader'],
                    exclude: /node_modules/,
                }
            ],
        },
        plugins: [
            new MiniCssExtractPlugin(), new BundleAnalyzerPlugin({analyzerMode: 'static', openAnalyzer: false})
        ],
        resolve: {
            extensions: ['.css', '.ts', '.js'],
        },
        devServer: {
            static: path.join(__dirname, "/")
        },
        devtool: 'source-map',
        optimization: {
            minimizer: [
                new CssMinimizerPlugin(),
            ],
            // runtimeChunk: 'single',
            // splitChunks: {
            //     chunks: 'all',
            // }
        },
    }
}