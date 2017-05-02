
function getEntrySources(sources) {
    if (process.env.NODE_ENV !== 'production') {
     //   sources.push('webpack-dev-server/client?http://local.dev:8082');
       // sources.push('webpack/hot/only-dev-server');
    }

    return sources;
}

module.exports = {
    entry: {
        application: getEntrySources([
            './src/app.jsx'
        ])
    },
    output: {
        publicPath: 'http://local.dev:8082/',
        filename: '../public/js/[name].js'
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
    }
};
