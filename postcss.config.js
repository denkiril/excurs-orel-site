/* eslint-disable global-require */
module.exports = {
  plugins: [
    require('autoprefixer')({
      // browsers: '> 2%, ie 10, safari > 9',
    }),
    require('postcss-inline-svg'),
    require('cssnano')({
      preset: [
        'default', {
          discardComments: {
            removeAll: true,
          },
        },
      ],
    }),
  ],
};
