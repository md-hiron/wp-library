const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
  entry: './src/index.jsx', // Make sure to use the correct entry point
  output: {
    path: path.resolve(__dirname, '../build'),
    filename: 'index.js',
  },
  resolve: {
    extensions: ['.js', '.jsx'], // Add '.jsx' as a resolvable extension
  },
  module: {
    rules: [
      {
        test: /\.(js|jsx)$/, // Update the regex to handle both .js and .jsx files
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-env', '@babel/preset-react'],
          },
        },
      },
      {
        test: /\.css$/,
        use: [MiniCssExtractPlugin.loader, 'css-loader', 'postcss-loader'],
      },
      {
        test: /\.svg$/,
        use: {
          loader: 'file-loader',
          options: {
            name: '[name].[hash].[ext]',
            outputPath: 'assets',
          },
        },
      },
    ],
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: 'index.css',
    }),
  ],
  mode: 'production',
};
