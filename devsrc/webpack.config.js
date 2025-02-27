const path = require('path');
const { VueLoaderPlugin } = require('vue-loader'); // Import the Vue loader plugin

module.exports = {
  entry: './src/main.ts',
  output: {
    filename: 'main.js',
    path: path.resolve(__dirname, '../wp-content/themes/volleyballnetwork/js'), // Use path.resolve for absolute path
    clean: true,
  },
  module: {
    rules: [
      {
        test: /\.tsx?$/,
        loader: 'ts-loader',
        options: {
          appendTsSuffixTo: [/\.vue$/], // Important for Vue + TypeScript support
        },
        exclude: /node_modules/,
      },
      {
        test: /\.vue$/, // Add a rule for .vue files
        use: 'vue-loader',
      },
      { // Add rules for CSS preprocessors if you use them in Vue components
        test: /\.css$/i,
        use: ["vue-style-loader", "css-loader"], // Use vue-style-loader for Vue
      },
      {
        test: /\.s[ac]ss$/i,
        use: [
          "vue-style-loader", // Use vue-style-loader for Vue
          "css-loader",
          "sass-loader",
        ],
      },
    ],
  },
  resolve: {
    extensions: ['.tsx', '.ts', '.js', '.vue'], // Add .vue to the extensions
    alias: { // Add an alias for Vue (important!)
      'vue': 'vue/dist/vue.esm-bundler.js', // For Vue 3 (full build)
    },
  },
  mode: 'development',
  devtool: 'inline-source-map',
  devServer: {
    static: './dist',
  },
  plugins: [
    new VueLoaderPlugin(), // Add the Vue loader plugin to the plugins array
  ],
};
