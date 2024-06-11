import gulp from 'gulp';
import watch from 'gulp-watch';
import { exec } from 'child_process';
import cleanCSS from 'gulp-clean-css';
import concat from 'gulp-concat';
import uglify from 'gulp-uglify';
import rename from 'gulp-rename';

// Minify JavaScript
gulp.task('minify-js', function() {
  return gulp.src('assets/js/script.js')
    .pipe(uglify())
    .pipe(rename('script.min.js'))
    .pipe(gulp.dest('assets/js'));
});

// Combine and minify CSS, then output to style-rtl.css
gulp.task('minify-css', function() {
  return gulp.src('assets/css/*.css')
    .pipe(concat('style-rtl.css'))
    .pipe(cleanCSS())
    .pipe(gulp.dest('.'));
});

// Compile RTL CSS after minifying CSS
gulp.task('compile-rtl', function(done) {
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
gulp.task('watch', function() {
  watch('assets/js/*.js', gulp.series('minify-js'));
  watch('assets/css/*.css', gulp.series('minify-css', 'compile-rtl'));
});

// Default task to run watch
gulp.task('default', gulp.series('watch'));
