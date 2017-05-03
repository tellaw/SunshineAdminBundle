
function getEntrySources(sources) {
    if (process.env.NODE_ENV !== 'production') {
        sources.push('webpack-dev-server/client?http://local.dev:8080');
        sources.push('webpack/hot/only-dev-server');
    }

    return sources;
}

const defaultEnv = {
    dev: true,
    production: false,
};

module.exports = {
    entry: {
        application: getEntrySources([
            './src/app.jsx'
        ])
    },
    output: {
        publicPath: "http://local.dev:8080/",
        filename: 'public/js/[name].js'
    },
    module: {
        rules: [
            {
                test: /.jsx?$/,
                exclude: /node_modules/,
                use: [
                    {
                        loader: 'babel-loader',
                        options: {
                            babelrc: false,
                            presets: [
                                ['es2015', { modules: false }],
                                'react',
                            ],
                        }
                    }
                ]
            },
        ]
    },
    devServer: {
        hot: true,
        host: '0.0.0.0',
        disableHostCheck: true,
        headers: { "Access-Control-Allow-Origin": "*" }
    }
};
