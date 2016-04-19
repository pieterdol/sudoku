module.exports = function(grunt) {
    
    grunt.initConfig({
        concat: {
            vendorScripts: {
                src: [
                    'node_modules/jquery/dist/jquery.min.js', 
                    'node_modules/bootstrap/dist/js/bootstrap.min.js',
                ],
                dest: 'dist/js/vendor.js',
            },
            styles: {
                src: ['styles/**/*.css'],
                dest: 'dist/css/styles.css',
            },
        },
        watch: {
            scripts: {
                files: ['scripts/**/*.js'],
                tasks: ['uglify'],
            },
            styles: {
                files: ['styles/**/*.css'],
                tasks: ['concat:styles'],
            },
        },
        uglify: {
            min: {
                files: {
                    'dist/js/scripts.min.js': ['scripts/sudoku.js']
                }
            }
        }
    });
    
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.registerTask('run', ['concat', 'uglify', 'watch']);
};