const fs = require('fs');
const path = require('path');

const oldPath = path.join(__dirname, './public_html/wp-content/themes/excursions/assets/css/style.css');
const newPath = path.join(__dirname, './public_html/wp-content/themes/excursions/style.css');

const remPaths = [
  path.join(__dirname, './public_html/wp-content/themes/excursions/assets/js/style.js'),
  path.join(__dirname, './public_html/wp-content/themes/excursions/assets/js/style-legacy.js'),
  path.join(__dirname, './public_html/wp-content/themes/excursions/assets/js/gallery.js'),
  path.join(__dirname, './public_html/wp-content/themes/excursions/assets/js/gallery-legacy.js'),
  path.join(__dirname, './public_html/wp-content/themes/excursions/assets/js/main_bottom.js'),
  path.join(__dirname, './public_html/wp-content/themes/excursions/assets/js/main_bottom-legacy.js'),
];

fs.rename(oldPath, newPath, (err) => {
  if (err) throw err;
  console.log('style.css was moved');
});

remPaths.forEach((remPath) => {
  fs.unlink(remPath, (err) => {
    if (err) throw err;
    console.log(`${remPath} was deleted`);
  });
});
