const path = require('path');


module.exports = {
  devtool: 'source-map',
  entry: {
    script: './dev/excursions/assets/js/script.js',
    widgets: './dev/excursions/assets/js/widgets.js',
    guidebook_map: './dev/excursions/assets/js/guidebook_map.js',
    events_map: './dev/excursions/assets/js/events_map.js',
    events: './dev/excursions/assets/js/events.js',
    'acf-map-yandex': './dev/excursions/assets/js/acf-map-yandex.js',
  },
  output: {
    path: path.join(__dirname, '/wp-content/themes/excursions/assets/js'),
    filename: '[name].js',
    // publicPath: '/build/',
  },
  module: {
    rules: [
      {
        test: /\.js/,
        exclude: /node_modules/,
        use: [
          {
            loader: 'babel-loader',
            options: {
              presets: [
                [
                  '@babel/preset-env',
                  {
                    targets: {
                      esmodules: true,
                    },
                    useBuiltIns: 'usage',
                  },
                ],
              ],
            },
          },
        ],
      },
    ],
  },

};
