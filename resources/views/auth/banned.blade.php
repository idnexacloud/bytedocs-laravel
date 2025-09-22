<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light dark">
    <meta name="theme-color" media="(prefers-color-scheme: light)" content="#f9fafb">
    <meta name="theme-color" media="(prefers-color-scheme: dark)" content="#0a0a0a">
    <title>ByteDocs - Access Blocked</title>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                </div>

                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-red-600 dark:text-red-400 mb-3">Access Blocked</h1>
                    <p class="text-lg text-gray-700 dark:text-gray-300">
                        Your IP address has been temporarily banned due to multiple failed authentication attempts.
                    </p>
                </div>
                
                <div class="mb-8 p-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 rounded-xl">
                    <h3 class="text-lg font-semibold text-red-800 dark:text-red-300 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Ban Details
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="space-y-3">
                            <div class="flex items-center text-gray-700 dark:text-gray-300">
                                <div class="w-2 h-2 bg-red-500 rounded-full mr-3 flex-shrink-0"></div>
                                <span class="font-medium mr-2">Max attempts exceeded:</span>
                                <span>{{ config('bytedocs.auth.ip_ban.max_attempts', 5) }} failed attempts</span>
                            </div>
                            <div class="flex items-center text-gray-700 dark:text-gray-300">
                                <div class="w-2 h-2 bg-red-500 rounded-full mr-3 flex-shrink-0"></div>
                                <span class="font-medium mr-2">Ban duration:</span>
                                <span>{{ config('bytedocs.auth.ip_ban.ban_duration', 60) }} minutes</span>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center text-gray-700 dark:text-gray-300">
                                <div class="w-2 h-2 bg-red-500 rounded-full mr-3 flex-shrink-0"></div>
                                <span class="font-medium mr-2">Your IP:</span>
                                <span class="font-mono">{{ request()->ip() }}</span>
                            </div>
                            <div class="flex items-center text-gray-700 dark:text-gray-300">
                                <div class="w-2 h-2 bg-red-500 rounded-full mr-3 flex-shrink-0"></div>
                                <span class="font-medium mr-2">Blocked at:</span>
                                <span class="font-mono">{{ now()->format('Y-m-d H:i:s') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-8 p-6 bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700/50 rounded-xl">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Need Access?
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        If you believe this is an error or need immediate access, please contact the system administrator.
                        You can try again after the ban period expires.
                    </p>
                </div>
                
                <div class="text-center">
                    <button 
                        id="retry-btn" 
                        class="px-6 py-3 bg-gray-400 text-white font-semibold rounded-xl cursor-not-allowed opacity-60 transition-all duration-200"
                        disabled
                    >
                        Please wait {{ config('bytedocs.auth.ip_ban.ban_duration', 60) }} minutes
                    </button>
                </div>

                <div class="mt-8 text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Protected by ByteDocs Security System
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const banDuration = {{ config('bytedocs.auth.ip_ban.ban_duration', 60) }};
        setTimeout(() => {
            window.location.reload();
        }, banDuration * 60 * 1000);
        
        let remainingMinutes = banDuration;
        const button = document.getElementById('retry-btn');
        
        const countdownInterval = setInterval(() => {
            remainingMinutes--;
            if (remainingMinutes > 0) {
                button.textContent = `Please wait ${remainingMinutes} minutes`;
            } else {
                button.textContent = 'Refresh Page';
                button.disabled = false;
                button.classList.remove('opacity-60', 'cursor-not-allowed', 'bg-gray-400');
                button.classList.add('bg-accent', 'hover:bg-accent-hover', 'cursor-pointer', 'transform', 'hover:scale-[1.02]');
                button.onclick = () => window.location.reload();
                clearInterval(countdownInterval);
            }
        }, 60000);
    </script>
</body>
</html>