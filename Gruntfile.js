module.exports = function(grunt){
	grunt.initConfig({
		pkg:grunt.file.readJSON('package.json'),
		sass:{
			dist:{
				options:{
					style:'compressed'
				},
				files:{
					'media/css/style.css':'media/css/style.scss'
				}
			}
		},
		watch: {
			css: {
				files: ['media/css/*.scss', 'media/css/*/*.scss'],
				tasks: ['sass']
			}
		},
		cssmin: {
			css:{
				files:{
					'media/css/style.min.css':'media/css/style.css'
				}
			}
		}
	});
	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.registerTask('minifycss', ['cssmin']);
	grunt.registerTask('default', ['sass']);
	//grunt.registerTask('default', ['watch']);
};

