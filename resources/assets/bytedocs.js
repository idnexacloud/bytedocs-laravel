// ByteDocs Theme Configuration
const themes = {
    green: {
        accent: '#166534',
        accentHover: '#0e4121',
        accentLight: '#d1fae5'
    },
    blue: {
        accent: '#1d4ed8',
        accentHover: '#1e40af',
        accentLight: '#dbeafe'
    },
    purple: {
        accent: '#7c3aed',
        accentHover: '#6d28d9',
        accentLight: '#e9d5ff'
    },
    red: {
        accent: '#dc2626',
        accentHover: '#b91c1c',
        accentLight: '#fecaca'
    },
    orange: {
        accent: '#ea580c',
        accentHover: '#c2410c',
        accentLight: '#fed7aa'
    },
    teal: {
        accent: '#0891b2',
        accentHover: '#0e7490',
        accentLight: '#a7f3d0'
    },
    pink: {
        accent: '#db2777',
        accentHover: '#be185d',
        accentLight: '#fce7f3'
    }
};

let currentTheme = localStorage.getItem('theme-color') || 'green';

// Validate theme exists, fallback to green if invalid
if (!themes[currentTheme]) {
    currentTheme = 'green';
    localStorage.setItem('theme-color', currentTheme);
}

const currentColors = themes[currentTheme];

tailwind.config = {
    theme: {
        extend: {
            colors: {
                'accent': currentColors.accent,
                'accent-hover': currentColors.accentHover,
                'accent-light': currentColors.accentLight,
            },
            fontFamily: {
                'sans': ['Inter', 'sans-serif']
            }
        }
    },
    darkMode: 'class'
}
