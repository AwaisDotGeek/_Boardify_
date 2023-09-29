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
        'black-1': '#404a4c',
        'black-2': '#3a434a',
        'black-3': '#333738',
        'black-4': '#212b2a',
        'black-5': '#0e0F11',
      }
    },
  },
  plugins: [],
}

