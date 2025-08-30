<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light dark">
    <meta name="theme-color" media="(prefers-color-scheme: light)" content="#f9fafb">
    <meta name="theme-color" media="(prefers-color-scheme: dark)" content="#0a0a0a">
    <title>{{ $title }} - @if($config && isset($config['description'])){{ $config['description'] }}@else Modern API Documentation @endif</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dompurify@3.1.6/dist/purify.min.js"></script>
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
    </script>
    <style>
        /* Make all elements use border-box so width calculations don't overflow */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        /* Prevent horizontal scrolling on small viewports caused by elements overflowing */
        html,
        body {
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }

        /* Allow flex children to shrink properly (fixes overflowing flex items) */
        .flex-1,
        .main-content,
        #sidebar,
        #endpointsContainer {
            min-width: 0;
        }

        /* Make images, tables and pre/code responsive */
        img,
        table,
        pre,
        code,
        .mobile-scroll-table {
            max-width: 100%;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        /* Ensure any long text inside code blocks wraps instead of forcing horizontal scroll */
        pre,
        code {
            white-space: pre-wrap;
            white-space: -moz-pre-wrap;
            white-space: -pre-wrap;
            white-space: -o-pre-wrap;
            word-wrap: break-word;
        }

        /* Color scheme support for browser chrome */
        :root {
            color-scheme: light dark;
        }

        /* JSON syntax highlighting - VS Code style */
        .dark .json-key {
            color: #9cdcfe; /* blue for keys (dark) */
            font-weight: 400;
        }
        .dark .json-string {
            color: #ce9178; /* orange for string (dark) */
        }
        .dark .json-number {
            color: #b5cea8; /* green for number (dark) */
        }
        .dark .json-boolean {
            color: #569cd6; /* blue for boolean (dark) */
            font-weight: 700;
        }
        .dark .json-null {
            color: #dcdcaa; /* yellow for null (dark) */
            font-style: italic;
        }
        /* Syntax chars like brackets, braces, commas for dark mode */
        .dark .json-brace {
            color: #ffd700; /* gold for braces {} (dark) */
            font-weight: bold;
        }
        .dark .json-bracket {
            color: #af82e2; /* orange for brackets [] (dark) */
            font-weight: bold;
        }
        .dark .json-comma {
            color: #fff; /* red for commas (dark) */
            font-weight: bold;
        }
        .dark .json-colon {
            color: #fff; /* green for colons (dark) */
            font-weight: bold;
        }

        /* Light mode overrides for JSON syntax highlighting */
        .json-key {
            color: #d32929; /* blue for keys (light) */
        }
        .json-string {
            color: #166534; /* red for string (light) */
        }
        .json-number {
            color: #000; /* green for number (light) */
        }
        .json-boolean {
            color: #0000ff; /* blue for boolean (light) */
        }
        .json-null {
            color: #795e26; /* brown for null (light) */
        }
        /* Syntax chars for light mode */
        .json-brace {
            color: #b8860b; /* dark golden rod for braces {} (light) */
            font-weight: bold;
        }
        .json-bracket {
            color: #d2691e; /* chocolate for brackets [] (light) */
            font-weight: bold;
        }
        .json-comma {
            color: #000; /* crimson for commas (light) */
            font-weight: bold;
        }
        .json-colon {
            color: #000; /* sea green for colons (light) */
            font-weight: bold;
        }

        /* Method badges (glassmorphism) */
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

        /* Dark mode method badges (glassmorphism) */
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

        /* Custom toggle styles for settings */
        .toggle-active {
            @apply bg-accent;
        }

        .toggle-active .toggle-slider {
            @apply transform translate-x-5;
        }

        /* Base URL badge custom styling */
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
        
        /* Dynamic accent dark background for dark theme */
        .dark-accent-bg {
            background-color: var(--accent-dark-bg, rgba(22, 101, 52, 0.2)) !important;
        }

        /* Responsive design */

        /* Mobile-first: Sidebar hidden by default on mobile */
        @media (max-width: 768px) {

            /* Sidebar: off-canvas, limited width to avoid forcing horizontal scroll */
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

            /* Main content should never force body width */
            .main-content {
                width: 100% !important;
                margin-left: 0 !important;
                min-width: 0;
                overflow-x: hidden;
            }

            /* Mobile overlay for sidebar: fixed and covers viewport without creating scroll */
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

            /* Mobile hamburger button visible on small screens */
            .mobile-menu-btn {
                display: flex !important;
            }

            /* Keep header width constrained */
            .glassmorphism-header,
            .glassmorphism-footer {
                padding: 1rem !important;
                width: 100%;
                left: 0;
                box-sizing: border-box;
            }

            /* Mobile-specific table responsive: allow scrolling inside container and avoid forcing body width */
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

            /* Mobile tabs responsive - prevent horizontal scroll */
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

            /* Tab container should scroll horizontally on mobile if needed */
            .tab-container {
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch !important;
                scrollbar-width: none !important;
                -ms-overflow-style: none !important;
            }

            .tab-container::-webkit-scrollbar {
                display: none !important;
            }

            /* Mobile endpoint header should wrap instead of overflowing */
            #endpointHeader {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 0.75rem !important;
                width: 100%;
            }

            /* Ensure all content containers are responsive */
            .p-6 {
                padding: 1rem !important;
            }

            /* Make JSON viewer responsive */
            .mobile-scroll-table+p,
            pre,
            code {
                word-break: break-word !important;
                overflow-wrap: break-word !important;
            }
        }

        /* Tablet */
        @media (min-width: 769px) and (max-width: 1023px) {
            #sidebar {
                min-width: 250px !important;
                max-width: 300px !important;
            }

            .mobile-menu-btn {
                display: none !important;
            }
        }

        /* Desktop */
        @media (min-width: 1024px) {
            #sidebar {
                min-width: 280px;
                max-width: 400px;
            }

            .mobile-menu-btn {
                display: none !important;
            }
        }

        /* Default mobile menu button hidden */
        .mobile-menu-btn {
            display: none;
        }

        /* Glassmorphism header effect */
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

        /* Dark mode glassmorphism */
        .dark .glassmorphism-header,
        .dark .glassmorphism-footer {
            background: rgba(10, 10, 10, 0.4) !important;
            border-bottom: 1px solid rgba(44, 45, 45, 0.4) !important;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3) !important;
        }

        /* Custom Scrollbar Styling */
        /* Webkit browsers (Chrome, Safari, Edge) */
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

        /* Dark mode scrollbar */
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

        /* Firefox scrollbar */
        * {
            scrollbar-width: thin;
            scrollbar-color: rgba(0, 0, 0, 0.2) rgba(0, 0, 0, 0.05);
        }

        .dark * {
            scrollbar-color: rgba(255, 255, 255, 0.2) rgba(255, 255, 255, 0.05);
        }

        /* Enhanced scrollbar for sidebar and main content */
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

        /* Fallback for browsers without backdrop-filter support */
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

        /* Chat AI Styles */
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

        /* Chat sidebar responsive behavior */
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

        /* Scroll styling for chat messages */
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
        /* Chat input tweaks (modernized) */
        #chatSidebar textarea#chatInput {
            line-height: 1.25rem;
            padding-top: 0.9rem;
            padding-bottom: 0.45rem;
            /* make sure long words wrap and newlines are preserved */
            white-space: pre-wrap;
            overflow-wrap: anywhere;
            -webkit-font-smoothing: antialiased;
            overflow: hidden; /* avoid native scrollbars while auto-sizing */
            transition: height 120ms ease, border-radius 140ms ease; /* smooth visual changes */
            -webkit-transition: height 120ms ease, border-radius 140ms ease;
        }

        /* Ensure the send button is centered vertically and has no extra shadow */
        #chatSidebar button#sendChatMessage {
            box-shadow: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            top: 50%;
            transform: translateY(-50%);
        }

        #chatSidebar button#sendChatMessage svg { height: 14px; width: 14px; }

        /* Reserve space at the bottom of the messages container so last message never hides behind input */
        #chatMessages {
            padding-bottom: 140px; /* accounts for max input height + padding */
            scroll-behavior: smooth;
        }

        /* Bubble utility to keep text wrapping correctly and preserve newlines */
        .chat-bubble {
            max-width: calc(100% - 64px);
            overflow-wrap: anywhere;
            word-break: break-word;
            /* white-space: pre-wrap; */
        }

        /* User bubble specific tweaks when aligned on the right */
        .chat-bubble.user {
                color: white;
            }

            /* Sidebar endpoint active state (subtle, theme aware) */
            [data-endpoint-id].endpoint-active {
                border-left-width: 3px !important;
                border-left-style: solid !important;
                border-left-color: var(--accent-color, #166534) !important;
                background-color: var(--accent-light-color, rgba(22,101,52,0.06)) !important;
                color: inherit !important;
            }

            /* Make method badge more pronounced when its endpoint is active */
            [data-endpoint-id].endpoint-active .method-get,
            [data-endpoint-id].endpoint-active .method-post,
            [data-endpoint-id].endpoint-active .method-put,
            [data-endpoint-id].endpoint-active .method-delete,
            [data-endpoint-id].endpoint-active .method-patch {
                box-shadow: 0 6px 18px rgba(2,6,23,0.06);
                transform: translateX(-2px);
            }

            /* Dark mode tweaks for active endpoint */
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
        <!-- Sidebar -->
        <div class="w-80 bg-gray-50 border-r border-gray-200 dark:bg-[#0a0a0a] dark:border-[#2c2d2d] flex flex-col transition-all duration-300 relative"
            id="sidebar" style="min-width: 280px; max-width: 500px;">
            <!-- Resize Handle -->
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
                <!-- Endpoints will be populated by JavaScript -->
            </div>
        </div>

        <!-- Mobile Sidebar Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden bg-white dark:bg-[#0a0a0a] main-content">
            <div class="flex-1 overflow-y-auto flex flex-col">
                <div class="glassmorphism-header p-6">
                    <!-- Mobile Header Layout -->
                    <div class="block md:hidden">
                        <!-- Top row: Menu button + Title + Settings -->
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

                        <!-- Second row: Base URL selector and Auth button -->
                        <div class="flex gap-2 mb-4">
                            <select
                                class="flex-1 px-3 py-2 border border-gray-300 dark:border-[#212121] rounded-md bg-white dark:bg-black text-gray-900 dark:text-white text-sm"
                                id="baseUrlSelect">
                                <!-- Options will be populated dynamically by JavaScript based on config -->
                            </select>
                            <button
                                class="px-4 py-2 bg-accent text-white rounded-md text-sm hover:bg-accent-hover transition-colors duration-200"
                                id="authBtn">Auth</button>
                        </div>
                    </div>

                    <!-- Desktop Header Layout -->
                    <div class="hidden md:flex justify-between items-center mb-4">
                        <div class="flex items-center gap-3">
                            <select
                                class="px-3 py-2 border border-gray-300 dark:border-[#212121] rounded-md bg-white dark:bg-black text-gray-900 dark:text-white text-sm min-w-50"
                                id="baseUrlSelectDesktop">
                                <!-- Options will be populated dynamically by JavaScript based on config -->
                            </select>
                        </div>
                        <div class="flex gap-3">
                            <button
                                class="px-4 py-1 border border-gray-300 dark:border-[#212121] rounded-md bg-white dark:bg-black text-gray-900 dark:text-white text-sm hover:bg-gray-50 dark:hover:bg-white dark:hover:text-black transition-colors duration-200"
                                id="settingsBtnDesktop">Settings</button>
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
                                <!-- Parameters Form -->
                                <div id="testParametersForm" class="hidden mb-6">
                                    <h4 class="text-md font-semibold mb-3 text-gray-900 dark:text-white">Parameters</h4>
                                    <div id="testParametersInputs" class="space-y-3 mb-4">
                                        <!-- Parameter inputs will be populated by JavaScript -->
                                    </div>
                                </div>

                                <!-- Request Body Form -->
                                <div id="testBodyForm" class="hidden mb-6">
                                    <h4 class="text-md font-semibold mb-3 text-gray-900 dark:text-white">Request Body
                                    </h4>
                                    <textarea id="testBodyInput"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-[#212121] rounded-md bg-white dark:bg-black text-gray-900 dark:text-white text-sm font-mono"
                                        rows="8" placeholder="Enter JSON request body..."></textarea>
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
                <div class="flex-grow"></div>
                <footer class="mt-auto py-3 glassmorphism-footer text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Made with ❤️ by <span class="font-medium text-gray-600 dark:text-gray-300">Bytedocs</span>
                    </p>
                </footer>
            </div>
        </div>

        <!-- Chat AI Sidebar -->
        <div class="bg-gray-50 border-l border-gray-200 dark:bg-[#0a0a0a] dark:border-[#2c2d2d] flex flex-col transition-all duration-300 hidden relative"
            id="chatSidebar" style="width: 320px; min-width: 280px; max-width: 600px;">
            <!-- Resize Handle -->
            <div class="absolute left-0 top-0 w-1 h-full cursor-col-resize bg-transparent hover:bg-accent transition-colors duration-200 z-10"
                id="resizeHandle"></div>

            <!-- Chat Header -->
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

            <!-- Chat Messages -->
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

            <!-- Chat Input (modernized: no top border, icon-only send inside input) -->
            <div class="p-4">
                <div class="relative">
                    <label for="chatInput" class="sr-only">Ask me anything about this API</label>
                    <textarea id="chatInput" rows="1" placeholder="Ketik pertanyaanmu..."
                        class="w-full resize-none pr-14 pl-4 py-2 bg-white dark:bg-[#212121] border border-gray-200 dark:border-[#2c2d2d] text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-accent transition-all rounded-xl"
                        style="height:50px; max-height:100px;"></textarea>

                    <button id="sendChatMessage" type="button" aria-label="Send" title="Send"
                        class="absolute right-2 top-[43%] transform -translate-y-1/2 w-9 h-9 p-1.5 bg-accent hover:bg-accent-hover text-white rounded-full shadow focus:outline-none">
                        <!-- Paper plane / send icon (smaller) -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Modal -->
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
                <!-- 'Auto Expand' setting removed -->
            </div>
        </div>
    </div>

    <!-- Authentication Modal -->
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
                        <!-- Auth inputs will be populated by JavaScript -->
                    </div>

                    <button
                        class="w-full bg-accent hover:bg-accent-hover text-white font-semibold px-4 py-2 rounded-md transition-colors duration-200"
                        id="saveAuth">Save Authentication</button>
                </div>
            </div>
        </div>
    </div>

    <!-- SVG Icons for Copy functionality -->
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
        // Get API data from Go backend
        const apiData = {!! json_encode($docsData) !!};
        const config = {!! json_encode($config) !!};

        // JSON formatter with simple syntax highlighting
        function escapeHtml(str) {
            return str
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');
        }

        function syntaxHighlight(jsonString) {
            // First escape HTML
            const safe = escapeHtml(jsonString);
            
            // Use a single comprehensive regex that captures all elements properly
            return safe.replace(/("(?:[^\\"]|\\.)*")\s*:|("(?:[^\\"]|\\.)*")|(\btrue\b|\bfalse\b|\bnull\b)|(-?\d+(?:\.\d+)?(?:[eE][+-]?\d+)?)|(\{)|(\})|(\[)|(\])|,/g, function(match, key, string, bool, number, openBrace, closeBrace, openBracket, closeBracket) {
                
                if (key) {
                    // This is a key followed by colon
                    return key.replace(/^("(?:[^\\"]|\\.)*")(\s*)$/, '<span class="json-key">$1</span>$2') + '<span class="json-colon">:</span>';
                }
                if (string) {
                    // This is a string value
                    return '<span class="json-string">' + string + '</span>';
                }
                if (bool) {
                    // This is true/false/null
                    if (bool === 'null') {
                        return '<span class="json-null">' + bool + '</span>';
                    } else {
                        return '<span class="json-boolean">' + bool + '</span>';
                    }
                }
                if (number) {
                    // This is a number
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

        // Copy to clipboard functionality
        function copyToClipboard(text, button) {
            navigator.clipboard.writeText(text).then(() => {
                // Change button appearance to show success
                const originalHTML = button.innerHTML;
                const originalClasses = button.className;
                
                button.className = button.className.replace('border-gray-300 dark:border-white', 'border-accent bg-accent-light dark:dark-accent-bg');
                button.innerHTML = `
                    <svg class="w-3 h-3" viewBox="0 0 24 24">
                        <use href="#check-icon"></use>
                    </svg>
                    Copied!
                `;
                
                // Reset after 2 seconds
                setTimeout(() => {
                    button.className = originalClasses;
                    button.innerHTML = originalHTML;
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy: ', err);
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                
                // Still show visual feedback
                const originalClasses = button.className;
                button.className = button.className.replace('border-gray-300 dark:border-white', 'border-accent bg-accent-light dark:dark-accent-bg');
                setTimeout(() => {
                    button.className = originalClasses;
                }, 2000);
            });
        }

        // Create JSON viewer with copy functionality
        function createJsonViewer(jsonString, title = 'JSON') {
            const copyId = 'copy_' + Math.random().toString(36).substr(2, 9);
            const beautifyId = 'beautify_' + Math.random().toString(36).substr(2, 9);
            
            // Parse and reformat to ensure it's valid JSON
            let parsedJson;
            try {
                parsedJson = JSON.parse(jsonString);
            } catch (e) {
                parsedJson = jsonString; // If not valid JSON, use as is
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

        // Transform backend data to match the template's expected format
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
                        responses: endpoint.responses || {}
                    }));
                });
            }
            
            return transformed;
        }

        const transformedApiData = transformApiData(apiData);

        // State management
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

        // DOM elements
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
        
        // Mobile menu elements
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        // Modal elements
        const settingsModal = document.getElementById('settingsModal');
        const authModal = document.getElementById('authModal');
        const settingsBtn = document.getElementById('settingsBtn');
        const settingsBtnDesktop = document.getElementById('settingsBtnDesktop');
        const authBtn = document.getElementById('authBtn');
        const authBtnDesktop = document.getElementById('authBtnDesktop');
        const baseUrlSelectDesktop = document.getElementById('baseUrlSelectDesktop');
        const closeSettings = document.getElementById('closeSettings');
        const closeAuth = document.getElementById('closeAuth');

    // Settings toggles
    const darkModeToggle = document.getElementById('darkModeToggle');
    const compactModeToggle = document.getElementById('compactModeToggle');

        // Auth elements
        const authType = document.getElementById('authType');
        const authInputs = document.getElementById('authInputs');
        const saveAuth = document.getElementById('saveAuth');

        // Initialize the application
        function init() {
            // Function to populate both select elements
            function populateBaseUrlSelects() {
                const selects = [baseUrlSelect, baseUrlSelectDesktop];
                
                selects.forEach(select => {
                    if (!select) return;
                    select.innerHTML = '';
                    console.log(config)
                    // Handle BaseURLs (new array format) or BaseURL (backward compatibility)
                    if (config && config.baseUrls && config.baseUrls.length > 0) {
                        // Use new BaseURLs array format
                        config.baseUrls.forEach((baseUrlOption, index) => {
                            const option = document.createElement('option');
                            option.value = baseUrlOption.url;
                            option.textContent = `${baseUrlOption.name} - ${baseUrlOption.url}`;
                            if (index === 0) option.selected = true; // Select first as default
                            select.appendChild(option);
                        });
                    } else if (config && config.baseUrl) {
                        // Fallback to single BaseURL for backward compatibility
                        const option = document.createElement('option');
                        option.value = config.baseUrl;
                        option.textContent = `Current - ${config.baseUrl}`;
                        option.selected = true;
                        select.appendChild(option);
                    } else {
                        // Default fallback options
                        const defaultOptions = [
                            { name: 'Production', url: 'https://api.example.com' },
                            { name: 'Staging', url: 'https://staging-api.example.com' },
                            { name: 'Development', url: 'https://dev-api.example.com' },
                            { name: 'Local', url: 'http://localhost:8080' }
                        ];
                        
                        defaultOptions.forEach((baseUrlOption, index) => {
                            const option = document.createElement('option');
                            option.value = baseUrlOption.url;
                            option.textContent = `${baseUrlOption.name} - ${baseUrlOption.url}`;
                            if (index === 0) option.selected = true;
                            select.appendChild(option);
                        });
                    }
                });
            }
            
            populateBaseUrlSelects();

            // Initialize filtered endpoints with all endpoints
            filteredEndpoints = Object.values(transformedApiData).flat();
            renderEndpoints();
            setupEventListeners();
            loadSettings();
            loadAuthentication();
            selectFirstEndpoint();
            initThemeColor();
            
            // Chat AI event listeners
            document.getElementById('chatAIToggle').addEventListener('click', toggleChatSidebar);
            
            // Setup sidebar resize functionality
            setupSidebarResize();
            setupLeftSidebarResize();
            document.getElementById('closeChatSidebar').addEventListener('click', toggleChatSidebar);
            document.getElementById('sendChatMessage').addEventListener('click', sendChatMessage);

            // Autoresize & keyboard handling for chat input
            (function setupChatInput() {
                const chatInput = document.getElementById('chatInput');
                if (!chatInput) return;

                // Auto-resize function with min/max and rounded toggle
                const MIN_HEIGHT = 50;
                const MAX_HEIGHT = 100;

                let raf = null;
                function setRounded(el, atMax) {
                    el.classList.remove('rounded-full', 'rounded-2xl');
                    el.classList.add(atMax ? 'rounded-2xl' : 'rounded-full');
                }

                function autoResize(el) {
                    // Use requestAnimationFrame to batch layout reads/writes and avoid jitter
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

                // Initial resize
                autoResize(chatInput, true);

                // Resize on input
                chatInput.addEventListener('input', (e) => {
                    autoResize(e.target);
                });

                // Handle paste: clamp immediately and normalize excessive whitespace
                chatInput.addEventListener('paste', (e) => {
                    setTimeout(() => {
                        // collapse long runs of spaces/tabs except newlines
                        chatInput.value = chatInput.value.replace(/[ \t]{3,}/g, ' ');
                        // Force an immediate resize on next frame
                        autoResize(chatInput);
                    }, 0);
                });

                // Key handling: Enter = send, Shift+Enter = newline
                chatInput.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') {
                        if (!e.shiftKey) {
                            e.preventDefault();
                            sendChatMessage();
                        } else {
                            // allow newline; autoresize will handle it via input event
                            // jump to max immediately when user presses Shift+Enter
                            setTimeout(() => autoResize(chatInput, true), 0);
                        }
                    }
                });

                // When chat opens, focus and resize
                const origToggle = toggleChatSidebar;
            })();
        }

        // Render endpoints in sidebar
        function renderEndpoints(endpointsToRender = null) {
            // If no specific endpoints provided, use all or filtered endpoints
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

        // State persistence for form data
        const endpointFormStates = {};
        
        // Save current form state
        function saveFormState() {
            if (!currentEndpoint) return;
            
            const state = {};
            
            // Save parameters form data
            const testParametersInputs = document.getElementById('testParametersInputs');
            if (testParametersInputs) {
                const inputs = testParametersInputs.querySelectorAll('input');
                inputs.forEach(input => {
                    if (input.name && input.value) {
                        state[input.name] = input.value;
                    }
                });
            }
            
            // Save body form data
            const testBodyInput = document.getElementById('testBodyInput');
            if (testBodyInput && testBodyInput.value) {
                state['body'] = testBodyInput.value;
            }
            
            // Save response data if exists
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
        
        // Restore form state for current endpoint
        function restoreFormState() {
            if (!currentEndpoint || !endpointFormStates[currentEndpoint.id]) return;
            
            const state = endpointFormStates[currentEndpoint.id];
            
            // Restore parameters form data
            const testParametersInputs = document.getElementById('testParametersInputs');
            if (testParametersInputs) {
                const inputs = testParametersInputs.querySelectorAll('input');
                inputs.forEach(input => {
                    if (input.name && state[input.name]) {
                        input.value = state[input.name];
                    }
                });
            }
            
            // Restore body form data
            const testBodyInput = document.getElementById('testBodyInput');
            if (testBodyInput && state['body']) {
                testBodyInput.value = state['body'];
            }
            
            // Restore response data
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

        // Select an endpoint
        function selectEndpoint(endpoint) {
            // Save current form state before switching
            saveFormState();
            
            currentEndpoint = endpoint;
            
            // Update active state: use endpoint-active class so we don't clobber other styling
            document.querySelectorAll('[data-endpoint-id]').forEach(item => {
                item.classList.remove('endpoint-active');
            });
            const activeItem = document.querySelector(`[data-endpoint-id="${endpoint.id}"]`);
            if (activeItem) activeItem.classList.add('endpoint-active');
            
            // Update header
            currentMethod.textContent = endpoint.method;
            currentMethod.className = `endpoint-method px-2 rounded-md text-sm method-${endpoint.method.toLowerCase()}`;
            
            // Get selected base URL info
            const selectedOption = baseUrlSelect.options[baseUrlSelect.selectedIndex];
            const selectedText = selectedOption ? selectedOption.textContent : 'Current';
            const baseUrlName = selectedText.split(' - ')[0];
            
            currentUrl.innerHTML = `
                <span class="base-url-badge">${baseUrlName}</span>
                <span class="endpoint-path">${endpoint.path}</span>
            `;
            
            // Show/hide Body tab based on method
            const bodyTab = document.getElementById('bodyTab');
            const hasBody = ['POST', 'PUT', 'PATCH'].includes(endpoint.method.toUpperCase());
            bodyTab.style.display = hasBody ? 'block' : 'none';
            
            // Update content
            updateContent();
            
            // Clear previous response when switching endpoints
            const responseContainer = document.getElementById('responseContainer');
            responseContainer.classList.add('hidden');
        }

        // Update main content based on selected endpoint
        function updateContent() {
            if (!currentEndpoint) return;
            
            // Overview tab - show proper description
            const description = getEndpointDescription(currentEndpoint);
            endpointDescription.textContent = description;
            
            // Parameters tab
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
            
            // Body tab - show request body for POST/PUT/PATCH
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
            
            // Responses tab - show proper responses for each endpoint
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
            
            // Update test form
            updateTestForm();
        }

        // Update test form based on selected endpoint
        function updateTestForm() {
            if (!currentEndpoint) return;
            
            const testParametersForm = document.getElementById('testParametersForm');
            const testParametersInputs = document.getElementById('testParametersInputs');
            const testBodyForm = document.getElementById('testBodyForm');
            const testBodyInput = document.getElementById('testBodyInput');
            
            // Show/hide parameters form
            if (currentEndpoint.parameters && currentEndpoint.parameters.length > 0) {
                testParametersForm.classList.remove('hidden');
                
                // Separate parameters by location
                const pathParams = currentEndpoint.parameters.filter(p => p.in === 'path');
                const queryParams = currentEndpoint.parameters.filter(p => p.in === 'query');
                const otherParams = currentEndpoint.parameters.filter(p => p.in !== 'path' && p.in !== 'query');
                
                let html = '';
                
                // Path Parameters Section
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
                
                // Query Parameters Section
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
                
                // Other Parameters Section (header, cookie, etc.)
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
            
            // Show/hide request body form
            const hasBody = ['POST', 'PUT', 'PATCH'].includes(currentEndpoint.method.toUpperCase());
            if (hasBody) {
                testBodyForm.classList.remove('hidden');
                
                // Only set default value if no saved state exists
                if (!endpointFormStates[currentEndpoint.id] || !endpointFormStates[currentEndpoint.id]['body']) {
                    // Pre-fill with example if available
                    const exampleBody = getRequestBodyExample(currentEndpoint);
                    if (exampleBody) {
                        testBodyInput.value = JSON.stringify(exampleBody, null, 2);
                    } else {
                        testBodyInput.value = '{\n  \n}';
                    }
                }
            } else {
                testBodyForm.classList.add('hidden');
            }
            
            // Restore form state after form is rendered
            setTimeout(restoreFormState, 0);
        }

        // Search functionality
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
                        endpoint.description || '',             // Basic description (fallback)
                        getEndpointDescription(endpoint),       // Detailed description (same as displayed)
                        endpoint.path || '',
                        endpoint.id || '',
                        endpoint.method || '',
                        endpoint.summary || '',                 // Summary field
                        // Also search in parameters if they exist
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

        // Clear search
        function clearSearch() {
            searchInput.value = '';
            performSearch();
        }

        // Select first endpoint
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

        // Tab switching
        function switchTab(tabName) {
            // Reset all tabs
            document.querySelectorAll('[data-tab]').forEach(tab => {
                tab.className = 'px-6 py-3 cursor-pointer border-b-2 border-transparent text-gray-600 dark:text-gray-300 hover:text-accent hover:border-accent transition-all duration-200';
                // Reset inline styles
                tab.style.borderBottomColor = '';
                tab.style.color = '';
            });
            
            // Reset all tab contents
            document.querySelectorAll('[id="overview"], [id="parameters"], [id="body"], [id="responses"], [id="test"]').forEach(content => {
                content.classList.add('hidden');
                content.classList.remove('block');
            });
            
            // Activate selected tab
            const activeTab = document.querySelector(`[data-tab="${tabName}"]`);
            activeTab.className = 'px-6 py-3 cursor-pointer border-b-2 font-medium transition-all duration-200';
            activeTab.style.borderBottomColor = 'var(--accent-color)';
            activeTab.style.color = 'var(--accent-color)';
            
            // Show selected content
            const activeContent = document.getElementById(tabName);
            activeContent.classList.remove('hidden');
            activeContent.classList.add('block');
        }

        // Test endpoint
        async function testEndpoint() {
            if (!currentEndpoint) return;
            
            testButton.disabled = true;
            testButton.textContent = 'Sending...';
            
            const startTime = Date.now();
            
            try {
                // Collect parameters from form
                const parameters = {};
                const paramInputs = document.querySelectorAll('[name^="param_"]');
                paramInputs.forEach(input => {
                    const paramName = input.name.replace('param_', '');
                    if (input.value.trim()) {
                        parameters[paramName] = input.value.trim();
                    }
                });
                
                // Build URL with parameters
                let url = `${baseUrlSelect.value}${currentEndpoint.path}`;
                
                // Replace path parameters (e.g., /users/{id})
                Object.entries(parameters).forEach(([key, value]) => {
                    url = url.replace(`{${key}}`, value);
                });
                
                // Add query parameters for GET requests
                if (currentEndpoint.method.toUpperCase() === 'GET' && Object.keys(parameters).length > 0) {
                    const queryParams = new URLSearchParams();
                    Object.entries(parameters).forEach(([key, value]) => {
                        // Only add if not already used as path parameter
                        if (!currentEndpoint.path.includes(`{${key}}`)) {
                            queryParams.append(key, value);
                        }
                    });
                    if (queryParams.toString()) {
                        url += '?' + queryParams.toString();
                    }
                }
                
                // Prepare request options
                const requestOptions = {
                    method: currentEndpoint.method,
                    headers: {
                        ...getAuthHeaders(),
                        'Content-Type': 'application/json'
                    }
                };
                
                // Add request body for POST/PUT/PATCH
                if (['POST', 'PUT', 'PATCH'].includes(currentEndpoint.method.toUpperCase())) {
                    const bodyInput = document.getElementById('testBodyInput');
                    if (bodyInput && bodyInput.value.trim()) {
                        try {
                            // Validate JSON
                            JSON.parse(bodyInput.value);
                            requestOptions.body = bodyInput.value;
                        } catch (e) {
                            throw new Error('Invalid JSON in request body');
                        }
                    }
                }
                
                // Make API call
                const response = await fetch(url, requestOptions);
                
                const endTime = Date.now();
                const duration = endTime - startTime;
                
                // Show response
                responseContainer.classList.remove('hidden');
                responseStatus.textContent = response.status;
                responseStatus.className = `response-status dark:text-white status-${response.status}`;
                responseTime.textContent = `${duration}ms`;
                
                try {
                    const responseData = await response.json();
                    responseBody.innerHTML = createJsonViewer(JSON.stringify(responseData, null, 2), 'Response');
                } catch (e) {
                    // Response is not JSON
                    const textResponse = await response.text();
                    responseBody.innerHTML = `<pre class="p-4 bg-gray-100 dark:bg-[#212121] border border-gray-200 dark:border-[#2c2d2d] rounded-lg font-mono text-sm">${textResponse || 'Empty response'}</pre>`;
                }
                
                // Save form state including the response
                saveFormState();
                
            } catch (error) {
                const endTime = Date.now();
                const duration = endTime - startTime;
                
                responseContainer.classList.remove('hidden');
                responseStatus.textContent = '500';
                responseStatus.className = 'response-status dark:text-white status-500';
                responseTime.textContent = `${duration}ms`;
                responseBody.innerHTML = createJsonViewer(JSON.stringify({ error: 'Request failed: ' + error.message }, null, 2), 'Error Response');
                
                // Save form state including the error response
                saveFormState();
            } finally {
                testButton.disabled = false;
                testButton.textContent = 'Send Request';
            }
        }

        // Get auth headers
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

        // Update theme color for mobile browser UI
        function updateThemeColor(color) {
            // Update or create theme-color meta tag
            let themeColorMeta = document.querySelector('meta[name="theme-color"]:not([media])');
            if (!themeColorMeta) {
                themeColorMeta = document.createElement('meta');
                themeColorMeta.name = 'theme-color';
                document.head.appendChild(themeColorMeta);
            }
            themeColorMeta.content = color;
            
            // Also update the media-specific ones
            const lightThemeMeta = document.querySelector('meta[name="theme-color"][media*="light"]');
            const darkThemeMeta = document.querySelector('meta[name="theme-color"][media*="dark"]');
            
            if (lightThemeMeta) lightThemeMeta.content = '#f9fafb';
            if (darkThemeMeta) darkThemeMeta.content = '#0a0a0a';
        }

        // Settings management
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
                        // Update theme-color for dark mode
                        updateThemeColor('#0a0a0a');
                    } else {
                        document.documentElement.classList.remove('dark');
                        // Update theme-color for light mode
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
                    // Re-render endpoints to apply compact mode visibility changes
                    renderEndpoints();
                    break;
                case 'showDescriptions':
                    // This could hide/show descriptions in sidebar
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
                // Auto-detect system dark mode preference on first load
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    settings.darkMode = true;
                }
            }
            
            Object.keys(settings).forEach(settingName => {
                applySetting(settingName);
            });
            
            // Set initial theme color based on current mode
            const isDark = document.documentElement.classList.contains('dark');
            updateThemeColor(isDark ? '#0a0a0a' : '#f9fafb');
        }

        // Authentication management
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

        // Event listeners
        function setupEventListeners() {
            // Copy button event delegation
            document.addEventListener('click', (e) => {
                if (e.target.closest('[data-copy-text]')) {
                    const button = e.target.closest('[data-copy-text]');
                    const textToCopy = button.getAttribute('data-copy-text');
                    if (textToCopy) {
                        copyToClipboard(textToCopy, button);
                    }
                }
            });

            // Beautify toggle event delegation  
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
                        // Switch to compact
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
                        // Switch to beautified
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

            // Hide modals on Escape
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' || e.key === 'Esc') {
                    // Close settings modal if open
                    if (settingsModal && !settingsModal.classList.contains('hidden')) {
                        settingsModal.classList.add('hidden');
                        settingsModal.classList.remove('flex');
                    }

                    // Close auth modal if open
                    if (authModal && !authModal.classList.contains('hidden')) {
                        authModal.classList.add('hidden');
                        authModal.classList.remove('flex');
                    }

                    // Close chat sidebar if open
                    const chatSidebar = document.getElementById('chatSidebar');
                    if (chatSidebar && !chatSidebar.classList.contains('hidden')) {
                        chatSidebar.classList.add('hidden');
                    }
                }
            });

            // Search
            searchInput.addEventListener('input', performSearch);
            searchClear.addEventListener('click', clearSearch);
            
            // Base URL change handler function
            function handleBaseUrlChange(selectElement) {
                if (currentEndpoint) {
                    // Get selected base URL info
                    const selectedOption = selectElement.options[selectElement.selectedIndex];
                    const selectedText = selectedOption ? selectedOption.textContent : 'Current';
                    const baseUrlName = selectedText.split(' - ')[0];
                    
                    currentUrl.innerHTML = `
                        <span class="base-url-badge">${baseUrlName}</span>
                        <span class="endpoint-path">${currentEndpoint.path}</span>
                    `;
                    
                    // Sync both selects
                    const selects = [baseUrlSelect, baseUrlSelectDesktop];
                    selects.forEach(select => {
                        if (select && select !== selectElement) {
                            select.value = selectElement.value;
                        }
                    });
                }
            }
            
            // Base URL event listeners
            baseUrlSelect.addEventListener('change', (e) => handleBaseUrlChange(e.target));
            if (baseUrlSelectDesktop) {
                baseUrlSelectDesktop.addEventListener('change', (e) => handleBaseUrlChange(e.target));
            }
            
            // Tabs
            document.querySelectorAll('.tab').forEach(tab => {
                tab.addEventListener('click', () => {
                    switchTab(tab.dataset.tab);
                });
            });
            
            // Test button
            testButton.addEventListener('click', testEndpoint);
            
            // Settings modal handlers
            function openSettings() {
                settingsModal.classList.remove('hidden');
                settingsModal.classList.add('flex');
            }
            
            settingsBtn.addEventListener('click', openSettings);
            if (settingsBtnDesktop) {
                settingsBtnDesktop.addEventListener('click', openSettings);
            }
            closeSettings.addEventListener('click', () => {
                settingsModal.classList.add('hidden');
                settingsModal.classList.remove('flex');
            });
            
            // Settings toggles
            darkModeToggle.addEventListener('click', () => toggleSetting('darkMode'));
            compactModeToggle.addEventListener('click', () => toggleSetting('compactMode'));
            
            // Theme color buttons
            document.querySelectorAll('.theme-color-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const selectedTheme = e.target.getAttribute('data-theme');
                    changeThemeColor(selectedTheme);
                });
            });
            
            // Auth modal handlers
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
            
            // Auth type change
            authType.addEventListener('change', updateAuthInputs);
            saveAuth.addEventListener('click', saveAuthentication);
            
            // Mobile menu
            mobileMenuBtn.addEventListener('click', toggleMobileSidebar);
            sidebarOverlay.addEventListener('click', closeMobileSidebar);
            
            // Close mobile sidebar when endpoint is selected (mobile UX)
            document.addEventListener('click', (e) => {
                if (e.target.closest('[data-endpoint-id]') && window.innerWidth <= 768) {
                    closeMobileSidebar();
                }
            });
            
            // Close mobile sidebar on window resize if desktop
            window.addEventListener('resize', () => {
                if (window.innerWidth > 768) {
                    closeMobileSidebar();
                }
            });
            
            // Close modals on backdrop click
            [settingsModal, authModal].forEach(modal => {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    }
                });
            });
            
            // Auto-save form state when inputs change (with debouncing)
            let formSaveTimeout;
            document.addEventListener('input', (e) => {
                const target = e.target;
                // Check if it's a test form input
                if (target.matches('[name^="param_"]') || target.id === 'testBodyInput') {
                    clearTimeout(formSaveTimeout);
                    formSaveTimeout = setTimeout(saveFormState, 300); // Debounce 300ms
                }
            });
        }
        
        // Helper function to convert hex to RGB
        function hexToRgb(hex) {
            const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
            return result ? {
                r: parseInt(result[1], 16),
                g: parseInt(result[2], 16),
                b: parseInt(result[3], 16)
            } : null;
        }
        
        // Theme color management
        function changeThemeColor(themeName) {
            if (!themes[themeName]) return;
            
            const newTheme = themes[themeName];
            currentTheme = themeName;
            
            // Save to localStorage
            localStorage.setItem('theme-color', themeName);
            
            // Update Tailwind config colors
            tailwind.config.theme.extend.colors.accent = newTheme.accent;
            tailwind.config.theme.extend.colors['accent-hover'] = newTheme.accentHover;
            tailwind.config.theme.extend.colors['accent-light'] = newTheme.accentLight;
            
            // Update CSS custom properties for elements that can't use Tailwind
            document.documentElement.style.setProperty('--accent-color', newTheme.accent);
            document.documentElement.style.setProperty('--accent-hover-color', newTheme.accentHover);
            document.documentElement.style.setProperty('--accent-light-color', newTheme.accentLight);
            
            // Create dark theme variants with opacity
            const accentRgb = hexToRgb(newTheme.accent);
            document.documentElement.style.setProperty('--accent-dark-bg', `rgba(${accentRgb.r}, ${accentRgb.g}, ${accentRgb.b}, 0.2)`);
            
            // Update button states to show active selection
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
        
        // Initialize selected theme color
        function initThemeColor() {
            const savedTheme = localStorage.getItem('theme-color') || 'green';
            
            // Set initial CSS custom properties
            const currentColors = themes[savedTheme];
            document.documentElement.style.setProperty('--accent-color', currentColors.accent);
            document.documentElement.style.setProperty('--accent-hover-color', currentColors.accentHover);
            document.documentElement.style.setProperty('--accent-light-color', currentColors.accentLight);
            
            // Create dark theme variants with opacity
            const accentRgb = hexToRgb(currentColors.accent);
            document.documentElement.style.setProperty('--accent-dark-bg', `rgba(${accentRgb.r}, ${accentRgb.g}, ${accentRgb.b}, 0.2)`);
            
            // Update button states
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

        // Get endpoint description based on endpoint details
        function getEndpointDescription(endpoint) {
            const descriptions = {
                'get--api-v1-users': 'Retrieve a list of all users with pagination support',
                'post--api-v1-users': 'Create a new user account with email verification',
                'get--api-v1-users-id': 'Retrieve detailed information about a specific user',
                'put--api-v1-users-id': 'Update user information (requires authentication)',
                'delete--api-v1-users-id': 'Permanently delete a user account',
                'get--api-v1-products': 'Retrieve all products with filtering options',
                'post--api-v1-products': 'Create a new product in the catalog',
                'get--api-v1-products-id': 'Retrieve detailed information about a specific product'
            };
            
            return descriptions[endpoint.id] || endpoint.description || 'No description available';
        }
        
        // Get request body example for POST/PUT/PATCH endpoints
        function getRequestBodyExample(endpoint) {
            const bodyExamples = {
                'post--api-v1-users': {
                    name: "John Doe",
                    email: "john@example.com",
                    password: "securepassword123"
                },
                'put--api-v1-users-id': {
                    name: "John Smith",
                    email: "johnsmith@example.com"
                },
                'post--api-v1-products': {
                    name: "iPhone 14",
                    price: 999.99,
                    description: "Latest iPhone model with advanced features",
                    category: "Electronics"
                }
            };
            
            return bodyExamples[endpoint.id] || null;
        }
        
        // Get proper responses for each endpoint
        function getEndpointResponses(endpoint) {
            const responseExamples = {
                'get--api-v1-users': {
                    '200': {
                        description: 'Success',
                        example: {
                            users: [
                                { id: 1, name: "John Doe", email: "john@example.com" },
                                { id: 2, name: "Jane Smith", email: "jane@example.com" }
                            ],
                            total: 100,
                            page: 1,
                            limit: 10
                        }
                    },
                    '400': { description: 'Invalid parameters', example: { error: "Invalid page number" } }
                },
                'post--api-v1-users': {
                    '201': {
                        description: 'User created successfully',
                        example: {
                            id: 123,
                            name: "John Doe",
                            email: "john@example.com",
                            created_at: "2024-01-01T00:00:00Z"
                        }
                    },
                    '400': { description: 'Validation Error', example: { error: "Email already exists" } }
                },
                'get--api-v1-users-id': {
                    '200': {
                        description: 'Success',
                        example: {
                            id: 123,
                            name: "John Doe",
                            email: "john@example.com",
                            created_at: "2024-01-01T00:00:00Z"
                        }
                    },
                    '404': { description: 'User not found', example: { error: "User not found" } }
                },
                'put--api-v1-users-id': {
                    '200': {
                        description: 'User updated successfully',
                        example: {
                            id: 123,
                            name: "John Smith",
                            email: "johnsmith@example.com",
                            updated_at: "2024-01-01T00:00:00Z"
                        }
                    },
                    '401': { description: 'Unauthorized', example: { error: "Authentication required" } },
                    '404': { description: 'User not found', example: { error: "User not found" } }
                },
                'delete--api-v1-users-id': {
                    '204': { description: 'User deleted successfully', example: null },
                    '401': { description: 'Unauthorized', example: { error: "Authentication required" } },
                    '404': { description: 'User not found', example: { error: "User not found" } }
                },
                'get--api-v1-products': {
                    '200': {
                        description: 'Success',
                        example: {
                            products: [
                                { id: 1, name: "iPhone 14", price: 999.99, description: "Latest iPhone" },
                                { id: 2, name: "MacBook Pro", price: 1999.99, description: "Professional laptop" }
                            ],
                            total: 50,
                            page: 1
                        }
                    }
                },
                'post--api-v1-products': {
                    '201': {
                        description: 'Product created successfully',
                        example: {
                            id: 456,
                            name: "iPhone 14",
                            price: 999.99,
                            description: "Latest iPhone model",
                            created_at: "2024-01-01T00:00:00Z"
                        }
                    }
                },
                'get--api-v1-products-id': {
                    '200': {
                        description: 'Success',
                        example: {
                            id: 1,
                            name: "iPhone 14",
                            price: 999.99,
                            description: "Latest iPhone model with advanced features",
                            category: "Electronics",
                            stock: 100
                        }
                    },
                    '404': { description: 'Product not found', example: { error: "Product not found" } }
                }
            };
            
            return responseExamples[endpoint.id] || endpoint.responses || {};
        }
        
        // Mobile menu functions
        function toggleMobileSidebar() {
            sidebar.classList.toggle('mobile-open');
            sidebarOverlay.classList.toggle('active');
        }
        
        function closeMobileSidebar() {
            sidebar.classList.remove('mobile-open');
            sidebarOverlay.classList.remove('active');
        }
        
        // Chat AI functionality
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
                        
                        // Constrain width between min and max
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
                    
                    // Constrain width between min and max
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
                // Focus on chat input
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
            // Scroll to bottom but keep a small offset so the input area won't cover content
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
                // --- DETECT: cuma switch ke marked kalau ada table markdown ---
                const looksLikeMDTable =
                    /\n\|.+\|\n\|?[:\- ]+\|/s.test(message) || // header row + separator
                    /(^|\n)\s*\|.*\|/m.test(message);         // bar-based rows

                if (looksLikeMDTable) {
                    // Render Markdown (GFM) + sanitize
                    const rawHtml = marked.parse(message, { gfm: true, breaks: true });

                    // Bungkus <table> biar bisa horizontal scroll di mobile
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

                    // Tambah utility classes biar style-nya konsisten sama punyamu
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
                    // --- PAKAI LOGIC LAMA KAMU (tetap aman) ---
                    let formattedMessage = escapeHtml(message);

                    // Headers
                    formattedMessage = formattedMessage.replace(/^### (.*$)/gm, '<h3 class="font-bold text-lg mt-4 mb-2">$1</h3>');
                    formattedMessage = formattedMessage.replace(/^## (.*$)/gm, '<h2 class="font-bold text-xl mt-4 mb-2">$1</h2>');
                    formattedMessage = formattedMessage.replace(/^# (.*$)/gm, '<h1 class="font-bold text-2xl mt-4 mb-2">$1</h1>');

                    // Code blocks
                    formattedMessage = formattedMessage.replace(/```(\w+)?\n([\s\S]*?)```/g, '<pre class="bg-[#202020] text-gray-100 p-3 rounded-md my-2 overflow-x-auto"><code class="text-sm">$2</code></pre>');

                    // Inline code
                    formattedMessage = formattedMessage.replace(/`([^`]+)`/g, '<code class="bg-gray-200 dark:bg-black px-1 py-0.5 rounded text-xs font-mono">$1</code>');

                    // Bold and italic
                    formattedMessage = formattedMessage.replace(/\*\*(.*?)\*\*/g, '<strong class="font-bold">$1</strong>');
                    formattedMessage = formattedMessage.replace(/\*(.*?)\*/g, '<em class="italic">$1</em>');

                    // Lists
                    formattedMessage = formattedMessage.replace(/^- (.*$)/gm, '<li class="ml-4 list-disc list-inside">$1</li>');
                    formattedMessage = formattedMessage.replace(/^(\d+)\. (.*$)/gm, '<li class="ml-4 list-decimal list-inside">$2</li>');

                    // Line breaks
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
            // give DOM a tick if complex content (tables) inserted, then scroll
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
                // Prepare the chat request with API context
                const chatRequest = {
                    message: userMessage,
                    context: `This is an API documentation for ${apiData?.info?.title || 'the API'}. The base URL is ${config?.baseUrls?.[0]?.url || config?.baseURL || 'not configured'}.`,
                    endpoint: currentEndpoint // Include current endpoint context if available
                };
                
                // Send request to chat endpoint
                const response = await fetch(`${window.location.origin}${config.docsPath || '/docs'}/chat`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(chatRequest)
                });
                
                const data = await response.json();
                
                hideTypingIndicator();
                
                if (data.error) {
                    // Show error message
                    addChatMessage(`Sorry, I encountered an error: ${data.error}`, 'ai');
                } else {
                    // Show AI response
                    console.log(data)
                    addChatMessage(data.response || 'Sorry, I couldn\'t generate a response.', 'ai');
                }
                
            } catch (error) {
                console.error('Chat error:', error);
                hideTypingIndicator();
                
                // Fallback to simple responses if API fails
                addChatMessage('Sorry, I\'m having trouble connecting to the AI service right now. Please try again later.', 'ai');
            }
            
            isTyping = false;
        }
        
        function sendChatMessage() {
            const chatInput = document.getElementById('chatInput');
            const message = chatInput.value.trim();
            
            if (!message || isTyping) return;
            
            // Add user message
            addChatMessage(message, 'user');
            
            // Clear input
            chatInput.value = '';
            // Reset height to minimum and rounded style
            try { chatInput.style.height = '50px'; chatInput.style.overflow = 'hidden'; chatInput.classList.remove('rounded-2xl'); chatInput.classList.add('rounded-full'); } catch (e) {}
            // Keep focus so user can continue typing
            try { chatInput.focus(); } catch (e) {}
            
            // Send message to AI
            sendAIRequest(message);
        }
        
        // Initialize the application when DOM is loaded
        document.addEventListener('DOMContentLoaded', init);
    </script>

</body>

</html>