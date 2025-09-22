<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="light dark">
    <meta name="theme-color" media="(prefers-color-scheme: light)" content="#f9fafb">
    <meta name="theme-color" media="(prefers-color-scheme: dark)" content="#0a0a0a">
    <title>{{ $title }} - ByteDocs</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dompurify@3.1.6/dist/purify.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.44.0/min/vs/loader.js"></script>
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
        
        .flex-1,
        .main-content,
        #sidebar,
        #endpointsContainer {
            min-width: 0;
        }
        
        img,
        table,
        pre,
        code,
        .mobile-scroll-table {
            max-width: 100%;
            overflow-wrap: anywhere;
            word-break: break-word;
        }
        
        pre,
        code {
            white-space: pre-wrap;
            white-space: -moz-pre-wrap;
            white-space: -pre-wrap;
            white-space: -o-pre-wrap;
            word-wrap: break-word;
        }
        
        :root {
            color-scheme: light dark;
        }
        
        .dark .json-key {
            color: #9cdcfe; 
            font-weight: 400;
        }
        .dark .json-string {
            color: #ce9178; 
        }
        .dark .json-number {
            color: #b5cea8; 
        }
        .dark .json-boolean {
            color: #569cd6; 
            font-weight: 700;
        }
        .dark .json-null {
            color: #dcdcaa; 
            font-style: italic;
        }
        
        .dark .json-brace {
            color: #ffd700; 
            font-weight: bold;
        }
        .dark .json-bracket {
            color: #af82e2; 
            font-weight: bold;
        }
        .dark .json-comma {
            color: #fff; 
            font-weight: bold;
        }
        .dark .json-colon {
            color: #fff; 
            font-weight: bold;
        }
        
        .json-key {
            color: #d32929; 
        }
        .json-string {
            color: #166534; 
        }
        .json-number {
            color: #000; 
        }
        .json-boolean {
            color: #0000ff; 
        }
        .json-null {
            color: #795e26; 
        }
        
        .json-brace {
            color: #b8860b; 
            font-weight: bold;
        }
        .json-bracket {
            color: #d2691e; 
            font-weight: bold;
        }
        .json-comma {
            color: #000; 
            font-weight: bold;
        }
        .json-colon {
            color: #000; 
            font-weight: bold;
        }
        
        .method-get,
        .method-post,
        .method-put,
        .method-delete,
        .method-patch {
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            border-radius: 8px;
            padding: 0.25rem 0.5rem;
            font-weight: 600;
            box-shadow: 0 6px 18px rgba(2, 6, 23, 0.06);
            background-clip: padding-box;
        }
        .method-get {
            background: linear-gradient(90deg, rgb(158 200 255 / 65%) 0%, rgba(96, 165, 250, 0.55) 100%) !important;
            color: #1e40af !important;
        }
        .method-post {
            background: linear-gradient(90deg, rgb(138 246 175 / 65%) 0%, rgba(52, 211, 153, 0.55) 100%) !important;
            color: #166534 !important;
        }
        .method-put {
            background: linear-gradient(90deg, rgb(251 237 177 / 65%) 0%, rgba(253, 230, 138, 0.55) 100%) !important;
            color: #92400e !important;
        }
        .method-delete {
            background: linear-gradient(90deg, rgb(250 195 195 / 65%) 0%, rgba(248, 113, 113, 0.55) 100%) !important;
            color: #991b1b !important;
        }
        .method-patch {
            background: linear-gradient(90deg, rgba(243, 232, 255, 0.65) 0%, rgba(192, 132, 252, 0.55) 100%) !important;
            color: #6b21a8 !important;
        }
        
        .dark .method-get,
        .dark .method-post,
        .dark .method-put,
        .dark .method-delete,
        .dark .method-patch {
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-radius: 8px;
            padding: 0.25rem 0.5rem;
            font-weight: 600;
            box-shadow: 0 8px 24px rgba(2, 6, 23, 0.5);
            background-clip: padding-box;
            border: 1px solid rgba(255, 255, 255, 0.04) !important;
        }
        .dark .method-get {
            background: linear-gradient(90deg, rgba(30, 58, 138, 0.28) 0%, rgba(37, 99, 235, 0.22) 100%) !important;
            color: #bfdbfe !important;
        }
        .dark .method-post {
            background: linear-gradient(90deg, rgba(20, 83, 45, 0.28) 0%, rgba(22, 101, 52, 0.22) 100%) !important;
            color: #bbf7d0 !important;
        }
        .dark .method-put {
            background: linear-gradient(90deg, rgba(120, 53, 15, 0.28) 0%, rgba(202, 138, 4, 0.22) 100%) !important;
            color: #fde68a !important;
        }
        .dark .method-delete {
            background: linear-gradient(90deg, rgba(127, 29, 29, 0.28) 0%, rgba(220, 38, 38, 0.22) 100%) !important;
            color: #fecaca !important;
        }
        .dark .method-patch {
            background: linear-gradient(90deg, rgba(88, 28, 135, 0.28) 0%, rgba(162, 28, 175, 0.22) 100%) !important;
            color: #e9d5ff !important;
        }
        
        .toggle-active {
            @apply bg-accent;
        }
        
        .mode-toggle-btn {
            color: #6b7280;
            background: transparent;
        }
        .mode-toggle-btn.active {
            background: white;
            color: #111827;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }
        .dark .mode-toggle-btn {
            color: #9ca3af;
        }
        .dark .mode-toggle-btn.active {
            background: #0a0a0a;
            color: #f9fafb;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }
        .toggle-active .toggle-slider {
            @apply transform translate-x-5;
        }
        
        .base-url-badge {
            display: inline-flex !important;
            align-items: center !important;
            background-color: var(--accent-color, #166534) !important;
            color: white !important;
            padding: 0px 8px !important;
            border-radius: 4px !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
        }
        
        .dark-accent-bg {
            background-color: var(--accent-dark-bg, rgba(22, 101, 52, 0.2)) !important;
        }

        @media (max-width: 768px) {
            
            #sidebar {
                position: fixed !important;
                left: -100% !important;
                top: 0 !important;
                height: 100vh !important;
                width: min(80vw, 320px) !important;
                z-index: 60 !important;
                transition: left 0.3s ease !important;
                border-right: 1px solid rgba(229, 231, 235, 0.5) !important;
                box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1) !important;
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
            }
            #sidebar.mobile-open {
                left: 0 !important;
            }
            
            .main-content {
                width: 100% !important;
                margin-left: 0 !important;
                min-width: 0;
                overflow-x: hidden;
            }
            
            .sidebar-overlay {
                position: fixed !important;
                inset: 0 !important;
                background: rgba(0, 0, 0, 0.5) !important;
                z-index: 55 !important;
                display: none !important;
            }
            .sidebar-overlay.active {
                display: block !important;
            }
            
            .mobile-menu-btn {
                display: flex !important;
            }
            
            .glassmorphism-header,
            .glassmorphism-footer {
                padding: 1rem !important;
                width: 100%;
                left: 0;
                box-sizing: border-box;
            }
            
            .mobile-scroll-table {
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch !important;
                max-width: 100% !important;
                width: 100% !important;
                border-radius: 0.5rem !important;
            }
            .mobile-scroll-table table {
                min-width: 600px !important;
                width: max-content !important;
                table-layout: fixed !important;
                border-collapse: separate !important;
                border-spacing: 0 !important;
            }
            .mobile-scroll-table th,
            .mobile-scroll-table td {
                min-width: 120px !important;
                white-space: nowrap !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
                padding: 0.75rem 0.5rem !important;
            }
            .mobile-scroll-table th:last-child,
            .mobile-scroll-table td:last-child {
                min-width: 200px !important;
                white-space: normal !important;
            }
            
            .tab {
                padding: 0.5rem 0.75rem !important;
                font-size: 0.875rem !important;
                white-space: nowrap !important;
                flex: 1 1 auto !important;
                text-align: center !important;
                min-width: 0 !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
            }
            
            .tab-container {
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch !important;
                scrollbar-width: none !important;
                -ms-overflow-style: none !important;
            }
            .tab-container::-webkit-scrollbar {
                display: none !important;
            }
            
            #endpointHeader {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 0.75rem !important;
                width: 100%;
            }
            
            .p-6 {
                padding: 1rem !important;
            }
            
            .mobile-scroll-table+p,
            pre,
            code {
                word-break: break-word !important;
                overflow-wrap: break-word !important;
            }
        }
        
        @media (min-width: 769px) and (max-width: 1023px) {
            #sidebar {
                min-width: 250px !important;
                max-width: 300px !important;
            }
            .mobile-menu-btn {
                display: none !important;
            }
        }
        
        @media (min-width: 1024px) {
            #sidebar {
                min-width: 280px;
                max-width: 400px;
            }
            .mobile-menu-btn {
                display: none !important;
            }
        }
        
        .mobile-menu-btn {
            display: none;
        }
        
        .glassmorphism-header {
            background: rgba(255, 255, 255, 0.4) !important;
            backdrop-filter: blur(18px) saturate(180%) !important;
            -webkit-backdrop-filter: blur(18px) saturate(180%) !important;
            border-bottom: 1px solid rgba(229, 231, 235, 0.4) !important;
            position: sticky !important;
            top: 0 !important;
            z-index: 40 !important;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1) !important;
        }
        .glassmorphism-footer {
            background: rgba(255, 255, 255, 0.4) !important;
            backdrop-filter: blur(18px) saturate(180%) !important;
            -webkit-backdrop-filter: blur(18px) saturate(180%) !important;
            border-bottom: 1px solid rgba(229, 231, 235, 0.4) !important;
            position: sticky !important;
            bottom: 0 !important;
            z-index: 40 !important;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1) !important;
        }
        
        .dark .glassmorphism-header,
        .dark .glassmorphism-footer {
            background: rgba(10, 10, 10, 0.4) !important;
            border-bottom: 1px solid rgba(44, 45, 45, 0.4) !important;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3) !important;
        }

        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.3));
            border-radius: 10px;
            border: 2px solid transparent;
            background-clip: padding-box;
            transition: all 0.3s ease;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.5));
            border-radius: 10px;
            border: 1px solid transparent;
            background-clip: padding-box;
        }
        ::-webkit-scrollbar-corner {
            background: transparent;
        }
        
        .dark ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
        }
        .dark ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0.25));
            border-radius: 10px;
            border: 2px solid transparent;
            background-clip: padding-box;
        }
        .dark ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0.4));
            border-radius: 10px;
            border: 1px solid transparent;
            background-clip: padding-box;
        }
        
        * {
            scrollbar-width: thin;
            scrollbar-color: rgba(0, 0, 0, 0.2) rgba(0, 0, 0, 0.05);
        }
        .dark * {
            scrollbar-color: rgba(255, 255, 255, 0.2) rgba(255, 255, 255, 0.05);
        }
        
        #sidebar::-webkit-scrollbar,
        .main-content::-webkit-scrollbar,
        .mobile-scroll-table::-webkit-scrollbar {
            width: 6px;
        }
        #sidebar::-webkit-scrollbar-thumb,
        .main-content::-webkit-scrollbar-thumb,
        .mobile-scroll-table::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, rgba(22, 101, 52, 0.3), rgba(22, 101, 52, 0.5));
            border-radius: 8px;
            border: 1px solid transparent;
            background-clip: padding-box;
        }
        #sidebar::-webkit-scrollbar-thumb:hover,
        .main-content::-webkit-scrollbar-thumb:hover,
        .mobile-scroll-table::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, rgba(22, 101, 52, 0.5), rgba(22, 101, 52, 0.7));
        }
        .dark #sidebar::-webkit-scrollbar-thumb,
        .dark .main-content::-webkit-scrollbar-thumb,
        .dark .mobile-scroll-table::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, rgba(34, 197, 94, 0.3), rgba(34, 197, 94, 0.5));
        }
        .dark #sidebar::-webkit-scrollbar-thumb:hover,
        .dark .main-content::-webkit-scrollbar-thumb:hover,
        .dark .mobile-scroll-table::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, rgba(34, 197, 94, 0.5), rgba(34, 197, 94, 0.7));
        }
        
        @supports not (backdrop-filter: blur(24px)) {
            .glassmorphism-header,
            .glassmorphism-footer {
                background: rgba(255, 255, 255, 0.95) !important;
            }
            .dark .glassmorphism-header,
            .glassmorphism-footer {
                background: rgba(10, 10, 10, 0.95) !important;
            }
        }
        
        .chat-md table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-block: 5px;
        }
        .chat-md thead tr {
            background: #f9fafb;
        }
        .dark .chat-md thead tr {
            background: #2c2d2d;
        }
        .chat-md th,
        .chat-md td {
            border: 1px solid #e5e7eb;
            padding: 0.3rem 0.75rem;
            text-align: left;
        }
        .dark .chat-md th,
        .dark .chat-md td {
            border-color: #2c2d2d;
        }
        .chat-md th {
            font-weight: 600;
            color: #111827;
        }
        .dark .chat-md th {
            color: #fff;
        }
        .chat-md tbody tr {
            background: #f9fafb;
        }
        .dark .chat-md tbody tr {
            background: #2c2d2d;
        }
        .chat-message {
            animation: slideInChat 0.3s ease-out;
        }
        .chat-message.user {
            margin-left: auto;
            margin-right: 0;
        }
        .chat-message.ai {
            margin-left: 0;
            margin-right: auto;
        }
        .chat-typing {
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 8px 12px;
        }
        .chat-typing-dot {
            width: 4px;
            height: 4px;
            background: #6b7280;
            border-radius: 50%;
            animation: chatTyping 1.4s infinite ease-in-out both;
        }
        .chat-typing-dot:nth-child(1) {
            animation-delay: -0.32s;
        }
        .chat-typing-dot:nth-child(2) {
            animation-delay: -0.16s;
        }
        .chat-typing-dot:nth-child(3) {
            animation-delay: 0s;
        }
        @keyframes slideInChat {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes chatTyping {
            0%,
            80%,
            100% {
                transform: scale(0.8);
                opacity: 0.5;
            }
            40% {
                transform: scale(1);
                opacity: 1;
            }
        }
        
        @media (max-width: 1024px) {
            #chatSidebar {
                position: fixed;
                right: 0;
                top: 0;
                height: 100vh;
                z-index: 40;
                box-shadow: -4px 0 12px rgba(0, 0, 0, 0.15);
            }
            #chatSidebar.hidden {
                transform: translateX(100%);
            }
            #chatSidebar:not(.hidden) {
                transform: translateX(0);
            }
        }
        
        #chatMessages::-webkit-scrollbar {
            width: 4px;
        }
        #chatMessages::-webkit-scrollbar-track {
            background: transparent;
        }
        #chatMessages::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 2px;
        }
        .dark #chatMessages::-webkit-scrollbar-thumb {
            background: #4b5563;
        }
        
        #chatSidebar textarea#chatInput {
            line-height: 1.25rem;
            padding-top: 0.9rem;
            padding-bottom: 0.45rem;
            
            white-space: pre-wrap;
            overflow-wrap: anywhere;
            -webkit-font-smoothing: antialiased;
            overflow: hidden; 
            transition: height 120ms ease, border-radius 140ms ease; 
            -webkit-transition: height 120ms ease, border-radius 140ms ease;
        }
        
        #chatSidebar button#sendChatMessage {
            box-shadow: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            top: 50%;
            transform: translateY(-50%);
        }
        #chatSidebar button#sendChatMessage svg { height: 14px; width: 14px; }
        
        #chatMessages {
            padding-bottom: 140px; 
            scroll-behavior: smooth;
        }
        
        .chat-bubble {
            max-width: calc(100% - 64px);
            overflow-wrap: anywhere;
            word-break: break-word;
            
        }
        
        .chat-bubble.user {
                color: white;
            }
            
            [data-endpoint-id].endpoint-active {
                border-left-width: 3px !important;
                border-left-style: solid !important;
                border-left-color: var(--accent-color, #166534) !important;
                background-color: var(--accent-light-color, rgba(22,101,52,0.06)) !important;
                color: inherit !important;
            }
            
            [data-endpoint-id].endpoint-active .method-get,
            [data-endpoint-id].endpoint-active .method-post,
            [data-endpoint-id].endpoint-active .method-put,
            [data-endpoint-id].endpoint-active .method-delete,
            [data-endpoint-id].endpoint-active .method-patch {
                box-shadow: 0 6px 18px rgba(2,6,23,0.06);
                transform: translateX(-2px);
            }
            
            .dark [data-endpoint-id].endpoint-active {
                background-color: rgba(255,255,255,0.02) !important;
                border-left-color: var(--accent-color, #166534) !important;
            }
            .dark [data-endpoint-id].endpoint-active .method-get,
            .dark [data-endpoint-id].endpoint-active .method-post,
            .dark [data-endpoint-id].endpoint-active .method-put,
            .dark [data-endpoint-id].endpoint-active .method-delete,
            .dark [data-endpoint-id].endpoint-active .method-patch {
                filter: brightness(0.95) saturate(1.05);
            }
        }
    </style>
</head>
<body class="bg-white dark:bg-black">
    <div class="flex h-screen">
        
        <div class="w-80 bg-gray-50 border-r border-gray-200 dark:bg-[#0a0a0a] dark:border-[#2c2d2d] flex flex-col transition-all duration-300 relative"
            id="sidebar" style="min-width: 280px; max-width: 500px;">
            
            <div class="absolute right-0 top-0 w-1 h-full cursor-col-resize bg-transparent hover:bg-accent transition-colors duration-200 z-10"
                id="leftResizeHandle"></div>
            <div class="p-6">
                <h1 class="text-2xl font-bold text-black dark:text-white mb-2">{{ $title }}</h1>
                <p class="text-gray-600 dark:text-gray-300 text-sm">@if($config && isset($config['description'])){{ $config['description'] }}@else Modern API Documentation @endif</p>
            </div>
            <div class="p-4 border-b border-gray-200 dark:border-[#2c2d2d]">
                <div class="relative">
                    <input type="text"
                        class="w-full px-4 py-2 pr-10 border border-gray-300 dark:border-[#212121] rounded-lg bg-white dark:bg-black text-gray-900 dark:text-white text-sm transition-colors duration-200 focus:outline-none focus:ring-3 focus:ring-accent-light focus:border-accent"
                        id="searchInput" placeholder="Search endpoints...">
                    <button
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 p-1 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-600 rounded hidden"
                        id="searchClear">×</button>
                </div>
            </div>
            <div class="px-4 py-2 text-xs text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-[#2c2d2d] hidden"
                id="searchResults">
                Found <span id="searchCount">0</span> endpoints
            </div>
            <div class="flex-1 overflow-y-auto py-4" id="endpointsContainer">
                
            </div>
            
            <div class="border-t border-gray-200 dark:border-[#2c2d2d] p-4 bg-gray-50 dark:bg-[#0a0a0a]">
                <div class="flex items-center gap-3">
                    <div class="flex flex-1 rounded-lg bg-gray-200 dark:bg-[#171717] p-1">
                        <button 
                            id="docsMode" 
                            class="flex-1 py-2 px-4 text-sm font-medium rounded-md transition-all duration-200 mode-toggle-btn active"
                            data-mode="docs">
                            <span class="flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                                </svg>
                                Docs
                            </span>
                        </button>
                        <button 
                            id="scenarioMode" 
                            class="flex-1 py-2 px-4 text-sm font-medium rounded-md transition-all duration-200 mode-toggle-btn"
                            data-mode="scenario">
                            <span class="flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Scenario
                            </span>
                        </button>
                    </div>

                    <button 
                        id="settingsBtnSidebar"
                        class="p-2 rounded-lg bg-gray-200 dark:bg-[#171717] text-gray-600 dark:text-gray-400 hover:bg-gray-300 dark:hover:bg-[#2c2d2d] hover:text-gray-800 dark:hover:text-gray-200 transition-all duration-200"
                        title="Settings">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        
        <div class="flex-1 flex flex-col overflow-hidden bg-white dark:bg-[#0a0a0a] main-content">
            <div class="flex-1 overflow-y-auto flex flex-col">
                
                <div id="docsContent">
                    <div class="glassmorphism-header p-6">
                        
                    <div class="block md:hidden">
                        
                        <div class="flex items-center justify-between mb-3">
                            <button
                                class="mobile-menu-btn p-2 rounded-md hover:bg-gray-100 dark:hover:bg-green-800 transition-colors duration-200"
                                id="mobileMenuBtn">
                                <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                            <div class="flex-1 text-center">
                                <h1 class="text-lg font-bold text-gray-900 dark:text-white">{{ $title }}</h1>
                                <p class="text-xs text-gray-600 dark:text-gray-400">API Documentation</p>
                            </div>
                            <div class="flex gap-2">
                                <button
                                    class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-green-800 transition-colors duration-200"
                                    id="exportJsonBtnMobile" title="Export OpenAPI JSON">
                                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </button>
                                <button
                                    class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-green-800 transition-colors duration-200"
                                    id="settingsBtn">
                                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="flex gap-2 mb-4">
                            <select
                                class="flex-1 px-3 py-2 border border-gray-300 dark:border-[#212121] rounded-md bg-white dark:bg-black text-gray-900 dark:text-white text-sm"
                                id="baseUrlSelect">
                                
                            </select>
                            <button
                                class="px-4 py-2 bg-accent text-white rounded-md text-sm hover:bg-accent-hover transition-colors duration-200"
                                id="authBtn">Auth</button>
                        </div>
                    </div>
                    
                    <div class="hidden md:flex justify-between items-center mb-4">
                        <div class="flex items-center gap-3">
                            <select
                                class="px-3 py-2 border border-gray-300 dark:border-[#212121] rounded-md bg-white dark:bg-black text-gray-900 dark:text-white text-sm min-w-50"
                                id="baseUrlSelectDesktop">
                                
                            </select>
                        </div>
                        <div class="flex gap-3">
                            <button
                                class="px-4 py-1 border border-gray-300 dark:border-[#212121] rounded-md bg-white dark:bg-black text-gray-900 dark:text-white text-sm hover:bg-gray-50 dark:hover:bg-white dark:hover:text-black transition-colors duration-200 flex items-center gap-2"
                                id="exportJsonBtn" title="Export OpenAPI JSON">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Export JSON
                            </button>
                            <button
                                class="px-4 py-1 bg-accent text-white rounded-md text-sm hover:bg-accent-hover transition-colors duration-200"
                                id="authBtnDesktop">Authentication</button>
                            <button
                                class="px-4 py-1 bg-accent text-white rounded-md text-sm hover:bg-accent-hover transition-colors duration-200 flex items-center gap-2"
                                id="chatAIToggle">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="flex items-center gap-4" id="endpointHeader">
                        <span
                            class="inline-block px-2 py-1 rounded text-xs font-semibold text-center min-w-16 bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100"
                            id="currentMethod">METHOD</span>
                        <div class="flex-1 font-mono text-sm text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-black border dark:border-[#212121] px-3 py-2 rounded-md flex items-center gap-2"
                            id="currentUrl">Select an endpoint</div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="border-b border-gray-200 dark:border-[#2c2d2d] mb-6">
                        <div class="flex tab-container">
                            <div class="tab px-6 py-3 cursor-pointer border-b-2 border-accent text-accent font-medium transition-all duration-200"
                                data-tab="overview">Overview</div>
                            <div class="tab px-6 py-3 cursor-pointer border-b-2 border-transparent text-gray-600 dark:text-gray-300 hover:text-accent hover:border-accent transition-all duration-200"
                                data-tab="parameters">Parameters</div>
                            <div class="tab px-6 py-3 cursor-pointer border-b-2 border-transparent text-gray-600 dark:text-gray-300 hover:text-accent hover:border-accent transition-all duration-200 hidden"
                                data-tab="body" id="bodyTab">Body</div>
                            <div class="tab px-6 py-3 cursor-pointer border-b-2 border-transparent text-gray-600 dark:text-gray-300 hover:text-accent hover:border-accent transition-all duration-200"
                                data-tab="responses">Responses</div>
                            <div class="tab px-6 py-3 cursor-pointer border-b-2 border-transparent text-gray-600 dark:text-gray-300 hover:text-accent hover:border-accent transition-all duration-200"
                                data-tab="test">Test</div>
                        </div>
                    </div>
                    <div class="block" id="overview">
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Description</h3>
                            <p class="text-gray-600 dark:text-gray-300" id="endpointDescription">Select an endpoint to
                                view its documentation.</p>
                        </div>
                    </div>
                    <div class="hidden" id="parameters">
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Parameters</h3>
                            <div id="parametersContent">
                                <p class="text-gray-600 dark:text-gray-300">No parameters available.</p>
                            </div>
                        </div>
                    </div>
                    <div class="hidden" id="body">
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Request Body</h3>
                            <div id="bodyContent">
                                <p class="text-gray-600 dark:text-gray-300">No request body required.</p>
                            </div>
                        </div>
                    </div>
                    <div class="hidden" id="responses">
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Response Examples</h3>
                            <div id="responsesContent">
                                <p class="text-gray-600 dark:text-gray-300">No response examples available.</p>
                            </div>
                        </div>
                    </div>
                    <div class="hidden" id="test">
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Test Endpoint</h3>
                            <div
                                class="bg-gray-50 dark:bg-[#171717] border border-gray-200 dark:border-[#171717] rounded-lg p-4">
                                
                                <div id="testParametersForm" class="hidden mb-6">
                                    <h4 class="text-md font-semibold mb-3 text-gray-900 dark:text-white">Parameters</h4>
                                    <div id="testParametersInputs" class="space-y-3 mb-4">
                                        
                                    </div>
                                </div>
                                
                                <div id="testBodyForm" class="hidden mb-6">
                                    <h4 class="text-md font-semibold mb-3 text-gray-900 dark:text-white">Request Body
                                    </h4>
                                    <div id="testBodyInput"
                                        class="w-full border border-gray-300 dark:border-[#212121] rounded-md"
                                        style="height: 200px;"></div>
                                </div>
                                <button
                                    class="bg-accent hover:bg-accent-hover text-white font-semibold px-6 py-3 rounded-md text-sm transition-colors duration-200 mb-4"
                                    id="testButton">Send Request</button>
                                <div class="hidden" id="responseContainer">
                                    <div class="flex justify-between items-center mb-2">
                                        <span
                                            class="inline-block px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100"
                                            id="responseStatus">200</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400"
                                            id="responseTime">245ms</span>
                                    </div>
                                    <div class="bg-gray-100 dark:bg-[#212121] border border-gray-200 dark:border-[#2c2d2d] rounded-lg font-mono text-sm overflow-x-auto"
                                        id="responseBody">
                                        Response will appear here...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div> 
                
                <div id="scenarioContent" class="hidden p-6">
                    <div class="w-full mx-auto">
                        
                        <div class="mb-6">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">API Scenarios</h1>
                                    <p class="text-gray-600 dark:text-gray-300 mt-1">Create and manage collections of API requests for comprehensive testing</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    
                                    <button class="bg-purple-100 hover:bg-purple-200 dark:bg-purple-900 dark:hover:bg-purple-800 text-purple-700 dark:text-purple-300 font-medium px-4 py-2 rounded-lg transition-colors duration-200 flex items-center gap-2" onclick="exportAllScenarios()" title="Export all scenarios">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                        Export All
                                    </button>
                                    
                                    <button class="bg-gray-100 hover:bg-gray-200 dark:bg-[#2c2d2d] dark:hover:bg-[#3c3d3d] text-gray-700 dark:text-gray-300 font-medium px-4 py-2 rounded-lg transition-colors duration-200 flex items-center gap-2" onclick="openImportModal()">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                                        </svg>
                                        Import JSON
                                    </button>
                                    
                                    <button class="bg-accent hover:bg-accent-hover text-white font-semibold px-4 py-2 rounded-lg transition-colors duration-200 flex items-center gap-2" id="createScenarioBtn">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        New Scenario
                                    </button>
                                </div>
                            </div>
                            
                            <div class="max-w-md">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                    <input type="text" id="scenarioSearchInput" class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-[#2c2d2d] rounded-lg bg-white dark:bg-black text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-accent focus:border-accent text-sm" placeholder="Search scenarios by name or description..." onkeyup="searchScenarios(this.value)">
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="scenariosGrid">
                            
                            <div class="bg-white dark:bg-[#171717] border-2 border-dashed border-gray-300 dark:border-[#2c2d2d] rounded-lg p-6 hover:border-accent transition-all duration-200 cursor-pointer flex flex-col items-center justify-center min-h-[200px]" id="addScenarioCard">
                                <div class="w-12 h-12 bg-gray-100 dark:bg-[#2c2d2d] rounded-lg flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </div>
                                <h3 class="font-medium text-gray-600 dark:text-gray-300 mb-1">Create New Scenario</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 text-center">Build a sequence of API requests for comprehensive testing</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex-grow"></div>
                <footer class="mt-auto py-3 glassmorphism-footer text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Made with ❤️ by <span class="font-medium text-gray-600 dark:text-gray-300">Bytedocs</span>
                    </p>
                </footer>
            </div>
        </div>
        
        <div id="scenarioModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
            <div class="bg-white dark:bg-[#171717] rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-auto">
                
                <div class="p-6 border-b border-gray-200 dark:border-[#2c2d2d] flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white" id="scenarioModalTitle">Create New Scenario</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Build a sequence of API requests for testing workflows</p>
                    </div>
                    <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200" id="closeScenarioModal">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <div class="flex h-[calc(90vh-160px)]">
                    
                    <div class="w-1/3 border-r border-gray-200 dark:border-[#2c2d2d] flex flex-col">
                        
                        <div class="flex border-b border-gray-200 dark:border-[#2c2d2d]">
                            <button id="informationTab" class="flex-1 px-4 py-3 text-sm font-medium text-center border-b-2 border-accent text-accent bg-accent/5" onclick="switchScenarioTab('information')">
                                <div class="flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Information
                                </div>
                            </button>
                            <button id="endpointsTab" class="flex-1 px-4 py-3 text-sm font-medium text-center border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300" onclick="switchScenarioTab('endpoints')">
                                <div class="flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    Endpoints
                                </div>
                            </button>
                        </div>
                        
                        <div class="flex-1 overflow-y-auto">
                            
                            <div id="informationTabContent" class="p-6 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Scenario Name</label>
                                    <input type="text" id="scenarioName" class="w-full px-3 py-2 border border-gray-300 dark:border-[#2c2d2d] rounded-md bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-accent" placeholder="Enter scenario name">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                                    <textarea id="scenarioDescription" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-[#2c2d2d] rounded-md bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-accent resize-none" placeholder="Describe what this scenario tests"></textarea>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Execution Mode</label>
                                    <div class="flex gap-3">
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="executionMode" value="waterfall" class="mr-2 text-accent focus:ring-accent" id="waterfallMode" checked>
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Sequential</span>
                                        </label>
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="executionMode" value="parallel" class="mr-2 text-accent focus:ring-accent" id="parallelMode">
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Parallel</span>
                                        </label>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Authentication</label>
                                    <div class="bg-gray-50 dark:bg-[#2c2d2d] border border-gray-200 dark:border-[#171717] rounded-lg p-3">
                                        <select id="scenarioAuthType" class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-[#171717] rounded bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-accent mb-2">
                                            <option value="none">No Authentication</option>
                                            <option value="bearer">Bearer Token</option>
                                            <option value="basic">Basic Auth</option>
                                            <option value="apikey">API Key</option>
                                        </select>
                                        <div id="scenarioAuthInputs" class="space-y-2">
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="endpointsTabContent" class="p-6 hidden">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Available Endpoints</label>
                                        <div class="mb-3">
                                            <input type="text" id="endpointSearch" class="w-full px-3 py-2 border border-gray-300 dark:border-[#2c2d2d] rounded bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-accent" placeholder="Search endpoints...">
                                        </div>
                                        <div class="border border-gray-300 dark:border-[#2c2d2d] rounded-md bg-white dark:bg-black max-h-[calc(100vh-400px)] overflow-y-auto">
                                            <div id="availableEndpoints" class="p-3 space-y-2">
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex-1 flex flex-col">
                        <div class="p-6 border-b border-gray-200 dark:border-[#2c2d2d]">
                            <div class="flex items-center justify-between">
                                <h3 class="font-medium text-gray-900 dark:text-white">Request Sequence</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    <button class="text-accent hover:text-accent-hover underline" onclick="switchScenarioTab('endpoints')">
                                        Switch to Endpoints tab
                                    </button> 
                                    to add requests to this scenario
                                </p>
                            </div>
                        </div>
                        <div class="flex-1 p-6">
                            <div id="scenarioRequests" class="space-y-3 min-h-[200px] border-2 border-dashed border-gray-300 dark:border-[#2c2d2d] rounded-lg p-4">
                                <div class="text-center text-gray-500 dark:text-gray-400 py-8" id="emptyScenarioMessage">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    <p>Click on endpoints from the left panel to build your scenario</p>
                                    <p class="text-xs mt-1">Requests will be executed in the order you add them</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 border-t border-gray-200 dark:border-[#2c2d2d] flex items-center justify-between">
                    <button class="px-4 py-2 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-200 border border-red-300 dark:border-red-600 rounded-md transition-colors duration-200" id="leftScenarioButton">Reset Form</button>
                    <div class="flex gap-3">
                        <button class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors duration-200" id="cancelScenario">Cancel</button>
                        <button class="bg-accent hover:bg-accent-hover text-white px-6 py-2 rounded-md font-medium transition-colors duration-200" id="saveScenario">Save Scenario</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="endpointConfigModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
            <div class="bg-white dark:bg-[#171717] rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-hidden">
                
                <div class="p-6 border-b border-gray-200 dark:border-[#2c2d2d] flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white" id="configModalTitle">Configure Request</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1" id="configModalSubtitle">Customize request parameters, headers, and body</p>
                    </div>
                    <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200" id="closeConfigModal">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <div class="p-6 overflow-y-auto max-h-[calc(90vh-180px)]">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        
                        <div class="space-y-6">
                            
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Basic Settings</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Enabled</label>
                                        <label class="flex items-center cursor-pointer">
                                            <input type="checkbox" id="configEnabled" class="mr-2 rounded text-accent focus:ring-accent" checked>
                                            <span class="text-sm text-gray-700 dark:text-gray-300">Execute this request</span>
                                        </label>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Timeout (milliseconds)</label>
                                        <input type="number" id="configTimeout" value="30000" min="1000" max="300000" class="w-full px-3 py-2 border border-gray-300 dark:border-[#2c2d2d] rounded-md bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-accent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Retry Count</label>
                                        <input type="number" id="configRetries" value="0" min="0" max="5" class="w-full px-3 py-2 border border-gray-300 dark:border-[#2c2d2d] rounded-md bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-accent">
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Request Parameters</h3>
                                <div id="configParameters" class="space-y-2">
                                    
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-6">
                            
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Custom Headers</h3>
                                <div id="configHeaders" class="space-y-2">
                                    <div class="flex gap-2 header-row">
                                        <input type="text" placeholder="Header name" class="flex-1 px-2 py-1 text-sm border border-gray-300 dark:border-[#2c2d2d] rounded bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-accent">
                                        <input type="text" placeholder="Header value" class="flex-1 px-2 py-1 text-sm border border-gray-300 dark:border-[#2c2d2d] rounded bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-accent">
                                        <button class="text-red-500 hover:text-red-700 px-2" onclick="this.parentElement.remove()">×</button>
                                    </div>
                                </div>
                                <button class="text-accent text-sm hover:text-accent-hover mt-2" id="addHeader">+ Add Header</button>
                            </div>
                            
                            <div id="configBodySection" class="hidden">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Request Body</h3>
                                <div class="mb-2">
                                    <label class="flex items-center cursor-pointer mb-2">
                                        <input type="checkbox" id="useExampleBody" class="mr-2 rounded text-accent focus:ring-accent" checked>
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Use example from API docs</span>
                                    </label>
                                </div>
                                <div id="configBodyEditor" class="w-full h-48 border border-gray-300 dark:border-[#2c2d2d] rounded-md bg-white dark:bg-black"></div>
                                <div class="mt-2 flex gap-2">
                                    <button class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-2 py-1 rounded hover:bg-gray-200 dark:hover:bg-gray-600" id="formatBody">Format JSON</button>
                                    <button class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-2 py-1 rounded hover:bg-gray-200 dark:hover:bg-gray-600" id="loadExampleBody">Load Example</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 border-t border-gray-200 dark:border-[#2c2d2d] flex items-center justify-end gap-3">
                    <button class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors duration-200" id="cancelConfig">Cancel</button>
                    <button class="bg-accent hover:bg-accent-hover text-white px-6 py-2 rounded-md font-medium transition-colors duration-200" id="saveConfig">Save Configuration</button>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-50 border-l border-gray-200 dark:bg-[#0a0a0a] dark:border-[#2c2d2d] flex flex-col transition-all duration-300 hidden relative"
            id="chatSidebar" style="width: 320px; min-width: 280px; max-width: 600px;">
            
            <div class="absolute left-0 top-0 w-1 h-full cursor-col-resize bg-transparent hover:bg-accent transition-colors duration-200 z-10"
                id="resizeHandle"></div>
            
            <div class="p-4 border-b border-gray-200 dark:border-[#2c2d2d] flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-accent rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">AI Assistant</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Ask about this API</p>
                    </div>
                </div>
                <button class="p-1 rounded hover:bg-gray-200 dark:hover:bg-[#212121] transition-colors"
                    id="closeChatSidebar">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            
            <div class="flex-1 overflow-y-auto p-4 space-y-4" id="chatMessages">
                <div class="flex items-start gap-3">
                    <div class="w-6 h-6 bg-accent rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div
                        class="bg-white dark:bg-[#171717] rounded-lg p-3 text-sm text-gray-900 dark:text-white max-w-xs">
                        <p>Hi! I'm your AI assistant. I can help you understand this API, generate code examples,
                            explain endpoints, and answer questions about the documentation.</p>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Try asking: "How do I authenticate?" or
                            "Show me a POST example"</p>
                    </div>
                </div>
            </div>
            
            <div class="p-4">
                <div class="relative">
                    <label for="chatInput" class="sr-only">Ask me anything about this API</label>
                    <textarea id="chatInput" rows="1" placeholder="Ketik pertanyaanmu..."
                        class="w-full resize-none pr-14 pl-4 py-2 bg-white dark:bg-[#212121] border border-gray-200 dark:border-[#2c2d2d] text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-accent transition-all rounded-xl"
                        style="height:50px; max-height:100px;"></textarea>
                    <button id="sendChatMessage" type="button" aria-label="Send" title="Send"
                        class="absolute right-2 top-[43%] transform -translate-y-1/2 w-9 h-9 p-1.5 bg-accent hover:bg-accent-hover text-white rounded-full shadow focus:outline-none">
                        
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div id="importModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-[#171717] rounded-lg shadow-xl max-w-2xl w-full mx-4">
            
            <div class="p-6 border-b border-gray-200 dark:border-[#2c2d2d] flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Import Scenarios</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Drag and drop JSON files or click to select</p>
                </div>
                <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200" onclick="closeImportModal()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <div class="p-6">
                
                <div id="dropZone" class="border-2 border-dashed border-gray-300 dark:border-[#2c2d2d] rounded-lg p-8 text-center hover:border-accent transition-colors duration-200 cursor-pointer">
                    <div class="flex flex-col items-center">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-[#2c2d2d] rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Drop your JSON files here</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">or click to browse files</p>
                        <button class="bg-accent hover:bg-accent-hover text-white px-4 py-2 rounded-lg transition-colors duration-200 text-sm font-medium">
                            Select Files
                        </button>
                    </div>
                </div>
                
                <input type="file" id="importModalFileInput" accept=".json" multiple class="hidden">
                
                <div id="importProgress" class="mt-4 hidden">
                    <div class="bg-gray-100 dark:bg-[#2c2d2d] rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 dark:text-white mb-2">Import Progress</h4>
                        <div id="importProgressList" class="space-y-2 text-sm"></div>
                    </div>
                </div>
            </div>
            
            <div class="p-6 border-t border-gray-200 dark:border-[#2c2d2d] flex justify-end gap-3">
                <button class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#2c2d2d] rounded-lg transition-colors duration-200" onclick="closeImportModal()">
                    Cancel
                </button>
            </div>
        </div>
    </div>
    
    <div id="scenarioDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-[#171717] rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-hidden">
            
            <div class="p-6 border-b border-gray-200 dark:border-[#2c2d2d] flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div id="detailsIcon" class="w-10 h-10 bg-gradient-to-br rounded-lg flex items-center justify-center">
                        
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white" id="detailsScenarioName">Scenario Details</h2>
                        <div class="flex items-center gap-2 mt-1">
                            <span id="detailsExecutionMode" class="text-xs px-2 py-0.5 text-white rounded-full font-medium">MODE</span>
                            <span class="text-sm text-gray-500 dark:text-gray-400" id="detailsRequestCount">0 requests</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200" title="Edit Scenario" onclick="editScenarioFromDetails()">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200" onclick="closeScenarioDetails()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-140px)]">
                
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Description</h3>
                    <p class="text-gray-600 dark:text-gray-400" id="detailsDescription">No description provided</p>
                </div>
                
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Execution Configuration</h3>
                    <div class="bg-gray-50 dark:bg-[#2c2d2d] rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Mode:</span>
                                <span id="detailsExecMode" class="ml-2 text-sm text-gray-600 dark:text-gray-400">Waterfall</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Total Requests:</span>
                                <span id="detailsReqCount" class="ml-2 text-sm text-gray-600 dark:text-gray-400">0</span>
                            </div>
                            <div class="md:col-span-2">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Authentication:</span>
                                <span id="detailsAuthType" class="ml-2 text-sm text-gray-600 dark:text-gray-400">No Authentication</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Requests</h3>
                    <div id="detailsRequestsList" class="space-y-3">
                        
                    </div>
                </div>
            </div>
            
            <div class="p-6 border-t border-gray-200 dark:border-[#2c2d2d] flex justify-between">
                <button class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#2c2d2d] rounded-lg transition-colors duration-200" onclick="closeScenarioDetails()">
                    Close
                </button>
                <div class="flex gap-3">
                    <button class="bg-purple-100 hover:bg-purple-200 dark:bg-purple-900 dark:hover:bg-purple-800 text-purple-700 dark:text-purple-300 px-4 py-2 rounded-lg transition-colors duration-200 text-sm font-medium" onclick="exportScenarioFromDetails()">
                        Export JSON
                    </button>
                    <button class="bg-accent hover:bg-accent-hover text-white px-4 py-2 rounded-lg transition-colors duration-200 text-sm font-medium" onclick="runScenarioFromDetails()">
                        Run Scenario
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center" id="settingsModal">
        <div
            class="bg-white dark:bg-[#171717] rounded-xl p-6 w-full max-w-md max-h-[80vh] overflow-y-auto border dark:border-[#2c2d2d]">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Settings</h3>
                <button class="text-gray-500 dark:text-gray-400 hover:text-[#2c2d2d] dark:hover:text-gray-200 text-2xl"
                    id="closeSettings">×</button>
            </div>
            <div>
                <div class="flex justify-between items-center py-4 border-b border-gray-200 dark:border-[#2c2d2d]">
                    <div>
                        <div class="font-medium text-gray-900 dark:text-white">Dark Mode</div>
                        <div class="text-sm text-gray-600 dark:text-gray-300 mt-1">Switch to dark theme</div>
                    </div>
                    <div class="relative w-11 h-6 bg-gray-200 dark:bg-gray-600 rounded-full cursor-pointer transition-colors duration-300"
                        id="darkModeToggle">
                        <div
                            class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full transition-transform duration-300">
                        </div>
                    </div>
                </div>
                <div class="flex justify-between items-center py-4 border-b border-gray-200 dark:border-[#2c2d2d]">
                    <div>
                        <div class="font-medium text-gray-900 dark:text-white">Compact Mode</div>
                        <div class="text-sm text-gray-600 dark:text-gray-300 mt-1">Reduce spacing and hide descriptions
                        </div>
                    </div>
                    <div class="relative w-11 h-6 bg-gray-200 dark:bg-gray-600 rounded-full cursor-pointer transition-colors duration-300"
                        id="compactModeToggle">
                        <div
                            class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full transition-transform duration-300">
                        </div>
                    </div>
                </div>
                <div class="py-4">
                    <div>
                        <div class="font-medium text-gray-900 dark:text-white mb-3">Theme Color</div>
                        <div class="text-sm text-gray-600 dark:text-gray-300 mb-4">Choose your preferred accent color</div>
                    </div>
                    <div class="grid grid-cols-7 gap-3">
                        <button class="theme-color-btn w-8 h-8 rounded-full border-2 border-gray-300 dark:border-gray-600 hover:scale-110 transition-transform duration-200" data-theme="green" style="background-color: #166534" title="Green"></button>
                        <button class="theme-color-btn w-8 h-8 rounded-full border-2 border-gray-300 dark:border-gray-600 hover:scale-110 transition-transform duration-200" data-theme="blue" style="background-color: #1d4ed8" title="Blue"></button>
                        <button class="theme-color-btn w-8 h-8 rounded-full border-2 border-gray-300 dark:border-gray-600 hover:scale-110 transition-transform duration-200" data-theme="purple" style="background-color: #7c3aed" title="Purple"></button>
                        <button class="theme-color-btn w-8 h-8 rounded-full border-2 border-gray-300 dark:border-gray-600 hover:scale-110 transition-transform duration-200" data-theme="red" style="background-color: #dc2626" title="Red"></button>
                        <button class="theme-color-btn w-8 h-8 rounded-full border-2 border-gray-300 dark:border-gray-600 hover:scale-110 transition-transform duration-200" data-theme="orange" style="background-color: #ea580c" title="Orange"></button>
                        <button class="theme-color-btn w-8 h-8 rounded-full border-2 border-gray-300 dark:border-gray-600 hover:scale-110 transition-transform duration-200" data-theme="teal" style="background-color: #0891b2" title="Teal"></button>
                        <button class="theme-color-btn w-8 h-8 rounded-full border-2 border-gray-300 dark:border-gray-600 hover:scale-110 transition-transform duration-200" data-theme="pink" style="background-color: #db2777" title="Pink"></button>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
    <div class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center" id="authModal">
        <div
            class="bg-white dark:bg-[#171717] rounded-xl p-6 w-full max-w-md max-h-[80vh] overflow-y-auto border dark:border-[#2c2d2d]">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Authentication</h3>
                <button class="text-gray-500 dark:text-gray-400 hover:text-[#2c2d2d] dark:hover:text-gray-200 text-2xl"
                    id="closeAuth">×</button>
            </div>
            <div>
                <div class="bg-gray-50 dark:bg-[#212121] border border-gray-200 dark:border-0 rounded-lg p-4">
                    <select
                        class="w-full px-3 py-2 border border-gray-300 dark:border-0 rounded-md bg-white dark:bg-[#212121] text-gray-900 dark:text-white mb-4"
                        id="authType">
                        <option value="none">No Authentication</option>
                        <option value="bearer">Bearer Token</option>
                        <option value="basic">Basic Auth</option>
                        <option value="apikey">API Key</option>
                    </select>
                    <div id="authInputs" class="mb-4">
                        
                    </div>
                    <button
                        class="w-full bg-accent hover:bg-accent-hover text-white font-semibold px-4 py-2 rounded-md transition-colors duration-200"
                        id="saveAuth">Save Authentication</button>
                </div>
            </div>
        </div>
    </div>
    
    <svg style="display: none;">
        <defs>
            <g id="copy-icon">
                <rect x="2" y="2" width="14" height="14" rx="2" ry="2" fill="none" stroke="currentColor"
                    stroke-width="1.5" />
                <path d="M6 2v14a2 2 0 002 2h8a2 2 0 002-2V7.5L12.5 2H8a2 2 0 00-2 2z" fill="none" stroke="currentColor"
                    stroke-width="1.5" />
            </g>
            <g id="check-icon">
                <polyline points="20,6 9,17 4,12" fill="none" stroke="currentColor" stroke-width="1.5" />
            </g>
        </defs>
    </svg>
    <script>

        const apiData = {!! json_encode($docsData) !!};
        const config = {!! json_encode($config) !!};

        function escapeHtml(str) {
            return str
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');
        }
        function syntaxHighlight(jsonString) {

            const safe = escapeHtml(jsonString);

            return safe.replace(/("(?:[^\\"]|\\.)*")\s*:|("(?:[^\\"]|\\.)*")|(\btrue\b|\bfalse\b|\bnull\b)|(-?\d+(?:\.\d+)?(?:[eE][+-]?\d+)?)|(\{)|(\})|(\[)|(\])|,/g, function(match, key, string, bool, number, openBrace, closeBrace, openBracket, closeBracket) {
                if (key) {

                    return key.replace(/^("(?:[^\\"]|\\.)*")(\s*)$/, '<span class="json-key">$1</span>$2') + '<span class="json-colon">:</span>';
                }
                if (string) {

                    return '<span class="json-string">' + string + '</span>';
                }
                if (bool) {

                    if (bool === 'null') {
                        return '<span class="json-null">' + bool + '</span>';
                    } else {
                        return '<span class="json-boolean">' + bool + '</span>';
                    }
                }
                if (number) {

                    return '<span class="json-number">' + number + '</span>';
                }
                if (openBrace) {
                    return '<span class="json-brace">{</span>';
                }
                if (closeBrace) {
                    return '<span class="json-brace">}</span>';
                }
                if (openBracket) {
                    return '<span class="json-bracket">[</span>';
                }
                if (closeBracket) {
                    return '<span class="json-bracket">]</span>';
                }
                if (match === ',') {
                    return '<span class="json-comma">,</span>';
                }
                return match;
            });
        }

        function copyToClipboard(text, button) {
            navigator.clipboard.writeText(text).then(() => {

                const originalHTML = button.innerHTML;
                const originalClasses = button.className;
                button.className = button.className.replace('border-gray-300 dark:border-white', 'border-accent bg-accent-light dark:dark-accent-bg');
                button.innerHTML = `
                    <svg class="w-3 h-3" viewBox="0 0 24 24">
                        <use href="#check-icon"></use>
                    </svg>
                    Copied!
                `;

                setTimeout(() => {
                    button.className = originalClasses;
                    button.innerHTML = originalHTML;
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy: ', err);

                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);

                const originalClasses = button.className;
                button.className = button.className.replace('border-gray-300 dark:border-white', 'border-accent bg-accent-light dark:dark-accent-bg');
                setTimeout(() => {
                    button.className = originalClasses;
                }, 2000);
            });
        }

        function createJsonViewer(jsonString, title = 'JSON') {
            const copyId = 'copy_' + Math.random().toString(36).substr(2, 9);
            const beautifyId = 'beautify_' + Math.random().toString(36).substr(2, 9);

            let parsedJson;
            try {
                parsedJson = JSON.parse(jsonString);
            } catch (e) {
                parsedJson = jsonString
            }
            const beautifiedJson = JSON.stringify(parsedJson, null, 2);
            const compactJson = JSON.stringify(parsedJson);
            return `
                <div class="bg-gray-50 dark:bg-[#212121] border border-gray-200 dark:border-0 rounded-xl p-4 font-mono text-sm overflow-x-auto mb-4 relative" data-beautify-id="${beautifyId}">
                    <div class="flex justify-between items-center mb-3 pb-2 border-b border-gray-200 dark:border-0">
                        <span class="text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wide">${title}</span>
                        <div class="flex gap-2">
                            <button class="flex items-center gap-1 px-2 py-1 border border-gray-300 rounded-md text-xs text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-green-800 transition-colors duration-200 dark:border-0 dark:bg-black" data-copy-text="${beautifiedJson.replace(/"/g, '&quot;')}" data-copy-id="${copyId}">
                                <svg class="w-3 h-3" viewBox="0 0 24 24">
                                    <use href="#copy-icon"></use>
                                </svg>
                                Copy
                            </button>
                            <button class="flex items-center gap-1 px-2 py-1 border border-gray-300 dark:border-white rounded-md text-xs text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-green-800 transition-colors duration-200 dark:border-0 dark:bg-black" data-beautify-target="${beautifyId}" data-beautified="${beautifiedJson.replace(/"/g, '&quot;')}" data-compact="${compactJson.replace(/"/g, '&quot;')}" data-is-beautified="true">
                                <svg class="w-3 h-3" viewBox="0 0 24 24">
                                    <path d="M3 3h18v2H3V3zm0 4h18v2H3V7zm0 4h18v2H3v-2zm0 4h18v2H3v-2z" fill="currentColor"/>
                                </svg>
                                Compact
                            </button>
                        </div>
                    </div>
                    <pre class="m-0 p-0 bg-transparent border-0 text-wrap text-gray-900 dark:text-white"><code class="json-content bg-transparent p-0 text-inherit">${syntaxHighlight(beautifiedJson)}</code></pre>
                </div>
            `;
        }

        function transformApiData(backendData) {
            const transformed = {};
            if (backendData.endpoints) {
                backendData.endpoints.forEach(section => {
                    const sectionName = section.name.toLowerCase();
                    transformed[sectionName] = section.endpoints.map(endpoint => ({
                        id: endpoint.id,
                        method: endpoint.method,
                        path: endpoint.path,
                        title: endpoint.summary,
                        description: endpoint.description || 'No description available',
                        parameters: endpoint.parameters || [],
                        requestBody: endpoint.requestBody || null,
                        responses: endpoint.responses || {}
                    }));
                });
            }
            return transformed;
        }
        const transformedApiData = transformApiData(apiData);

        let currentEndpoint = null;
        let filteredEndpoints = [];
        let settings = {
            darkMode: false,
            compactMode: false
        };
        let auth = {
            type: 'none',
            token: '',
            username: '',
            password: '',
            apiKey: '',
            keyName: 'X-API-Key'
        };

        const sidebar = document.getElementById('sidebar');
        const searchInput = document.getElementById('searchInput');
        const searchClear = document.getElementById('searchClear');
        const searchResults = document.getElementById('searchResults');
        const searchCount = document.getElementById('searchCount');
        const endpointsContainer = document.getElementById('endpointsContainer');
        const baseUrlSelect = document.getElementById('baseUrlSelect');
        const currentMethod = document.getElementById('currentMethod');
        const currentUrl = document.getElementById('currentUrl');
        const endpointDescription = document.getElementById('endpointDescription');
        const parametersContent = document.getElementById('parametersContent');
        const bodyContent = document.getElementById('bodyContent');
        const responsesContent = document.getElementById('responsesContent');
        const testButton = document.getElementById('testButton');
        const responseContainer = document.getElementById('responseContainer');
        const responseStatus = document.getElementById('responseStatus');
        const responseTime = document.getElementById('responseTime');
        const responseBody = document.getElementById('responseBody');

        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        const settingsModal = document.getElementById('settingsModal');
        const authModal = document.getElementById('authModal');
        const settingsBtn = document.getElementById('settingsBtn');
        const settingsBtnSidebar = document.getElementById('settingsBtnSidebar');
        const authBtn = document.getElementById('authBtn');
        const authBtnDesktop = document.getElementById('authBtnDesktop');
        const baseUrlSelectDesktop = document.getElementById('baseUrlSelectDesktop');
        const closeSettings = document.getElementById('closeSettings');
        const closeAuth = document.getElementById('closeAuth');

    const darkModeToggle = document.getElementById('darkModeToggle');
    const compactModeToggle = document.getElementById('compactModeToggle');

        const authType = document.getElementById('authType');
        const authInputs = document.getElementById('authInputs');
        const saveAuth = document.getElementById('saveAuth');

        function init() {

            function populateBaseUrlSelects() {
                const selects = [baseUrlSelect, baseUrlSelectDesktop];
                selects.forEach(select => {
                    if (!select) return;
                    select.innerHTML = '';

                    const currentOriginOption = document.createElement('option');
                    currentOriginOption.value = window.location.origin;
                    currentOriginOption.textContent = `Current - ${window.location.origin}`;
                    currentOriginOption.selected = true;
                    select.appendChild(currentOriginOption);

                    if (config && config.baseUrls && config.baseUrls.length > 0) {

                        config.baseUrls.forEach((baseUrlOption) => {
                            const option = document.createElement('option');
                            option.value = baseUrlOption.url;
                            option.textContent = `${baseUrlOption.name} - ${baseUrlOption.url}`;
                            select.appendChild(option);
                        });
                    } else if (config && config.baseUrl) {

                        const option = document.createElement('option');
                        option.value = config.baseUrl;
                        option.textContent = `Config - ${config.baseUrl}`;
                        select.appendChild(option);
                    } else {

                        const defaultOptions = [
                            { name: 'Production', url: 'https://api.example.com' },
                            { name: 'Staging', url: 'https://staging-api.example.com' },
                            { name: 'Development', url: 'https://dev-api.example.com' },
                            { name: 'Local', url: 'http://localhost:8080' }
                        ];
                        defaultOptions.forEach((baseUrlOption) => {
                            const option = document.createElement('option');
                            option.value = baseUrlOption.url;
                            option.textContent = `${baseUrlOption.name} - ${baseUrlOption.url}`;
                            select.appendChild(option);
                        });
                    }
                });
            }
            populateBaseUrlSelects();

            filteredEndpoints = Object.values(transformedApiData).flat();
            renderEndpoints();
            setupEventListeners();
            loadSettings();
            loadAuthentication();
            selectFirstEndpoint();
            initThemeColor();

            document.getElementById('chatAIToggle').addEventListener('click', toggleChatSidebar);

            setupSidebarResize();
            setupLeftSidebarResize();
            document.getElementById('closeChatSidebar').addEventListener('click', toggleChatSidebar);
            document.getElementById('sendChatMessage').addEventListener('click', sendChatMessage);

            (function setupChatInput() {
                const chatInput = document.getElementById('chatInput');
                if (!chatInput) return;

                const MIN_HEIGHT = 50;
                const MAX_HEIGHT = 100;
                let raf = null;
                function setRounded(el, atMax) {
                    el.classList.remove('rounded-full', 'rounded-2xl');
                    el.classList.add(atMax ? 'rounded-2xl' : 'rounded-full');
                }
                function autoResize(el) {

                    if (raf) cancelAnimationFrame(raf);
                    raf = requestAnimationFrame(() => {
                        el.style.height = 'auto';
                        const scroll = el.scrollHeight;
                        const containsNewline = /\n/.test(el.value);
                        const shouldMax = containsNewline || scroll > MAX_HEIGHT;
                        const newHeight = shouldMax ? MAX_HEIGHT : Math.max(scroll, MIN_HEIGHT);
                        el.style.height = newHeight + 'px';
                        setRounded(el, shouldMax);
                    });
                }

                autoResize(chatInput, true);

                chatInput.addEventListener('input', (e) => {
                    autoResize(e.target);
                });

                chatInput.addEventListener('paste', (e) => {
                    setTimeout(() => {

                        chatInput.value = chatInput.value.replace(/[ \t]{3,}/g, ' ');

                        autoResize(chatInput);
                    }, 0);
                });

                chatInput.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') {
                        if (!e.shiftKey) {
                            e.preventDefault();
                            sendChatMessage();
                        } else {

                            setTimeout(() => autoResize(chatInput, true), 0);
                        }
                    }
                });

                const origToggle = toggleChatSidebar;
            })();
        }

        function renderEndpoints(endpointsToRender = null) {

            const endpointsToShow = endpointsToRender || filteredEndpoints || Object.values(transformedApiData).flat();
            endpointsContainer.innerHTML = '';
            Object.keys(transformedApiData).forEach(category => {
                const categoryEndpoints = transformedApiData[category].filter(endpoint => 
                    endpointsToShow.includes(endpoint)
                );
                if (categoryEndpoints.length === 0) return;
                const groupDiv = document.createElement('div');
                groupDiv.className = 'mb-6';
                const titleDiv = document.createElement('div');
                titleDiv.className = 'px-6 pb-3 text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider';
                titleDiv.textContent = `${category.charAt(0).toUpperCase() + category.slice(1)} (${categoryEndpoints.length})`;
                groupDiv.appendChild(titleDiv);
                categoryEndpoints.forEach(endpoint => {
                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'px-6 py-3 cursor-pointer border-l-3 border-transparent hover:bg-gray-100 dark:hover:bg-[#171717] hover:border-accent transition-all duration-200';
                    itemDiv.dataset.endpointId = endpoint.id;
                    itemDiv.innerHTML = `
                        <div class="flex items-center gap-3 mb-1">
                            <div class="inline-block px-2 py-1 rounded text-xs font-semibold text-center min-w-16 method-${endpoint.method.toLowerCase()}">${endpoint.method}</div>
                            <div class="font-mono text-sm text-gray-900 dark:text-white">${endpoint.path}</div>
                        </div>
                        <div class="text-xs text-gray-600 dark:text-gray-300 endpoint-description" style="display: ${settings.compactMode ? 'none' : 'block'}">${getEndpointDescription(endpoint)}</div>
                    `;
                    itemDiv.addEventListener('click', () => selectEndpoint(endpoint));
                    groupDiv.appendChild(itemDiv);
                });
                endpointsContainer.appendChild(groupDiv);
            });
        }

        const endpointFormStates = {};

        function saveFormState() {
            if (!currentEndpoint) return;
            const state = {};

            const testParametersInputs = document.getElementById('testParametersInputs');
            if (testParametersInputs) {
                const inputs = testParametersInputs.querySelectorAll('input');
                inputs.forEach(input => {
                    if (input.name && input.value) {
                        state[input.name] = input.value;
                    }
                });
            }

            if (monacoEditor) {
                const value = monacoEditor.getValue();
                if (value) {
                    state['body'] = value;
                }
            }

            const responseContainer = document.getElementById('responseContainer');
            if (responseContainer && !responseContainer.classList.contains('hidden')) {
                const responseStatus = document.getElementById('responseStatus');
                const responseTime = document.getElementById('responseTime');
                const responseBody = document.getElementById('responseBody');
                state['response'] = {
                    status: responseStatus?.textContent || '',
                    statusClass: responseStatus?.className || '',
                    time: responseTime?.textContent || '',
                    body: responseBody?.innerHTML || '',
                    visible: true
                };
            }
            endpointFormStates[currentEndpoint.id] = state;
        }

        function restoreFormState() {
            if (!currentEndpoint || !endpointFormStates[currentEndpoint.id]) return;
            const state = endpointFormStates[currentEndpoint.id];

            const testParametersInputs = document.getElementById('testParametersInputs');
            if (testParametersInputs) {
                const inputs = testParametersInputs.querySelectorAll('input');
                inputs.forEach(input => {
                    if (input.name && state[input.name]) {
                        input.value = state[input.name];
                    }
                });
            }

            if (monacoEditor && state['body']) {
                monacoEditor.setValue(state['body']);
            }

            if (state['response']) {
                const responseContainer = document.getElementById('responseContainer');
                const responseStatus = document.getElementById('responseStatus');
                const responseTime = document.getElementById('responseTime');
                const responseBody = document.getElementById('responseBody');
                if (state.response.visible && responseContainer) {
                    responseContainer.classList.remove('hidden');
                    if (responseStatus) {
                        responseStatus.textContent = state.response.status;
                        responseStatus.className = state.response.statusClass;
                    }
                    if (responseTime) {
                        responseTime.textContent = state.response.time;
                    }
                    if (responseBody) {
                        responseBody.innerHTML = state.response.body;
                    }
                }
            }
        }

        function selectEndpoint(endpoint) {

            saveFormState();
            currentEndpoint = endpoint;

            document.querySelectorAll('[data-endpoint-id]').forEach(item => {
                item.classList.remove('endpoint-active');
            });
            const activeItem = document.querySelector(`[data-endpoint-id="${endpoint.id}"]`);
            if (activeItem) activeItem.classList.add('endpoint-active');

            currentMethod.textContent = endpoint.method;
            currentMethod.className = `endpoint-method px-2 rounded-md text-sm method-${endpoint.method.toLowerCase()}`;

            const selectedOption = baseUrlSelect.options[baseUrlSelect.selectedIndex];
            const selectedText = selectedOption ? selectedOption.textContent : 'Current';
            const baseUrlName = selectedText.split(' - ')[0];
            currentUrl.innerHTML = `
                <span class="base-url-badge">${baseUrlName}</span>
                <span class="endpoint-path">${endpoint.path}</span>
            `;

            const bodyTab = document.getElementById('bodyTab');
            const hasBody = ['POST', 'PUT', 'PATCH'].includes(endpoint.method.toUpperCase());
            bodyTab.style.display = hasBody ? 'block' : 'none';

            updateContent();

            const responseContainer = document.getElementById('responseContainer');
            responseContainer.classList.add('hidden');
        }

        function updateContent() {
            if (!currentEndpoint) return;

            const description = getEndpointDescription(currentEndpoint);
            endpointDescription.textContent = description;

            if (currentEndpoint.parameters && currentEndpoint.parameters.length > 0) {
                parametersContent.innerHTML = `
                    <div class="mobile-scroll-table">
                        <table class="w-full border-collapse mb-4 bg-white dark:bg-black border border-gray-200 dark:border-0 rounded-lg overflow-hidden">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-[#2c2d2d]">
                                <th class="px-3 py-2 text-left border-b border-gray-200 dark:border-0 font-semibold text-gray-900 dark:text-white">Name</th>
                                <th class="px-3 py-2 text-left border-b border-gray-200 dark:border-0 font-semibold text-gray-900 dark:text-white">Location</th>
                                <th class="px-3 py-2 text-left border-b border-gray-200 dark:border-0 font-semibold text-gray-900 dark:text-white">Type</th>
                                <th class="px-3 py-2 text-left border-b border-gray-200 dark:border-0 font-semibold text-gray-900 dark:text-white">Required</th>
                                <th class="px-3 py-2 text-left border-b border-gray-200 dark:border-0 font-semibold text-gray-900 dark:text-white">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${currentEndpoint.parameters.map(param => `
                                <tr class="border-b border-gray-200 dark:border-0">
                                    <td class="px-3 py-2"><code class="bg-gray-100 dark:bg-green-800 dark:text-white px-2 py-1 rounded text-sm">${param.name}</code></td>
                                    <td class="px-3 py-2"><span class="px-2 py-1 rounded text-xs font-medium ${param.in === 'path' ? 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' : 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100'}">${param.in}</span></td>
                                    <td class="px-3 py-2"><span class="bg-gray-100 dark:bg-green-800 dark:text-white px-2 py-1 rounded text-xs font-mono">${param.type}</span></td>
                                    <td class="px-3 py-2">${param.required ? '<span class="text-red-600 dark:text-red-400 font-semibold">Yes</span>' : 'No'}</td>
                                    <td class="px-3 py-2 text-gray-600 dark:text-gray-300">${param.description || 'No description available'}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                        </table>
                    </div>
                `;
            } else {
                parametersContent.innerHTML = '<p>No parameters required.</p>';
            }

            const bodyContent = document.getElementById('bodyContent');
            if (['POST', 'PUT', 'PATCH'].includes(currentEndpoint.method.toUpperCase())) {
                const requestBody = getRequestBodyExample(currentEndpoint);
                if (requestBody) {
                    const pretty = JSON.stringify(requestBody, null, 2);
                    bodyContent.innerHTML = `
                        ${createJsonViewer(pretty, 'Request Body')}
                        <p class="text-muted" style="margin-top: 8px; font-size: 14px;"></p>
                    `;
                } else {
                    bodyContent.innerHTML = '<p>No request body example available.</p>';
                }
            } else {
                bodyContent.innerHTML = '<p>No request body required for this method.</p>';
            }

            const responses = getEndpointResponses(currentEndpoint);
            if (responses && Object.keys(responses).length > 0) {
                responsesContent.innerHTML = Object.entries(responses).map(([status, response]) => {
                    const exampleHtml = response.example !== undefined && response.example !== null
                        ? createJsonViewer(JSON.stringify(response.example, null, 2), `Response ${status}`)
                        : '';
                    return `
                        <div class="mb-6 p-4 border border-gray-200 dark:border-[#1b1b1b] rounded-2xl bg-white dark:bg-[#171717]">
                            <h4 class="mb-3"><span class="inline-block px-2 py-1 rounded text-xs font-semibold mr-2 ${status.startsWith('2') ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100'}">${status}</span><span class="text-gray-900 dark:text-white">${response.description}</span></h4>
                            ${exampleHtml}
                        </div>`;
                }).join('');
            } else {
                responsesContent.innerHTML = '<p>No response examples available.</p>';
            }

            updateTestForm();
        }

        function updateTestForm() {
            if (!currentEndpoint) return;
            const testParametersForm = document.getElementById('testParametersForm');
            const testParametersInputs = document.getElementById('testParametersInputs');
            const testBodyForm = document.getElementById('testBodyForm');
            const testBodyInput = document.getElementById('testBodyInput');

            if (currentEndpoint.parameters && currentEndpoint.parameters.length > 0) {
                testParametersForm.classList.remove('hidden');

                const pathParams = currentEndpoint.parameters.filter(p => p.in === 'path');
                const queryParams = currentEndpoint.parameters.filter(p => p.in === 'query');
                const otherParams = currentEndpoint.parameters.filter(p => p.in !== 'path' && p.in !== 'query');
                let html = '';

                if (pathParams.length > 0) {
                    html += `
                        <div class="mb-4">
                            <h5 class="text-sm font-semibold text-gray-900 dark:text-white mb-2 flex items-center">
                                <span class="px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100 mr-2">path</span>
                                Path Parameters
                            </h5>
                            <div class="space-y-3">
                                ${pathParams.map(param => {
                                    const isRequired = param.required ? 'required' : '';
                                    const placeholder = param.description || `Enter ${param.name}`;
                                    return `
                                        <div class="flex flex-col">
                                            <label class="text-sm font-medium text-[#2c2d2d] dark:text-gray-300 mb-1">
                                                ${param.name} 
                                                <span class="text-xs text-gray-500 dark:text-gray-400">(${param.type})</span>
                                                ${param.required ? '<span class="text-red-500">*</span>' : ''}
                                            </label>
                                            <input 
                                                type="text" 
                                                name="param_${param.name}" 
                                                class="px-3 py-2 border border-gray-300 dark:border-[#212121] rounded-md bg-white dark:bg-black text-gray-900 dark:text-white text-sm" 
                                                placeholder="${placeholder}"
                                                ${isRequired}
                                            >
                                            ${param.description ? `<span class="text-xs text-gray-500 dark:text-gray-400 mt-1">${param.description}</span>` : ''}
                                        </div>
                                    `;
                                }).join('')}
                            </div>
                        </div>
                    `;
                }

                if (queryParams.length > 0) {
                    html += `
                        <div class="mb-4">
                            <h5 class="text-sm font-semibold text-gray-900 dark:text-white mb-2 flex items-center">
                                <span class="px-2 py-1 rounded text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100 mr-2">query</span>
                                Query Parameters
                            </h5>
                            <div class="space-y-3">
                                ${queryParams.map(param => {
                                    const isRequired = param.required ? 'required' : '';
                                    const placeholder = param.description || `Enter ${param.name}`;
                                    return `
                                        <div class="flex flex-col">
                                            <label class="text-sm font-medium text-[#2c2d2d] dark:text-gray-300 mb-1">
                                                ${param.name} 
                                                <span class="text-xs text-gray-500 dark:text-gray-400">(${param.type})</span>
                                                ${param.required ? '<span class="text-red-500">*</span>' : ''}
                                            </label>
                                            <input 
                                                type="text" 
                                                name="param_${param.name}" 
                                                class="px-3 py-2 border border-gray-300 dark:border-[#212121] rounded-md bg-white dark:bg-black text-gray-900 dark:text-white text-sm" 
                                                placeholder="${placeholder}"
                                                ${isRequired}
                                            >
                                            ${param.description ? `<span class="text-xs text-gray-500 dark:text-gray-400 mt-1">${param.description}</span>` : ''}
                                        </div>
                                    `;
                                }).join('')}
                            </div>
                        </div>
                    `;
                }

                if (otherParams.length > 0) {
                    html += `
                        <div class="mb-4">
                            <h5 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Other Parameters</h5>
                            <div class="space-y-3">
                                ${otherParams.map(param => {
                                    const isRequired = param.required ? 'required' : '';
                                    const placeholder = param.description || `Enter ${param.name}`;
                                    return `
                                        <div class="flex flex-col">
                                            <label class="text-sm font-medium text-[#2c2d2d] dark:text-gray-300 mb-1">
                                                ${param.name} 
                                                <span class="px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-green-800 dark:text-gray-100 mr-1">${param.in}</span>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">(${param.type})</span>
                                                ${param.required ? '<span class="text-red-500">*</span>' : ''}
                                            </label>
                                            <input 
                                                type="text" 
                                                name="param_${param.name}" 
                                                class="px-3 py-2 border border-gray-300 dark:border-[#212121] rounded-md bg-white dark:bg-black text-gray-900 dark:text-white text-sm" 
                                                placeholder="${placeholder}"
                                                ${isRequired}
                                            >
                                            ${param.description ? `<span class="text-xs text-gray-500 dark:text-gray-400 mt-1">${param.description}</span>` : ''}
                                        </div>
                                    `;
                                }).join('')}
                            </div>
                        </div>
                    `;
                }
                testParametersInputs.innerHTML = html;
            } else {
                testParametersForm.classList.add('hidden');
            }

            const hasBody = ['POST', 'PUT', 'PATCH'].includes(currentEndpoint.method.toUpperCase());
            if (hasBody) {
                testBodyForm.classList.remove('hidden');

                if (!endpointFormStates[currentEndpoint.id] || !endpointFormStates[currentEndpoint.id]['body']) {

                    const exampleBody = getRequestBodyExample(currentEndpoint);
                    const defaultValue = exampleBody ? JSON.stringify(exampleBody, null, 2) : '{\n  \n}';
                    if (monacoEditor) {
                        monacoEditor.setValue(defaultValue);
                    }
                }
            } else {
                testBodyForm.classList.add('hidden');
            }

            setTimeout(restoreFormState, 0);
        }

        function performSearch() {
            const query = searchInput.value.toLowerCase().trim();
            if (query === '') {
                filteredEndpoints = Object.values(transformedApiData).flat();
                searchResults.classList.add('hidden');
                searchClear.classList.add('hidden');
            } else {
                filteredEndpoints = Object.values(transformedApiData).flat().filter(endpoint => {
                    const searchTargets = [
                        endpoint.title || '',
                        endpoint.description || '',
                        getEndpointDescription(endpoint),
                        endpoint.path || '',
                        endpoint.id || '',
                        endpoint.method || '',
                        endpoint.summary || '',

                        ...(endpoint.parameters || []).map(param => 
                            `${param.name} ${param.type} ${param.description || ''}`
                        )
                    ];
                    return searchTargets.some(target => 
                        target.toLowerCase().includes(query)
                    );
                });
                searchResults.classList.remove('hidden');
                searchClear.classList.remove('hidden');
                searchCount.textContent = filteredEndpoints.length;
            }
            renderEndpoints();
        }

        function clearSearch() {
            searchInput.value = '';
            performSearch();
        }

        function selectFirstEndpoint() {
            if (filteredEndpoints && filteredEndpoints.length > 0) {
                selectEndpoint(filteredEndpoints[0]);
            } else {
                const firstCategory = Object.keys(transformedApiData)[0];
                if (firstCategory && transformedApiData[firstCategory][0]) {
                    selectEndpoint(transformedApiData[firstCategory][0]);
                }
            }
        }

        function switchTab(tabName) {

            document.querySelectorAll('[data-tab]').forEach(tab => {
                tab.className = 'px-6 py-3 cursor-pointer border-b-2 border-transparent text-gray-600 dark:text-gray-300 hover:text-accent hover:border-accent transition-all duration-200';

                tab.style.borderBottomColor = '';
                tab.style.color = '';
            });

            document.querySelectorAll('[id="overview"], [id="parameters"], [id="body"], [id="responses"], [id="test"]').forEach(content => {
                content.classList.add('hidden');
                content.classList.remove('block');
            });

            const activeTab = document.querySelector(`[data-tab="${tabName}"]`);
            activeTab.className = 'px-6 py-3 cursor-pointer border-b-2 font-medium transition-all duration-200';
            activeTab.style.borderBottomColor = 'var(--accent-color)';
            activeTab.style.color = 'var(--accent-color)';

            const activeContent = document.getElementById(tabName);
            activeContent.classList.remove('hidden');
            activeContent.classList.add('block');
        }

        async function testEndpoint() {
            if (!currentEndpoint) return;
            testButton.disabled = true;
            testButton.textContent = 'Sending...';
            const startTime = Date.now();
            try {

                const parameters = {};
                const paramInputs = document.querySelectorAll('[name^="param_"]');
                paramInputs.forEach(input => {
                    const paramName = input.name.replace('param_', '');
                    if (input.value.trim()) {
                        parameters[paramName] = input.value.trim();
                    }
                });

                let baseUrl = baseUrlSelect.value || window.location.origin;
                let url = `${baseUrl}${currentEndpoint.path}`;

                Object.entries(parameters).forEach(([key, value]) => {
                    url = url.replace(`{${key}}`, value);
                });

                if (currentEndpoint.method.toUpperCase() === 'GET' && Object.keys(parameters).length > 0) {
                    const queryParams = new URLSearchParams();
                    Object.entries(parameters).forEach(([key, value]) => {

                        if (!currentEndpoint.path.includes(`{${key}}`)) {
                            queryParams.append(key, value);
                        }
                    });
                    if (queryParams.toString()) {
                        url += '?' + queryParams.toString();
                    }
                }

                const requestOptions = {
                    method: currentEndpoint.method,
                    headers: {
                        ...getAuthHeaders(),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                };

                if (!['GET', 'HEAD', 'OPTIONS'].includes(currentEndpoint.method.toUpperCase())) {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (csrfToken) {
                        requestOptions.headers['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
                    }
                }

                if (['POST', 'PUT', 'PATCH'].includes(currentEndpoint.method.toUpperCase())) {
                    if (monacoEditor) {
                        const bodyValue = monacoEditor.getValue().trim();
                        if (bodyValue) {
                            try {

                                JSON.parse(bodyValue);
                                requestOptions.body = bodyValue;
                            } catch (e) {
                                throw new Error('Invalid JSON in request body');
                            }
                        }
                    }
                }

                const response = await fetch(url, requestOptions);
                const endTime = Date.now();
                const duration = endTime - startTime;

                responseContainer.classList.remove('hidden');
                responseStatus.textContent = response.status;
                responseStatus.className = `response-status dark:text-white status-${response.status}`;
                responseTime.textContent = `${duration}ms`;
                try {
                    const responseData = await response.json();
                    responseBody.innerHTML = createJsonViewer(JSON.stringify(responseData, null, 2), 'Response');
                } catch (e) {

                    const textResponse = await response.text();
                    responseBody.innerHTML = `<pre class="p-4 bg-gray-100 dark:bg-[#212121] border border-gray-200 dark:border-[#2c2d2d] rounded-lg font-mono text-sm">${textResponse || 'Empty response'}</pre>`;
                }

                saveFormState();
            } catch (error) {
                const endTime = Date.now();
                const duration = endTime - startTime;
                responseContainer.classList.remove('hidden');
                responseStatus.textContent = '500';
                responseStatus.className = 'response-status dark:text-white status-500';
                responseTime.textContent = `${duration}ms`;
                responseBody.innerHTML = createJsonViewer(JSON.stringify({ error: 'Request failed: ' + error.message }, null, 2), 'Error Response');

                saveFormState();
            } finally {
                testButton.disabled = false;
                testButton.textContent = 'Send Request';
            }
        }

        function getAuthHeaders() {
            const headers = {};
            switch (auth.type) {
                case 'bearer':
                    if (auth.token) {
                        headers.Authorization = `Bearer ${auth.token}`;
                    }
                    break;
                case 'basic':
                    if (auth.username && auth.password) {
                        headers.Authorization = `Basic ${btoa(auth.username + ':' + auth.password)}`;
                    }
                    break;
                case 'apikey':
                    if (auth.apiKey) {
                        headers[auth.keyName] = auth.apiKey;
                    }
                    break;
            }
            return headers;
        }

        function updateThemeColor(color) {

            let themeColorMeta = document.querySelector('meta[name="theme-color"]:not([media])');
            if (!themeColorMeta) {
                themeColorMeta = document.createElement('meta');
                themeColorMeta.name = 'theme-color';
                document.head.appendChild(themeColorMeta);
            }
            themeColorMeta.content = color;

            const lightThemeMeta = document.querySelector('meta[name="theme-color"][media*="light"]');
            const darkThemeMeta = document.querySelector('meta[name="theme-color"][media*="dark"]');
            if (lightThemeMeta) lightThemeMeta.content = '#f9fafb';
            if (darkThemeMeta) darkThemeMeta.content = '#0a0a0a';
        }

        function toggleSetting(settingName) {
            settings[settingName] = !settings[settingName];
            applySetting(settingName);
            saveSettings();
        }
        function applySetting(settingName) {
            const toggle = document.getElementById(`${settingName}Toggle`);
            if (!toggle) return;
            const toggleSlider = toggle.querySelector('div');
            if (settings[settingName]) {
                toggle.classList.add('toggle-active');
                toggle.classList.remove('bg-gray-200', 'dark:bg-gray-600');
                toggle.classList.add('bg-accent');
                toggleSlider.classList.add('translate-x-5');
                toggleSlider.classList.remove('translate-x-0');
            } else {
                toggle.classList.remove('toggle-active');
                toggle.classList.add('bg-gray-200', 'dark:bg-gray-600');
                toggle.classList.remove('bg-accent');
                toggleSlider.classList.remove('translate-x-5');
                toggleSlider.classList.add('translate-x-0');
            }
            switch (settingName) {
                case 'darkMode':
                    if (settings.darkMode) {
                        document.documentElement.classList.add('dark');

                        updateThemeColor('#0a0a0a');
                    } else {
                        document.documentElement.classList.remove('dark');

                        updateThemeColor('#f9fafb');
                    }
                    break;
                case 'compactMode':
                    if (settings.compactMode) {
                        sidebar.classList.add('w-70');
                        sidebar.classList.remove('w-80');
                    } else {
                        sidebar.classList.remove('w-70');
                        sidebar.classList.add('w-80');
                    }

                    renderEndpoints();
                    break;
                case 'showDescriptions':

                    break;
            }
        }
        function saveSettings() {
            localStorage.setItem('apiDocsSettings', JSON.stringify(settings));
        }
        function loadSettings() {
            const saved = localStorage.getItem('apiDocsSettings');
            if (saved) {
                settings = { ...settings, ...JSON.parse(saved) };
            } else {

                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    settings.darkMode = true;
                }
            }
            Object.keys(settings).forEach(settingName => {
                applySetting(settingName);
            });

            const isDark = document.documentElement.classList.contains('dark');
            updateThemeColor(isDark ? '#0a0a0a' : '#f9fafb');
        }

        function updateAuthInputs() {
            const type = authType.value;
            let html = '';
            switch (type) {
                case 'bearer':
                    html = '<input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-[#383838] rounded-md bg-white dark:bg-[#212121] text-gray-900 dark:text-white mb-2" id="authToken" placeholder="Bearer token" value="' + auth.token + '">';
                    break;
                case 'basic':
                    html = `
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-[#383838] rounded-md bg-white dark:bg-[#212121] text-gray-900 dark:text-white mb-2" id="authUsername" placeholder="Username" value="${auth.username}">
                        <input type="password" class="w-full px-3 py-2 border border-gray-300 dark:border-[#383838] rounded-md bg-white dark:bg-[#212121] text-gray-900 dark:text-white mb-2" id="authPassword" placeholder="Password" value="${auth.password}">
                    `;
                    break;
                case 'apikey':
                    html = `
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-[#383838] rounded-md bg-white dark:bg-[#212121] text-gray-900 dark:text-white mb-2" id="authKeyName" placeholder="Header name (e.g., X-API-Key)" value="${auth.keyName}">
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-[#383838] rounded-md bg-white dark:bg-[#212121] text-gray-900 dark:text-white mb-2" id="authApiKey" placeholder="API key value" value="${auth.apiKey}">
                    `;
                    break;
            }
            authInputs.innerHTML = html;
        }
        function saveAuthentication() {
            auth.type = authType.value;
            switch (auth.type) {
                case 'bearer':
                    auth.token = document.getElementById('authToken')?.value || '';
                    break;
                case 'basic':
                    auth.username = document.getElementById('authUsername')?.value || '';
                    auth.password = document.getElementById('authPassword')?.value || '';
                    break;
                case 'apikey':
                    auth.keyName = document.getElementById('authKeyName')?.value || 'X-API-Key';
                    auth.apiKey = document.getElementById('authApiKey')?.value || '';
                    break;
            }
            localStorage.setItem('apiDocsAuth', JSON.stringify(auth));
            authModal.classList.add('hidden');
            authModal.classList.remove('flex');
        }
        function loadAuthentication() {
            const saved = localStorage.getItem('apiDocsAuth');
            if (saved) {
                auth = { ...auth, ...JSON.parse(saved) };
            }
            authType.value = auth.type;
            updateAuthInputs();
        }

        function setupEventListeners() {

            document.addEventListener('click', (e) => {
                if (e.target.closest('[data-copy-text]')) {
                    const button = e.target.closest('[data-copy-text]');
                    const textToCopy = button.getAttribute('data-copy-text');
                    if (textToCopy) {
                        copyToClipboard(textToCopy, button);
                    }
                }
            });

            document.addEventListener('click', (e) => {
                if (e.target.closest('[data-beautify-target]')) {
                    const button = e.target.closest('[data-beautify-target]');
                    const targetId = button.getAttribute('data-beautify-target');
                    const isBeautified = button.getAttribute('data-is-beautified') === 'true';
                    const beautifiedJson = button.getAttribute('data-beautified');
                    const compactJson = button.getAttribute('data-compact');
                    const codeBlock = document.querySelector(`[data-beautify-id="${targetId}"]`);
                    const codeContent = codeBlock.querySelector('.json-content');
                    const copyButton = codeBlock.querySelector('.copy-button');
                    if (isBeautified) {

                        codeContent.innerHTML = syntaxHighlight(compactJson);
                        button.innerHTML = `
                            <svg class="copy-icon" viewBox="0 0 24 24" style="width: 14px; height: 14px;">
                                <path d="M3 7h18v2H3V7zm0 4h18v2H3v-2z" fill="currentColor"/>
                                <path d="M3 15h12v2H3v-2z" fill="currentColor"/>
                            </svg>
                            Beautify
                        `;
                        button.setAttribute('data-is-beautified', 'false');
                        copyButton.setAttribute('data-copy-text', compactJson);
                    } else {

                        codeContent.innerHTML = syntaxHighlight(beautifiedJson);
                        button.innerHTML = `
                            <svg class="copy-icon" viewBox="0 0 24 24" style="width: 14px; height: 14px;">
                                <path d="M3 3h18v2H3V3zm0 4h18v2H3V7zm0 4h18v2H3v-2zm0 4h18v2H3v-2z" fill="currentColor"/>
                            </svg>
                            Compact
                        `;
                        button.setAttribute('data-is-beautified', 'true');
                        copyButton.setAttribute('data-copy-text', beautifiedJson);
                    }
                }
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' || e.key === 'Esc') {

                    if (settingsModal && !settingsModal.classList.contains('hidden')) {
                        settingsModal.classList.add('hidden');
                        settingsModal.classList.remove('flex');
                    }

                    if (authModal && !authModal.classList.contains('hidden')) {
                        authModal.classList.add('hidden');
                        authModal.classList.remove('flex');
                    }

                    const chatSidebar = document.getElementById('chatSidebar');
                    if (chatSidebar && !chatSidebar.classList.contains('hidden')) {
                        chatSidebar.classList.add('hidden');
                    }
                }
            });

            searchInput.addEventListener('input', performSearch);
            searchClear.addEventListener('click', clearSearch);

            function handleBaseUrlChange(selectElement) {
                if (currentEndpoint) {

                    const selectedOption = selectElement.options[selectElement.selectedIndex];
                    const selectedText = selectedOption ? selectedOption.textContent : 'Current';
                    const baseUrlName = selectedText.split(' - ')[0];
                    currentUrl.innerHTML = `
                        <span class="base-url-badge">${baseUrlName}</span>
                        <span class="endpoint-path">${currentEndpoint.path}</span>
                    `;

                    const selects = [baseUrlSelect, baseUrlSelectDesktop];
                    selects.forEach(select => {
                        if (select && select !== selectElement) {
                            select.value = selectElement.value;
                        }
                    });
                }
            }

            baseUrlSelect.addEventListener('change', (e) => handleBaseUrlChange(e.target));
            if (baseUrlSelectDesktop) {
                baseUrlSelectDesktop.addEventListener('change', (e) => handleBaseUrlChange(e.target));
            }

            document.querySelectorAll('.tab').forEach(tab => {
                tab.addEventListener('click', () => {
                    switchTab(tab.dataset.tab);
                });
            });

            testButton.addEventListener('click', testEndpoint);

            function openSettings() {
                settingsModal.classList.remove('hidden');
                settingsModal.classList.add('flex');
            }
            settingsBtn.addEventListener('click', openSettings);
            if (settingsBtnSidebar) {
                settingsBtnSidebar.addEventListener('click', openSettings);
            }

            function exportOpenApiJson() {
                try {
                    const timestamp = new Date().toISOString().split('T')[0];
                    const filename = `openapi-${apiData?.info?.title?.toLowerCase().replace(/\s+/g, '-') || 'api'}-${timestamp}.json`;
                    const dataStr = JSON.stringify(apiData, null, 2);
                    const dataBlob = new Blob([dataStr], { type: 'application/json' });
                    const link = document.createElement('a');
                    link.href = URL.createObjectURL(dataBlob);
                    link.download = filename;
                    link.click();
                    showNotification(`OpenAPI JSON exported as ${filename}`, 'success', 3000);
                } catch (error) {
                    showNotification('Failed to export OpenAPI JSON', 'error', 3000);
                    console.error('Export error:', error);
                }
            }
            const exportJsonBtn = document.getElementById('exportJsonBtn');
            const exportJsonBtnMobile = document.getElementById('exportJsonBtnMobile');
            if (exportJsonBtn) {
                exportJsonBtn.addEventListener('click', exportOpenApiJson);
            }
            if (exportJsonBtnMobile) {
                exportJsonBtnMobile.addEventListener('click', exportOpenApiJson);
            }
            closeSettings.addEventListener('click', () => {
                settingsModal.classList.add('hidden');
                settingsModal.classList.remove('flex');
            });

            darkModeToggle.addEventListener('click', () => toggleSetting('darkMode'));
            compactModeToggle.addEventListener('click', () => toggleSetting('compactMode'));

            document.querySelectorAll('.theme-color-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const selectedTheme = e.target.getAttribute('data-theme');
                    changeThemeColor(selectedTheme);
                });
            });

            function openAuth() {
                loadAuthentication();
                authModal.classList.remove('hidden');
                authModal.classList.add('flex');
            }
            authBtn.addEventListener('click', openAuth);
            if (authBtnDesktop) {
                authBtnDesktop.addEventListener('click', openAuth);
            }
            closeAuth.addEventListener('click', () => {
                authModal.classList.add('hidden');
                authModal.classList.remove('flex');
            });

            authType.addEventListener('change', updateAuthInputs);
            saveAuth.addEventListener('click', saveAuthentication);

            mobileMenuBtn.addEventListener('click', toggleMobileSidebar);
            sidebarOverlay.addEventListener('click', closeMobileSidebar);

            document.addEventListener('click', (e) => {
                if (e.target.closest('[data-endpoint-id]') && window.innerWidth <= 768) {
                    closeMobileSidebar();
                }
            });

            window.addEventListener('resize', () => {
                if (window.innerWidth > 768) {
                    closeMobileSidebar();
                }
            });

            [settingsModal, authModal].forEach(modal => {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    }
                });
            });

            let formSaveTimeout;
            document.addEventListener('input', (e) => {
                const target = e.target;

                if (target.matches('[name^="param_"]')) {
                    clearTimeout(formSaveTimeout);
                    formSaveTimeout = setTimeout(saveFormState, 300)
                }
            });
        }

        function hexToRgb(hex) {
            const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
            return result ? {
                r: parseInt(result[1], 16),
                g: parseInt(result[2], 16),
                b: parseInt(result[3], 16)
            } : null;
        }

        function changeThemeColor(themeName) {
            if (!themes[themeName]) return;
            const newTheme = themes[themeName];
            currentTheme = themeName;

            localStorage.setItem('theme-color', themeName);

            tailwind.config.theme.extend.colors.accent = newTheme.accent;
            tailwind.config.theme.extend.colors['accent-hover'] = newTheme.accentHover;
            tailwind.config.theme.extend.colors['accent-light'] = newTheme.accentLight;

            document.documentElement.style.setProperty('--accent-color', newTheme.accent);
            document.documentElement.style.setProperty('--accent-hover-color', newTheme.accentHover);
            document.documentElement.style.setProperty('--accent-light-color', newTheme.accentLight);

            const accentRgb = hexToRgb(newTheme.accent);
            document.documentElement.style.setProperty('--accent-dark-bg', `rgba(${accentRgb.r}, ${accentRgb.g}, ${accentRgb.b}, 0.2)`);

            document.querySelectorAll('.theme-color-btn').forEach(btn => {
                const theme = btn.getAttribute('data-theme');
                if (theme === themeName) {
                    btn.style.border = '3px solid ' + newTheme.accent;
                    btn.style.boxShadow = '0 0 0 2px rgba(255,255,255,0.8)';
                } else {
                    btn.style.border = '2px solid';
                    btn.style.boxShadow = 'none';
                }
            });
        }

        function initThemeColor() {
            const savedTheme = localStorage.getItem('theme-color') || 'green';

            const currentColors = themes[savedTheme];
            document.documentElement.style.setProperty('--accent-color', currentColors.accent);
            document.documentElement.style.setProperty('--accent-hover-color', currentColors.accentHover);
            document.documentElement.style.setProperty('--accent-light-color', currentColors.accentLight);

            const accentRgb = hexToRgb(currentColors.accent);
            document.documentElement.style.setProperty('--accent-dark-bg', `rgba(${accentRgb.r}, ${accentRgb.g}, ${accentRgb.b}, 0.2)`);

            document.querySelectorAll('.theme-color-btn').forEach(btn => {
                const theme = btn.getAttribute('data-theme');
                if (theme === savedTheme) {
                    btn.style.border = '3px solid ' + themes[savedTheme].accent;
                    btn.style.boxShadow = '0 0 0 2px rgba(255,255,255,0.8)';
                } else {
                    btn.style.border = '2px solid';
                    btn.style.boxShadow = 'none';
                }
            });
        }

        function getEndpointDescription(endpoint) {
            return endpoint.description || endpoint.summary || 'No description available';
        }

        function getRequestBodyExample(endpoint) {
            return endpoint.requestBody?.example || null;
        }

        function getEndpointResponses(endpoint) {
            return endpoint.responses || {};
        }

        function toggleMobileSidebar() {
            sidebar.classList.toggle('mobile-open');
            sidebarOverlay.classList.toggle('active');
        }
        function closeMobileSidebar() {
            sidebar.classList.remove('mobile-open');
            sidebarOverlay.classList.remove('active');
        }

        let isChatOpen = false;
        let isTyping = false;
        function setupSidebarResize() {
            const resizeHandle = document.getElementById('resizeHandle');
            const chatSidebar = document.getElementById('chatSidebar');
            let isResizing = false;
            if (resizeHandle) {
                resizeHandle.addEventListener('mousedown', (e) => {
                    isResizing = true;
                    document.body.style.cursor = 'col-resize';
                    document.body.style.userSelect = 'none';
                    const startX = e.clientX;
                    const startWidth = parseInt(window.getComputedStyle(chatSidebar).width, 10);
                    function handleMouseMove(e) {
                        if (!isResizing) return;
                        const deltaX = startX - e.clientX;
                        const newWidth = startWidth + deltaX;

                        const minWidth = 280;
                        const maxWidth = 600;
                        const constrainedWidth = Math.min(Math.max(newWidth, minWidth), maxWidth);
                        chatSidebar.style.width = constrainedWidth + 'px';
                    }
                    function handleMouseUp() {
                        isResizing = false;
                        document.body.style.cursor = '';
                        document.body.style.userSelect = '';
                        document.removeEventListener('mousemove', handleMouseMove);
                        document.removeEventListener('mouseup', handleMouseUp);
                    }
                    document.addEventListener('mousemove', handleMouseMove);
                    document.addEventListener('mouseup', handleMouseUp);
                    e.preventDefault();
                });
            }
        }
        function setupLeftSidebarResize() {
            const leftResizeHandle = document.getElementById('leftResizeHandle');
            const leftSidebar = document.getElementById('sidebar');
            let isResizing = false;
            leftResizeHandle.addEventListener('mousedown', (e) => {
                isResizing = true;
                document.body.style.cursor = 'col-resize';
                document.body.style.userSelect = 'none';
                const startX = e.clientX;
                const startWidth = parseInt(window.getComputedStyle(leftSidebar).width, 10);
                function handleMouseMove(e) {
                    if (!isResizing) return;
                    const deltaX = e.clientX - startX;
                    const newWidth = startWidth + deltaX;

                    const minWidth = 280;
                    const maxWidth = 500;
                    const constrainedWidth = Math.min(Math.max(newWidth, minWidth), maxWidth);
                    leftSidebar.style.width = constrainedWidth + 'px';
                }
                function handleMouseUp() {
                    isResizing = false;
                    document.body.style.cursor = '';
                    document.body.style.userSelect = '';
                    document.removeEventListener('mousemove', handleMouseMove);
                    document.removeEventListener('mouseup', handleMouseUp);
                }
                document.addEventListener('mousemove', handleMouseMove);
                document.addEventListener('mouseup', handleMouseUp);
                e.preventDefault();
            });
        }
        function toggleChatSidebar() {
            const chatSidebar = document.getElementById('chatSidebar');
            const chatToggleBtn = document.getElementById('chatAIToggle');
            isChatOpen = !isChatOpen;
            if (isChatOpen) {
                chatSidebar.classList.remove('hidden');
                chatToggleBtn.classList.add('bg-accent-hover');
                chatToggleBtn.classList.remove('bg-accent');

                setTimeout(() => {
                    document.getElementById('chatInput').focus();
                }, 100);
            } else {
                chatSidebar.classList.add('hidden');
                chatToggleBtn.classList.remove('bg-accent-hover');
                chatToggleBtn.classList.add('bg-accent');
            }
        }
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        function safeScrollToBottom(container) {

            container.scrollTop = container.scrollHeight - 8;
        }
        function addChatMessage(message, sender = 'user') {
            const chatMessages = document.getElementById('chatMessages');
            const messageDiv = document.createElement('div');
            messageDiv.className = 'flex items-start gap-3 chat-message';
            if (sender === 'user') {
                messageDiv.innerHTML = `
                    <div class="chat-bubble user bg-accent rounded-lg p-3 text-sm ml-auto">
                        ${escapeHtml(message)}
                    </div>
                `;
                messageDiv.classList.add('flex-row-reverse');
            } else {

                const looksLikeMDTable =
                    /\n\|.+\|\n\|?[:\- ]+\|/s.test(message) ||
                    /(^|\n)\s*\|.*\|/m.test(message)
                if (looksLikeMDTable) {

                    const rawHtml = marked.parse(message, { gfm: true, breaks: true });

                    const wrapTables = (html) => {
                        const div = document.createElement('div');
                        div.innerHTML = html;
                        div.querySelectorAll('table').forEach((tbl) => {
                            const wrap = document.createElement('div');
                            wrap.className = 'mobile-scroll-table';
                            tbl.parentNode.insertBefore(wrap, tbl);
                            wrap.appendChild(tbl);
                        });
                        return div.innerHTML;
                    };
                    const safeHtml = DOMPurify.sanitize(wrapTables(rawHtml));

                    const temp = document.createElement('div');
                    temp.innerHTML = safeHtml;
                    temp.querySelectorAll('h1').forEach(el => el.classList.add('font-bold', 'text-2xl', 'mt-4', 'mb-2'));
                    temp.querySelectorAll('h2').forEach(el => el.classList.add('font-bold', 'text-xl', 'mt-4', 'mb-2'));
                    temp.querySelectorAll('h3').forEach(el => el.classList.add('font-bold', 'text-lg', 'mt-4', 'mb-2'));
                    temp.querySelectorAll('pre').forEach(el => el.classList.add('bg-[#202020]', 'text-gray-100', 'p-3', 'rounded-md', 'my-2', 'overflow-x-auto'));
                    temp.querySelectorAll('code:not(pre code)').forEach(el => el.classList.add('bg-gray-200', 'dark:bg-black', 'px-1', 'py-0.5', 'rounded', 'text-xs', 'font-mono'));
                    const finalHtml = temp.innerHTML;
                    messageDiv.innerHTML = `
                    <div class="w-6 h-6 bg-accent rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="chat-bubble bg-white dark:bg-[#171717] rounded-lg p-3 text-sm text-gray-900 dark:text-white max-w-full flex-1 prose prose-sm max-w-none chat-md">
                        ${finalHtml}
                    </div>
                    `;
                } else {

                    let formattedMessage = escapeHtml(message);

                    formattedMessage = formattedMessage.replace(/^### (.*$)/gm, '<h3 class="font-bold text-lg mt-4 mb-2">$1</h3>');
                    formattedMessage = formattedMessage.replace(/^## (.*$)/gm, '<h2 class="font-bold text-xl mt-4 mb-2">$1</h2>');
                    formattedMessage = formattedMessage.replace(/^# (.*$)/gm, '<h1 class="font-bold text-2xl mt-4 mb-2">$1</h1>');

                    formattedMessage = formattedMessage.replace(/```(\w+)?\n([\s\S]*?)```/g, '<pre class="bg-[#202020] text-gray-100 p-3 rounded-md my-2 overflow-x-auto"><code class="text-sm">$2</code></pre>');

                    formattedMessage = formattedMessage.replace(/`([^`]+)`/g, '<code class="bg-gray-200 dark:bg-black px-1 py-0.5 rounded text-xs font-mono">$1</code>');

                    formattedMessage = formattedMessage.replace(/\*\*(.*?)\*\*/g, '<strong class="font-bold">$1</strong>');
                    formattedMessage = formattedMessage.replace(/\*(.*?)\*/g, '<em class="italic">$1</em>');

                    formattedMessage = formattedMessage.replace(/^- (.*$)/gm, '<li class="ml-4 list-disc list-inside">$1</li>');
                    formattedMessage = formattedMessage.replace(/^(\d+)\. (.*$)/gm, '<li class="ml-4 list-decimal list-inside">$2</li>');

                    formattedMessage = formattedMessage.replace(/\n\n/g, '</p><p class="mt-2">');
                    formattedMessage = formattedMessage.replace(/\n/g, '<br>');
                    if (!formattedMessage.startsWith('<h') && !formattedMessage.startsWith('<pre>')) {
                        formattedMessage = '<p>' + formattedMessage + '</p>';
                    }
                    messageDiv.innerHTML = `
                    <div class="w-6 h-6 bg-accent rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="chat-bubble bg-white dark:bg-[#171717] rounded-lg p-3 text-sm text-gray-900 dark:text-white max-w-full flex-1 prose prose-sm max-w-none">
                        ${formattedMessage}
                    </div>
                    `;
                }
            }
            chatMessages.appendChild(messageDiv);

            setTimeout(() => safeScrollToBottom(chatMessages), 20);
        }
        function showTypingIndicator() {
            const chatMessages = document.getElementById('chatMessages');
            const typingDiv = document.createElement('div');
            typingDiv.className = 'flex items-start gap-3';
            typingDiv.id = 'typingIndicator';
            typingDiv.innerHTML = `
                <div class="w-6 h-6 bg-accent rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div class="bg-white dark:bg-[#171717] rounded-lg p-3">
                    <div class="chat-typing">
                        <div class="chat-typing-dot"></div>
                        <div class="chat-typing-dot"></div>
                        <div class="chat-typing-dot"></div>
                    </div>
                </div>
            `;
            chatMessages.appendChild(typingDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        function hideTypingIndicator() {
            const typingIndicator = document.getElementById('typingIndicator');
            if (typingIndicator) {
                typingIndicator.remove();
            }
        }
        async function sendAIRequest(userMessage) {
            if (isTyping) return;
            isTyping = true;
            showTypingIndicator();
            try {

                const chatRequest = {
                    message: userMessage
                    // Backend will auto-provide complete API context via getAPIContext()
                    // No need to send context or endpoint from frontend
                };

                // Debug: Log the chat request payload
                console.log('🔍 Frontend Chat Request Payload:', {
                    message: chatRequest.message,
                    note: 'Backend will auto-provide complete API context'
                });

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const response = await fetch(`${window.location.origin}${config.docsPath || '/docs'}/chat`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(chatRequest)
                });
                const data = await response.json();
                hideTypingIndicator();
                if (data.error) {

                    addChatMessage(`Sorry, I encountered an error: ${data.error}`, 'ai');
                } else {

                    addChatMessage(data.response || 'Sorry, I couldn\'t generate a response.', 'ai');
                }
            } catch (error) {
                console.error('Chat error:', error);
                hideTypingIndicator();

                addChatMessage('Sorry, I\'m having trouble connecting to the AI service right now. Please try again later.', 'ai');
            }
            isTyping = false;
        }
        function sendChatMessage() {
            const chatInput = document.getElementById('chatInput');
            const message = chatInput.value.trim();
            if (!message || isTyping) return;

            addChatMessage(message, 'user');

            chatInput.value = '';

            try { chatInput.style.height = '50px'; chatInput.style.overflow = 'hidden'; chatInput.classList.remove('rounded-2xl'); chatInput.classList.add('rounded-full'); } catch (e) {}

            try { chatInput.focus(); } catch (e) {}

            sendAIRequest(message);
        }

        let monacoEditor = null;
        let configBodyEditor = null;

        function initMonacoEditor() {
            require.config({ paths: { vs: 'https://cdn.jsdelivr.net/npm/monaco-editor@0.44.0/min/vs' } });
            require(['vs/editor/editor.main'], function () {
                const container = document.getElementById('testBodyInput');
                if (container) {
                    monacoEditor = monaco.editor.create(container, {
                        value: '{\n  \n}',
                        language: 'json',
                        theme: document.documentElement.classList.contains('dark') ? 'vs-dark' : 'vs',
                        automaticLayout: true,
                        minimap: { enabled: false },
                        scrollBeyondLastLine: false,
                        wordWrap: 'on',
                        lineNumbers: 'on',
                        glyphMargin: false,
                        folding: true,
                        lineDecorationsWidth: 0,
                        lineNumbersMinChars: 3,
                        fontSize: 14,
                        fontFamily: 'ui-monospace, SFMono-Regular, "SF Mono", Consolas, "Liberation Mono", Menlo, monospace'
                    });

                    let monacoSaveTimeout;
                    monacoEditor.onDidChangeModelContent(() => {
                        clearTimeout(monacoSaveTimeout);
                        monacoSaveTimeout = setTimeout(saveFormState, 300)
                    });

                    const observer = new MutationObserver(function(mutations) {
                        mutations.forEach(function(mutation) {
                            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                                const isDark = document.documentElement.classList.contains('dark');
                                monaco.editor.setTheme(isDark ? 'vs-dark' : 'vs');
                            }
                        });
                    });
                    observer.observe(document.documentElement, { attributes: true });
                }
            });
        }

        function initConfigBodyEditor() {
            if (!window.monaco || configBodyEditor) return;
            const container = document.getElementById('configBodyEditor');
            if (container) {
                configBodyEditor = monaco.editor.create(container, {
                    value: '{\n  \n}',
                    language: 'json',
                    theme: document.documentElement.classList.contains('dark') ? 'vs-dark' : 'vs',
                    automaticLayout: true,
                    minimap: { enabled: false },
                    scrollBeyondLastLine: false,
                    wordWrap: 'on',
                    lineNumbers: 'on',
                    glyphMargin: false,
                    folding: true,
                    lineDecorationsWidth: 0,
                    lineNumbersMinChars: 3,
                    fontSize: 14,
                    fontFamily: 'ui-monospace, SFMono-Regular, "SF Mono", Consolas, "Liberation Mono", Menlo, monospace'
                });

                const observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                            if (configBodyEditor) {
                                const isDark = document.documentElement.classList.contains('dark');
                                monaco.editor.setTheme(isDark ? 'vs-dark' : 'vs');
                            }
                        }
                    });
                });
                observer.observe(document.documentElement, { attributes: true });
            }
        }

        let currentMode = 'docs'
        function switchMode(mode) {
            currentMode = mode;

            document.querySelectorAll('.mode-toggle-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelector(`[data-mode="${mode}"]`).classList.add('active');

            const docsContent = document.getElementById('docsContent');
            const scenarioContent = document.getElementById('scenarioContent');
            if (mode === 'docs') {
                docsContent.style.display = 'block';
                scenarioContent.style.display = 'none';

                document.getElementById('endpointsContainer').style.display = 'block';
                document.getElementById('searchInput').style.display = 'block';
                document.getElementById('searchInput').parentElement.style.display = 'block';
            } else if (mode === 'scenario') {
                docsContent.style.display = 'none';
                scenarioContent.style.display = 'block';

                document.getElementById('endpointsContainer').style.display = 'block';
                document.getElementById('searchInput').style.display = 'block';
                document.getElementById('searchInput').parentElement.style.display = 'block';

                document.getElementById('searchInput').placeholder = 'Search endpoints to add to scenario...';
            }

            localStorage.setItem('bytedocs-mode', mode);
        }
        function initModeToggle() {

            document.getElementById('docsMode').addEventListener('click', () => switchMode('docs'));
            document.getElementById('scenarioMode').addEventListener('click', () => switchMode('scenario'));

            const savedMode = localStorage.getItem('bytedocs-mode');
            if (savedMode && ['docs', 'scenario'].includes(savedMode)) {
                switchMode(savedMode);
            } else {
                switchMode('docs')
            }
        }

        function switchScenarioTab(tab) {

            const informationTab = document.getElementById('informationTab');
            const endpointsTab = document.getElementById('endpointsTab');
            const informationContent = document.getElementById('informationTabContent');
            const endpointsContent = document.getElementById('endpointsTabContent');

            informationTab.classList.remove('border-accent', 'text-accent', 'bg-accent/5');
            informationTab.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400', 'hover:text-gray-700', 'dark:hover:text-gray-300', 'hover:border-gray-300');
            endpointsTab.classList.remove('border-accent', 'text-accent', 'bg-accent/5');
            endpointsTab.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400', 'hover:text-gray-700', 'dark:hover:text-gray-300', 'hover:border-gray-300');

            informationContent.classList.add('hidden');
            endpointsContent.classList.add('hidden');

            if (tab === 'information') {
                informationTab.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400', 'hover:text-gray-700', 'dark:hover:text-gray-300', 'hover:border-gray-300');
                informationTab.classList.add('border-accent', 'text-accent', 'bg-accent/5');
                informationContent.classList.remove('hidden');
            } else if (tab === 'endpoints') {
                endpointsTab.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400', 'hover:text-gray-700', 'dark:hover:text-gray-300', 'hover:border-gray-300');
                endpointsTab.classList.add('border-accent', 'text-accent', 'bg-accent/5');
                endpointsContent.classList.remove('hidden');
            }
        }

        let scenarios = [];
        let currentScenario = null;
        let isEditingScenario = false;

        function loadScenarios() {
            const savedScenarios = localStorage.getItem('bytedocs-scenarios');
            if (savedScenarios) {
                scenarios = JSON.parse(savedScenarios);
            }
            renderScenariosGrid();
        }

        function saveScenarios() {
            localStorage.setItem('bytedocs-scenarios', JSON.stringify(scenarios));
        }

        function renderScenariosGrid() {

            filteredScenarios = [...scenarios];

            const searchInput = document.getElementById('scenarioSearchInput');
            if (searchInput) searchInput.value = '';
            const grid = document.getElementById('scenariosGrid');
            const addCard = document.getElementById('addScenarioCard');

            const existingCards = grid.querySelectorAll('.scenario-card');
            existingCards.forEach(card => card.remove());

            hideNoResultsMessage();

            scenarios.forEach((scenario, index) => {
                const card = createScenarioCard(scenario, index);
                grid.insertBefore(card, addCard);
            });
        }

        const scenarioIcons = [
            `<svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
            `<svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/></svg>`,
            `<svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/></svg>`,
            `<svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 01-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4zm9 1a1 1 0 010-2h4a1 1 0 011 1v4a1 1 0 01-2 0V6.414l-2.293 2.293a1 1 0 11-1.414-1.414L13.586 5H12zm-9 7a1 1 0 012 0v1.586l2.293-2.293a1 1 0 111.414 1.414L6.414 15H8a1 1 0 010 2H4a1 1 0 01-1-1v-4zm13-1a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 010-2h1.586l-2.293-2.293a1 1 0 111.414-1.414L15.586 13H14a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>`,
            `<svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V4a2 2 0 00-2-2H6zm1 2a1 1 0 000 2h6a1 1 0 100-2H7zm6 7a1 1 0 011 1v3a1 1 0 11-2 0v-3a1 1 0 011-1zm-3 3a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zm-4 1a1 1 0 011 1v.01a1 1 0 11-2 0V16a1 1 0 011-1z" clip-rule="evenodd"/></svg>`,
            `<svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/></svg>`,
            `<svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"/></svg>`,
            `<svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"/><path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"/></svg>`,
            `<svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/></svg>`,
            `<svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/></svg>`,
        ];

        const scenarioGradients = [
            'from-pink-500 to-rose-500',
            'from-purple-500 to-indigo-500', 
            'from-blue-500 to-cyan-500',
            'from-green-500 to-emerald-500',
            'from-yellow-500 to-orange-500',
            'from-red-500 to-pink-500',
            'from-indigo-500 to-purple-500',
            'from-cyan-500 to-blue-500',
            'from-emerald-500 to-teal-500',
            'from-orange-500 to-red-500',
            'from-violet-500 to-purple-500',
            'from-teal-500 to-green-500',
            'from-amber-500 to-yellow-500',
            'from-rose-500 to-pink-500'
        ];

        function getScenarioVisuals(scenarioName) {

            let hash = 0;
            for (let i = 0; i < scenarioName.length; i++) {
                const char = scenarioName.charCodeAt(i);
                hash = ((hash << 5) - hash) + char;
                hash = hash & hash
            }
            const iconIndex = Math.abs(hash) % scenarioIcons.length;
            const gradientIndex = Math.abs(hash >> 8) % scenarioGradients.length;
            return {
                icon: scenarioIcons[iconIndex],
                gradient: scenarioGradients[gradientIndex]
            };
        }

        function createScenarioCard(scenario, index) {
            const card = document.createElement('div');
            card.className = 'scenario-card bg-white dark:bg-[#171717] border border-gray-200 dark:border-[#2c2d2d] rounded-lg p-6 hover:shadow-lg transition-all duration-200 cursor-pointer';

            card.addEventListener('click', (e) => {

                if (e.target.closest('button')) return;
                showScenarioDetails(index);
            });
            const executionMode = scenario.executionMode || 'waterfall';
            const isParallel = executionMode === 'parallel';

            const visuals = getScenarioVisuals(scenario.name);

            const modeConfig = isParallel ? {
                badgeColor: 'bg-orange-500',
                badgeText: 'PARALLEL'
            } : {
                badgeColor: 'bg-blue-500',
                badgeText: 'WATERFALL'
            };
            card.innerHTML = `
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br ${visuals.gradient} rounded-lg flex items-center justify-center">
                            ${visuals.icon}
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="font-semibold text-gray-900 dark:text-white">${scenario.name}</h3>
                                <span class="text-xs px-2 py-0.5 ${modeConfig.badgeColor} text-white rounded-full font-medium">${modeConfig.badgeText}</span>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">${scenario.requests.length} request${scenario.requests.length !== 1 ? 's' : ''}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200" title="Export JSON" onclick="exportScenario(${index})">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </button>
                        <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200" title="Edit" onclick="editScenario(${index})">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button class="text-gray-400 hover:text-red-500 transition-colors duration-200" title="Delete" onclick="deleteScenario(${index})">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="space-y-2 mb-4">
                    ${scenario.requests.slice(0, 3).map(req => `
                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                            <span class="w-2 h-2 ${getMethodColor(req.method)} rounded-full"></span>
                            <span class="method-${req.method.toLowerCase()}">${req.method}</span>
                            <span>${req.path}</span>
                        </div>
                    `).join('')}
                    ${scenario.requests.length > 3 ? `<p class="text-xs text-gray-500 dark:text-gray-400">+${scenario.requests.length - 3} more requests</p>` : ''}
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500 dark:text-gray-400">${scenario.description || 'No description'}</span>
                    <button class="bg-accent hover:bg-accent-hover text-white px-3 py-1.5 rounded text-xs font-medium transition-colors duration-200" onclick="runScenario(${index})">
                        Run Scenario
                    </button>
                </div>
            `;
            return card;
        }

        function getMethodColor(method) {
            switch(method.toUpperCase()) {
                case 'GET': return 'bg-blue-500';
                case 'POST': return 'bg-green-500';
                case 'PUT': return 'bg-yellow-500';
                case 'PATCH': return 'bg-purple-500';
                case 'DELETE': return 'bg-red-500';
                default: return 'bg-gray-500';
            }
        }

        function resetToCleanCreateState() {
            isEditingScenario = false;
            currentScenario = { name: '', description: '', requests: [], variables: {}, authentication: { type: 'none' } };

            document.getElementById('scenarioName').value = '';
            document.getElementById('scenarioDescription').value = '';
            const endpointSearch = document.getElementById('endpointSearch');
            if (endpointSearch) endpointSearch.value = '';

            const waterfallMode = document.getElementById('waterfallMode');
            if (waterfallMode) waterfallMode.checked = true;

            const container = document.getElementById('scenarioRequests');
            if (container) {
                const existingRequests = container.querySelectorAll('.scenario-request-item');
                existingRequests.forEach(item => item.remove());
            }

            const emptyMessage = document.getElementById('emptyScenarioMessage');
            if (emptyMessage) emptyMessage.style.display = 'block';

            updateLeftScenarioButton();
        }

        function openScenarioModal(scenario = null) {
            const modal = document.getElementById('scenarioModal');
            const title = document.getElementById('scenarioModalTitle');
            if (scenario) {

                isEditingScenario = true;
                currentScenario = { ...scenario }
                title.textContent = 'Edit Scenario';
                document.getElementById('scenarioName').value = scenario.name;
                document.getElementById('scenarioDescription').value = scenario.description || '';

                const executionMode = scenario.executionMode || 'waterfall';
                const waterfallMode = document.getElementById('waterfallMode');
                const parallelMode = document.getElementById('parallelMode');
                if (executionMode === 'parallel') {
                    if (parallelMode) parallelMode.checked = true;
                } else {
                    if (waterfallMode) waterfallMode.checked = true;
                }
                renderScenarioRequests(scenario.requests || []);
            } else {

                title.textContent = isEditingScenario ? 'Edit Scenario' : 'Create New Scenario';

                document.getElementById('scenarioName').value = currentScenario.name || '';
                document.getElementById('scenarioDescription').value = currentScenario.description || '';

                const executionMode = currentScenario.executionMode || 'waterfall';
                const waterfallMode = document.getElementById('waterfallMode');
                const parallelMode = document.getElementById('parallelMode');
                if (executionMode === 'parallel') {
                    if (parallelMode) parallelMode.checked = true;
                } else {
                    if (waterfallMode) waterfallMode.checked = true;
                }

                renderScenarioRequests(currentScenario.requests || []);
            }

            loadScenarioAuthentication();

            updateLeftScenarioButton();

            switchScenarioTab('information');

            populateAvailableEndpoints();
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function populateAvailableEndpoints(searchQuery = '') {
            const container = document.getElementById('availableEndpoints');
            container.innerHTML = '';
            const query = searchQuery.toLowerCase();

            Object.keys(transformedApiData).forEach(category => {
                const categoryEndpoints = transformedApiData[category].filter(endpoint => {
                    if (!query) return true;
                    return (
                        endpoint.path.toLowerCase().includes(query) ||
                        endpoint.method.toLowerCase().includes(query) ||
                        (endpoint.title && endpoint.title.toLowerCase().includes(query)) ||
                        category.toLowerCase().includes(query)
                    );
                });
                if (categoryEndpoints.length === 0) return;
                const categoryDiv = document.createElement('div');
                categoryDiv.className = 'mb-2';
                categoryDiv.innerHTML = `<h4 class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">${category.toUpperCase()}</h4>`;
                categoryEndpoints.forEach(endpoint => {

                    const isSelected = currentScenario && currentScenario.requests && 
                        currentScenario.requests.some(req => 
                            req.method === endpoint.method && req.path === endpoint.path
                        );
                    const endpointBtn = document.createElement('button');

                    if (isSelected) {
                        endpointBtn.className = 'w-full text-left p-2 text-xs border border-accent bg-accent/10 rounded mb-1 opacity-60 cursor-not-allowed transition-colors duration-200';
                        endpointBtn.disabled = true;
                    } else {
                        endpointBtn.className = 'w-full text-left p-2 text-xs border border-gray-200 dark:border-[#2c2d2d] rounded mb-1 hover:bg-gray-50 dark:hover:bg-[#2c2d2d] transition-colors duration-200';
                    }

                    const pathText = query ? highlightMatch(endpoint.path, query) : endpoint.path;
                    endpointBtn.innerHTML = `
                        <div class="flex items-center gap-2">
                            <span class="method-${endpoint.method.toLowerCase()} text-xs px-1 py-0.5 rounded">${endpoint.method}</span>
                            <span class="text-gray-900 dark:text-white truncate">${pathText}</span>
                            ${isSelected ? '<span class="ml-auto text-xs text-accent font-medium">✓</span>' : ''}
                        </div>
                    `;
                    if (!isSelected) {
                        endpointBtn.onclick = () => addEndpointToScenario(endpoint);
                    }
                    categoryDiv.appendChild(endpointBtn);
                });
                container.appendChild(categoryDiv);
            });

            if (query && container.children.length === 0) {
                container.innerHTML = '<p class="text-xs text-gray-500 dark:text-gray-400 text-center py-4">No endpoints found</p>';
            }
        }

        function highlightMatch(text, query) {
            if (!query) return text;
            const regex = new RegExp(`(${query})`, 'gi');
            return text.replace(regex, '<mark class="bg-yellow-200 dark:bg-yellow-800">$1</mark>');
        }

        function addEndpointToScenario(endpoint) {
            if (!currentScenario.requests) {
                currentScenario.requests = [];
            }

            const exists = currentScenario.requests.some(req => 
                req.method === endpoint.method && req.path === endpoint.path
            );
            if (!exists) {

                let exampleBody = null;
                if (['POST', 'PUT', 'PATCH'].includes(endpoint.method)) {
                    if (endpoint.requestBody && endpoint.requestBody.example) {
                        exampleBody = endpoint.requestBody.example;
                    } else if (endpoint.requestBody && endpoint.requestBody.content && endpoint.requestBody.content['application/json'] && endpoint.requestBody.content['application/json'].example) {
                        exampleBody = endpoint.requestBody.content['application/json'].example;
                    }
                }
                currentScenario.requests.push({
                    id: endpoint.id,
                    method: endpoint.method,
                    path: endpoint.path,
                    title: endpoint.title,
                    originalEndpoint: endpoint,
                    config: {
                        enabled: true,
                        timeout: 30000,
                        retries: 0,
                        parameters: {},
                        headers: {},
                        body: exampleBody,
                        useExampleBody: exampleBody ? true : false
                    }
                });
                renderScenarioRequests(currentScenario.requests);

                const searchQuery = document.getElementById('endpointSearch') ? document.getElementById('endpointSearch').value : '';
                populateAvailableEndpoints(searchQuery);
            } else {
                showNotification('Endpoint already exists in scenario', 'error', 3000);
            }
        }

        function closeScenarioModal() {
            const modal = document.getElementById('scenarioModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');

        }

        function resetScenarioForm() {
            if (confirm('Are you sure you want to reset the entire form? All unsaved changes will be lost.')) {
                currentScenario = { name: '', description: '', requests: [], variables: {} };
                isEditingScenario = false;

                document.getElementById('scenarioName').value = '';
                document.getElementById('scenarioDescription').value = '';
                document.getElementById('endpointSearch').value = '';

                renderScenarioRequests([]);

                populateAvailableEndpoints();

                document.getElementById('scenarioModalTitle').textContent = 'Create New Scenario';

                updateLeftScenarioButton();
                showNotification('Form has been reset', 'info');
            }
        }

        function deleteCurrentScenario() {
            if (!isEditingScenario || !currentScenario.id) {
                showNotification('No scenario to delete', 'error');
                return;
            }
            
            if (confirm(`Are you sure you want to delete the scenario "${currentScenario.name}"? This action cannot be undone.`)) {

                const index = scenarios.findIndex(s => s.id === currentScenario.id);
                if (index !== -1) {
                    scenarios.splice(index, 1);
                    saveScenarios();
                    renderScenariosGrid();
                    closeScenarioModal();
                    showNotification('Scenario deleted successfully', 'success');
                } else {
                    showNotification('Scenario not found', 'error');
                }
            }
        }

        function updateLeftScenarioButton() {
            const button = document.getElementById('leftScenarioButton');
            if (isEditingScenario) {
                button.textContent = 'Delete';
                button.onclick = deleteCurrentScenario;
            } else {
                button.textContent = 'Reset Form';
                button.onclick = resetScenarioForm;
            }
        }

        function renderScenarioRequests(requests) {
            const container = document.getElementById('scenarioRequests');
            const emptyMessage = document.getElementById('emptyScenarioMessage');

            const existingRequests = container.querySelectorAll('.scenario-request-item');
            existingRequests.forEach(item => item.remove());
            if (requests.length === 0) {
                emptyMessage.style.display = 'block';
                return;
            }
            emptyMessage.style.display = 'none';

            requests.forEach((request, index) => {
                const item = createScenarioRequestItem(request, index);
                container.appendChild(item);
            });
        }

        function createScenarioRequestItem(request, index) {
            const item = document.createElement('div');
            item.className = 'scenario-request-item bg-gray-50 dark:bg-[#2c2d2d] border border-gray-200 dark:border-[#171717] rounded-lg p-3 flex items-center justify-between';
            item.innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <span class="text-lg text-gray-400 select-none">${index + 1}.</span>
                        <span class="method-${request.method.toLowerCase()} text-xs px-2 py-1 rounded">${request.method}</span>
                    </div>
                    <span class="text-sm text-gray-900 dark:text-white">${request.path}</span>
                </div>
                <div class="flex items-center gap-2">
                    <button class="text-gray-400 hover:text-gray-600 transition-colors duration-200" title="Configure" onclick="configureRequest(${index})">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </button>
                    <button class="text-gray-400 hover:text-red-500 transition-colors duration-200" title="Remove" onclick="removeRequestFromScenario(${index})">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            `;
            return item;
        }

        function initScenarioManagement() {

            loadScenarios();

            document.getElementById('createScenarioBtn').addEventListener('click', () => {

                resetToCleanCreateState();
                openScenarioModal();
            });
            document.getElementById('addScenarioCard').addEventListener('click', () => {

                resetToCleanCreateState();
                openScenarioModal();
            });
            document.getElementById('closeScenarioModal').addEventListener('click', closeScenarioModal);
            document.getElementById('cancelScenario').addEventListener('click', closeScenarioModal);
            document.getElementById('saveScenario').addEventListener('click', saveCurrentScenario);

            document.getElementById('scenarioAuthType').addEventListener('change', updateScenarioAuthInputs);

            document.getElementById('closeConfigModal').addEventListener('click', closeEndpointConfigModal);
            document.getElementById('cancelConfig').addEventListener('click', closeEndpointConfigModal);
            document.getElementById('saveConfig').addEventListener('click', saveEndpointConfiguration);
            document.getElementById('addHeader').addEventListener('click', () => addHeaderRow());
            document.getElementById('formatBody').addEventListener('click', formatRequestBody);
            document.getElementById('loadExampleBody').addEventListener('click', loadExampleRequestBody);

            let searchTimeout;
            document.addEventListener('input', (e) => {
                if (e.target.id === 'endpointSearch') {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        populateAvailableEndpoints(e.target.value);
                    }, 300);
                }

                if (e.target.id === 'scenarioName' || e.target.id === 'scenarioDescription') {
                    if (currentScenario) {
                        if (e.target.id === 'scenarioName') {
                            currentScenario.name = e.target.value;
                        } else if (e.target.id === 'scenarioDescription') {
                            currentScenario.description = e.target.value;
                        }
                    }
                }
            });

            const scenarioModal = document.getElementById('scenarioModal');
            scenarioModal.addEventListener('click', (e) => {
                if (e.target === scenarioModal) {
                    closeScenarioModal();
                }
            });

            const scenarioDetailsModal = document.getElementById('scenarioDetailsModal');
            scenarioDetailsModal.addEventListener('click', (e) => {
                if (e.target === scenarioDetailsModal) {
                    closeScenarioDetails();
                }
            });

            if (!localStorage.getItem('bytedocs-scenarios-info-shown')) {
                setTimeout(() => {
                    showNotification('Scenarios are saved to your browser\'s local storage', 'info', 5000);
                    localStorage.setItem('bytedocs-scenarios-info-shown', 'true');
                }, 2000);
            }
        }

        function showNotification(message, type = 'info', duration = 3000) {
            const notification = document.createElement('div');
            const bgColor = type === 'error' ? 'bg-red-500' : type === 'success' ? 'bg-green-500' : 'bg-blue-500';
            notification.className = `fixed top-4 right-4 ${bgColor} text-white px-4 py-2 rounded-lg shadow-lg z-50 max-w-sm`;
            notification.innerHTML = `
                <div class="flex items-center gap-2">
                    <span class="text-sm">${message}</span>
                    <button class="ml-2 text-white hover:text-gray-200" onclick="this.parentElement.parentElement.remove()">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            `;
            document.body.appendChild(notification);
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, duration);
        }

        function saveCurrentScenario() {
            console.log('Save scenario button clicked')
            const name = document.getElementById('scenarioName').value.trim();
            const description = document.getElementById('scenarioDescription').value.trim();
            const executionMode = document.querySelector('input[name="executionMode"]:checked').value;

            saveScenarioAuthentication();
            console.log('Scenario name:', name)
            console.log('Current scenario:', currentScenario)
            console.log('Execution mode:', executionMode)
            if (!name) {
                showNotification('Please enter a scenario name', 'error');
                return;
            }
            if (!currentScenario.requests || currentScenario.requests.length === 0) {
                showNotification('Please add at least one request to the scenario', 'error');
                return;
            }
            const scenario = {
                id: isEditingScenario ? currentScenario.id : Date.now(),
                name: name,
                description: description,
                executionMode: executionMode,
                requests: currentScenario.requests || [],
                variables: currentScenario.variables || {},
                authentication: currentScenario.authentication || { type: 'none' },
                created: isEditingScenario ? currentScenario.created : new Date().toISOString(),
                modified: new Date().toISOString()
            };
            console.log('Saving scenario:', scenario)
            if (isEditingScenario) {
                const index = scenarios.findIndex(s => s.id === currentScenario.id);
                if (index !== -1) {
                    scenarios[index] = scenario;
                }
            } else {
                scenarios.push(scenario);
            }
            saveScenarios();
            renderScenariosGrid();
            closeScenarioModal();

            const action = isEditingScenario ? 'updated' : 'created';
            showNotification(`Scenario "${name}" ${action} successfully`, 'success', 3000);
        }

        function editScenario(index) {
            openScenarioModal(scenarios[index]);
        }

        function deleteScenario(index) {
            const scenario = scenarios[index];
            if (confirm(`Are you sure you want to delete "${scenario.name}"?`)) {
                scenarios.splice(index, 1);
                saveScenarios();
                renderScenariosGrid();
                showNotification(`Scenario "${scenario.name}" deleted`, 'info', 3000);
            }
        }

        function exportScenario(index) {
            const scenario = scenarios[index];
            if (!scenario) return;
            const exportData = {
                name: scenario.name,
                description: scenario.description,
                executionMode: scenario.executionMode || 'waterfall',
                requests: scenario.requests || [],
                variables: scenario.variables || {},
                exported: new Date().toISOString(),
                version: '1.0'
            };
            const jsonString = JSON.stringify(exportData, null, 2);
            const blob = new Blob([jsonString], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = `${scenario.name.replace(/[^a-z0-9]/gi, '_').toLowerCase()}_scenario.json`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
            showNotification(`Scenario "${scenario.name}" exported successfully`, 'success');
        }

        function exportAllScenarios() {
            if (scenarios.length === 0) {
                showNotification('No scenarios to export', 'info');
                return;
            }
            const exportData = {
                scenarios: scenarios.map(scenario => ({
                    name: scenario.name,
                    description: scenario.description,
                    executionMode: scenario.executionMode || 'waterfall',
                    requests: scenario.requests || [],
                    variables: scenario.variables || {}
                })),
                exported: new Date().toISOString(),
                version: '1.0',
                type: 'scenarios_collection'
            };
            const jsonString = JSON.stringify(exportData, null, 2);
            const blob = new Blob([jsonString], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            const timestamp = new Date().toISOString().split('T')[0];
            link.download = `bytedocs_scenarios_${timestamp}.json`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
            showNotification(`${scenarios.length} scenarios exported successfully`, 'success');
        }

        function openImportModal() {
            const modal = document.getElementById('importModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            setupImportDragAndDrop();
        }

        function closeImportModal() {
            const modal = document.getElementById('importModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');

            document.getElementById('importProgress').classList.add('hidden');
            document.getElementById('importProgressList').innerHTML = '';
            document.getElementById('importModalFileInput').value = '';
        }

        function setupImportDragAndDrop() {
            const dropZone = document.getElementById('dropZone');
            const fileInput = document.getElementById('importModalFileInput');

            dropZone.addEventListener('click', () => {
                fileInput.click();
            });

            fileInput.addEventListener('change', (e) => {
                handleImportFiles(e.target.files);
            });

            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.classList.add('border-accent', 'bg-accent', 'bg-opacity-5');
            });
            dropZone.addEventListener('dragleave', (e) => {
                e.preventDefault();
                dropZone.classList.remove('border-accent', 'bg-accent', 'bg-opacity-5');
            });
            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.classList.remove('border-accent', 'bg-accent', 'bg-opacity-5');
                handleImportFiles(e.dataTransfer.files);
            });
        }

        function handleImportFiles(files) {
            if (files.length === 0) return;

            const progressDiv = document.getElementById('importProgress');
            const progressList = document.getElementById('importProgressList');
            progressDiv.classList.remove('hidden');
            progressList.innerHTML = '';
            let importedCount = 0;
            let totalFiles = files.length;
            Array.from(files).forEach((file, index) => {

                if (!file.name.endsWith('.json')) {
                    addProgressItem(file.name, 'error', 'Invalid file type - only JSON files are allowed');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    try {
                        const importData = JSON.parse(e.target.result);

                        if (importData.type === 'scenarios_collection' && importData.scenarios) {

                            let collectionImported = 0;
                            importData.scenarios.forEach(scenarioData => {
                                if (processScenarioImport(scenarioData)) {
                                    collectionImported++;
                                }
                            });
                            addProgressItem(file.name, 'success', `Imported ${collectionImported} scenarios from collection`);
                            importedCount += collectionImported;
                        } else {

                            if (processScenarioImport(importData)) {
                                addProgressItem(file.name, 'success', 'Scenario imported successfully');
                                importedCount++;
                            } else {
                                addProgressItem(file.name, 'error', 'Invalid scenario format');
                            }
                        }
                    } catch (error) {
                        addProgressItem(file.name, 'error', 'Invalid JSON format');
                    }

                    if (index === totalFiles - 1) {
                        setTimeout(() => {
                            if (importedCount > 0) {

                                localStorage.setItem('bytedocs-scenarios', JSON.stringify(scenarios));

                                renderScenariosGrid();
                                showNotification(`${importedCount} scenario(s) imported successfully`, 'success');

                                setTimeout(closeImportModal, 2000);
                            }
                        }, 500);
                    }
                };
                reader.readAsText(file);
            });
        }

        function processScenarioImport(importData) {

            if (!importData.name || !importData.requests || !Array.isArray(importData.requests)) {
                return false;
            }

            const existingIndex = scenarios.findIndex(s => s.name === importData.name);

            const newScenario = {
                name: importData.name,
                description: importData.description || '',
                executionMode: importData.executionMode || 'waterfall',
                requests: importData.requests.map(req => ({
                    ...req,
                    config: req.config || {
                        enabled: true,
                        timeout: 30000,
                        retries: 0,
                        parameters: {},
                        headers: {},
                        body: null,
                        useExampleBody: false
                    }
                })),
                variables: importData.variables || {}
            };

            if (existingIndex !== -1) {
                scenarios[existingIndex] = newScenario;
            } else {
                scenarios.push(newScenario);
            }
            return true;
        }

        function addProgressItem(fileName, status, message) {
            const progressList = document.getElementById('importProgressList');
            const item = document.createElement('div');
            item.className = 'flex items-center gap-2';
            const statusIcon = status === 'success' 
                ? '<svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>'
                : '<svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>';
            item.innerHTML = `
                ${statusIcon}
                <span class="font-medium text-gray-900 dark:text-white">${fileName}</span>
                <span class="text-gray-500 dark:text-gray-400">- ${message}</span>
            `;
            progressList.appendChild(item);
        }

        let currentDetailScenarioIndex = null;
        function showScenarioDetails(index) {
            const scenario = scenarios[index];
            if (!scenario) return;
            currentDetailScenarioIndex = index;
            const modal = document.getElementById('scenarioDetailsModal');

            const visuals = getScenarioVisuals(scenario.name);
            const executionMode = scenario.executionMode || 'waterfall';
            const isParallel = executionMode === 'parallel';

            document.getElementById('detailsScenarioName').textContent = scenario.name;
            document.getElementById('detailsDescription').textContent = scenario.description || 'No description provided';

            const iconDiv = document.getElementById('detailsIcon');
            iconDiv.className = `w-10 h-10 bg-gradient-to-br ${visuals.gradient} rounded-lg flex items-center justify-center`;
            iconDiv.innerHTML = visuals.icon;

            const modeSpan = document.getElementById('detailsExecutionMode');
            const modeConfig = isParallel ? {
                badgeColor: 'bg-orange-500',
                badgeText: 'PARALLEL'
            } : {
                badgeColor: 'bg-blue-500', 
                badgeText: 'WATERFALL'
            };
            modeSpan.className = `text-xs px-2 py-0.5 ${modeConfig.badgeColor} text-white rounded-full font-medium`;
            modeSpan.textContent = modeConfig.badgeText;

            const requestCount = scenario.requests.length;
            document.getElementById('detailsRequestCount').textContent = `${requestCount} request${requestCount !== 1 ? 's' : ''}`;
            document.getElementById('detailsExecMode').textContent = isParallel ? 'Parallel' : 'Waterfall';
            document.getElementById('detailsReqCount').textContent = requestCount.toString();

            const auth = scenario.authentication || { type: 'none' };
            let authDisplayText = 'No Authentication';
            switch (auth.type) {
                case 'bearer':
                    authDisplayText = auth.token ? 'Bearer Token (configured)' : 'Bearer Token (not configured)';
                    break;
                case 'basic':
                    authDisplayText = (auth.username && auth.password) ? 'Basic Auth (configured)' : 'Basic Auth (not configured)';
                    break;
                case 'apikey':
                    authDisplayText = auth.apiKey ? `API Key - ${auth.keyName || 'X-API-Key'} (configured)` : 'API Key (not configured)';
                    break;
                case 'none':
                default:
                    authDisplayText = 'No Authentication';
                    break;
            }
            document.getElementById('detailsAuthType').textContent = authDisplayText;

            const requestsList = document.getElementById('detailsRequestsList');
            requestsList.innerHTML = '';
            if (scenario.requests.length === 0) {
                requestsList.innerHTML = `
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v1M7 8h10l-5 5-5-5z"/>
                        </svg>
                        <p>No requests in this scenario</p>
                    </div>
                `;
            } else {
                scenario.requests.forEach((request, reqIndex) => {
                    const requestDiv = document.createElement('div');
                    requestDiv.className = 'bg-gray-50 dark:bg-[#2c2d2d] rounded-lg p-4 border border-gray-200 dark:border-[#3c3d3d]';
                    const methodColor = getMethodColor(request.method);
                    const configCount = Object.keys(request.config.parameters || {}).length + Object.keys(request.config.headers || {}).length;
                    const hasConfig = configCount > 0 || request.config.body;

                    let configDetails = '';

                    const timeout = request.config.timeout || 30000;
                    const retries = request.config.retries || 0;
                    const enabled = request.config.enabled !== false;
                    
                    configDetails += `
                        <div class="mt-3 pt-3 border-t border-gray-200 dark:border-[#3c3d3d]">
                            <h5 class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Configuration</h5>
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div><span class="text-gray-500 dark:text-gray-400">Status:</span> <span class="${enabled ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'}">${enabled ? 'Enabled' : 'Disabled'}</span></div>
                                <div><span class="text-gray-500 dark:text-gray-400">Timeout:</span> <span class="text-gray-700 dark:text-gray-300">${timeout}ms</span></div>
                                <div><span class="text-gray-500 dark:text-gray-400">Retries:</span> <span class="text-gray-700 dark:text-gray-300">${retries}</span></div>
                            </div>
                        </div>
                    `;

                    if (Object.keys(request.config.parameters || {}).length > 0) {
                        configDetails += `
                            <div class="mt-3 pt-3 border-t border-gray-200 dark:border-[#3c3d3d]">
                                <h5 class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Parameters (${Object.keys(request.config.parameters).length})</h5>
                                <div class="space-y-1">
                                    ${Object.entries(request.config.parameters).map(([name, value]) => `
                                        <div class="flex justify-between text-xs">
                                            <span class="text-gray-600 dark:text-gray-400 font-mono">${name}:</span>
                                            <span class="text-gray-700 dark:text-gray-300 break-all ml-2">${value}</span>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        `;
                    }

                    if (Object.keys(request.config.headers || {}).length > 0) {
                        configDetails += `
                            <div class="mt-3 pt-3 border-t border-gray-200 dark:border-[#3c3d3d]">
                                <h5 class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Custom Headers (${Object.keys(request.config.headers).length})</h5>
                                <div class="space-y-1">
                                    ${Object.entries(request.config.headers).map(([name, value]) => `
                                        <div class="flex justify-between text-xs">
                                            <span class="text-gray-600 dark:text-gray-400 font-mono">${name}:</span>
                                            <span class="text-gray-700 dark:text-gray-300 break-all ml-2">${value}</span>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        `;
                    }

                    if (request.config.body) {
                        const bodyPreview = JSON.stringify(request.config.body, null, 2);
                        const isLongBody = bodyPreview.length > 200;
                        const displayBody = isLongBody ? bodyPreview.substring(0, 200) + '...' : bodyPreview;
                        
                        configDetails += `
                            <div class="mt-3 pt-3 border-t border-gray-200 dark:border-[#3c3d3d]">
                                <h5 class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Request Body</h5>
                                <div class="bg-gray-100 dark:bg-[#1c1c1c] rounded p-2 text-xs">
                                    <pre class="text-gray-700 dark:text-gray-300 font-mono whitespace-pre-wrap">${displayBody}</pre>
                                    ${isLongBody ? '<div class="text-gray-500 dark:text-gray-400 mt-1">... (truncated)</div>' : ''}
                                </div>
                            </div>
                        `;
                    }
                    
                    requestDiv.innerHTML = `
                        <div class="flex items-center justify-between cursor-pointer hover:bg-gray-100 dark:hover:bg-[#3c3d3d] rounded p-2 -m-2 transition-colors duration-200" onclick="toggleRequestDetails(${reqIndex})">
                            <div class="flex items-center gap-3">
                                <span class="w-2 h-2 ${methodColor} rounded-full"></span>
                                <span class="font-mono text-sm font-medium method-${request.method.toLowerCase()}">${request.method}</span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">${request.path}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                ${hasConfig ? '<span class="text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded-full">Configured</span>' : ''}
                                <span class="text-xs text-gray-500 dark:text-gray-400">#${reqIndex + 1}</span>
                                <svg class="w-4 h-4 text-gray-400 transform transition-transform duration-200 collapse-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                        <div class="request-details hidden">
                            ${configDetails}
                        </div>
                    `;
                    requestsList.appendChild(requestDiv);
                });
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        function closeScenarioDetails() {
            const modal = document.getElementById('scenarioDetailsModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            currentDetailScenarioIndex = null;
        }
        
        function toggleRequestDetails(requestIndex) {
            const requestDivs = document.querySelectorAll('#detailsRequestsList .request-details');
            const collapseIcons = document.querySelectorAll('#detailsRequestsList .collapse-icon');
            
            if (requestDivs[requestIndex] && collapseIcons[requestIndex]) {
                const details = requestDivs[requestIndex];
                const icon = collapseIcons[requestIndex];
                
                if (details.classList.contains('hidden')) {

                    details.classList.remove('hidden');
                    icon.style.transform = 'rotate(180deg)';
                } else {

                    details.classList.add('hidden');
                    icon.style.transform = 'rotate(0deg)';
                }
            }
        }
        function editScenarioFromDetails() {
            if (currentDetailScenarioIndex !== null) {
                const scenarioIndex = currentDetailScenarioIndex
                const scenario = scenarios[scenarioIndex];
                
                if (scenario) {
                    closeScenarioDetails();

                    setTimeout(() => {
                        editScenario(scenarioIndex);
                    }, 100);
                } else {
                    showNotification('Scenario not found', 'error');
                }
            } else {
                showNotification('No scenario selected', 'error');
            }
        }
        function exportScenarioFromDetails() {
            if (currentDetailScenarioIndex !== null) {
                exportScenario(currentDetailScenarioIndex);
            }
        }
        function runScenarioFromDetails() {
            if (currentDetailScenarioIndex !== null) {
                closeScenarioDetails();
                runScenario(currentDetailScenarioIndex);
            }
        }

        function updateScenarioAuthInputs() {
            const type = document.getElementById('scenarioAuthType').value;
            const container = document.getElementById('scenarioAuthInputs');
            let html = '';
            const authData = currentScenario.authentication || {};
            switch (type) {
                case 'bearer':
                    html = '<input type="text" class="w-full px-2 py-1 text-xs border border-gray-300 dark:border-[#171717] rounded bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-accent" id="scenarioAuthToken" placeholder="Bearer token" value="' + (authData.token || '') + '">';
                    break;
                case 'basic':
                    html = `
                        <input type="text" class="w-full px-2 py-1 text-xs border border-gray-300 dark:border-[#171717] rounded bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-accent mb-1" id="scenarioAuthUsername" placeholder="Username" value="${authData.username || ''}">
                        <input type="password" class="w-full px-2 py-1 text-xs border border-gray-300 dark:border-[#171717] rounded bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-accent" id="scenarioAuthPassword" placeholder="Password" value="${authData.password || ''}">
                    `;
                    break;
                case 'apikey':
                    html = `
                        <input type="text" class="w-full px-2 py-1 text-xs border border-gray-300 dark:border-[#171717] rounded bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-accent mb-1" id="scenarioAuthKeyName" placeholder="Header name (e.g., X-API-Key)" value="${authData.keyName || 'X-API-Key'}">
                        <input type="text" class="w-full px-2 py-1 text-xs border border-gray-300 dark:border-[#171717] rounded bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-accent" id="scenarioAuthApiKey" placeholder="API key value" value="${authData.apiKey || ''}">
                    `;
                    break;
            }
            container.innerHTML = html;
        }
        function saveScenarioAuthentication() {
            const authType = document.getElementById('scenarioAuthType').value;
            if (!currentScenario.authentication) {
                currentScenario.authentication = {};
            }
            currentScenario.authentication.type = authType;
            switch (authType) {
                case 'bearer':
                    currentScenario.authentication.token = document.getElementById('scenarioAuthToken')?.value || '';
                    break;
                case 'basic':
                    currentScenario.authentication.username = document.getElementById('scenarioAuthUsername')?.value || '';
                    currentScenario.authentication.password = document.getElementById('scenarioAuthPassword')?.value || '';
                    break;
                case 'apikey':
                    currentScenario.authentication.keyName = document.getElementById('scenarioAuthKeyName')?.value || 'X-API-Key';
                    currentScenario.authentication.apiKey = document.getElementById('scenarioAuthApiKey')?.value || '';
                    break;
                case 'none':
                default:
                    currentScenario.authentication = { type: 'none' };
                    break;
            }
        }
        function loadScenarioAuthentication() {
            const authData = currentScenario.authentication || { type: 'none' };
            document.getElementById('scenarioAuthType').value = authData.type;
            updateScenarioAuthInputs();
        }
        function getScenarioAuthHeaders(scenarioAuth = null) {
            const auth = scenarioAuth || (currentScenario && currentScenario.authentication) || { type: 'none' };
            const headers = {};
            switch (auth.type) {
                case 'bearer':
                    if (auth.token) {
                        headers['Authorization'] = `Bearer ${auth.token}`;
                    }
                    break;
                case 'basic':
                    if (auth.username && auth.password) {
                        const credentials = btoa(`${auth.username}:${auth.password}`);
                        headers['Authorization'] = `Basic ${credentials}`;
                    }
                    break;
                case 'apikey':
                    if (auth.keyName && auth.apiKey) {
                        headers[auth.keyName] = auth.apiKey;
                    }
                    break;
            }
            return headers;
        }

        let filteredScenarios = [];
        function searchScenarios(query) {
            if (!query.trim()) {

                filteredScenarios = [...scenarios];
            } else {

                const searchTerm = query.toLowerCase();
                filteredScenarios = scenarios.filter(scenario => 
                    scenario.name.toLowerCase().includes(searchTerm) || 
                    (scenario.description && scenario.description.toLowerCase().includes(searchTerm))
                );
            }

            renderFilteredScenariosGrid();
        }
        function renderFilteredScenariosGrid() {
            const grid = document.getElementById('scenariosGrid');
            const addCard = document.getElementById('addScenarioCard');

            const existingCards = grid.querySelectorAll('.scenario-card');
            existingCards.forEach(card => card.remove());

            filteredScenarios.forEach((scenario) => {
                const originalIndex = scenarios.findIndex(s => s === scenario);
                const card = createScenarioCard(scenario, originalIndex);
                grid.insertBefore(card, addCard);
            });

            if (filteredScenarios.length === 0 && document.getElementById('scenarioSearchInput').value.trim()) {
                showNoResultsMessage();
            } else {
                hideNoResultsMessage();
            }
        }
        function showNoResultsMessage() {
            const grid = document.getElementById('scenariosGrid');
            const addCard = document.getElementById('addScenarioCard');

            const existingMessage = document.getElementById('noResultsMessage');
            if (existingMessage) existingMessage.remove();

            const noResultsDiv = document.createElement('div');
            noResultsDiv.id = 'noResultsMessage';
            noResultsDiv.className = 'col-span-full text-center py-12';
            noResultsDiv.innerHTML = `
                <div class="text-gray-400 dark:text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <h3 class="text-lg font-medium mb-2">No scenarios found</h3>
                    <p class="text-sm">Try adjusting your search terms or create a new scenario</p>
                </div>
            `;
            if (addCard) {
                addCard.classList.add('hidden');
                grid.insertBefore(noResultsDiv, addCard);
            } else {
                grid.appendChild(noResultsDiv);
            }
        }
        function hideNoResultsMessage() {
            const existingMessage = document.getElementById('noResultsMessage');
            if (existingMessage) existingMessage.remove();
            const addCard = document.getElementById('addScenarioCard');
            if (addCard) {
                addCard.classList.remove('hidden');
            }
        }

        function removeRequestFromScenario(index) {
            if (currentScenario && currentScenario.requests) {
                currentScenario.requests.splice(index, 1);
                renderScenarioRequests(currentScenario.requests);

                const searchQuery = document.getElementById('endpointSearch') ? document.getElementById('endpointSearch').value : '';
                populateAvailableEndpoints(searchQuery);
            }
        }

        function configureRequest(index) {
            const request = currentScenario.requests[index];
            if (!request) return;

            window.currentConfigIndex = index;
            window.currentConfigRequest = request;

            openEndpointConfigModal(request);
        }

        function openEndpointConfigModal(request) {
            const modal = document.getElementById('endpointConfigModal');
            const title = document.getElementById('configModalTitle');
            const subtitle = document.getElementById('configModalSubtitle');
            title.textContent = `Configure ${request.method} ${request.path}`;
            subtitle.textContent = `Customize request parameters, headers, and body for this endpoint`;

            document.getElementById('configEnabled').checked = request.config.enabled;
            document.getElementById('configTimeout').value = request.config.timeout || 30000;
            document.getElementById('configRetries').value = request.config.retries || 0;

            populateConfigParameters(request);

            populateConfigHeaders(request.config.headers || {});

            const bodySection = document.getElementById('configBodySection');
            if (['POST', 'PUT', 'PATCH'].includes(request.method)) {
                bodySection.classList.remove('hidden');
                document.getElementById('useExampleBody').checked = request.config.useExampleBody;

                if (!configBodyEditor && window.monaco) {
                    initConfigBodyEditor();
                }

                if (configBodyEditor) {
                    const bodyContent = request.config.body ? JSON.stringify(request.config.body, null, 2) : '{\n  \n}';
                    configBodyEditor.setValue(bodyContent);
                } else {

                    setTimeout(() => {
                        if (!configBodyEditor) initConfigBodyEditor();
                        if (configBodyEditor) {
                            const bodyContent = request.config.body ? JSON.stringify(request.config.body, null, 2) : '{\n  \n}';
                            configBodyEditor.setValue(bodyContent);
                        }
                    }, 100);
                }
            } else {
                bodySection.classList.add('hidden');
            }
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeEndpointConfigModal() {
            const modal = document.getElementById('endpointConfigModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function populateConfigParameters(request) {
            const container = document.getElementById('configParameters');
            container.innerHTML = '';

            const endpoint = request.originalEndpoint;
            if (!endpoint || !endpoint.parameters) return;
            endpoint.parameters.forEach((param, index) => {
                const paramDiv = document.createElement('div');
                paramDiv.className = 'space-y-2 p-3 border border-gray-200 dark:border-[#2c2d2d] rounded-md';
                const currentValue = request.config.parameters[param.name] || param.default || '';
                const isRequired = param.required ? 'Required' : 'Optional';
                paramDiv.innerHTML = `
                    <div class="flex items-center justify-between">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">${param.name}</label>
                        <span class="text-xs text-gray-500 dark:text-gray-400">${isRequired}</span>
                    </div>
                    <input type="text" 
                           value="${currentValue}" 
                           placeholder="${param.description || param.name}"
                           data-param-name="${param.name}"
                           class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-[#2c2d2d] rounded bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-accent">
                    <p class="text-xs text-gray-500 dark:text-gray-400">${param.description || 'No description'}</p>
                `;
                container.appendChild(paramDiv);
            });
            if (endpoint.parameters.length === 0) {
                container.innerHTML = '<p class="text-sm text-gray-500 dark:text-gray-400">No parameters available for this endpoint</p>';
            }
        }

        function populateConfigHeaders(headers) {
            const container = document.getElementById('configHeaders');

            const existingHeaders = container.querySelectorAll('.header-row');
            existingHeaders.forEach(header => header.remove());

            Object.entries(headers).forEach(([name, value]) => {
                addHeaderRow(name, value);
            });
        }

        function addHeaderRow(name = '', value = '') {
            const container = document.getElementById('configHeaders');
            const headerDiv = document.createElement('div');
            headerDiv.className = 'flex gap-2 header-row';
            headerDiv.innerHTML = `
                <input type="text" placeholder="Header name" value="${name}" 
                       class="flex-1 px-2 py-1 text-sm border border-gray-300 dark:border-[#2c2d2d] rounded bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-accent">
                <input type="text" placeholder="Header value" value="${value}"
                       class="flex-1 px-2 py-1 text-sm border border-gray-300 dark:border-[#2c2d2d] rounded bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-accent">
                <button class="text-red-500 hover:text-red-700 px-2" onclick="this.parentElement.remove()">×</button>
            `;

            container.insertBefore(headerDiv, container.lastElementChild);
        }

        function saveEndpointConfiguration() {
            const index = window.currentConfigIndex;
            if (index === undefined || !currentScenario.requests[index]) return;
            const request = currentScenario.requests[index];

            request.config.enabled = document.getElementById('configEnabled').checked;
            request.config.timeout = parseInt(document.getElementById('configTimeout').value) || 30000;
            request.config.retries = parseInt(document.getElementById('configRetries').value) || 0;

            const paramInputs = document.querySelectorAll('#configParameters input[data-param-name]');
            request.config.parameters = {};
            paramInputs.forEach(input => {
                const paramName = input.getAttribute('data-param-name');
                if (input.value.trim()) {
                    request.config.parameters[paramName] = input.value.trim();
                }
            });

            const headerRows = document.querySelectorAll('#configHeaders .header-row');
            request.config.headers = {};
            headerRows.forEach(row => {
                const nameInput = row.querySelector('input:first-child');
                const valueInput = row.querySelector('input:nth-child(2)');
                if (nameInput && valueInput && nameInput.value.trim() && valueInput.value.trim()) {
                    request.config.headers[nameInput.value.trim()] = valueInput.value.trim();
                }
            });

            if (['POST', 'PUT', 'PATCH'].includes(request.method)) {
                request.config.useExampleBody = document.getElementById('useExampleBody').checked;

                let bodyText = '';
                if (configBodyEditor) {
                    bodyText = configBodyEditor.getValue().trim();
                }
                if (bodyText) {
                    try {
                        request.config.body = JSON.parse(bodyText);
                    } catch (e) {
                        showNotification('Invalid JSON in request body', 'error');
                        return;
                    }
                } else {
                    request.config.body = null;
                }
            }

            renderScenarioRequests(currentScenario.requests);

            saveScenarios();
            
            closeEndpointConfigModal();
            showNotification('Configuration saved successfully', 'success');
        }

        function formatRequestBody() {
            if (!configBodyEditor) return;
            const bodyText = configBodyEditor.getValue().trim();
            if (!bodyText) return;
            try {
                const parsed = JSON.parse(bodyText);
                configBodyEditor.setValue(JSON.stringify(parsed, null, 2));
                showNotification('JSON formatted successfully', 'success');
            } catch (e) {
                showNotification('Invalid JSON format', 'error');
            }
        }

        function loadExampleRequestBody() {
            const request = window.currentConfigRequest;
            if (!request || !request.originalEndpoint) return;
            const endpoint = request.originalEndpoint;
            let exampleBody = null;
            if (endpoint.requestBody && endpoint.requestBody.example) {
                exampleBody = endpoint.requestBody.example;
            } else if (endpoint.requestBody && endpoint.requestBody.content && endpoint.requestBody.content['application/json'] && endpoint.requestBody.content['application/json'].example) {
                exampleBody = endpoint.requestBody.content['application/json'].example;
            }
            if (exampleBody) {
                if (configBodyEditor) {
                    configBodyEditor.setValue(JSON.stringify(exampleBody, null, 2));
                }
                document.getElementById('useExampleBody').checked = true;
                showNotification('Example body loaded successfully', 'success');
            } else {
                showNotification('No example body available for this endpoint', 'info');
            }
        }

        async function runScenario(index) {
            const scenario = scenarios[index];
            if (!scenario || !scenario.requests || scenario.requests.length === 0) {
                showNotification('No requests found in this scenario', 'error');
                return;
            }

            const enabledRequests = scenario.requests.filter(req => req.config.enabled !== false);
            if (enabledRequests.length === 0) {
                showNotification('No enabled requests found in this scenario', 'error');
                return;
            }
            const executionMode = scenario.executionMode || 'waterfall';
            showNotification(`Starting scenario: ${scenario.name} (${executionMode} mode)`, 'info');

            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            modal.innerHTML = `
                <div class="bg-white dark:bg-[#171717] rounded-lg shadow-xl max-w-6xl w-full mx-4 max-h-[90vh] overflow-hidden">
                    <div class="p-6 border-b border-gray-200 dark:border-[#2c2d2d] flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Running Scenario: ${scenario.name}</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">${enabledRequests.length} requests • ${executionMode} execution</p>
                        </div>
                        <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200" onclick="this.closest('.fixed').remove()">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="p-6 overflow-y-auto max-h-[70vh]">
                        <div id="scenarioResults" class="space-y-4">
                            
                        </div>
                    </div>
                    <div class="p-6 border-t border-gray-200 dark:border-[#2c2d2d] flex justify-end">
                        <button class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md" onclick="this.closest('.fixed').remove()">Close</button>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);

            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.remove();
                }
            });
            const resultsContainer = modal.querySelector('#scenarioResults');

            if (executionMode === 'parallel') {
                await executeRequestsInParallel(enabledRequests, resultsContainer, scenario.authentication);
            } else {
                await executeRequestsSequentially(enabledRequests, resultsContainer, scenario.authentication);
            }
            showNotification(`Scenario "${scenario.name}" completed`, 'success');
        }

        async function executeRequestsInParallel(requests, resultsContainer, scenarioAuth = null) {

            const resultItems = [];
            requests.forEach((request, i) => {
                const resultItem = createResultItem(request, i);
                resultsContainer.appendChild(resultItem);
                resultItems.push(resultItem);
            });

            const promises = requests.map((request, i) => executeRequest(request, i, resultItems[i], scenarioAuth));
            await Promise.allSettled(promises);
        }

        async function executeRequestsSequentially(requests, resultsContainer, scenarioAuth = null) {
            for (let i = 0; i < requests.length; i++) {
                const request = requests[i];

                const resultItem = createResultItem(request, i);
                resultsContainer.appendChild(resultItem);

                await executeRequest(request, i, resultItem, scenarioAuth);

                if (i < requests.length - 1) {
                    await new Promise(resolve => setTimeout(resolve, 1000));
                }
            }
        }

        function createResultItem(request, index) {
            const resultItem = document.createElement('div');
            resultItem.className = 'border border-gray-200 dark:border-[#2c2d2d] rounded-lg p-4';
            resultItem.innerHTML = `
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-lg text-gray-400">${index + 1}.</span>
                    <span class="method-${request.method.toLowerCase()} text-xs px-2 py-1 rounded">${request.method}</span>
                    <span class="text-sm text-gray-900 dark:text-white">${request.path}</span>
                    <div class="ml-auto">
                        <div class="w-4 h-4 border-2 border-accent border-t-transparent rounded-full animate-spin"></div>
                    </div>
                </div>
                <div class="result-content hidden">
                    
                </div>
            `;
            return resultItem;
        }

        async function executeRequest(request, index, resultItem, scenarioAuth = null) {
            try {

                const baseUrlElement = document.getElementById('baseUrlSelect') || document.querySelector('select[name="base_url"]');
                const baseUrlValue = baseUrlElement ? baseUrlElement.value : window.location.origin;
                let fullUrl = request.path;
                if (!request.path.startsWith('http')) {
                    fullUrl = baseUrlValue.replace(/\/$/, '') + (request.path.startsWith('/') ? request.path : '/' + request.path);
                }

                if (request.config.parameters) {
                    Object.entries(request.config.parameters).forEach(([name, value]) => {
                        fullUrl = fullUrl.replace(`{${name}}`, encodeURIComponent(value));
                    });
                }

                const options = {
                    method: request.method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                };

                const authToUse = scenarioAuth || window.auth;
                if (authToUse && authToUse.type !== 'none') {
                    const authHeaders = getScenarioAuthHeaders(authToUse);
                    Object.assign(options.headers, authHeaders);
                }

                if (request.config.headers) {
                    Object.assign(options.headers, request.config.headers);
                }

                if (['POST', 'PUT', 'PATCH'].includes(request.method) && request.config.body) {
                    options.body = JSON.stringify(request.config.body);
                }

                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), request.config.timeout || 30000);
                options.signal = controller.signal;
                const startTime = Date.now();
                const response = await fetch(fullUrl, options);
                clearTimeout(timeoutId);
                const endTime = Date.now();
                const responseTime = endTime - startTime;
                let responseData;
                try {
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        responseData = await response.json();
                    } else {
                        responseData = await response.text();
                    }
                } catch (parseError) {
                    responseData = await response.text();
                }

                const statusClass = response.ok ? 'text-green-600' : 'text-red-600';
                const statusText = response.ok ? 'Success' : 'Error';
                resultItem.querySelector('.ml-auto').innerHTML = `
                    <span class="${statusClass} text-sm font-medium">${response.status} ${statusText}</span>
                    <span class="text-xs text-gray-500 ml-2">${responseTime}ms</span>
                `;
                const resultContent = resultItem.querySelector('.result-content');
                resultContent.innerHTML = `
                    <div class="mt-3 p-3 bg-gray-50 dark:bg-[#2c2d2d] rounded">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Response (${response.status}) - ${fullUrl}</h4>
                        <pre class="text-xs text-gray-600 dark:text-gray-400 whitespace-pre-wrap max-h-32 overflow-y-auto">${typeof responseData === 'string' ? responseData : JSON.stringify(responseData, null, 2)}</pre>
                    </div>
                `;
                resultContent.classList.remove('hidden');
            } catch (error) {
                console.error('Request failed:', error);

                resultItem.querySelector('.ml-auto').innerHTML = `
                    <span class="text-red-600 text-sm font-medium">Failed</span>
                `;
                const resultContent = resultItem.querySelector('.result-content');
                resultContent.innerHTML = `
                    <div class="mt-3 p-3 bg-red-50 dark:bg-red-900/20 rounded">
                        <h4 class="text-sm font-medium text-red-700 dark:text-red-300 mb-2">Error</h4>
                        <pre class="text-xs text-red-600 dark:text-red-400 whitespace-pre-wrap">${error.message}</pre>
                    </div>
                `;
                resultContent.classList.remove('hidden');
            }
        }

        function initDragAndDrop() {
            let draggedEndpoint = null;

            function addDragToEndpoints() {
                const endpointItems = document.querySelectorAll('.endpoint-item');
                endpointItems.forEach(item => {
                    item.draggable = true;
                    item.addEventListener('dragstart', (e) => {
                        if (currentMode !== 'scenario') return;
                        const endpointId = item.getAttribute('data-endpoint-id');
                        const endpoint = findEndpointById(endpointId);
                        if (endpoint) {
                            draggedEndpoint = {
                                id: endpointId,
                                method: endpoint.method,
                                path: endpoint.path,
                                title: endpoint.title,
                                parameters: endpoint.parameters || [],
                                requestBody: endpoint.requestBody || null
                            };
                            e.dataTransfer.effectAllowed = 'copy';
                            item.classList.add('opacity-50');
                        }
                    });
                    item.addEventListener('dragend', (e) => {
                        item.classList.remove('opacity-50');
                        draggedEndpoint = null;
                    });
                });
            }

            function findEndpointById(endpointId) {
                for (const sectionName in transformedApiData) {
                    const endpoint = transformedApiData[sectionName].find(ep => ep.id === endpointId);
                    if (endpoint) return endpoint;
                }
                return null;
            }

            const scenarioRequests = document.getElementById('scenarioRequests');
            scenarioRequests.addEventListener('dragover', (e) => {
                if (draggedEndpoint) {
                    e.preventDefault();
                    e.dataTransfer.dropEffect = 'copy';
                    scenarioRequests.classList.add('border-accent', 'bg-accent-light', 'bg-opacity-10');
                }
            });
            scenarioRequests.addEventListener('dragleave', (e) => {

                if (!scenarioRequests.contains(e.relatedTarget)) {
                    scenarioRequests.classList.remove('border-accent', 'bg-accent-light', 'bg-opacity-10');
                }
            });
            scenarioRequests.addEventListener('drop', (e) => {
                e.preventDefault();
                scenarioRequests.classList.remove('border-accent', 'bg-accent-light', 'bg-opacity-10');
                if (draggedEndpoint && currentScenario) {

                    const exists = currentScenario.requests.some(req => 
                        req.method === draggedEndpoint.method && req.path === draggedEndpoint.path
                    );
                    if (!exists) {
                        if (!currentScenario.requests) {
                            currentScenario.requests = [];
                        }
                        currentScenario.requests.push({
                            id: draggedEndpoint.id,
                            method: draggedEndpoint.method,
                            path: draggedEndpoint.path,
                            title: draggedEndpoint.title,
                            parameters: draggedEndpoint.parameters,
                            requestBody: draggedEndpoint.requestBody,
                            config: {
                                enabled: true,
                                timeout: 30000,
                                variables: {}
                            }
                        });
                        renderScenarioRequests(currentScenario.requests);
                    } else {

                        showNotification('Endpoint already exists in scenario', 'error', 3000);
                    }
                }
                draggedEndpoint = null;
            });

            const originalRenderEndpoints = renderEndpoints;
            window.renderEndpoints = function(endpointsToRender = null) {
                originalRenderEndpoints(endpointsToRender);
                setTimeout(addDragToEndpoints, 100)
            };

            setTimeout(addDragToEndpoints, 1000)
        }

        document.addEventListener('DOMContentLoaded', function() {
            init();
            initMonacoEditor();
            initModeToggle();
            initScenarioManagement();

        });
    </script>
</body>
</html>