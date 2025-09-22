<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light dark">
    <meta name="theme-color" media="(prefers-color-scheme: light)" content="#f9fafb">
    <meta name="theme-color" media="(prefers-color-scheme: dark)" content="#0a0a0a">
    <title>ByteDocs - Configuration Error</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
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
        
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html,
        body {
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .animate-pulse-custom {
            animation: pulse 2s infinite;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-[#0a0a0a] font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-2xl">
            <div class="bg-white/70 dark:bg-black/40 backdrop-blur-xl border border-gray-200/50 dark:border-gray-700/50 rounded-2xl p-8 shadow-2xl">
                
                <div class="flex justify-center mb-6">
                    <div class="w-20 h-20 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center animate-pulse-custom">
                        <svg class="w-12 h-12 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>

                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-red-600 dark:text-red-400 mb-3">{{ $error_title }}</h1>
                    <p class="text-lg text-gray-700 dark:text-gray-300">
                        {{ $error_message }}
                    </p>
                </div>
                
                <div class="mb-8 p-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 rounded-xl">
                    <h3 class="text-lg font-semibold text-red-800 dark:text-red-300 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        How to Fix
                    </h3>
                    <div class="space-y-3">
                        @foreach($error_details as $detail)
                            <div class="flex items-start text-gray-700 dark:text-gray-300">
                                <div class="w-2 h-2 bg-red-500 rounded-full mr-3 mt-2 flex-shrink-0"></div>
                                <span class="text-sm">{{ $detail }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="mb-8 p-6 bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700/50 rounded-xl">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m18 4l4 4-4 4M6 16l-4-4 4-4"></path>
                        </svg>
                        Configuration Example
                    </h3>
                    <div class="bg-gray-900 dark:bg-black rounded-lg p-4 font-mono text-sm overflow-x-auto">
                        <div class="text-gray-400 dark:text-gray-500 mb-2">Add to your .env file:</div>
                        <div class="text-green-400">BYTEDOCS_AUTH_ENABLED=true</div>
                        <div class="text-yellow-400">BYTEDOCS_AUTH_PASSWORD=your_secure_password</div>
                        <div class="text-blue-400">BYTEDOCS_AUTH_SESSION_EXPIRE=1440</div>
                        <div class="text-purple-400">BYTEDOCS_AUTH_IP_BAN_MAX_ATTEMPTS=5</div>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-3">
                        Remember to use a strong password and keep it secure!
                    </p>
                </div>
                
                <div class="text-center space-y-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Or disable authentication completely:
                    </p>
                    <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-3 font-mono text-sm">
                        <span class="text-gray-400">BYTEDOCS_AUTH_ENABLED=</span><span class="text-red-500">false</span>
                    </div>
                </div>

                <div class="mt-8 text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        ByteDocs Configuration Error • Contact Administrator
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>