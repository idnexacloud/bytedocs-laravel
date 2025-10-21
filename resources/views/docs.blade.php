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
    <script src="{{ asset('bytedocs/bytedocs.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('bytedocs/bytedocs.css') }}">
    <style>
        /* Performance AI Table Styling */
        .perf-ai-table table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 1.5rem 0;
            font-size: 0.875rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .dark .perf-ai-table table {
            border-color: #3c3d3d;
        }

        .perf-ai-table thead {
            background: #f9fafb;
        }

        .dark .perf-ai-table thead {
            background: #171717;
        }

        .perf-ai-table th {
            padding: 0.875rem 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.8125rem;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
        }

        .dark .perf-ai-table th {
            color: #d1d5db;
            border-bottom-color: #3c3d3d;
        }

        .perf-ai-table td {
            padding: 0.875rem 1rem;
            color: #4b5563;
            border-bottom: 1px solid #f3f4f6;
            line-height: 1.6;
        }

        .dark .perf-ai-table td {
            color: #9ca3af;
            border-bottom-color: #2c2d2d;
        }

        .perf-ai-table tbody tr {
            transition: background-color 0.15s ease;
        }

        .perf-ai-table tbody tr:hover {
            background: #f9fafb;
        }

        .dark .perf-ai-table tbody tr:hover {
            background: #1f1f1f;
        }

        .perf-ai-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Zebra striping for better readability */
        .perf-ai-table tbody tr:nth-child(even) {
            background: #fafafa;
        }

        .dark .perf-ai-table tbody tr:nth-child(even) {
            background: #1a1a1a;
        }

        /* Code inside table cells */
        .perf-ai-table td code,
        .perf-ai-table th code {
            background: #f3f4f6;
            color: #7c3aed;
            padding: 0.125rem 0.375rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .dark .perf-ai-table td code,
        .dark .perf-ai-table th code {
            background: #0a0a0a;
            color: #a78bfa;
        }

        /* Better spacing for lists in prose */
        .perf-ai-table ul,
        .perf-ai-table ol {
            margin-top: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .perf-ai-table li {
            margin-top: 0.375rem;
            margin-bottom: 0.375rem;
        }

        /* Horizontal rule spacing */
        .perf-ai-table hr {
            margin-top: 2rem;
            margin-bottom: 2rem;
            border: 0;
            border-top: 1px solid #e5e7eb;
        }

        .dark .perf-ai-table hr {
            border-top-color: #3c3d3d;
        }
    </style>
</head>
<body class="bg-white dark:bg-black">
    <div class="flex h-screen">
        
        @include('bytedocs::partials.sidebar')
        
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        
        <div class="flex-1 flex flex-col overflow-hidden bg-white dark:bg-[#0a0a0a] main-content">
            <div class="flex-1 overflow-y-auto flex flex-col">
                
                <div id="docsContent">
                    @include('bytedocs::modes.docs')
                </div> 
                
                <div id="scenarioContent" class="hidden">
                    @include('bytedocs::modes.scenario')
                </div>
                <div class="flex-grow"></div>
                @include('bytedocs::partials.footer')
            </div>
        </div>
        
        <div id="scenarioModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
            @include('bytedocs::partials.modals.create_new_scenario')
        </div>
        
        <div id="endpointConfigModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-2 sm:p-4">
            @include('bytedocs::partials.modals.configuration_endpoint_scenario')
        </div>
        
        <div class="bg-gray-50 border-l border-gray-200 dark:bg-[#0a0a0a] dark:border-[#2c2d2d] flex flex-col transition-all duration-300 hidden relative"
        id="chatSidebar" style="width: 320px; min-width: 280px; max-width: 600px;">
            @include('bytedocs::partials.ai_assistant')
        </div>
    </div>
    
    <div id="importModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        @include('bytedocs::partials.modals.import_scenario')
    </div>
    
    <div id="scenarioDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-2 sm:p-4">
        @include('bytedocs::partials.modals.detail_scenario')
    </div>
    
    <div class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center" id="settingsModal">
        @include('bytedocs::partials.modals.settings')
    </div>
    
    <div class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center" id="authModal">
        @include('bytedocs::partials.modals.authorization')
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
                // Copy failed silently

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
            if (settingsBtnSidebar) {
                settingsBtnSidebar.addEventListener('click', openSettings);
            }

            function exportOpenApiYaml() {
                try {
                    // Use direct route URL
                    const yamlUrl = '{{ route('bytedocs.openapi.yaml') }}';
                    
                    // Create a temporary link and trigger download
                    const link = document.createElement('a');
                    link.href = yamlUrl;
                    link.target = '_blank';
                    link.download = 'openapi.yaml';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    
                    showNotification('OpenAPI YAML export initiated', 'success', 3000);
                } catch (error) {
                    showNotification('Failed to export OpenAPI YAML', 'error', 3000);
                    // Export failed silently
                }
            }
            const exportYamlBtn = document.getElementById('exportYamlBtn');
            const exportYamlBtnMobile = document.getElementById('exportYamlBtnMobile');
            if (exportYamlBtn) {
                exportYamlBtn.addEventListener('click', exportOpenApiYaml);
            }
            if (exportYamlBtnMobile) {
                exportYamlBtnMobile.addEventListener('click', exportOpenApiYaml);
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
            document.getElementById('mobileMenuBtnScenario').addEventListener('click', toggleMobileSidebar);
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
                
                // Re-render scenario requests to adjust mobile/desktop layout
                if (currentScenario && currentScenario.requests) {
                    renderScenarioRequests(currentScenario.requests);
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
            let savedTheme = localStorage.getItem('theme-color') || 'green';
            
            // Validate theme exists, fallback to green if invalid
            if (!themes[savedTheme]) {
                savedTheme = 'green';
                localStorage.setItem('theme-color', savedTheme);
            }

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
                // Chat error occurred silently
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
            
            // Close mobile sidebar when switching modes
            closeMobileSidebar();

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

        function switchMobileScenarioTab(tab) {
            const mobileInfoTab = document.getElementById('mobileInfoTab');
            const mobileEndpointsTab = document.getElementById('mobileEndpointsTab');
            const mobileSequenceTab = document.getElementById('mobileSequenceTab');
            const mobileInformationContent = document.getElementById('mobileInformationContent');
            const mobileEndpointsContent = document.getElementById('mobileEndpointsContent');
            const mobileSequenceContent = document.getElementById('mobileSequenceContent');

            // Reset all tabs
            [mobileInfoTab, mobileEndpointsTab, mobileSequenceTab].forEach(tabEl => {
                tabEl.classList.remove('border-accent', 'text-accent', 'bg-accent/5');
                tabEl.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
            });

            // Hide all content
            [mobileInformationContent, mobileEndpointsContent, mobileSequenceContent].forEach(content => {
                content.classList.add('hidden');
            });

            // Show selected tab and content
            if (tab === 'information') {
                mobileInfoTab.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
                mobileInfoTab.classList.add('border-accent', 'text-accent', 'bg-accent/5');
                mobileInformationContent.classList.remove('hidden');
            } else if (tab === 'endpoints') {
                mobileEndpointsTab.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
                mobileEndpointsTab.classList.add('border-accent', 'text-accent', 'bg-accent/5');
                mobileEndpointsContent.classList.remove('hidden');
                // Sync endpoints content for mobile
                syncEndpointsToMobile();
            } else if (tab === 'sequence') {
                mobileSequenceTab.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
                mobileSequenceTab.classList.add('border-accent', 'text-accent', 'bg-accent/5');
                mobileSequenceContent.classList.remove('hidden');
                // Sync sequence content for mobile
                syncSequenceToMobile();
            }
        }

        function syncEndpointsToMobile() {
            // No need to manually sync anymore, populateAvailableEndpoints handles both
            populateAvailableEndpoints(document.getElementById('endpointSearch')?.value || '');
        }

        function syncSequenceToMobile() {
            // No need to manually sync anymore, renderScenarioRequests handles both
            if (currentScenario && currentScenario.requests) {
                renderScenarioRequests(currentScenario.requests);
            }
        }

        function syncMobileToDesktop() {
            // Sync form data from mobile to desktop
            const mobileScenarioName = document.getElementById('mobileScenarioName');
            const desktopScenarioName = document.getElementById('scenarioName');
            if (mobileScenarioName && desktopScenarioName) {
                desktopScenarioName.value = mobileScenarioName.value;
            }

            const mobileScenarioDescription = document.getElementById('mobileScenarioDescription');
            const desktopScenarioDescription = document.getElementById('scenarioDescription');
            if (mobileScenarioDescription && desktopScenarioDescription) {
                desktopScenarioDescription.value = mobileScenarioDescription.value;
            }

            // Sync execution mode
            const mobileExecutionMode = document.querySelector('input[name="mobileExecutionMode"]:checked');
            const desktopExecutionMode = document.querySelector('input[name="executionMode"][value="' + (mobileExecutionMode?.value || 'waterfall') + '"]');
            if (desktopExecutionMode) {
                desktopExecutionMode.checked = true;
            }

            // Sync auth type
            const mobileAuthType = document.getElementById('mobileScenarioAuthType');
            const desktopAuthType = document.getElementById('scenarioAuthType');
            if (mobileAuthType && desktopAuthType) {
                desktopAuthType.value = mobileAuthType.value;
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
            card.className = 'scenario-card bg-white dark:bg-[#171717] border border-gray-200 dark:border-[#2c2d2d] rounded-lg p-4 sm:p-6 hover:shadow-lg transition-all duration-200 cursor-pointer';

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
                    <div class="flex items-center gap-2 sm:gap-3 flex-1 min-w-0">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br ${visuals.gradient} rounded-lg flex items-center justify-center flex-shrink-0">
                            ${visuals.icon}
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:gap-2 mb-1">
                                <h3 class="font-semibold text-gray-900 dark:text-white text-sm sm:text-base truncate">${scenario.name}</h3>
                                <span class="text-xs px-2 py-0.5 ${modeConfig.badgeColor} text-white rounded-full font-medium self-start sm:self-auto">${modeConfig.badgeText}</span>
                            </div>
                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">${scenario.requests.length} request${scenario.requests.length !== 1 ? 's' : ''}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1 sm:gap-2 flex-shrink-0">
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
                        <div class="flex items-center gap-2 text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                            <span class="w-2 h-2 ${getMethodColor(req.method)} rounded-full flex-shrink-0"></span>
                            <span class="method-${req.method.toLowerCase()} flex-shrink-0">${req.method}</span>
                            <span class="truncate">${req.path}</span>
                        </div>
                    `).join('')}
                    ${scenario.requests.length > 3 ? `<p class="text-xs text-gray-500 dark:text-gray-400">+${scenario.requests.length - 3} more requests</p>` : ''}
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <span class="text-xs text-gray-500 dark:text-gray-400 truncate">${scenario.description || 'No description'}</span>
                    <button class="bg-accent hover:bg-accent-hover text-white px-3 py-1.5 rounded text-xs font-medium transition-colors duration-200 flex-shrink-0" onclick="runScenario(${index})">
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
            document.getElementById('mobileScenarioName').value = '';
            document.getElementById('mobileScenarioDescription').value = '';
            
            const endpointSearch = document.getElementById('endpointSearch');
            if (endpointSearch) endpointSearch.value = '';
            const mobileEndpointSearch = document.getElementById('mobileEndpointSearch');
            if (mobileEndpointSearch) mobileEndpointSearch.value = '';

            const waterfallMode = document.getElementById('waterfallMode');
            if (waterfallMode) waterfallMode.checked = true;
            const mobileWaterfallMode = document.querySelector('input[name="mobileExecutionMode"][value="waterfall"]');
            if (mobileWaterfallMode) mobileWaterfallMode.checked = true;

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
                
                // Also populate mobile form
                document.getElementById('mobileScenarioName').value = scenario.name;
                document.getElementById('mobileScenarioDescription').value = scenario.description || '';

                const executionMode = scenario.executionMode || 'waterfall';
                const waterfallMode = document.getElementById('waterfallMode');
                const parallelMode = document.getElementById('parallelMode');
                const mobileWaterfallMode = document.querySelector('input[name="mobileExecutionMode"][value="waterfall"]');
                const mobileParallelMode = document.querySelector('input[name="mobileExecutionMode"][value="parallel"]');
                
                if (executionMode === 'parallel') {
                    if (parallelMode) parallelMode.checked = true;
                    if (mobileParallelMode) mobileParallelMode.checked = true;
                } else {
                    if (waterfallMode) waterfallMode.checked = true;
                    if (mobileWaterfallMode) mobileWaterfallMode.checked = true;
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
            switchMobileScenarioTab('information');

            populateAvailableEndpoints();
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function populateAvailableEndpoints(searchQuery = '') {
            const container = document.getElementById('availableEndpoints');
            const mobileContainer = document.getElementById('mobileAvailableEndpoints');
            container.innerHTML = '';
            if (mobileContainer) mobileContainer.innerHTML = '';
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
                    
                    // Also create mobile version
                    if (mobileContainer) {
                        const mobileEndpointBtn = endpointBtn.cloneNode(true);
                        if (!isSelected) {
                            mobileEndpointBtn.onclick = () => addEndpointToScenario(endpoint);
                        }
                        
                        // Create or get mobile category div
                        let mobileCategoryDiv = mobileContainer.querySelector(`[data-category="${category}"]`);
                        if (!mobileCategoryDiv) {
                            mobileCategoryDiv = document.createElement('div');
                            mobileCategoryDiv.className = 'mb-2';
                            mobileCategoryDiv.setAttribute('data-category', category);
                            mobileCategoryDiv.innerHTML = `<h4 class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">${category.toUpperCase()}</h4>`;
                            mobileContainer.appendChild(mobileCategoryDiv);
                        }
                        mobileCategoryDiv.appendChild(mobileEndpointBtn);
                    }
                });
                container.appendChild(categoryDiv);
            });

            if (query && container.children.length === 0) {
                container.innerHTML = '<p class="text-xs text-gray-500 dark:text-gray-400 text-center py-4">No endpoints found</p>';
                if (mobileContainer) {
                    mobileContainer.innerHTML = '<p class="text-xs text-gray-500 dark:text-gray-400 text-center py-4">No endpoints found</p>';
                }
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
            const buttons = document.querySelectorAll('.scenario-left-btn');
            buttons.forEach(button => {
                if (isEditingScenario) {
                    button.textContent = 'Delete';
                    button.onclick = deleteCurrentScenario;
                } else {
                    button.textContent = 'Reset Form';
                    button.onclick = resetScenarioForm;
                }
            });
        }

        function renderScenarioRequests(requests) {
            const container = document.getElementById('scenarioRequests');
            const emptyMessage = document.getElementById('emptyScenarioMessage');
            const mobileContainer = document.getElementById('mobileScenarioRequests');
            const mobileEmptyMessage = document.getElementById('mobileEmptyScenarioMessage');

            // Clear desktop container
            const existingRequests = container.querySelectorAll('.scenario-request-item');
            existingRequests.forEach(item => item.remove());
            
            // Clear mobile container
            if (mobileContainer) {
                const mobileExistingRequests = mobileContainer.querySelectorAll('.scenario-request-item');
                mobileExistingRequests.forEach(item => item.remove());
            }
            
            if (requests.length === 0) {
                emptyMessage.style.display = 'block';
                if (mobileEmptyMessage) mobileEmptyMessage.style.display = 'block';
                return;
            }
            
            emptyMessage.style.display = 'none';
            if (mobileEmptyMessage) mobileEmptyMessage.style.display = 'none';

            requests.forEach((request, index) => {
                const item = createScenarioRequestItem(request, index);
                container.appendChild(item);
                
                // For mobile container, we need to create items optimized for mobile
                if (mobileContainer) {
                    const mobileItem = createMobileScenarioRequestItem(request, index);
                    mobileContainer.appendChild(mobileItem);
                }
            });
        }

        function createScenarioRequestItem(request, index) {
            const item = document.createElement('div');
            // Desktop version - original layout
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

        function createMobileScenarioRequestItem(request, index) {
            const item = document.createElement('div');
            // Mobile version - more compact
            item.className = 'scenario-request-item bg-gray-50 dark:bg-[#2c2d2d] border border-gray-200 dark:border-[#171717] rounded-lg p-3';
            item.innerHTML = `
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2 flex-1 min-w-0">
                        <span class="text-sm text-gray-400 select-none flex-shrink-0">${index + 1}.</span>
                        <span class="method-${request.method.toLowerCase()} text-xs px-1.5 py-0.5 rounded flex-shrink-0">${request.method}</span>
                    </div>
                    <div class="flex items-center gap-1 flex-shrink-0">
                        <button class="text-gray-400 hover:text-gray-600 transition-colors duration-200 p-1" title="Configure" onclick="configureRequest(${index})">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </button>
                        <button class="text-gray-400 hover:text-red-500 transition-colors duration-200 p-1" title="Remove" onclick="removeRequestFromScenario(${index})">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="text-sm text-gray-900 dark:text-white truncate">${request.path}</div>
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
            // Handle both mobile and desktop cancel buttons
            document.querySelectorAll('.scenario-cancel-btn').forEach(btn => {
                btn.addEventListener('click', closeScenarioModal);
            });
            
            // Handle both mobile and desktop save buttons
            document.querySelectorAll('.scenario-save-btn').forEach(btn => {
                btn.addEventListener('click', saveCurrentScenario);
            });

            document.getElementById('scenarioAuthType').addEventListener('change', updateScenarioAuthInputs);

            document.getElementById('closeConfigModal').addEventListener('click', closeEndpointConfigModal);
            document.getElementById('cancelConfig').addEventListener('click', closeEndpointConfigModal);
            document.getElementById('saveConfig').addEventListener('click', saveEndpointConfiguration);
            document.getElementById('addHeader').addEventListener('click', () => addHeaderRow());
            document.getElementById('formatBody').addEventListener('click', formatRequestBody);
            document.getElementById('loadExampleBody').addEventListener('click', loadExampleRequestBody);

            let searchTimeout;
            document.addEventListener('input', (e) => {
                if (e.target.id === 'endpointSearch' || e.target.id === 'mobileEndpointSearch') {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        populateAvailableEndpoints(e.target.value);
                        // Also populate mobile endpoints if searching from mobile
                        if (e.target.id === 'mobileEndpointSearch') {
                            syncEndpointsToMobile();
                        }
                    }, 300);
                }

                if (e.target.id === 'scenarioName' || e.target.id === 'scenarioDescription' || e.target.id === 'mobileScenarioName' || e.target.id === 'mobileScenarioDescription') {
                    if (currentScenario) {
                        if (e.target.id === 'scenarioName' || e.target.id === 'mobileScenarioName') {
                            currentScenario.name = e.target.value;
                            // Sync between mobile and desktop
                            if (e.target.id === 'mobileScenarioName') {
                                document.getElementById('scenarioName').value = e.target.value;
                            } else {
                                document.getElementById('mobileScenarioName').value = e.target.value;
                            }
                        } else if (e.target.id === 'scenarioDescription' || e.target.id === 'mobileScenarioDescription') {
                            currentScenario.description = e.target.value;
                            // Sync between mobile and desktop
                            if (e.target.id === 'mobileScenarioDescription') {
                                document.getElementById('scenarioDescription').value = e.target.value;
                            } else {
                                document.getElementById('mobileScenarioDescription').value = e.target.value;
                            }
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
            
            // Check if we're on mobile and sync data first
            const isMobile = window.innerWidth < 1024;
            if (isMobile) {
                syncMobileToDesktop();
            }
            
            const name = document.getElementById('scenarioName').value.trim();
            const description = document.getElementById('scenarioDescription').value.trim();
            const executionMode = document.querySelector('input[name="executionMode"]:checked').value;

            saveScenarioAuthentication();
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
                            <div class="flex items-center gap-3 min-w-0 flex-1">
                                <span class="w-2 h-2 ${methodColor} rounded-full flex-shrink-0"></span>
                                <span class="font-mono text-sm font-medium method-${request.method.toLowerCase()} flex-shrink-0">${request.method}</span>
                                <span class="text-sm text-gray-600 dark:text-gray-400 truncate" title="${request.path}">${request.path}</span>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
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
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-2 sm:p-4';
            modal.innerHTML = `
                <div class="bg-white dark:bg-[#171717] rounded-lg shadow-xl max-w-6xl w-full max-h-full sm:max-h-[90vh] overflow-hidden flex flex-col">
                    <div class="p-3 sm:p-6 border-b border-gray-200 dark:border-[#2c2d2d] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="min-w-0 flex-1">
                            <h2 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white truncate">Running Scenario: ${scenario.name}</h2>
                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">${enabledRequests.length} requests • ${executionMode} execution</p>
                        </div>
                        <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200 flex-shrink-0" onclick="this.closest('.fixed').remove()">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="flex-1 p-3 sm:p-6 overflow-y-auto min-h-0">
                        <div id="scenarioResults" class="space-y-4">
                            
                        </div>
                    </div>
                    <div class="flex-shrink-0 p-3 sm:p-6 border-t border-gray-200 dark:border-[#2c2d2d] flex justify-end">
                        <button class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md w-full sm:w-auto" onclick="this.closest('.fixed').remove()">Close</button>
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
            resultItem.className = 'border border-gray-200 dark:border-[#2c2d2d] rounded-lg p-3 sm:p-4';
            resultItem.innerHTML = `
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-lg text-gray-400 flex-shrink-0">${index + 1}.</span>
                    <span class="method-${request.method.toLowerCase()} text-xs px-2 py-1 rounded flex-shrink-0">${request.method}</span>
                    <span class="text-sm text-gray-900 dark:text-white truncate min-w-0 flex-1" title="${request.path}">${request.path}</span>
                    <div class="flex-shrink-0 status-container">
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
                const statusContainer = resultItem.querySelector('.status-container');
                statusContainer.innerHTML = `
                    <div class="text-right">
                        <div class="${statusClass} text-sm font-medium">${response.status} ${statusText}</div>
                        <div class="text-xs text-gray-500">${responseTime}ms</div>
                    </div>
                `;
                const resultContent = resultItem.querySelector('.result-content');
                resultContent.innerHTML = `
                    <div class="mt-3 p-3 bg-gray-50 dark:bg-[#2c2d2d] rounded">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Response (${response.status})</h4>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-2 break-all" title="${fullUrl}">${fullUrl}</div>
                        <pre class="text-xs text-gray-600 dark:text-gray-400 whitespace-pre-wrap max-h-32 overflow-y-auto overflow-x-auto">${typeof responseData === 'string' ? responseData : JSON.stringify(responseData, null, 2)}</pre>
                    </div>
                `;
                resultContent.classList.remove('hidden');
            } catch (error) {
                // Request failed silently

                const statusContainer = resultItem.querySelector('.status-container');
                statusContainer.innerHTML = `
                    <div class="text-right">
                        <div class="text-red-600 text-sm font-medium">Failed</div>
                    </div>
                `;
                const resultContent = resultItem.querySelector('.result-content');
                resultContent.innerHTML = `
                    <div class="mt-3 p-3 bg-red-50 dark:bg-red-900/20 rounded">
                        <h4 class="text-sm font-medium text-red-700 dark:text-red-300 mb-2">Error</h4>
                        <pre class="text-xs text-red-600 dark:text-red-400 whitespace-pre-wrap break-all">${error.message}</pre>
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

        // Performance Test Management
        function initPerformanceTest() {
            const perfModeConstant = document.getElementById('perfModeConstant');
            const perfModeStages = document.getElementById('perfModeStages');
            const perfConstantForm = document.getElementById('perfConstantForm');
            const perfStagesForm = document.getElementById('perfStagesForm');
            const perfAddStage = document.getElementById('perfAddStage');
            const perfRunTest = document.getElementById('perfRunTest');
            const perfGenerateScript = document.getElementById('perfGenerateScript');
            const perfToggleAdvanced = document.getElementById('perfToggleAdvanced');
            const perfAdvancedOptions = document.getElementById('perfAdvancedOptions');
            const perfError = document.getElementById('perfError');
            const perfErrorContent = document.getElementById('perfErrorContent');
            const perfDismissError = document.getElementById('perfDismissError');
            const perfResults = document.getElementById('perfResults');
            const perfClearResults = document.getElementById('perfClearResults');

            let currentPerfMode = 'constant';
            let stageCounter = 1;

            // Load saved custom k6 path from localStorage
            const savedK6Path = localStorage.getItem('k6_custom_path');
            if (savedK6Path) {
                document.getElementById('perfK6CustomPath').value = savedK6Path;
            }

            // Save custom k6 path to localStorage when changed
            document.getElementById('perfK6CustomPath').addEventListener('change', (e) => {
                const path = e.target.value.trim();
                if (path) {
                    localStorage.setItem('k6_custom_path', path);
                } else {
                    localStorage.removeItem('k6_custom_path');
                }
            });

            // Error and results handlers
            perfDismissError.addEventListener('click', () => {
                perfError.classList.add('hidden');
            });

            perfClearResults.addEventListener('click', () => {
                perfResults.classList.add('hidden');
            });

            // Result tabs switching
            document.querySelectorAll('.perf-result-tab').forEach(tab => {
                tab.addEventListener('click', () => {
                    const tabName = tab.getAttribute('data-tab');
                    switchPerfResultTab(tabName);
                });
            });

            // AI Analyst trigger
            // Language selector - load from localStorage
            const savedLanguage = localStorage.getItem('perf_ai_language') || 'en';
            const languageSelect = document.getElementById('perfAiLanguage');
            if (languageSelect) {
                languageSelect.value = savedLanguage;

                // Save language preference when changed
                languageSelect.addEventListener('change', (e) => {
                    localStorage.setItem('perf_ai_language', e.target.value);
                });
            }

            document.getElementById('perfTriggerAiAnalyst').addEventListener('click', async () => {
                if (!latestTestResults) {
                    showNotification('No test results available', 'error', 3000);
                    return;
                }

                const button = document.getElementById('perfTriggerAiAnalyst');
                const placeholder = document.getElementById('perfAiAnalystPlaceholder');
                const resultDiv = document.getElementById('perfAiAnalystResult');
                const selectedLanguage = document.getElementById('perfAiLanguage').value;

                // Show loading state
                button.disabled = true;
                button.innerHTML = `
                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Analyzing...
                `;

                try {
                    const docsPath = (typeof config !== 'undefined' && config.docs_path) ? config.docs_path : '/docs';
                    const response = await fetch(`${docsPath}/performance/ai-analyst`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            results: latestTestResults,
                            language: selectedLanguage
                        })
                    });

                    const result = await response.json();

                    if (result.success) {
                        // Parse markdown with marked.js and sanitize with DOMPurify
                        const rawHtml = marked.parse(result.analysis);
                        const cleanHtml = DOMPurify.sanitize(rawHtml);

                        // Hide placeholder, show result
                        placeholder.classList.add('hidden');
                        resultDiv.classList.remove('hidden');
                        resultDiv.innerHTML = `
                            <div class="bg-white dark:bg-[#212121] p-6 rounded-lg border border-gray-200 dark:border-[#3c3d3d]">
                                <div class="flex items-start gap-3 mb-4">
                                    <svg class="w-6 h-6 text-accent flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">AI Performance Analysis</h3>
                                        <div class="prose prose-sm dark:prose-invert max-w-none
                                            prose-headings:text-gray-900 dark:prose-headings:text-white
                                            prose-p:text-gray-700 dark:prose-p:text-gray-300 prose-p:leading-relaxed
                                            prose-strong:text-gray-900 dark:prose-strong:text-white prose-strong:font-semibold
                                            prose-code:text-accent prose-code:bg-gray-100 dark:prose-code:bg-[#0a0a0a] prose-code:px-1.5 prose-code:py-0.5 prose-code:rounded prose-code:text-xs
                                            prose-pre:bg-gray-900 prose-pre:text-green-400 prose-pre:p-4 prose-pre:rounded-lg
                                            prose-ul:text-gray-700 dark:prose-ul:text-gray-300 prose-ul:leading-relaxed
                                            prose-ol:text-gray-700 dark:prose-ol:text-gray-300 prose-ol:leading-relaxed
                                            prose-li:text-gray-700 dark:prose-li:text-gray-300 prose-li:my-1
                                            prose-a:text-accent hover:prose-a:text-accent-hover prose-a:underline
                                            perf-ai-table">${cleanHtml}</div>
                                    </div>
                                </div>
                                <button type="button" onclick="document.getElementById('perfAiAnalystPlaceholder').classList.remove('hidden'); document.getElementById('perfAiAnalystResult').classList.add('hidden');" class="text-xs text-gray-600 dark:text-gray-400 hover:text-accent transition-colors">
                                    Run analysis again
                                </button>
                            </div>
                        `;
                        showNotification('AI analysis completed!', 'success', 3000);
                    } else {
                        displayPerfError(result.error || 'Failed to analyze with AI');
                    }
                } catch (error) {
                    displayPerfError('Error analyzing with AI: ' + error.message);
                } finally {
                    // Reset button
                    button.disabled = false;
                    button.innerHTML = `
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Analyze with AI
                    `;
                }
            });

            // Mode switching
            perfModeConstant.addEventListener('click', () => {
                currentPerfMode = 'constant';
                perfModeConstant.classList.add('border-accent', 'bg-accent', 'bg-opacity-10', 'dark:bg-opacity-20', 'text-accent');
                perfModeConstant.classList.remove('border-gray-300', 'dark:border-[#3c3d3d]', 'bg-white', 'dark:bg-[#212121]', 'text-gray-600', 'dark:text-gray-400');

                perfModeStages.classList.remove('border-accent', 'bg-accent', 'bg-opacity-10', 'dark:bg-opacity-20', 'text-accent');
                perfModeStages.classList.add('border-gray-300', 'dark:border-[#3c3d3d]', 'bg-white', 'dark:bg-[#212121]', 'text-gray-600', 'dark:text-gray-400');

                perfConstantForm.classList.remove('hidden');
                perfStagesForm.classList.add('hidden');
            });

            perfModeStages.addEventListener('click', () => {
                currentPerfMode = 'stages';
                perfModeStages.classList.add('border-accent', 'bg-accent', 'bg-opacity-10', 'dark:bg-opacity-20', 'text-accent');
                perfModeStages.classList.remove('border-gray-300', 'dark:border-[#3c3d3d]', 'bg-white', 'dark:bg-[#212121]', 'text-gray-600', 'dark:text-gray-400');

                perfModeConstant.classList.remove('border-accent', 'bg-accent', 'bg-opacity-10', 'dark:bg-opacity-20', 'text-accent');
                perfModeConstant.classList.add('border-gray-300', 'dark:border-[#3c3d3d]', 'bg-white', 'dark:bg-[#212121]', 'text-gray-600', 'dark:text-gray-400');

                perfConstantForm.classList.add('hidden');
                perfStagesForm.classList.remove('hidden');
            });

            // Add stage functionality
            perfAddStage.addEventListener('click', () => {
                stageCounter++;
                const stagesList = document.getElementById('perfStagesList');
                const stageHtml = `
                    <div class="perf-stage bg-white dark:bg-[#212121] border border-gray-200 dark:border-[#3c3d3d] rounded-lg p-4" data-stage="${stageCounter}">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Stage ${stageCounter}</span>
                            <button type="button" class="perf-remove-stage text-red-500 hover:text-red-700 text-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Target VUs</label>
                                <input type="number" class="perf-stage-vus w-full px-2 py-1.5 border border-gray-300 dark:border-[#3c3d3d] rounded bg-white dark:bg-[#0a0a0a] text-gray-900 dark:text-white text-sm" value="10" min="0" max="1000">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Duration</label>
                                <div class="flex gap-1">
                                    <input type="number" class="perf-stage-duration flex-1 px-2 py-1.5 border border-gray-300 dark:border-[#3c3d3d] rounded bg-white dark:bg-[#0a0a0a] text-gray-900 dark:text-white text-sm" value="30" min="1" max="3600">
                                    <select class="perf-stage-unit px-2 py-1.5 border border-gray-300 dark:border-[#3c3d3d] rounded bg-white dark:bg-[#0a0a0a] text-gray-900 dark:text-white text-xs">
                                        <option value="s" selected>s</option>
                                        <option value="m">m</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                stagesList.insertAdjacentHTML('beforeend', stageHtml);
                attachStageRemoveHandlers();
            });

            // Remove stage functionality
            function attachStageRemoveHandlers() {
                document.querySelectorAll('.perf-remove-stage').forEach(btn => {
                    btn.onclick = (e) => {
                        const stage = e.target.closest('.perf-stage');
                        const stages = document.querySelectorAll('.perf-stage');
                        if (stages.length > 1) {
                            stage.remove();
                            renumberStages();
                        } else {
                            showNotification('At least one stage is required', 'error', 3000);
                        }
                    };
                });
            }

            function renumberStages() {
                const stages = document.querySelectorAll('.perf-stage');
                stages.forEach((stage, index) => {
                    const num = index + 1;
                    stage.querySelector('span').textContent = `Stage ${num}`;
                    stage.setAttribute('data-stage', num);

                    // Show/hide remove button
                    const removeBtn = stage.querySelector('.perf-remove-stage');
                    if (stages.length === 1) {
                        removeBtn.classList.add('hidden');
                    } else {
                        removeBtn.classList.remove('hidden');
                    }
                });
                stageCounter = stages.length;
            }

            attachStageRemoveHandlers();
            renumberStages();

            // Toggle advanced options
            perfToggleAdvanced.addEventListener('click', () => {
                perfAdvancedOptions.classList.toggle('hidden');
                const svg = perfToggleAdvanced.querySelector('svg');
                svg.classList.toggle('rotate-180');
            });

            // Run performance test
            perfRunTest.addEventListener('click', async () => {
                if (!currentEndpoint) {
                    showNotification('Please select an endpoint first', 'error', 3000);
                    return;
                }

                const perfTestConfig = gatherPerfConfig();
                if (!perfTestConfig) return;

                // Show loading state
                perfRunTest.disabled = true;
                perfRunTest.innerHTML = `
                    <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Running Test...
                `;

                try {
                    const docsPath = perfTestConfig.docs_path || '/docs';
                    const response = await fetch(`${docsPath}/performance/run`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(perfTestConfig)
                    });

                    const result = await response.json();

                    if (result.success) {
                        displayPerfResults(result.results);
                        showNotification('Performance test completed!', 'success', 3000);
                    } else {
                        displayPerfError(result.error || 'Test failed');
                    }
                } catch (error) {
                    displayPerfError('Error running test: ' + error.message);
                } finally {
                    perfRunTest.disabled = false;
                    perfRunTest.innerHTML = `
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Run Performance Test
                    `;
                }
            });

            // Generate script
            perfGenerateScript.addEventListener('click', async () => {
                if (!currentEndpoint) {
                    showNotification('Please select an endpoint first', 'error', 3000);
                    return;
                }

                const perfTestConfig = gatherPerfConfig();
                if (!perfTestConfig) return;

                try {
                    const docsPath = perfTestConfig.docs_path || '/docs';
                    const response = await fetch(`${docsPath}/performance/script`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(perfTestConfig)
                    });

                    const result = await response.json();

                    if (result.success) {
                        showScriptModal(result.script);
                    } else {
                        displayPerfError(result.error || 'Failed to generate script');
                    }
                } catch (error) {
                    displayPerfError('Error generating script: ' + error.message);
                }
            });

            function gatherPerfConfig() {
                const baseUrl = document.getElementById('baseUrlSelect').value || document.getElementById('baseUrlSelectDesktop').value;
                if (!baseUrl) {
                    showNotification('Please select a base URL', 'error', 3000);
                    return null;
                }

                const url = baseUrl + currentEndpoint.path;
                const method = currentEndpoint.method;
                const thinkTime = parseFloat(document.getElementById('perfThinkTime').value) || 1;
                const includeAuth = document.getElementById('perfIncludeAuth').checked;
                const customK6Path = document.getElementById('perfK6CustomPath').value.trim();

                let perfConfig = {
                    url,
                    method,
                    mode: currentPerfMode,
                    think_time: thinkTime,
                    headers: {},
                    docs_path: (typeof config !== 'undefined' && config.docs_path) ? config.docs_path : '/docs'
                };

                // Add custom k6 path if provided
                if (customK6Path) {
                    perfConfig.k6_path = customK6Path;
                }

                // Add auth headers if needed
                if (includeAuth && window.authConfig) {
                    if (window.authConfig.type === 'bearer' && window.authConfig.token) {
                        perfConfig.headers['Authorization'] = `Bearer ${window.authConfig.token}`;
                    } else if (window.authConfig.type === 'api-key' && window.authConfig.apiKey && window.authConfig.apiKeyName) {
                        perfConfig.headers[window.authConfig.apiKeyName] = window.authConfig.apiKey;
                    } else if (window.authConfig.type === 'basic' && window.authConfig.username && window.authConfig.password) {
                        const credentials = btoa(`${window.authConfig.username}:${window.authConfig.password}`);
                        perfConfig.headers['Authorization'] = `Basic ${credentials}`;
                    }
                }

                if (currentPerfMode === 'constant') {
                    perfConfig.vus = parseInt(document.getElementById('perfConstantVUs').value) || 10;
                    const duration = parseInt(document.getElementById('perfConstantDuration').value) || 30;
                    const unit = document.getElementById('perfConstantDurationUnit').value || 's';
                    perfConfig.duration = `${duration}${unit}`;

                    const iterations = document.getElementById('perfConstantIterations').value;
                    if (iterations) {
                        perfConfig.iterations = parseInt(iterations);
                    }
                } else {
                    perfConfig.stages = [];
                    document.querySelectorAll('.perf-stage').forEach(stage => {
                        const vus = parseInt(stage.querySelector('.perf-stage-vus').value) || 10;
                        const duration = parseInt(stage.querySelector('.perf-stage-duration').value) || 30;
                        const unit = stage.querySelector('.perf-stage-unit').value || 's';
                        perfConfig.stages.push({
                            target: vus,
                            duration: `${duration}${unit}`
                        });
                    });
                }

                return perfConfig;
            }

            function displayPerfError(errorMessage) {
                const perfError = document.getElementById('perfError');
                const perfErrorContent = document.getElementById('perfErrorContent');
                const perfResults = document.getElementById('perfResults');

                // Hide results, show error
                perfResults.classList.add('hidden');
                perfError.classList.remove('hidden');

                // Set error message
                perfErrorContent.textContent = errorMessage;

                // Scroll to error
                perfError.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }

            // Store latest test results for AI analysis
            let latestTestResults = null;

            function displayPerfResults(results) {
                const perfResults = document.getElementById('perfResults');
                const perfResultsContent = document.getElementById('perfResultsContent');
                const perfAnalystContent = document.getElementById('perfAnalystContent');
                const perfError = document.getElementById('perfError');

                // Store results for AI analyst
                latestTestResults = results;

                // Hide error, show results
                perfError.classList.add('hidden');
                perfResults.classList.remove('hidden');

                // Reset to Computer View tab
                switchPerfResultTab('computer');

                // Populate Computer View
                let html = '<div class="space-y-3">';

                if (results.summary) {
                    html += '<div class="grid grid-cols-2 md:grid-cols-3 gap-3">';

                    if (results.summary.total_requests) {
                        html += `
                            <div class="bg-white dark:bg-[#212121] p-3 rounded border border-gray-200 dark:border-[#3c3d3d]">
                                <div class="text-xs text-gray-500 dark:text-gray-400">Total Requests</div>
                                <div class="text-lg font-bold text-gray-900 dark:text-white">${results.summary.total_requests}</div>
                            </div>
                        `;
                    }

                    if (results.summary.avg_response_time) {
                        html += `
                            <div class="bg-white dark:bg-[#212121] p-3 rounded border border-gray-200 dark:border-[#3c3d3d]">
                                <div class="text-xs text-gray-500 dark:text-gray-400">Avg Response Time</div>
                                <div class="text-lg font-bold text-gray-900 dark:text-white">${results.summary.avg_response_time}</div>
                            </div>
                        `;
                    }

                    if (results.summary.failure_rate) {
                        html += `
                            <div class="bg-white dark:bg-[#212121] p-3 rounded border border-gray-200 dark:border-[#3c3d3d]">
                                <div class="text-xs text-gray-500 dark:text-gray-400">Failure Rate</div>
                                <div class="text-lg font-bold text-red-600">${results.summary.failure_rate}</div>
                            </div>
                        `;
                    }

                    html += '</div>';
                }

                if (results.output) {
                    html += `
                        <div class="mt-4">
                            <h5 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Full Output</h5>
                            <pre class="bg-gray-900 text-green-400 p-4 rounded text-xs overflow-x-auto font-mono">${escapeHtml(results.output)}</pre>
                        </div>
                    `;
                }

                html += '</div>';

                perfResultsContent.innerHTML = html;

                // Populate Analyst Result
                displayAnalystResult(results);

                // Reset AI Analyst tab
                document.getElementById('perfAiAnalystPlaceholder').classList.remove('hidden');
                document.getElementById('perfAiAnalystResult').classList.add('hidden');

                // Scroll to results
                perfResults.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }

            function switchPerfResultTab(tabName) {
                // Update tab buttons
                document.querySelectorAll('.perf-result-tab').forEach(tab => {
                    const isActive = tab.getAttribute('data-tab') === tabName;
                    if (isActive) {
                        // Active tab styling - raised tab effect
                        tab.classList.add('border-accent', 'text-accent',
                            'bg-gray-100', 'dark:bg-[#0a0a0a]',
                            'border-l', 'border-r', 'border-t',
                            'border-gray-200', 'dark:border-[#3c3d3d]',
                            'rounded-t-lg');
                        tab.classList.remove('border-transparent', 'text-gray-600', 'dark:text-gray-400',
                            'hover:text-gray-900', 'dark:hover:text-gray-200',
                            'hover:border-gray-300', 'dark:hover:border-gray-600');
                    } else {
                        // Inactive tab styling
                        tab.classList.remove('border-accent', 'text-accent',
                            'bg-gray-100', 'dark:bg-[#0a0a0a]',
                            'border-l', 'border-r', 'border-t',
                            'border-gray-200', 'dark:border-[#3c3d3d]',
                            'rounded-t-lg');
                        tab.classList.add('border-transparent', 'text-gray-600', 'dark:text-gray-400',
                            'hover:text-gray-900', 'dark:hover:text-gray-200',
                            'hover:border-gray-300', 'dark:hover:border-gray-600');
                    }
                });

                // Show/hide tab contents
                document.querySelectorAll('.perf-result-tab-content').forEach(content => {
                    content.classList.add('hidden');
                });
                document.getElementById(`perfResultTab${tabName.charAt(0).toUpperCase() + tabName.slice(1)}`).classList.remove('hidden');
            }

            function displayAnalystResult(results) {
                const perfAnalystContent = document.getElementById('perfAnalystContent');

                let html = '<div class="space-y-6">';

                // Check if HTTP traffic actually occurred
                const hasHttpTraffic = results.summary && results.summary.has_http_traffic !== false;

                // If no HTTP traffic, show error warning
                if (!hasHttpTraffic) {
                    html += `
                        <div class="bg-red-50 dark:bg-red-900 dark:bg-opacity-20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                            <div class="flex items-start gap-3">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <div>
                                    <h4 class="text-sm font-semibold text-red-800 dark:text-red-300 mb-2">No HTTP Traffic Detected</h4>
                                    <p class="text-sm text-red-700 dark:text-red-200">
                                        The test completed but did not generate any HTTP requests. This means the endpoint was not actually tested.
                                    </p>
                                    <p class="text-sm text-red-700 dark:text-red-200 mt-2">
                                        <strong>Possible causes:</strong><br>
                                        • Invalid URL format<br>
                                        • Network connectivity issues<br>
                                        • Script configuration error<br>
                                        • Server not responding
                                    </p>
                                    <p class="text-sm text-red-700 dark:text-red-200 mt-2">
                                        <strong>Recommendation:</strong> Check the endpoint URL and try again.
                                    </p>
                                </div>
                            </div>
                        </div>
                    `;
                    perfAnalystContent.innerHTML = html + '</div>';
                    return;
                }

                // Summary Cards
                if (results.summary) {
                    html += '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">';

                    // Performance Grade
                    const avgTime = results.summary.avg_response_time ? parseFloat(results.summary.avg_response_time) : 0;
                    const failureRate = results.summary.failure_rate ? parseFloat(results.summary.failure_rate) : 0;

                    let grade = 'A';
                    let gradeColor = 'green';
                    let gradeText = 'Excellent';

                    if (avgTime > 1000 || failureRate > 5) {
                        grade = 'F';
                        gradeColor = 'red';
                        gradeText = 'Poor';
                    } else if (avgTime > 500 || failureRate > 2) {
                        grade = 'C';
                        gradeColor = 'yellow';
                        gradeText = 'Fair';
                    } else if (avgTime > 200 || failureRate > 0.5) {
                        grade = 'B';
                        gradeColor = 'blue';
                        gradeText = 'Good';
                    }

                    html += `
                        <div class="bg-white dark:bg-[#212121] p-4 rounded-lg border border-gray-200 dark:border-[#3c3d3d]">
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Performance Grade</h3>
                            <div class="flex items-center gap-4">
                                <div class="text-5xl font-bold text-${gradeColor}-600">${grade}</div>
                                <div>
                                    <div class="text-lg font-semibold text-gray-900 dark:text-white">${gradeText}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Overall Performance</div>
                                </div>
                            </div>
                        </div>
                    `;

                    // Key Metrics
                    html += `
                        <div class="bg-white dark:bg-[#212121] p-4 rounded-lg border border-gray-200 dark:border-[#3c3d3d]">
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Key Metrics</h3>
                            <div class="space-y-2">
                                ${results.summary.total_requests ? `
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Total Requests</span>
                                        <span class="text-sm font-semibold text-gray-900 dark:text-white">${results.summary.total_requests}</span>
                                    </div>
                                ` : ''}
                                ${results.summary.avg_response_time ? `
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Avg Response Time</span>
                                        <span class="text-sm font-semibold text-gray-900 dark:text-white">${results.summary.avg_response_time}</span>
                                    </div>
                                ` : ''}
                                ${results.summary.failure_rate ? `
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Failure Rate</span>
                                        <span class="text-sm font-semibold text-red-600">${results.summary.failure_rate}</span>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    `;

                    html += '</div>';

                    // Analysis & Recommendations
                    html += '<div class="bg-white dark:bg-[#212121] p-4 rounded-lg border border-gray-200 dark:border-[#3c3d3d]">';
                    html += '<h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Analysis & Recommendations</h3>';
                    html += '<div class="space-y-3">';

                    // Response Time Analysis
                    if (avgTime > 0) {
                        html += '<div class="flex items-start gap-2">';
                        if (avgTime < 200) {
                            html += '<svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>';
                            html += `<div><p class="text-sm text-gray-900 dark:text-white font-medium">Response Time: Excellent</p><p class="text-xs text-gray-600 dark:text-gray-400">Average response time (${avgTime}ms) is very good.</p></div>`;
                        } else if (avgTime < 500) {
                            html += '<svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>';
                            html += `<div><p class="text-sm text-gray-900 dark:text-white font-medium">Response Time: Good</p><p class="text-xs text-gray-600 dark:text-gray-400">Average response time (${avgTime}ms) is acceptable. Consider optimizing for better performance.</p></div>`;
                        } else {
                            html += '<svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>';
                            html += `<div><p class="text-sm text-gray-900 dark:text-white font-medium">Response Time: Needs Improvement</p><p class="text-xs text-gray-600 dark:text-gray-400">Average response time (${avgTime}ms) is high. Consider caching, database optimization, or CDN usage.</p></div>`;
                        }
                        html += '</div>';
                    }

                    // Failure Rate Analysis
                    if (failureRate >= 0) {
                        html += '<div class="flex items-start gap-2">';
                        if (failureRate < 0.1) {
                            html += '<svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>';
                            html += `<div><p class="text-sm text-gray-900 dark:text-white font-medium">Reliability: Excellent</p><p class="text-xs text-gray-600 dark:text-gray-400">Failure rate (${failureRate}%) is very low.</p></div>`;
                        } else if (failureRate < 5) {
                            html += '<svg class="w-5 h-5 text-yellow-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>';
                            html += `<div><p class="text-sm text-gray-900 dark:text-white font-medium">Reliability: Moderate</p><p class="text-xs text-gray-600 dark:text-gray-400">Failure rate (${failureRate}%) detected. Investigate error handling and edge cases.</p></div>`;
                        } else {
                            html += '<svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>';
                            html += `<div><p class="text-sm text-gray-900 dark:text-white font-medium">Reliability: Critical Issue</p><p class="text-xs text-gray-600 dark:text-gray-400">High failure rate (${failureRate}%). Immediate attention required. Check server logs and error handling.</p></div>`;
                        }
                        html += '</div>';
                    }

                    html += '</div></div>';
                }

                html += '</div>';
                perfAnalystContent.innerHTML = html;
            }

            function showScriptModal(script) {
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
                modal.innerHTML = `
                    <div class="bg-white dark:bg-[#171717] rounded-lg max-w-3xl w-full max-h-[80vh] flex flex-col">
                        <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-[#2c2d2d]">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Generated k6 Script</h3>
                            <button class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200" onclick="this.closest('.fixed').remove()">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        <div class="flex-1 overflow-auto p-4">
                            <pre class="bg-gray-900 text-green-400 p-4 rounded text-sm overflow-x-auto font-mono">${escapeHtml(script)}</pre>
                        </div>
                        <div class="p-4 border-t border-gray-200 dark:border-[#2c2d2d] flex gap-3">
                            <button class="flex-1 px-4 py-2 bg-accent text-white rounded hover:bg-accent-hover" onclick="navigator.clipboard.writeText(\`${script.replace(/`/g, '\\`')}\`).then(() => showNotification('Copied to clipboard!', 'success', 2000))">
                                Copy to Clipboard
                            </button>
                            <button class="px-4 py-2 border border-gray-300 dark:border-[#3c3d3d] text-gray-700 dark:text-gray-300 rounded hover:bg-gray-50 dark:hover:bg-[#212121]" onclick="this.closest('.fixed').remove()">
                                Close
                            </button>
                        </div>
                    </div>
                `;
                document.body.appendChild(modal);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            init();
            initMonacoEditor();
            initModeToggle();
            initScenarioManagement();
            initPerformanceTest();

        });
    </script>
</body>
</html>