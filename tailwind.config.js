const defaultTheme = require("tailwindcss/defaultTheme");
const colors = require("tailwindcss/colors");

module.exports = {
    mode: "jit",
    purge: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
        "./vendor/spatie/laravel-support-bubble/config/**/*.php",
        "./vendor/spatie/laravel-support-bubble/resources/views/**/*.blade.php",
    ],
    safelist: [
        {
            pattern: /grid-cols-/,
            variants: ["md", "lg", "hover", "focus"],
        },
        {
            pattern: /box-/,
            variants: ["lg", "hover", "focus"],
        },
        {
            pattern: /h-/,
            variants: ["lg", "hover", "focus"],
        },
        {
            pattern: /w-/,
            variants: ["lg", "hover", "focus"],
        },
        {
            pattern: /border-/,
            variants: ["lg", "hover", "focus"],
        },
        {
            pattern: /bg-/,
            variants: ["lg", "hover", "focus"],
        },
        {
            pattern: /text-/,
            variants: ["lg", "hover", "focus"],
        },
        {
            pattern: /^ring-/,
            variants: ["lg", "hover", "focus"],
        },
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
};
