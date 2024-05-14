/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./vendor/tales-from-a-dev/flowbite-bundle/templates/**/*.html.twig",
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
    "./node_modules/flowbite/**/*.js",
    "./vendor/tales-from-a-dev/flowbite-bundle/templates/**/*.html.twig"
    // set up the path to the flowbite package
  ],
  theme: {
    darkMode: 'class',
    colors: {
      'primary': '#18629C',
    },
    extend: {},
  },
  plugins: [
    require('flowbite/plugin','@tailwindcss/forms') // add the flowbite plugin
  ],
}