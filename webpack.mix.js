const mix = require('laravel-mix');

// Set the public path to `public_html`
mix.setPublicPath('public_html');

// Minify individual CSS and JS files
mix.minify('public_html/assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css');
mix.minify('public_html/assets/plugins/material-date-range-picker/dist/duDatepicker-theme.css');
mix.minify('public_html/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css');
mix.minify('public_html/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js');
mix.minify('public_html/assets/plugins/datatable/js/dataTables.bootstrap5.js');
mix.minify('public_html/assets/plugins/select2/css/select2-bootstrap4.css');
mix.minify('public_html/assets/plugins/input-tags/css/tagsinput.css');
mix.minify('public_html/assets/plugins/external/sample/js/common.js');
mix.minify('public_html/assets/plugins/simplebar/css/simplebar.css');
mix.minify('public_html/assets/js/form-date-time-pickers.js');
mix.minify('public_html/assets/css/header-colors.css');
mix.minify('public_html/assets/js/table-datatable.js');
mix.minify('public_html/assets/css/dark-theme.css');
mix.minify('public_html/assets/js/form-select2.js');
mix.minify('public_html/assets/css/semi-dark.css');
mix.minify('public_html/assets/js/analytics.js');
mix.minify('public_html/assets/js/scrollbar.js');
mix.minify('public_html/assets/css/icons.css');
mix.minify('public_html/assets/js/main_tp.js');
mix.minify('public_html/assets/css/app.css');
mix.minify('public_html/assets/js/app.js');
mix.minify('public_html/assets/js/new.js');

// Enable cache busting (optional, but recommended)
mix.version();
