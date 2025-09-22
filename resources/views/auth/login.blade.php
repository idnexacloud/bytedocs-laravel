<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light dark">
    <meta name="theme-color" media="(prefers-color-scheme: light)" content="#f9fafb">
    <meta name="theme-color" media="(prefers-color-scheme: dark)" content="#0a0a0a">
    <title>ByteDocs - Authentication Required</title>
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
    </style>
</head>
<body class="bg-gray-50 dark:bg-[#0a0a0a] font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <div class="bg-white/70 dark:bg-black/40 backdrop-blur-xl border border-gray-200/50 dark:border-gray-700/50 rounded-2xl p-8 shadow-2xl">
                
                <div class="flex justify-center mb-6">
                    <div class="w-16 h-16 bg-accent/10 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                </div>

                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">ByteDocs</h1>
                    <p class="text-gray-600 dark:text-gray-400">Authentication Required</p>
                </div>
                
                @if($error)
                    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 rounded-xl">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm text-red-700 dark:text-red-300">{{ $error }}</p>
                        </div>
                    </div>
                @endif
                
                <form method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="w-full px-4 py-3 pr-12 bg-white dark:bg-gray-900/50 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                                placeholder="Enter password to access documentation"
                                required
                                autofocus
                            >
                            <button 
                                type="button"
                                id="togglePassword"
                                class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200"
                                tabindex="-1"
                            >
                                <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg id="eyeOffIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <button 
                        type="submit" 
                        class="w-full bg-accent hover:bg-accent-hover text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 dark:focus:ring-offset-gray-900"
                    >
                        Access Documentation
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Secured by ByteDocs Authentication
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('password').focus();
        
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            const eyeOffIcon = document.getElementById('eyeOffIcon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                passwordField.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        });
        
        const errorDiv = document.querySelector('.bg-red-50');
        if (errorDiv) {
            setTimeout(() => {
                errorDiv.style.opacity = '0';
                errorDiv.style.transition = 'opacity 0.5s';
                setTimeout(() => errorDiv.remove(), 500);
            }, 5000);
        }
        
        document.querySelector('form').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Authenticating...';
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-75');
            
            setTimeout(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-75');
            }, 3000);
        });
    </script>
</body>
</html>