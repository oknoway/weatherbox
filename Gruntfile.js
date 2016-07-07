module.exports = function( grunt ) {

  'use strict';

  // Load all grunt tasks
  require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);

  // Initialize Grunt config
  grunt.initConfig();

  // Project configuration
  grunt.config.merge( {
    pkg: grunt.file.readJSON( 'package.json' ),
    dirs: {
      bower: './bower_components',
      assets: './assets',
      css: '<%= dirs.assets %>/css',
      sass: '<%= dirs.css %>/src',
      js: '<%= dirs.assets %>/js',
      maps: '<%= dirs.assets %>/maps',
      img: './img',
      vendor: '<%= dirs.assets %>/vendor'
    }
  } );

  // Include specific files
  require( './build/build-install.js' )( grunt );
  require( './build/build-scripts.js' )( grunt );
  require( './build/build-styles.js' )( grunt );
  require( './build/build-watch.js' )( grunt );
  require( './build/grunt.local.js' )( grunt );

  // Default task.
  grunt.registerTask( 'default', [ 'install', 'styles', 'scripts' ] );

  grunt.util.linefeed = '\n';

};
