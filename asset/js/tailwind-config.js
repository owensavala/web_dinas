// Tailwind CSS Configuration - Cream Mocha (Krem & Cokelat) Palette Edition
tailwind.config = {
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                mocha: {
                    latar: '#F6EFE6',      // Latar 60%
                    permukaan: '#E9DCCB',  // Permukaan 30%
                    teks: '#2B1D14',       // Teks espresso
                    aksen: '#A8643A',      // Aksen 10%
                    redup: '#8C7B6B',      // Redup / Muted
                },
                brand: {
                    50: '#FBF8F4',
                    100: '#F6EFE6',
                    200: '#E9DCCB',
                    300: '#D9C5B0',
                    400: '#C2895E',
                    500: '#A8643A',
                    600: '#94542E',
                    700: '#794222',
                    800: '#522B15',
                    900: '#2B1D14',
                    950: '#1A110B',
                },
                gold: {
                    300: '#E2B895',
                    400: '#D49B74',
                    500: '#A8643A',
                    600: '#94542E',
                    700: '#794222',
                },
                slate: {
                    50: '#F6EFE6',   // Latar hangat
                    100: '#EFE6DB',  // Krem muda
                    200: '#E9DCCB',  // Permukaan / surface
                    300: '#D7C6B3',  // Border lembut
                    400: '#A89889',  // Placeholder / ikon halus
                    500: '#8C7B6B',  // Redup / secondary text
                    600: '#6E5F52',  // Body text sekunder
                    700: '#504237',  // Body text utama
                    800: '#38271E',  // Dark accent
                    900: '#2B1D14',  // Teks espresso utama
                    950: '#1A110B',  // Deep dark
                }
            },
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', 'Inter', 'system-ui', 'sans-serif'],
                serif: ['"Playfair Display"', 'Georgia', 'serif'],
            },
            boxShadow: {
                'card': '0 4px 20px -2px rgba(43, 29, 20, 0.05), 0 2px 6px -1px rgba(43, 29, 20, 0.02)',
                'card-hover': '0 20px 30px -10px rgba(168, 100, 58, 0.14), 0 8px 12px -6px rgba(43, 29, 20, 0.04)',
                'glow': '0 0 25px rgba(168, 100, 58, 0.35)',
            }
        }
    }
};
