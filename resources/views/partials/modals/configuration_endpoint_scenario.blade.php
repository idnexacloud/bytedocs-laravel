<div class="bg-white dark:bg-[#171717] rounded-lg shadow-xl max-w-6xl w-full max-h-full sm:max-h-[90vh] overflow-hidden flex flex-col">
    
    <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-[#2c2d2d] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white" id="configModalTitle">Configure Request</h2>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1" id="configModalSubtitle">Customize request parameters, headers, and body</p>
        </div>
        <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200" id="closeConfigModal">
            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    
    <div class="flex-1 p-3 sm:p-6 overflow-y-auto min-h-0">
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
    
    <div class="flex-shrink-0 p-3 sm:p-6 border-t border-gray-200 dark:border-[#2c2d2d] flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3">
        <button class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 transition-colors duration-200 sm:w-auto w-full" id="cancelConfig">Cancel</button>
        <button class="bg-accent hover:bg-accent-hover text-white px-6 py-2 rounded-md font-medium transition-colors duration-200 sm:w-auto w-full" id="saveConfig">Save Configuration</button>
    </div>
</div>