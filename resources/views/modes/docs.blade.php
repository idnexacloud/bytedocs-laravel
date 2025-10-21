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
            <div class="tab px-6 py-3 cursor-pointer border-b-2 border-transparent text-gray-600 dark:text-gray-300 hover:text-accent hover:border-accent transition-all duration-200"
                data-tab="performance">Performance Test</div>
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
    <div class="hidden" id="performance">
        <div class="mb-8">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Performance Test</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Run load tests against this endpoint using k6</p>

            <div class="bg-gray-50 dark:bg-[#171717] border border-gray-200 dark:border-[#2c2d2d] rounded-lg p-6">

                <!-- Driver Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Test Driver
                    </label>
                    <select id="perfTestDriver" class="w-full px-3 py-2 border border-gray-300 dark:border-[#3c3d3d] rounded-md bg-white dark:bg-[#212121] text-gray-900 dark:text-white text-sm">
                        <option value="k6" selected>k6 Local</option>
                        <option value="jmeter" disabled>JMeter (Coming Soon)</option>
                        <option value="locust" disabled>Locust (Coming Soon)</option>
                    </select>
                </div>

                <!-- Custom k6 Path (Optional) -->
                <div class="mb-6 bg-blue-50 dark:bg-blue-900 dark:bg-opacity-10 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Custom k6 Path (Optional)
                    </label>
                    <input type="text" id="perfK6CustomPath" placeholder="e.g., C:\ProgramData\chocolatey\bin\k6.exe"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-[#3c3d3d] rounded-md bg-white dark:bg-[#212121] text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-accent focus:border-transparent font-mono">
                    <p class="text-xs text-blue-700 dark:text-blue-300 mt-2">
                        Leave empty for auto-detect. Specify full path to k6 executable if auto-detect fails.
                    </p>
                </div>

                <!-- Mode Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        Test Mode
                    </label>
                    <div class="flex gap-3">
                        <button type="button" id="perfModeConstant" class="flex-1 px-4 py-3 border-2 border-accent bg-accent bg-opacity-10 dark:bg-opacity-20 text-accent rounded-lg text-sm font-medium transition-all duration-200 hover:bg-opacity-20">
                            <div class="flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Constant Load
                            </div>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Fixed number of users</p>
                        </button>
                        <button type="button" id="perfModeStages" class="flex-1 px-4 py-3 border-2 border-gray-300 dark:border-[#3c3d3d] bg-white dark:bg-[#212121] text-gray-600 dark:text-gray-400 rounded-lg text-sm font-medium transition-all duration-200 hover:border-accent hover:text-accent">
                            <div class="flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                                </svg>
                                Multi-Stage
                            </div>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Ramp up/down pattern</p>
                        </button>
                    </div>
                </div>

                <!-- Constant Mode Form -->
                <div id="perfConstantForm" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Virtual Users (VUs)
                                <span class="text-xs text-gray-500 dark:text-gray-400 font-normal">- Concurrent users</span>
                            </label>
                            <input type="number" id="perfConstantVUs" value="10" min="1" max="1000"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-[#3c3d3d] rounded-md bg-white dark:bg-[#212121] text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-accent focus:border-transparent">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Number of simultaneous virtual users</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Duration
                                <span class="text-xs text-gray-500 dark:text-gray-400 font-normal">- Test length</span>
                            </label>
                            <div class="flex gap-2">
                                <input type="number" id="perfConstantDuration" value="30" min="1" max="3600"
                                    class="flex-1 px-3 py-2 border border-gray-300 dark:border-[#3c3d3d] rounded-md bg-white dark:bg-[#212121] text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-accent focus:border-transparent">
                                <select id="perfConstantDurationUnit" class="px-3 py-2 border border-gray-300 dark:border-[#3c3d3d] rounded-md bg-white dark:bg-[#212121] text-gray-900 dark:text-white text-sm">
                                    <option value="s" selected>seconds</option>
                                    <option value="m">minutes</option>
                                </select>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">How long to run the test</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Ramp-up Time
                                <span class="text-xs text-gray-500 dark:text-gray-400 font-normal">- Optional</span>
                            </label>
                            <div class="flex gap-2">
                                <input type="number" id="perfConstantRampUp" value="0" min="0" max="600"
                                    class="flex-1 px-3 py-2 border border-gray-300 dark:border-[#3c3d3d] rounded-md bg-white dark:bg-[#212121] text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-accent focus:border-transparent">
                                <span class="px-3 py-2 text-sm text-gray-600 dark:text-gray-400">seconds</span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Time to reach target VUs (0 = instant)</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Iterations per VU
                                <span class="text-xs text-gray-500 dark:text-gray-400 font-normal">- Optional</span>
                            </label>
                            <input type="number" id="perfConstantIterations" value="" min="1" max="10000" placeholder="Unlimited"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-[#3c3d3d] rounded-md bg-white dark:bg-[#212121] text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-accent focus:border-transparent">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Max requests per user (leave empty for unlimited)</p>
                        </div>
                    </div>
                </div>

                <!-- Multi-Stage Mode Form -->
                <div id="perfStagesForm" class="hidden space-y-4">
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Test Stages</h4>
                        <button type="button" id="perfAddStage" class="px-3 py-1 bg-accent text-white text-xs rounded-md hover:bg-accent-hover transition-colors duration-200 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Stage
                        </button>
                    </div>

                    <div id="perfStagesList" class="space-y-3">
                        <!-- Stage 1 - Default -->
                        <div class="perf-stage bg-white dark:bg-[#212121] border border-gray-200 dark:border-[#3c3d3d] rounded-lg p-4" data-stage="1">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Stage 1</span>
                                <button type="button" class="perf-remove-stage text-red-500 hover:text-red-700 text-xs hidden">
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
                    </div>

                    <div class="bg-blue-50 dark:bg-blue-900 dark:bg-opacity-20 border border-blue-200 dark:border-blue-800 rounded-lg p-3">
                        <p class="text-xs text-blue-800 dark:text-blue-300 flex items-start gap-2">
                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <span>Stages run sequentially. Each stage gradually ramps to the target VU count over the specified duration.</span>
                        </p>
                    </div>
                </div>

                <!-- Advanced Options (Collapsible) -->
                <div class="mt-6 border-t border-gray-200 dark:border-[#3c3d3d] pt-4">
                    <button type="button" id="perfToggleAdvanced" class="flex items-center justify-between w-full text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-accent transition-colors">
                        <span>Advanced Options</span>
                        <svg class="w-4 h-4 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="perfAdvancedOptions" class="hidden mt-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Think Time (seconds)
                                <span class="text-xs text-gray-500 dark:text-gray-400 font-normal">- Delay between requests</span>
                            </label>
                            <input type="number" id="perfThinkTime" value="1" min="0" max="60" step="0.1"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-[#3c3d3d] rounded-md bg-white dark:bg-[#212121] text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-accent focus:border-transparent">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Pause between each request per VU</p>
                        </div>
                        <div>
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer">
                                <input type="checkbox" id="perfIncludeAuth" class="w-4 h-4 rounded border-gray-300 dark:border-[#3c3d3d] text-accent focus:ring-accent">
                                <span>Include Authentication Headers</span>
                            </label>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 ml-6">Use configured auth from Authentication modal</p>
                        </div>
                    </div>
                </div>

                <!-- Run Button -->
                <div class="mt-6 pt-4 border-t border-gray-200 dark:border-[#3c3d3d] flex gap-3">
                    <button type="button" id="perfRunTest" class="flex-1 bg-accent hover:bg-accent-hover text-white font-semibold px-6 py-3 rounded-md text-sm transition-colors duration-200 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Run Performance Test
                    </button>
                    <button type="button" id="perfGenerateScript" class="px-6 py-3 border-2 border-accent text-accent font-semibold rounded-md text-sm transition-colors duration-200 hover:bg-accent hover:text-white flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                        </svg>
                        View Script
                    </button>
                </div>

                <!-- Error Area (Initially Hidden) -->
                <div id="perfError" class="hidden mt-6 pt-6 border-t border-gray-200 dark:border-[#3c3d3d]">
                    <div class="bg-red-50 dark:bg-[#2d1414] border border-red-200 dark:border-red-900 rounded-lg p-4 relative">
                        <button type="button" id="perfDismissError" class="absolute top-3 right-3 text-red-400 hover:text-red-600 dark:text-red-500 dark:hover:text-red-300 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                        <div class="pr-8">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-red-800 dark:text-red-300 mb-2">Test Failed</h4>
                                    <div id="perfErrorContent" class="text-sm text-red-700 dark:text-red-200 whitespace-pre-wrap font-mono">
                                        <!-- Error message will be populated here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Results Area (Initially Hidden) -->
                <div id="perfResults" class="hidden mt-6 pt-6 border-t border-gray-200 dark:border-[#3c3d3d]">
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Test Results</h4>
                        <button type="button" id="perfClearResults" class="px-3 py-1 text-xs text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white border border-gray-300 dark:border-[#3c3d3d] rounded transition-colors">
                            Clear
                        </button>
                    </div>

                    <!-- Results Tabs -->
                    <div class="border-b border-gray-200 dark:border-[#2c2d2d] mb-4">
                        <div class="flex gap-1 -mb-px">
                            <button type="button" class="perf-result-tab px-4 py-2.5 text-sm font-medium border-b-2 border-accent text-accent bg-gray-100 dark:bg-[#0a0a0a] border-l border-r border-t border-gray-200 dark:border-[#3c3d3d] rounded-t-lg transition-all" data-tab="computer">
                                Computer View
                            </button>
                            <button type="button" class="perf-result-tab px-4 py-2.5 text-sm font-medium border-b-2 border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600 transition-all" data-tab="analyst">
                                Analyst Result
                            </button>
                            <button type="button" class="perf-result-tab px-4 py-2.5 text-sm font-medium border-b-2 border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600 transition-all" data-tab="ai">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                    AI Analyst
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- Tab Contents -->
                    <div id="perfResultTabComputer" class="perf-result-tab-content">
                        <div id="perfResultsContent" class="bg-gray-100 dark:bg-[#0a0a0a] border border-gray-200 dark:border-[#3c3d3d] rounded-lg p-4">
                            <!-- Computer view results will be populated here -->
                        </div>
                    </div>

                    <div id="perfResultTabAnalyst" class="perf-result-tab-content hidden">
                        <div id="perfAnalystContent" class="bg-gray-100 dark:bg-[#0a0a0a] border border-gray-200 dark:border-[#3c3d3d] rounded-lg p-4">
                            <!-- Analyst results will be populated here -->
                        </div>
                    </div>

                    <div id="perfResultTabAi" class="perf-result-tab-content hidden">
                        <div id="perfAiAnalystContent" class="bg-gray-100 dark:bg-[#0a0a0a] border border-gray-200 dark:border-[#3c3d3d] rounded-lg p-4">
                            <!-- AI analyst results will be populated here -->
                            <div id="perfAiAnalystPlaceholder" class="text-center py-8">
                                <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                                <p class="text-gray-600 dark:text-gray-400 mb-4">Click below to analyze results with AI</p>

                                <!-- Language Selector -->
                                <div class="mb-4 flex items-center justify-center gap-2">
                                    <label for="perfAiLanguage" class="text-sm text-gray-600 dark:text-gray-400">Analysis Language:</label>
                                    <select id="perfAiLanguage" class="px-3 py-1.5 text-sm border border-gray-300 dark:border-[#3c3d3d] rounded-md bg-white dark:bg-[#0a0a0a] text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-accent focus:border-transparent">
                                        <option value="en">English</option>
                                        <option value="id">Bahasa Indonesia</option>
                                        <option value="zh">中文 (Chinese)</option>
                                        <option value="ja">日本語 (Japanese)</option>
                                        <option value="ko">한국어 (Korean)</option>
                                        <option value="es">Español (Spanish)</option>
                                        <option value="fr">Français (French)</option>
                                        <option value="de">Deutsch (German)</option>
                                        <option value="pt">Português (Portuguese)</option>
                                        <option value="ru">Русский (Russian)</option>
                                    </select>
                                </div>

                                <button type="button" id="perfTriggerAiAnalyst" class="px-6 py-2 bg-accent hover:bg-accent-hover text-white rounded-md text-sm transition-colors flex items-center gap-2 mx-auto">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    Analyze with AI
                                </button>
                            </div>
                            <div id="perfAiAnalystResult" class="hidden"></div>
                        </div>
                    </div>
                </div>
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