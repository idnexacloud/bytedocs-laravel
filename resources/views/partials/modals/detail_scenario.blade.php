<div class="bg-white dark:bg-[#171717] rounded-lg shadow-xl max-w-6xl w-full max-h-full sm:max-h-[90vh] overflow-hidden flex flex-col">
    
    <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-[#2c2d2d] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
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
    
    <div class="flex-1 p-3 sm:p-6 overflow-y-auto min-h-0">
        
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
    
    <div class="flex-shrink-0 p-3 sm:p-6 border-t border-gray-200 dark:border-[#2c2d2d] flex flex-col sm:flex-row justify-between gap-3">
        <button class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#2c2d2d] rounded-lg transition-colors duration-200 sm:w-auto w-full" onclick="closeScenarioDetails()">
            Close
        </button>
        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
            <button class="bg-purple-100 hover:bg-purple-200 dark:bg-purple-900 dark:hover:bg-purple-800 text-purple-700 dark:text-purple-300 px-4 py-2 rounded-lg transition-colors duration-200 text-sm font-medium sm:w-auto w-full" onclick="exportScenarioFromDetails()">
                Export JSON
            </button>
            <button class="bg-accent hover:bg-accent-hover text-white px-4 py-2 rounded-lg transition-colors duration-200 text-sm font-medium sm:w-auto w-full" onclick="runScenarioFromDetails()">
                Run Scenario
            </button>
        </div>
    </div>
</div>