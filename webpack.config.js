const path = require('path');

const baseConfig = {
  devtool: 'source-map',
  entry: {
    script: './dev/excursions/assets/js/script.js',
    widgets: './dev/excursions/assets/js/widgets.js',
    guidebook_map: './dev/excursions/assets/js/guidebook_map.js',
    events_map: './dev/excursions/assets/js/events_map.js',
    events: './dev/excursions/assets/js/events.js',
    acf_map: './dev/excursions/assets/js/acf_map.js',
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

const modernConfig = Object.assign({}, baseConfig, {
  output: {
    path: path.join(__dirname, '/wp-content/themes/excursions/assets/js'),
    filename: '[name].js',
    // publicPath: '/build/',
  },
  module: {
    rules: [
      configureBabelLoader(
        { esmodules: true },
      ),
    ],
  },
});

const legacyConfig = Object.assign({}, baseConfig, {
  output: {
    path: path.join(__dirname, '/wp-content/themes/excursions/assets/js'),
    filename: '[name]-legacy.js',
    // publicPath: '/build/',
  },
  module: {
    rules: [
      configureBabelLoader(
        '> 2%, ie 10, safari > 9',
      ),
      // {
      //   test: /\.js/,
      //   exclude: /node_modules/,
      //   use: [
      //     {
      //       loader: 'babel-loader',
      //       options: {
      //         presets: [
      //           [
      //             '@babel/preset-env',
      //             {
      //               targets: '> 2%, ie 10, safari > 9',
      //               useBuiltIns: 'usage',
      //             },
      //           ],
      //         ],
      //       },
      //     },
      //   ],
      // },
    ],
  },
});

module.exports = [
  modernConfig, legacyConfig,
];
