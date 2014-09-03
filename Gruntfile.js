module.exports = function(grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        ngAnnotate: {
            options: {
                singleQuotes: true
            },
            js: {
                files: {
                    'app/assets/js/tmp/angular.js': [
                        'app/assets/js/app.js',
                        'app/assets/js/controllers/*.js'
                    ]
                }
            }
        },
        bosonic: {
            build: {
                src: ['app/assets/components/*.html'],
                css: 'public/static/js/components.css',
                js: 'public/static/js/components.js'
            }
        },
        concat: {
            css: {
                src: [
                    'app/assets/css/*.css',
                    'public/static/js/components.css'
                ],
                dest: 'public/static/css/style.css'
            },
            js : {
                src : [
                    'app/assets/js/libs/*.js',
                    'app/assets/js/plugins/*.js',
                    'app/assets/js/modules/*.js',
                    'app/assets/js/tmp/*.js',
                    'app/assets/js/lara.js'
                ],
                dest : 'public/static/js/app.js'
            },
            components : {
                src : [
                    'app/assets/js/bosonic.js',
                    'public/static/js/components.js'
                ],
                dest : 'public/static/js/components.js'
            }
        },
        autoprefixer: {
            build: {
                options: {
                    browsers: ['last 2 version', 'ie 9']
                },
                src: 'public/static/css/style.css',
                dest: 'public/static/css/style.css'
            }
        },
        cssmin : {
            minify:{
                src: 'public/static/css/style.css',
                dest: 'public/static/css/style.min.css'
            }
        },
        uglify : {
            js: {
                options: {
                    sourceMap: true
                },
                files: {
                    'public/static/js/app.min.js' : [ 'public/static/js/app.js' ],
                    'public/static/js/components.min.js' : [ 'public/static/js/components.js' ]
                }
            }
        },
        hashres: {
            build: {
                options: {
                    renameFiles: false,
                    fileNameFormat: '${name}.${hash}.${ext}'
                },
                src: [
                    'public/static/css/style.min.css',
                    'public/static/js/app.min.js',
                    'public/static/js/components.min.js'
                ],
                dest: 'app/config/assets.php'
            }
        },
        clean: {
            tmpjs: ['app/assets/js/tmp']
        }
    });

    grunt.loadNpmTasks('grunt-autoprefixer');
    grunt.loadNpmTasks('grunt-bosonic');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-hashres');
    grunt.loadNpmTasks('grunt-ng-annotate');

    grunt.registerTask('default', [
        'ngAnnotate:js', 'bosonic:build', 'concat:components',
        'concat:css', 'autoprefixer:build', 'cssmin:minify',
        'concat:js', 'uglify:js', 'hashres:build', 'clean:tmpjs'
    ]);
};
