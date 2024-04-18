/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
    "./node_modules/flowbite/**/*.js" // set up the path to the flowbite package
  ],
  theme: {
    colors: {
      'primary': '#18629C',
    },
    extend: {},
  },
  plugins: [
    require('flowbite/plugin') // add the flowbite plugin
  ],
}