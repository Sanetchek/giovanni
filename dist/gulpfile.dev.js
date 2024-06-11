"use strict";

var _gulp = _interopRequireDefault(require("gulp"));

var _gulpWatch = _interopRequireDefault(require("gulp-watch"));

var _child_process = require("child_process");

var _gulpCleanCss = _interopRequireDefault(require("gulp-clean-css"));

var _gulpConcat = _interopRequireDefault(require("gulp-concat"));

var _gulpUglify = _interopRequireDefault(require("gulp-uglify"));

var _gulpRename = _interopRequireDefault(require("gulp-rename"));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { "default": obj }; }

// Minify JavaScript
_gulp["default"].task('minify-js', function () {
  return _gulp["default"].src('assets/js/script.js').pipe((0, _gulpUglify["default"])()).pipe((0, _gulpRename["default"])('script.min.js')).pipe(_gulp["default"].dest('assets/js'));
}); // Combine and minify CSS, then output to style-rtl.css


_gulp["default"].task('minify-css', function () {
  return _gulp["default"].src('assets/css/*.css').pipe((0, _gulpConcat["default"])('style-rtl.css')).pipe((0, _gulpCleanCss["default"])()).pipe(_gulp["default"].dest('.'));
}); // Compile RTL CSS after minifying CSS


_gulp["default"].task('compile-rtl', function (done) {
  (0, _child_process.exec)('npm run compile:rtl', function (error, stdout, stderr) {
    if (error) {
      console.error("exec error: ".concat(error));
      done(error);
      return;
    }

    console.log("stdout: ".concat(stdout));
    console.error("stderr: ".concat(stderr));
    done();
  });
}); // Watch task to run minify-js and minify-css on file changes


_gulp["default"].task('watch', function () {
  (0, _gulpWatch["default"])('assets/js/*.js', _gulp["default"].series('minify-js'));
  (0, _gulpWatch["default"])('assets/css/*.css', _gulp["default"].series('minify-css', 'compile-rtl'));
}); // Default task to run watch


_gulp["default"].task('default', _gulp["default"].series('watch'));