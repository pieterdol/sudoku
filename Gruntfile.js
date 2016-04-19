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
    });
    
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.registerTask('run', ['concat', 'uglify', 'cssmin', 'watch']);
};