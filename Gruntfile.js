module.exports = function(grunt){
	grunt.initConfig({
		pkg:grunt.file.readJSON('package.json'),
		sass:{
			dist:{
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
		}
	});
	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.registerTask('default', ['sass']);
	//grunt.registerTask('default', ['watch']);
};

