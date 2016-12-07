var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
  mix
    .sass(['app.scss'], 'public/css/app.css')
    .copy('bower_components/font-awesome/fonts', 'public/build/fonts')
    .copy('bower_components/bootstrap/fonts', 'public/build/bootstrap/fonts')
    .version('css/app.css');

  mix
    .copy('bower_components/bootstrap/dist/js/bootstrap.min.js', 'public/assets/bootstrap/js/bootstrap.min.js')
    .copy('bower_components/remarkable-bootstrap-notify/dist', 'public/assets/js/bootstrap-notify')
    .copy('bower_components/animate.css/animate.min.css', 'public/assets/css/animate.min.css')
    .copy('bower_components/moment/min/moment-with-locales.min.js', 'public/assets/moment/js/moment-with-locales.min.js')
    .copy('bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js', 'public/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js');

  /* jQuery */
  mix
    .copy('bower_components/jquery/dist', 'public/assets/jquery/js');

  /* DataTables */
  mix
    .copy('bower_components/datatables.net/js', 'public/assets/datatables/js')
    .copy('bower_components/datatables.net-bs/js', 'public/assets/datatables/js')
    .copy('bower_components/datatables.net-bs/css', 'public/assets/datatables/css')
    .copy('bower_components/datatables.net-select/js', 'public/assets/datatables/js')
    .copy('bower_components/datatables.net-select-bs/css', 'public/assets/datatables/css')
    .copy('resources/assets/datatables-i18n/Japanese.json', 'public/assets/datatables/i18n/Japanese.json')
    .copy('resources/assets/jquery-datatables-checkboxes/js', 'public/assets/datatables/js')
    .copy('resources/assets/jquery-datatables-checkboxes/css', 'public/assets/datatables/css');

  /* CKEditor */
  mix
    .copy('bower_components/ckeditor/ckeditor.js', 'public/assets/ckeditor/ckeditor.js')
    .copy('bower_components/ckeditor/lang/ja.js', 'public/assets/ckeditor/lang/ja.js')
    .copy('bower_components/ckeditor/contents.css', 'public/assets/ckeditor/contents.css')
    .copy('bower_components/ckeditor/plugins', 'public/assets/ckeditor/plugins')
    .copy('resources/assets/ckeditor', 'public/assets/ckeditor');

  /* bootstrap file input */
  mix
    .copy('bower_components/bootstrap-fileinput/css', 'public/assets/bootstrap-fileinput/css')
    .copy('bower_components/bootstrap-fileinput/img', 'public/assets/bootstrap-fileinput/img')
    .copy('bower_components/bootstrap-fileinput/js', 'public/assets/bootstrap-fileinput/js');

});
