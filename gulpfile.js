const gulp = require('gulp');
const plumber = require('gulp-plumber');
const sourcemaps = require('gulp-sourcemaps');
const postcss = require('gulp-postcss');
const gulpIf = require('gulp-if');
const sass = require('gulp-sass')(require('sass'));
const rename = require('gulp-rename');
const { createGulpEsbuild } = require('gulp-esbuild');
const esbuild = createGulpEsbuild({ incremental: false });

const paths = { scss:'scss/**/*.scss', js:'src/js/**/*.js', outCss:'dist/css', outJs:'dist/js' };
const isProd = process.env.NODE_ENV === 'production';

function clean() {
  const out = path.resolve('dist');
  return new Promise((resolve, reject) => {
    fs.rm(out, { recursive: true, force: true }, (err) => {
      if (err) return reject(err);
      resolve();
    });
  });
}

function stylesMain(){
  return gulp.src(['scss/main.scss'], { allowEmpty: true })
    .pipe(plumber())
    .pipe(gulpIf(!isProd, sourcemaps.init()))
    .pipe(sass.sync({ outputStyle: 'expanded' }))
    .pipe(postcss([ require('autoprefixer')(), ...(isProd ? [require('cssnano')()] : []) ]))
    .pipe(rename({ basename: 'main', suffix: '.min' }))
    .pipe(gulpIf(!isProd, sourcemaps.write('.')))
    .pipe(gulp.dest(paths.outCss));
}

function scripts(){
  return gulp.src(['src/js/*.js'], { allowEmpty: true })
    .pipe(plumber())
    .pipe(esbuild({ bundle:true, format:'iife', target:'es2018', sourcemap:!isProd,
                    outdir: paths.outJs, entryNames:'[name]', legalComments:'none', minify:isProd }));
}

function watchAll(){ 
  gulp.watch(paths.scss, stylesMain);
  gulp.watch(paths.js, scripts);
}

const dev = gulp.series(clean, gulp.parallel(stylesMain, scripts));
const build = gulp.series(() => { process.env.NODE_ENV='production'; return Promise.resolve(); }, clean, gulp.parallel(stylesMain, scripts));

exports.clean = clean; exports.styles = stylesMain; exports.scripts = scripts;
exports.dev = dev; exports.build = build; exports.watch = gulp.series(dev, watchAll);
