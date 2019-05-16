const path = require('path');
const webpack = require('webpack');
const merge = require('webpack-merge');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
// const transformRemoveConsole = require('transform-remove-console');

// const devMode = process.env.NODE_ENV !== 'production';

const PATHS = {
  src: path.join(__dirname, './dev/excursions/assets/'),
  dist: path.join(__dirname, '/public_html/wp-content/themes/excursions/'),
  assets: 'assets/',
};

const baseConfig = {
  // devtool: 'source-map',
  entry: {
    script: `${PATHS.src}js/script.js`,
    widgets: `${PATHS.src}js/widgets.js`,
    guidebook_map: `${PATHS.src}js/guidebook_map.js`,
    events_map: `${PATHS.src}js/events_map.js`,
    events: `${PATHS.src}js/events.js`,
    acf_map: `${PATHS.src}js/acf_map.js`,
    style: `${PATHS.src}css/style.css`,
    gallery: `${PATHS.src}css/gallery.css`,
    main_bottom: `${PATHS.src}css/main_bottom.css`,
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: `${PATHS.assets}css/[name].css`,
      // filename: `${PATHS.dist}/[name].css`,
    }),
  ],
};

// const addBabelPlugins = () => {
//   let plugins = {};
//   if (process.env.NODE_ENV === 'production') {
//     plugins += ['transform-remove-console', { exclude: ['error', 'warn'] }];
//   }

//   return plugins;
// };

const configureBabelLoader = targetsAny => ({
  test: /\.js$/,
  exclude: /node_modules/,
  use: {
    loader: 'babel-loader',
    options: {
      babelrc: false,
      // envName: process.env.production ? 'production' : 'development',
      presets: [
        ['@babel/preset-env', {
          debug: true,
          modules: false,
          useBuiltIns: 'usage',
          targets: targetsAny,
        }],
      ],
      // plugins: ['transform-remove-console'],
      env: {
        production: {
          plugins: ['transform-remove-console'],
        },
      },
    },
  },
});

const configureCssLoader = () => ({
  test: /\.css$/,
  exclude: /node_modules/,
  use: [
    'style-loader',
    MiniCssExtractPlugin.loader,
    {
      loader: 'css-loader',
      options: { url: false },
    },
    {
      loader: 'postcss-loader',
      options: { sourceMap: true },
      // options: { sourceMap: true, config: { path: path.join(__dirname) } },
    },
  ],
});

//   {
//   test: /\.css$/,
//   use: {
//     loader: MiniCssExtractPlugin.loader,
//     options: {
//       path: PATHS.dist,
//       filename: `${PATHS.assets}css/[name].css`,
//     },
//   },
// }];
// "browserslist": "> 2%, ie 10, safari > 9",

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
      configureCssLoader(),
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
        // '> 2%, ie 10, safari > 9',
      ),
      configureCssLoader(),
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

// const prodWebpackConfig = {
//   // stats: 'none',
//   module: {
//     rules: [{
//       test: /\.js$/,
//       exclude: /node_modules/,
//       use: {
//         loader: 'babel-loader',
//         options: {
//           plugins: [
//             // '@babel/plugin-syntax-dynamic-import',
//             ['transform-remove-console', { exclude: ['error', 'warn'] }],
//             // ['transform-remove-console'],
//           ],
//         },
//       },
//     },
//     ],
//   },
// };

// module.exports = [ modernConfig, legacyConfig ];

module.exports = (env, argv) => {
  if (argv.mode === 'development') {
    modernConfig = merge(modernConfig, devWebpackConfig);
    legacyConfig = merge(legacyConfig, devWebpackConfig);
  }
  // else {
  // (argv.mode === 'production')
  // modernConfig = merge(prodWebpackConfig, modernConfig);
  // legacyConfig = merge(prodWebpackConfig, legacyConfig);
  // }

  return [modernConfig, legacyConfig];
};
