# Dev kit for the Sunshine Bundle

## Node Packages configuration
```
{
  "name": "reactjs_app",
  "version": "1.0.0",
  "description": "",
  "main": "index.js",
  "scripts": {
    "test": "echo \"Error: no test specified\" && exit 1"
  },
  "author": "",
  "license": "ISC",
  "dependencies": {
    "babel-loader": "^7.0.0",
    "react": "^15.5.4",
    "react-dom": "^15.5.4",
    "react-router": "^4.1.1",
    "reactify": "^1.1.1",
    "reflux": "^6.4.1",
    "vinyl-source-stream": "^1.1.0",
    "watchify": "^3.9.0"
  },
  "devDependencies": {
    "jsx-loader": "^0.13.2",
    "react-hot-loader": "^1.3.1",
    "webpack": "^2.4.1",
    "webpack-dev-server": "^2.4.5"
  }
}
```

## Global dependencies

The serveur must have the following elements installed :

1. Webpack
2. Webpack-Dev-Server

```
npm install -g webpack
npm install -g webpack-dev-server
```

## Host Configuration
The server must respond to 'local.dev' url. Add it to your host configuration file if needed.

```
local.dev   127.0.0.1
```

## Running Dev Server

Dev server include hot and live reload for React source code.

Go to the 'Resources/reactjs_app' :
```
webpack-dev-server --open --hot --inline
```

## Configuration parameter to enter 'Dev Mode'
In order to enable live reload, you must set a config parameter to :

```
dev_mode: true
```

