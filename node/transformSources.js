const path = require('path');
const fs = require('fs');
// eslint-disable-next-line import/no-extraneous-dependencies
const babel = require('@babel/core');
// eslint-disable-next-line import/no-extraneous-dependencies
const postcss = require('postcss');
const autoprefixer = require('autoprefixer');
const postcssInlineSvg = require('postcss-inline-svg');
const cssnano = require('cssnano');

const devMode = process.env.NODE_ENV !== 'production';

// const srcDirPath = '../dev/excursions/assets/';
const themeDirPath = '../public_html/wp-content/themes/excursions/';
const PATHS = {
  cssSrc: path.join(__dirname, themeDirPath, 'assets/src/css/'),
  jsSrc: path.join(__dirname, themeDirPath, 'assets/src/js/'),
  themeDir: path.join(__dirname, themeDirPath),
  cssProd: path.join(__dirname, themeDirPath, 'assets/css/'),
  jsProd: path.join(__dirname, themeDirPath, 'assets/js/'),
};

const walkSync = (dir, list) => {
  let filelist = list || [];
  fs.readdirSync(dir).forEach((file) => {
    filelist = fs.statSync(path.join(dir, file)).isDirectory()
      ? walkSync(path.join(dir, file), filelist)
      : filelist.concat(path.join(dir, file));
  });
  return filelist;
};

const cssFilelist = walkSync(PATHS.cssSrc);
const jsFilelist = walkSync(PATHS.jsSrc);
// console.log(cssFilelist);
// console.log(jsFilelist);

cssFilelist.forEach((file) => {
  const basename = path.basename(file);
  const newFilePath = (basename === 'style.css') ? path.join(PATHS.themeDir, basename) : path.join(PATHS.cssProd, basename);
  // console.log(`${ind}: ${basename} -> ${newFilePath}`);

  function transformCss() {
    fs.readFile(file, (err, css) => {
      if (err) throw err;
      postcss([autoprefixer, postcssInlineSvg, cssnano])
        .process(css, { from: file, to: newFilePath })
        .then((result) => {
          fs.writeFile(newFilePath, result.css, (wrErr) => {
            if (wrErr) throw wrErr;
            console.log('File \x1b[36m%s\x1b[0m is created successfully.', newFilePath);
          });
          // if (result.map) {
          //     fs.writeFile('dest/app.css.map', result.map, () => true);
          // }
        });
    });
  }

  transformCss();

  if (devMode) {
    console.log(`Watch ${basename}...`);
    fs.watch(file, (eventType, filename) => {
      // console.log(`event type is: ${eventType}`);
      if (filename) {
        transformCss();
      } else {
        console.log('filename not provided');
      }
    });
  }
});

jsFilelist.forEach((file) => {
  const ext = path.extname(file);
  const basename = path.basename(file, ext);
  const newFilePathModern = path.join(PATHS.jsProd, basename + ext);
  const newFilePathLegacy = path.join(PATHS.jsProd, `${basename}-legacy${ext}`);
  // console.log(`${ind}: ${basename + ext} -> ${newFilePathModern}`);
  // console.log(`${ind}: ${basename + ext} -> ${newFilePathLegacy}`);

  const env = {
    production: {
      presets: [['minify', { builtIns: false }]],
      plugins: ['transform-remove-console'],
      comments: false,
    },
  };

  function transformJs() {
    babel.transformFileAsync(file, {
      babelrc: false,
      presets: ['@babel/preset-env'],
      env,
    })
      .then((result) => {
        fs.writeFile(newFilePathLegacy, result.code, (err) => {
          if (err) throw err;
          console.log('File \x1b[36m%s\x1b[0m is created successfully.', newFilePathLegacy);
        });
      })
      .catch(err => console.error(err));

    babel.transformFileAsync(file, {
      babelrc: false,
      env,
    })
      .then((result) => {
        fs.writeFile(newFilePathModern, result.code, (err) => {
          if (err) throw err;
          console.log('File \x1b[36m%s\x1b[0m is created successfully.', newFilePathModern);
        });
      })
      .catch(err => console.error(err));
  }

  transformJs();

  if (devMode) {
    console.log(`Watch ${basename + ext}...`);
    fs.watch(file, (eventType, filename) => {
      // console.log(`event type is: ${eventType}`);
      if (filename) {
        transformJs();
      } else {
        console.log('filename not provided');
      }
    });
  }
});
