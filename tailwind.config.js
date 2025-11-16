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
        'sph-green': {
          light: '#f0fdf4',
          DEFAULT: '#16a34a',
          dark: '#166534',
        },
        'sph-sidebar-active-bg': '#dcfce7',
        'sph-text-dark': '#374151',
        'sph-text-medium': '#6b7280',
        'sph-border': '#e5e7eb',
        'sph-logout-bg': '#fee2e2',
        'sph-logout-text': '#dc2626',
        'sph-logout-border': '#fca5a5',
      }
    },
  },
  plugins: [],
}
