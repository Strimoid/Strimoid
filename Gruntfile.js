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
        cssmin : {
            minify:{
                src: 'public/static/css/style.css',
                dest: 'public/static/css/style.css'
            }
        },
        uglify : {
            js: {
                options: {
                    sourceMap: true
                },
                files: {
                    'public/static/js/app.js' : [ 'public/static/js/app.js' ]
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.registerTask('default', [ 'concat:css', 'cssmin:minify', 'concat:js', 'uglify:js' ]);
};
