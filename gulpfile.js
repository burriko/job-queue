var gulp = require('gulp');
var exec = require('child_process').exec;
var console = require('better-console');

gulp.task('phpunit', function() {
    exec('vendor/bin/phpunit --colors=always', function(error, stdout) {
        console.clear();
        console.log(stdout);
    });
});

gulp.task('default', function() {
    gulp.watch('**/*.php', { debounceDelay: 1000 }, ['phpunit']);
});