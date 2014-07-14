module.exports = function(grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        concat: {
            css: {
                src: [
                    'app/assets/css/*'
                ],
                dest: 'public/static/css/style.css'
            },
            js : {
                src : [
                    'app/assets/js/libs/*.js',
                    'app/assets/js/plugins/*.js',
                    'app/assets/js/modules/*.js',
                    'app/assets/js/*.js'
                ],
                dest : 'public/static/js/app.js'
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
                    'public/static/js/app.min.js' : [ 'public/static/js/app.js' ]
                }
            }
        },
        hashres: {
            build: {
                options: {
                    renameFiles: false,
                    fileNameFormat: '${name}.${hash}.${ext}'
                },
                src: [ 'public/static/css/style.min.css', 'public/static/js/app.min.js' ],
                dest: 'app/config/assets.php'
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-hashres');
    grunt.loadNpmTasks('grunt-autoprefixer');

    grunt.registerTask('default', [
        'concat:css', 'autoprefixer:build', 'cssmin:minify', 'concat:js', 'uglify:js', 'hashres:build'
    ]);
};
