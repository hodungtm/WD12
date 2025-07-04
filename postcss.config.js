// postcss.config.js
import tailwindcss from '@tailwindcss/postcss'
import autoprefixer from 'autoprefixer'

export default {
<<<<<<< HEAD
  plugins: [
    tailwindcss(), 
    autoprefixer(),
  ],
}
=======
    plugins: {
        '@tailwindcss/postcss': {},
        autoprefixer: {},
    },
};
>>>>>>> main
