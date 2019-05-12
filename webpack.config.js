const path = require('path');
const webpack = require('webpack');
const merge = require('webpack-merge');

const PATHS = {
  src: path.join(__dirname, './dev/excursions/assets/js/'),
  dist: path.join(__dirname, '/public_html/wp-content/themes/excursions/'),
  assets: 'assets/',
};

const baseConfig = {
  // devtool: 'source-map',
  entry: {
    script: `${PATHS.src}script.js`,
    widgets: `${PATHS.src}widgets.js`,
    guidebook_map: `${PATHS.src}guidebook_map.js`,
    events_map: `${PATHS.src}events_map.js`,
    events: `${PATHS.src}events.js`,
    acf_map: `${PATHS.src}acf_map.js`,
  },
};

const configureBabelLoader = targetsAny => ({
  test: /\.js$/,
  exclude: /node_modules/,
  use: {
    loader: 'babel-loader',
    options: {
      babelrc: false,
      presets: [
        ['@babel/preset-env', {
          debug: true,
          modules: false,
          useBuiltIns: 'usage',
          targets: targetsAny,
        }],
      ],
      // plugins: ['@babel/plugin-syntax-dynamic-import'],
    },
  },
});

let modernConfig = Object.assign({}, baseConfig, {
  output: {
    path: PATHS.dist,
    filename: `${PATHS.assets}js/[name].js`,
    publicPath: '/',
  },
  module: {
    rules: [
      configureBabelLoader(
        { esmodules: true },
      ),
    ],
  },
});

let legacyConfig = Object.assign({}, baseConfig, {
  output: {
    path: PATHS.dist,
    filename: `${PATHS.assets}js/[name]-legacy.js`,
    publicPath: '/',
  },
  module: {
    rules: [
      configureBabelLoader(
        '> 2%, ie 10, safari > 9',
      ),
    ],
  },
});

const devWebpackConfig = {
  // DEV config
  // mode: 'development',
  devtool: 'cheap-module-eval-source-map',
  plugins: [
    new webpack.SourceMapDevToolPlugin({
      filename: 'map/[file].map',
    }),
  ],
};

const prodWebpackConfig = {
  module: {
    rules: [{
      test: /\.js$/,
      exclude: /node_modules/,
      use: {
        loader: 'babel-loader',
        options: {
          plugins: [
            // '@babel/plugin-syntax-dynamic-import',
            ['transform-remove-console', { exclude: ['error', 'warn'] }],
          ],
        },
      },
    },
    ],
  },
};

// module.exports = [
//   modernConfig, legacyConfig,
// ];

module.exports = (env, argv) => {
  if (argv.mode === 'development') {
    modernConfig = merge(modernConfig, devWebpackConfig);
    legacyConfig = merge(legacyConfig, devWebpackConfig);
  }

  if (argv.mode === 'production') {
    modernConfig = merge(modernConfig, prodWebpackConfig);
    legacyConfig = merge(legacyConfig, prodWebpackConfig);
  }

  return [modernConfig, legacyConfig];
};
