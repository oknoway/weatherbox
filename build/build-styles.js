module.exports = function( grunt ) {

  'use strict';

  // Project configuration
  grunt.config.merge( {
    scsslint : {
      dist: [
        '<%= dirs.sass %>/**/*.scss'
      ],
      options: {
        config: '.scss-lint.yml',
        maxBuffer: 800 * 1024,
        exclude: [ '<%= dirs.sass %>/vendor/**/*.scss' ]
      }
    },
    sass:  {
      options: {
        sourceMap: true,
        sourceMapContents: true,
        includePaths: [
          '<%= dirs.sass %>/vendor',
          '<%= dirs.vendor %>/scss'
        ]
      },
      dist: {
        files: {
          '<%= dirs.css %>/weatherbox.css': '<%= dirs.sass %>/weatherbox.scss',
          '<%= dirs.css %>/admin.css': '<%= dirs.sass %>/admin.scss',
          '<%= dirs.css %>/editor.css': '<%= dirs.sass %>/editor.scss'
        }
      }
    },
    postcss: {
      options: {
        map: {
          inline: false,
          prev: false,
          annotation: '<%= dirs.maps %>'
        },
        processors: [
          require('autoprefixer')({
            browsers: [ 'last 2 versions', 'ie >= 8', 'Android >= 4' ]
          }),
          require('cssnano')()
        ]
      },
      dist: {
        src: '<%= dirs.css %>/*.css'
      }
    },
    clean: {
      maps: [ '<%= dirs.css %>/*.map' ]
    },
    watch:  {
      sass: {
        files: [ '<%= dirs.sass %>/**/*.scss' ],
        tasks: [ 'modernizr', 'styles' ]
      },
    }
  } );

  // Process Styles.
  grunt.registerTask( 'styles', [ 'sass', 'postcss', 'clean' ] );

};
