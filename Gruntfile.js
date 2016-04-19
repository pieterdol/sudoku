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
                tasks: ['cssmin'],
            },
        },
        uglify: {
            files: {
                'dist/js/scripts.min.js': ['scripts/**/*.js']
            }
        },
        cssmin: {
            options: {
                shorthandCompacting: false,
                roundingPrecision: -1
            },
            target: {
                files: {
                    'dist/css/styles.min.css': ['styles/**/*.css']
                }
            }
        }
    });
    
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.registerTask('run', ['uglify', 'cssmin', 'watch']);
};