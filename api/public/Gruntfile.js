module.exports = function(grunt) {

    grunt.initConfig({
        cssmin: {
            target: {
                files: [{
                    expand: true,
                    cwd: "css",
                    src: ['*.css', '!css/style.css'],
                    dest: 'css',
                    ext: '.min.css'
                }]
            }
        },
        uglify: {
            tikrow: {
                files: [{
                    expand: true,
                    cwd: 'js',
                    src: ['*.js', '!bootstrap.*', '!*.min.*'],
                    dest: 'js/min'
                }]
            }
        },
        watch: {
            js: {
                files: ['js/*.js'],
                tasks: ['uglify'],
                options: {
                    spawn: false,
                },
            },
            css: {
                files: ['css/*.css', '!css/*.min.css'],
                tasks: ['cssmin'],
                options: {
                    spawn: false,
                },
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-watch');
};