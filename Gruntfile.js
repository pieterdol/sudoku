module.exports = function(grunt) {
    
    grunt.initConfig({
        concat: {
            scripts: {
                src: [
                    'node_modules/jquery/dist/jquery.min.js', 
                    'node_modules/bootstrap/dist/js/bootstrap.min.js',
                    'scripts/**/*.js', 
                ],
                dest: 'dist/js/scripts.js',
            },
            styles: {
                src: ['styles/**/*.css'],
                dest: 'dist/css/styles.css',
            },
        },
        watch: {
            scripts: {
                files: ['scripts/**/*.js'],
                tasks: ['concat:scripts'],
            },
            styles: {
                files: ['styles/**/*.css'],
                tasks: ['concat:styles'],
            },
        },
    });
    
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.registerTask('run', ['concat', 'watch']);
};