//Node.js Built-In Modules
const path = require('path');

//css extract plugin for production
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

//Webpack config
module.exports = {
  //Create entry points
  //One for frontend and one for the admin area
  entry: {
    'js/frontend': './assets/js/frontend.js',
    'css/frontend': './assets/css/frontend.scss',
  },

  //Create output files
  //One for each of our entry points
  output: {
    path: path.resolve(__dirname, 'dist'),
    filename: '[name].js',
  },

  module: {
    rules: [
      //JavaScript loader
      {
        //Look for any .js files
        test: /\.js$/,
        exclude: /node_modules/,
        //Use Babel for compiling
        loader: 'babel-loader',
      },
      //SCSS/Sass loader
      {
        //Look for any .scss or .sass files
        test: /\.s(a|c)ss$/,
        exclude: /node_modules/,
        use: [
          {
            loader: MiniCssExtractPlugin.loader,
            options: {
              hmr: process.env.NODE_ENV === 'development',
            },
          },
          'css-loader',
          'sass-loader',
        ],
      },
      //File loader for 'fonts'
      //This is necessary:
      //Regular SCSS and JS loaders cannot load and process fonts directly.
      {
        test: /\.(woff(2)?|ttf|eot|svg)(\?v=\d+\.\d+\.\d+)?$/,
        exclude: '/node_modules/',
        use: [
          {
            loader: 'url-loader',
            options: {
              name: '[name].[ext]',
              outputPath: 'fonts/',
            },
          },
        ],
      },
    ],
  },

  //Simplify imports in our JavaScript files
  resolve: {
    extensions: ['.js', '.scss'],
  },

  //Plugins config
  plugins: [
    new MiniCssExtractPlugin({
      filename: '[name].css',
      chuckFilename: '[id].css',
    }),
  ],
};
