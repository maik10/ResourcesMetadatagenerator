/**
 * System configuration for Angular 2 samples
 * Adjust as necessary for your application needs.
 */
(function(global) {
  // map tells the System loader where to look for things
  var map = {
    'app':                        'app', // 'dist',
  };
  // packages tells the System loader how to load when no filename and/or no extension
  var packages = {
    'app':                        { main: 'main.js',  defaultExtension: 'js' },
  };
  var meta = {
      deps: {
        "main":'app/main.js'
      }
  };
  var config = {
    map: map,
    packages: packages,
    meta:meta
  }
  System.config(config);
})(this);
