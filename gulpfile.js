import gulp from 'gulp';
import watch from 'gulp-watch';
import {
  exec
} from 'child_process';
import cleanCSS from 'gulp-clean-css';
import concat from 'gulp-concat';
import uglify from 'gulp-uglify';
import postcss from 'gulp-postcss';
import autoprefixer from 'autoprefixer';

// Minify JavaScript
gulp.task('minify-scripts', function (done) {
  console.log('Starting minify-scripts task');
  return gulp.src('assets/js/scripts/*.js')
    .pipe(concat('scripts.min.js'))
    .pipe(uglify().on('error', function (err) {
      console.error('Uglify error:', err.toString());
      this.emit('end');
    }))
    .pipe(gulp.dest('assets/js'))
    .on('end', function () {
      console.log('minify-scripts task completed');
      done();
    });
});

// Combine, minify, and autoprefix CSS, then output to style-rtl.css
gulp.task('minify-css', function () {
  console.log('Starting minify-css task');
  return gulp.src('assets/css/*.css')
    .pipe(concat('style-rtl.css'))
    .pipe(postcss([autoprefixer()]))
    .pipe(cleanCSS())
    .pipe(gulp.dest('.'));
});

// Compile RTL CSS after minifying CSS
gulp.task('compile-rtl', function (done) {
  console.log('Starting compile-rtl task');
  exec('npm run compile:rtl', (error, stdout, stderr) => {
    if (error) {
      console.error(`exec error: ${error}`);
      done(error);
      return;
    }
    console.log(`stdout: ${stdout}`);
    console.error(`stderr: ${stderr}`);
    done();
  });
});

// Watch task to run minify-js and minify-css on file changes
gulp.task('watch', function () {
  console.log('Watching files...');
  watch('assets/js/scripts/*.js', gulp.series('minify-scripts'));
  watch('assets/css/*.css', gulp.series('minify-css', 'compile-rtl'));
});

// Default task to run watch
gulp.task('default', gulp.series('watch'));
