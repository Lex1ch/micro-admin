const mix = require('laravel-mix');

mix.setPublicPath('./public/');

mix.webpackConfig(webpack => {
  return {
    plugins: [
      new webpack.ProvidePlugin({
        $: 'jquery',
        jQuery: 'jquery',
        'window.jQuery': 'jquery'
      })
    ]
  };
});

// front-end

// back-end
mix.js('resources/backend/js/admin.js', 'public/backend/js')
    .styles('resources/backend/fonts/icomoon/styles.min.css', 'public/backend/css/font-icomoon.css')
    .sass('resources/backend/sass/admin.scss', 'public/backend/css')
    .options({
      postCss: [
        require('css-mqpacker'),
        require('cssnano')({
          preset: [
            'default', {
              discardComments: {
                removeAll: true
              }
            }
          ]
        })
      ]
    })
    .version();

