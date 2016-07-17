var gulp = require('gulp');
var uglify = require('gulp-uglify');
var concat = require('gulp-concat');
var minifycss = require('gulp-minify-css');
var sass = require('gulp-sass');
var browserSync = require('browser-sync').create();
var reload = browserSync.reload;

// localhost 를 프록시로 웹서버 실행
gulp.task('server', ['uglify', 'minifycss'], function () {
	return browserSync.init({
                port:8080,
                proxy:'localhost',
                notify: false,
                logConnections: false,
                logPrefix: 'laravel elixir browserSync',
                loadOnRestart: false
    });
});

//자바스크립트 파일을 minify
gulp.task('uglify', function () {
    return gulp.src('./resources/assets/js/**/*.js') // resources/assets/js 폴더 아래의 모든 js 파일을
        .pipe(concat('main.js')) //병합하고
        .pipe(uglify()) //minify 해서
        .pipe(gulp.dest('public/js')) // public/js 폴더에 저장
        .pipe(browserSync.reload({stream:true})); //browserSync 로 브라우저에 반영
});

//CSS 파일을 minify
gulp.task('minifycss', function () {
    return gulp.src('./resources/assets/sass/**/*.scss') // resources/assets/sass 폴더 아래의 모든 sass 파일을
    	.pipe(sass.sync().on('error', sass.logError)) // css로 변환하고
        .pipe(concat('main.css')) //병합하고
        .pipe(minifycss()) //minify 해서
        .pipe(gulp.dest('public/css')) // public/css 폴더에 저장
        .pipe(browserSync.reload({stream:true})); // browserSync 로 브라우저에 반영
});

//파일 변경 감지
gulp.task('watch', function () {
    gulp.watch('./resources/assets/js/**/*.js', ['uglify']);
    gulp.watch('./resources/assets/sass/**/*.scss', ['minifycss']);
    gulp.watch(['./resources/views/**/*.php','public/**/*.php','public/*.php','config/**/*.php','app/**/*.php'],reload);
});

//gulp를 실행하면 default 로 minifycss task를 실행
gulp.task('default', ['server', 'watch']);


// var elixir = require('laravel-elixir');
// elixir(function(mix) {
//     mix.sass('app.scss');
// });