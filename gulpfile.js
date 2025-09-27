const gulp = require('gulp');
const fs = require('fs');
const path = require('path');
const plumber = require('gulp-plumber');
const sourcemaps = require('gulp-sourcemaps');
const postcss = require('gulp-postcss');
const sass = require('gulp-sass')(require('sass'));
const rename = require('gulp-rename');

const esbuild = require('esbuild'); // ← use native esbuild API (reliable)

const paths = {
  scss: 'scss/**/*.scss',
  js: 'src/js/**/*.js',
  outCss: 'dist/css',
  outJs: 'dist/js',
};

function clean() {
  const out = path.resolve('dist');
  return new Promise((resolve, reject) => {
    fs.rm(out, { recursive: true, force: true }, (err) => {
      if (err) return reject(err);
      resolve();
    });
  });
}

/* ======================
   CSS — always sourcemaps + minify
   ====================== */

function stylesMain() {
  return gulp.src(['scss/main.scss'], { allowEmpty: true })
    .pipe(plumber())
    .pipe(sourcemaps.init())
    .pipe(
      sass.sync({ outputStyle: 'expanded' }).on('error', sass.logError)
    )
    .pipe(postcss([
      require('autoprefixer')(),
      require('cssnano')(), // always minify
    ]))
    .pipe(rename({ basename: 'main', suffix: '.min' }))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(paths.outCss));
}

/* ======================
  JS — always sourcemaps + minify
  ====================== */

function scripts() {
  // discover entry points (e.g., main.js, admin.js)
  const srcDir = 'src/js';
  if (!fs.existsSync(srcDir)) {
    // nothing to build, ensure out dir exists to avoid errors
    fs.mkdirSync(paths.outJs, { recursive: true });
    return Promise.resolve();
  }

  const entryPoints = fs.readdirSync(srcDir)
    .filter(f => f.endsWith('.js'))
    .map(f => path.join(srcDir, f));

  if (entryPoints.length === 0) {
    fs.mkdirSync(paths.outJs, { recursive: true });
    return Promise.resolve();
  }

  // ensure outdir exists
  fs.mkdirSync(paths.outJs, { recursive: true });

  return esbuild.build({
    entryPoints,
    outdir: paths.outJs,
    bundle: true,
    format: 'iife',
    target: ['es2018'],
    sourcemap: true,     // always
    minify: true,        // always
    entryNames: '[name].min', // outputs main.min.js, admin.min.js
    legalComments: 'none',
    logLevel: 'silent',  // change to 'info' if you want logs
  });
}

/* ======================
  Watch / Tasks
  ====================== */

function watchAll() {
  gulp.watch(paths.scss, stylesMain);
  gulp.watch(paths.js, scripts);
}

const dev = gulp.series(clean, gulp.parallel(stylesMain, scripts));
const build = gulp.series(clean, gulp.parallel(stylesMain, scripts));

exports.clean = clean;
exports.styles = stylesMain;
exports.scripts = scripts;
exports.dev = dev;
exports.build = build;
exports.watch = gulp.series(dev, watchAll);
