/** @type {import('tailwindcss').Config} */
module.exports = {
  variants: {
    extend: {
      textOpacity: ['dark']
    }
  },
  darkMode: 'class',
  content: ["././**/*.{php,js}"],
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}

