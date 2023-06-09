const defaultTheme = require("tailwindcss/defaultTheme");
const colors = require("tailwindcss/colors");

module.exports = {
    mode: "jit",
    content: [
      "./resources/**/*.blade.php",
      "./resources/**/*.js",
    ],
    purge: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
        "./vendor/spatie/laravel-support-bubble/config/**/*.php",
        "./vendor/spatie/laravel-support-bubble/resources/views/**/*.blade.php",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ["Nunito", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                rose: colors.rose,
                sky: colors.sky,
                teal: {
                    100: "#019DBB",
                    200: "#019DBB",
                    300: "#019DBB",
                    400: "#02abc9",
                    500: "#019DBB",
                    600: "#0088a0",
                    700: "#019DBB",
                    800: "#019DBB",
                    900: "#019DBB",
                },
            },
        },
    },
    plugins: [
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
    ],
  }
