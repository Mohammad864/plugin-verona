const path = require('path');

module.exports = {
  entry: './assets/src/js/admin/index.js',
  output: {
    filename: 'admin.js',
    path: path.resolve(__dirname, 'assets/dist/js'),
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-env'],
          },
        },
      },
    ],
  },
};
