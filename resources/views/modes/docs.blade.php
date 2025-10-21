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
                    id="exportYamlBtnMobile" title="Export OpenAPI YAML">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
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
                id="exportYamlBtn" title="Export OpenAPI YAML">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export OpenAPI.yaml
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