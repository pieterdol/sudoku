module.exports = function(grunt) {
    
    grunt.initConfig({
        concat: {
            vendorScripts: {
                src: [
                    'node_modules/jquery/dist/jquery.min.js', 
                    'node_modules/bootstrap/dist/js/bootstrap.min.js',
                ],
                dest: 'dist/js/vendor.js'
            }
        },
        uglify: {
            scripts: {
                files: {
                    'dist/js/scripts.min.js': ['scripts/**/*.js']
                }
            }
        },
        cssmin: {
            options: {
                shorthandCompacting: false,
                roundingPrecision: -1
            },
            target: {
                files: {
                    'dist/css/vendor.css': [
                        'node_modules/bootstrap/dist/css/bootstrap.min.css',
                    ]
                }
            }
        },
        sass: {
            dist: {
                options: {
                    style: 'compressed'
                },
                files: {
                    'dist/css/styles.min.css': 'styles/**/*.css'
                },
            }
        },
        watch: {
            scripts: {
                files: ['scripts/**/*.js'],
                tasks: ['uglify:scripts'],
                options: {
                    livereload: true
                }
            },
            styles: {
                files: ['styles/**/*.css'],
                tasks: ['sass'],
                options: {
                    livereload: true
                }
            },
            files: {
                files: ['views/**/*.twig'],
                options: {
                    livereload: true
                }
            }
        }
    });
    
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.registerTask('run', ['concat', 'uglify', 'cssmin', 'sass', 'watch']);
};