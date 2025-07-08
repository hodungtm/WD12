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
>>>>>>> ab6ed55fcaf0e846b2515f99403a739e4cb61a4a
