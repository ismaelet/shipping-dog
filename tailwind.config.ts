/** @type {import('tailwindcss').Config} */

export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        // Custom colors
        colors: {
            // Primary
            primary: "hsl(var(--color-primary-400-hsl) / <alpha-value>)",
            "primary-200": "hsl(var(--color-primary-200-hsl) / <alpha-value>)",
            "primary-300": "hsl(var(--color-primary-300-hsl) / <alpha-value>)",
            "primary-400": "hsl(var(--color-primary-400-hsl) / <alpha-value>)",
            "primary-500": "hsl(var(--color-primary-500-hsl) / <alpha-value>)",
            "primary-700": "hsl(var(--color-primary-700-hsl) / <alpha-value>)",

            // Secondary
            secondary: "hsl(var(--color-secondary-400-hsl) / <alpha-value>)",
            "secondary-400":
                "hsl(var(--color-secondary-400-hsl) / <alpha-value>)",

            // Neutral light
            "neutral-light-100":
                "hsl(var(--color-neutral-light-100-hsl) / <alpha-value>)",
            "neutral-light-200":
                "hsl(var(--color-neutral-light-200-hsl) / <alpha-value>)",
            "neutral-light-300":
                "hsl(var(--color-neutral-light-300-hsl) / <alpha-value>)",
            "neutral-light-400":
                "hsl(var(--color-neutral-light-400-hsl) / <alpha-value>)",
            "neutral-light-700":
                "hsl(var(--color-neutral-light-700-hsl) / <alpha-value>)",
            "neutral-light-900":
                "hsl(var(--color-neutral-light-900-hsl) / <alpha-value>)",

            // Neutral dark
            "neutral-dark-900":
                "hsl(var(--color-neutral-dark-900-hsl) / <alpha-value>)",

            // Neutral theme
            "neutral-100": "hsl(var(--color-neutral-100-hsl) / <alpha-value>)",
            "neutral-200": "hsl(var(--color-neutral-200-hsl) / <alpha-value>)",
            "neutral-400": "hsl(var(--color-neutral-400-hsl) / <alpha-value>)",
            "neutral-700": "hsl(var(--color-neutral-700-hsl) / <alpha-value>)",
            "neutral-900": "hsl(var(--color-neutral-900-hsl) / <alpha-value>)",

            // Background
            background: "hsl(var(--color-background-400-hsl) / <alpha-value>)",
            "background-400":
                "hsl(var(--color-background-400-hsl) / <alpha-value>)",
            "background-500":
                "hsl(var(--color-background-500-hsl) / <alpha-value>)",

            // Surfaces
            "surface-400": "hsl(var(--color-surface-400-hsl) / <alpha-value>)",
            "surface-700": "hsl(var(--color-surface-700-hsl) / <alpha-value>)",

            // Utility colors
            success: "hsl(var(--color-success-400-hsl) / <alpha-value>)",
            error: "hsl(var(--color-error-400-hsl) / <alpha-value>)",
        },
        // Custom borders
        borderWidth: {
            DEFAULT: "var(--border-width)",
        },
        // Custom border radius
        borderRadius: {
            none: "0",
            sm: "0.125rem",
            DEFAULT: ".5rem",
            lg: "2rem",
            xl: "4rem",
            full: "9999px",
        },
        // Custom font families
        fontFamily: {
            sans: "var(--font-family-paragraph)",
            heading: "var(--font-family-heading)",
            serif: "var(--font-family-serif)",
        },
        // Extended config
        extend: {
            // New heights
            height: {
                "screen-min": "100svh",
            },
            // New box shadows
            boxShadow: {
                DEFAULT: "0 1rem 4rem -1rem hsl(0, 0%, 0%, 0.15)",
                lg: "0 3rem 6rem -2rem hsl(0, 0%, 0%, 0.2)",
            },
            // New font sizes
            fontSize: {
                // Headings
                "main-heading": [
                    "var(--font-size-900)",
                    {
                        fontWeight: "var(--font-weight-bold)",
                        letterSpacing: "0.03em",
                        lineHeight: "1.1",
                    },
                ],
                "section-heading": [
                    "var(--font-size-800)",
                    {
                        fontWeight: "var(--font-weight-semi-bold)",
                        letterSpacing: "0.03em",
                        lineHeight: "1.2",
                    },
                ],
                "component-heading": [
                    "var(--font-size-700)",
                    {
                        fontWeight: "var(--font-weight-semi-bold)",
                        letterSpacing: "0.03em",
                        lineHeight: "1.2",
                    },
                ],

                // Subheadings
                "main-subheading": [
                    "var(--font-size-700)",
                    {
                        fontWeight: "var(--font-weight-semibold)",
                        letterSpacing: "0.05em",
                        lineHeight: "1.2",
                    },
                ],
                "section-subheading": [
                    "var(--font-size-700)",
                    {
                        fontWeight: "var(--font-weight-semibold)",
                        letterSpacing: "0.05em",
                        lineHeight: "1.2",
                    },
                ],
                "component-subheading": [
                    "var(--font-size-700)",
                    {
                        fontWeight: "var(--font-weight-semibold)",
                        letterSpacing: "0.05em",
                        lineHeight: "1.2",
                    },
                ],

                // Paragraphs
                "big-paragraph": [
                    "1.25rem",
                    {
                        lineHeight: "1.75",
                    },
                ],
                paragraph: [
                    "var(--font-size-400)",
                    {
                        lineHeight: "1.75",
                    },
                ],
                "small-paragraph": ["var(--font-size-300)", {}],
            },
        },
    },
};
