<div class="bg-white dark:bg-[#171717] rounded-none lg:rounded-lg shadow-xl w-full h-full lg:max-w-6xl lg:w-full lg:mx-4 lg:max-h-[90vh] lg:h-auto overflow-hidden flex flex-col">
    
    <div class="flex-shrink-0 p-4 sm:p-6 border-b border-gray-200 dark:border-[#2c2d2d] flex items-center justify-between">
        <div>
            <h2 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white" id="scenarioModalTitle">Create New Scenario</h2>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">Build a sequence of API requests for testing workflows</p>
        </div>
        <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200" id="closeScenarioModal">
            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    
    <!-- Mobile Tab Navigation (visible only on mobile) -->
    <div class="lg:hidden flex-shrink-0 border-b border-gray-200 dark:border-[#2c2d2d]">
        <div class="flex">
            <button id="mobileInfoTab" class="flex-1 px-3 py-3 text-sm font-medium text-center border-b-2 border-accent text-accent bg-accent/5" onclick="switchMobileScenarioTab('information')">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="hidden sm:inline">Information</span>
                    <span class="sm:hidden">Info</span>
                </div>
            </button>
            <button id="mobileEndpointsTab" class="flex-1 px-3 py-3 text-sm font-medium text-center border-b-2 border-transparent text-gray-500 dark:text-gray-400" onclick="switchMobileScenarioTab('endpoints')">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    Endpoints
                </div>
            </button>
            <button id="mobileSequenceTab" class="flex-1 px-2 py-3 text-sm font-medium text-center border-b-2 border-transparent text-gray-500 dark:text-gray-400" onclick="switchMobileScenarioTab('sequence')">
                <div class="flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span>Sequence</span>
                </div>
            </button>
        </div>
    </div>

    <!-- Mobile Content Panels -->
    <div class="lg:hidden flex-1 overflow-hidden flex flex-col">
        <!-- Mobile Information Panel -->
        <div id="mobileInformationContent" class="flex-1 overflow-y-auto p-4 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Scenario Name</label>
                <input type="text" id="mobileScenarioName" class="w-full px-3 py-2 border border-gray-300 dark:border-[#2c2d2d] rounded-md bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-accent" placeholder="Enter scenario name">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                <textarea id="mobileScenarioDescription" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-[#2c2d2d] rounded-md bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-accent resize-none" placeholder="Describe what this scenario tests"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Execution Mode</label>
                <div class="flex gap-4">
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="mobileExecutionMode" value="waterfall" class="mr-2 text-accent focus:ring-accent" checked>
                        <span class="text-sm text-gray-700 dark:text-gray-300">Sequential</span>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="mobileExecutionMode" value="parallel" class="mr-2 text-accent focus:ring-accent">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Parallel</span>
                    </label>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Authentication</label>
                <div class="bg-gray-50 dark:bg-[#2c2d2d] border border-gray-200 dark:border-[#171717] rounded-lg p-3">
                    <select id="mobileScenarioAuthType" class="w-full px-2 py-1 text-sm border border-gray-300 dark:border-[#171717] rounded bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-accent mb-2">
                        <option value="none">No Authentication</option>
                        <option value="bearer">Bearer Token</option>
                        <option value="basic">Basic Auth</option>
                        <option value="apikey">API Key</option>
                    </select>
                    <div id="mobileScenarioAuthInputs" class="space-y-2"></div>
                </div>
            </div>
        </div>

        <!-- Mobile Endpoints Panel -->
        <div id="mobileEndpointsContent" class="flex-1 overflow-hidden p-4 hidden flex flex-col">
            <div class="flex flex-col h-full">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Available Endpoints</label>
                <div class="mb-3">
                    <input type="text" id="mobileEndpointSearch" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-[#2c2d2d] rounded bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-accent" placeholder="Search endpoints...">
                </div>
                <div class="border border-gray-300 dark:border-[#2c2d2d] rounded-md bg-white dark:bg-black flex-1 overflow-y-auto">
                    <div id="mobileAvailableEndpoints" class="p-3 space-y-2"></div>
                </div>
            </div>
        </div>

        <!-- Mobile Sequence Panel -->
        <div id="mobileSequenceContent" class="flex-1 overflow-hidden p-4 hidden flex flex-col">
            <div class="mb-4">
                <h3 class="font-medium text-gray-900 dark:text-white mb-2">Request Sequence</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Switch to Endpoints tab to add requests to this scenario
                </p>
            </div>
            <div id="mobileScenarioRequests" class="flex-1 border-2 border-dashed border-gray-300 dark:border-[#2c2d2d] rounded-lg p-3 overflow-y-auto space-y-2">
                <div class="text-center text-gray-500 dark:text-gray-400 py-8" id="mobileEmptyScenarioMessage">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    <p>Click on endpoints from the Endpoints tab to build your scenario</p>
                    <p class="text-xs mt-1">Requests will be executed in the order you add them</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Layout (hidden on mobile) -->
    <div class="hidden lg:flex lg:flex-1 lg:overflow-hidden">
        
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
                
                <div id="endpointsTabContent" class="p-3 sm:p-6 hidden">
                    <div class="space-y-3 sm:space-y-4">
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Available Endpoints</label>
                            <div class="mb-3">
                                <input type="text" id="endpointSearch" class="w-full px-2 sm:px-3 py-2 text-sm border border-gray-300 dark:border-[#2c2d2d] rounded bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-accent" placeholder="Search endpoints...">
                            </div>
                            <div class="border border-gray-300 dark:border-[#2c2d2d] rounded-md bg-white dark:bg-black max-h-[calc(100vh-300px)] sm:max-h-[calc(100vh-400px)] overflow-y-auto">
                                <div id="availableEndpoints" class="p-2 sm:p-3 space-y-2">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="w-2/3 flex flex-col border-l border-gray-200 dark:border-[#2c2d2d]">
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
    
    <div class="flex-shrink-0 p-3 sm:p-6 border-t border-gray-200 dark:border-[#2c2d2d] flex flex-col gap-3">
        <!-- Mobile: Stack buttons vertically -->
        <div class="sm:hidden flex flex-col gap-2">
            <div class="flex gap-2">
                <button class="flex-1 px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors duration-200 scenario-cancel-btn">Cancel</button>
                <button class="flex-1 bg-accent hover:bg-accent-hover text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 scenario-save-btn">Save Scenario</button>
            </div>
            <button class="w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-200 border border-red-300 dark:border-red-600 rounded-md transition-colors duration-200 scenario-left-btn">Reset Form</button>
        </div>
        
        <!-- Desktop: Original layout -->
        <div class="hidden sm:flex sm:items-center sm:justify-between">
            <button class="px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-200 border border-red-300 dark:border-red-600 rounded-md transition-colors duration-200 scenario-left-btn" id="leftScenarioButton">Reset Form</button>
            <div class="flex gap-3">
                <button class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors duration-200 scenario-cancel-btn" id="cancelScenario">Cancel</button>
                <button class="bg-accent hover:bg-accent-hover text-white px-6 py-2 rounded-md text-sm font-medium transition-colors duration-200 scenario-save-btn" id="saveScenario">Save Scenario</button>
            </div>
        </div>
    </div>
</div>