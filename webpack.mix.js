const mix = require("laravel-mix");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js("resources/js/app.js", "public/js")
    .js("resources/js/store_app.js", "public/js")
    .js("resources/js/user_app.js", "public/js")
    .sourceMaps()
    .webpackConfig({
        resolve: {
            alias: {
                "@resources": path.resolve(__dirname, "resources/"),
                "@": path.resolve(__dirname, "resources/js/src/"),
                "@themeConfig": path.resolve(
                    __dirname,
                    "resources/js/themeConfig.js"
                ),
                "@core": path.resolve(__dirname, "resources/js/src/@core"),
                "@validations": path.resolve(
                    __dirname,
                    "resources/js/src/@core/utils/validations/validations.js"
                ),
                "@axios": path.resolve(
                    __dirname,
                    "resources/js/src/libs/axios"
                ),

                "@@": path.resolve(__dirname, "resources/js/store_src/"),
                "@@core": path.resolve(
                    __dirname,
                    "resources/js/store_src/@core"
                ),
                "@@validations": path.resolve(
                    __dirname,
                    "resources/js/store_src/@core/utils/validations/validations.js"
                ),
                "@@axios": path.resolve(
                    __dirname,
                    "resources/js/store_src/libs/axios"
                ),

                "@@@": path.resolve(__dirname, "resources/js/user_src/"),
                "@@@core": path.resolve(
                    __dirname,
                    "resources/js/user_src/@core"
                ),
                "@@@validations": path.resolve(
                    __dirname,
                    "resources/js/user_src/@core/utils/validations/validations.js"
                ),
                "@@@axios": path.resolve(
                    __dirname,
                    "resources/js/user_src/libs/axios"
                ),
                "@@@themeConfig": path.resolve(
                    __dirname,
                    "resources/js/user_src/themeConfig.js"
                ),
            },
        },
        module: {
            rules: [
                {
                    test: /\.s[ac]ss$/i,
                    use: [
                        {
                            loader: "sass-loader",
                            options: {
                                sassOptions: {
                                    includePaths: [
                                        "node_modules",
                                        "resources/assets",
                                    ],
                                },
                            },
                        },
                    ],
                },
                {
                    test: /(\.(png|jpe?g|gif|webp)$|^((?!font).)*\.svg$)/,
                    loaders: {
                        loader: "file-loader",
                        options: {
                            name: "images/[path][name].[ext]",
                            context:
                                "../vuexy-vuejs-bootstrap-vue-template/src/assets/images",
                            //   context: 'frontend/src/assets/images'
                        },
                    },
                },
            ],
        },
        devServer: {
            proxy: {
                "*": "http://localhost:8000/admin",
            },
        },
    })
    .sass("resources/scss/core.scss", "public/css")
    .options({
        postCss: [require("autoprefixer"), require("postcss-rtl")],
    });
mix.copy("resources/css/loader.css", "public/css");

// ------------------------------------------------
// If you are deploying on subdomain/subfolder. Uncomment below code before running 'yarn prod' or 'npm run production' command.
// Please Change below 'publicPath' and 'setResourceRoot' options as per your sub-directory path. We have kept our current live demo options which is deployed in sub-folder.
// ------------------------------------------------

/*
 if (mix.inProduction()) {
   mix.version()
   mix.webpackConfig({
     output: {
       publicPath: '/demo/vuexy-vuejs-laravel-admin-template/demo-1/',
       chunkFilename: 'js/chunks/[name].[chunkhash].js'
     }
   })
   mix.setResourceRoot('/demo/vuexy-vuejs-laravel-admin-template/demo-1/')
 }
 */

// ------------------------------------------------
// If you are deploying on subdomain/subfolder then comment out below code before running 'yarn prod' or 'npm run production' command.
// ------------------------------------------------

mix.webpackConfig({
    output: {
        chunkFilename: "js/chunks/[name].[chunkhash].js",
    },
});

/*
 |--------------------------------------------------------------------------
 | Browsersync Reloading
 |--------------------------------------------------------------------------
 |
 | BrowserSync can automatically monitor your files for changes, and inject your changes into the browser without requiring a manual refresh.
 | You may enable support for this by calling the mix.browserSync() method:
 | Make Sure to run `php artisan serve` and `yarn watch` command to run Browser Sync functionality
 | Refer official documentation for more information: https://laravel.com/docs/9.x/mix#browsersync-reloading
 */

mix.browserSync("http://127.0.0.1:8000/admin");
