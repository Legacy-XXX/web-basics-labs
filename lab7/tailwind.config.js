/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./*.php', './includes/**/*.php', './assets/js/**/*.js'],
  theme: {
    // Власні брейкпоінти: додано xs (480px) і 3xl (1700px)
    screens: {
      xs: '480px',
      sm: '640px',
      md: '768px',
      lg: '1024px',
      xl: '1280px',
      '3xl': '1700px',
    },
    extend: {
      // Фірмові кольори магазину «Клац»
      colors: {
        klats: {
          50: '#eef5ff',
          100: '#d9e9ff',
          200: '#bcd6ff',
          500: '#2f6fed',
          600: '#1f5ad6',
          700: '#1a48a8',
          900: '#0f2a5f',
        },
        zap: {
          400: '#ffc24d',
          500: '#ffb020',
          600: '#e09500',
        },
      },
      // Власний шрифт для заголовків
      fontFamily: {
        display: ['"Segoe UI"', 'Montserrat', 'system-ui', 'sans-serif'],
      },
      // Власний розмір шрифту, відступи, радіус і тінь
      fontSize: {
        hero: ['3.25rem', { lineHeight: '1.1' }],
      },
      spacing: {
        18: '4.5rem',
        128: '32rem',
      },
      borderRadius: {
        card: '1.25rem',
      },
      boxShadow: {
        card: '0 10px 30px -12px rgba(15, 42, 95, 0.35)',
      },
    },
  },
  plugins: [],
};
