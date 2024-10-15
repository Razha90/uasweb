/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './views/*.php',
  ],
  theme: {
    extend: {
      height: {
        "canvas": "calc(78vh - 2.5rem)",
      }
    },
  },
  plugins: [],
}

