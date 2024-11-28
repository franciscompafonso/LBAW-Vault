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
        charcoal: '#464646',

        wisteria: '#C59FDF',
        ultraViolet: '#4F517D',
        darkSlateGrey: '#1A3A3A',
        cambridgeBlue:'#7FC29B',
        babyPowder: '#FFFFFB'

      }
    },
  },
  plugins: [],
}