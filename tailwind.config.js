/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        burgundy: '#800020',
        whiteSmoke: '#F4F4F4',
        eerieBlack: '#191716',
        keppel: '#3BA99C',
        charcoal: '#464646'
      }

    },
  },
  plugins: [],
}