const gulp = require('gulp');
const sassify = require('gulp-sass');
const pugify = require('gulp-pug');
const extreplacify = require('gulp-ext-replace');
const plumberify = require('gulp-plumber');

gulp.task('javascript',function(){
    gulp.src([
        "src/js/*.js",
        "node_modules/materialize-css/dist/js/materialize.min.js",
        "node_modules/jquery/dist/jquery.min.js"
    ]).pipe(gulp.dest("build/js/"));
});

gulp.task('php-classes',function(){
    gulp.src("src/php/classes/*.php").pipe(gulp.dest("build/php/"));
});

gulp.task('php-api',function(){
    gulp.src("src/php/api/**/*.php",{base:"src/php/api"}).pipe(gulp.dest("build/api/"));
});

gulp.task('pug',function(){
    gulp.src("src/pug/site/**/*.pug",{base:"src/pug/site"})
    .pipe(plumberify()).pipe(pugify({
        pretty:true,
        basedir:"./src/pug"
    })).pipe(extreplacify("php")).pipe(gulp.dest("build/"));
});

gulp.task('sass',function(){
    gulp.src([
        'node_modules/materialize-css/sass/materialize.scss',
        'src/sass/*.scss'
    ]).pipe(plumberify())
    .pipe(sassify())
    .pipe(gulp.dest("build/css"))
});

gulp.task('watch',function(){
    gulp.watch("src/php/**/*",['php-api','php-classes']);
    gulp.watch("src/pug/**/*.pug",['pug']);
    gulp.watch("src/js/**/*.js",['javascript']);
    gulp.watch(["node_modules/materialize-css/sass/**/*.scss",'src/sass/*'],['sass']);
});

gulp.task('img',function(){
    return gulp.src("src/img/**/*",{base:"src/img/"}).pipe(gulp.dest("build/img"));
});

gulp.task('css',function(){
    return gulp.src(["src/css/flag-icon.min.css"]).pipe(gulp.dest("build/css"));
});

gulp.task('flag-img',function(){
    return gulp.src('src/flags/**/*',{base:"src/flags"}).pipe(gulp.dest("build/flags"));
});

gulp.task('default',['javascript','php-classes','php-api','pug','sass','css','flag-img','watch','img']);
