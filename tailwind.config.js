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
        skyBlue: '#97D7ED',
        warmTaupe: '#936D4D',
        deepNavy: '#264653',
        softCream: '#F4E8C1'
      }

    },
  },
  plugins: [],
}