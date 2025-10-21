<!-- Mobile Header for Scenario Mode -->
<div class="block md:hidden bg-white dark:bg-[#0a0a0a] border-b border-gray-200 dark:border-[#2c2d2d] p-4">
    <div class="flex items-center justify-between">
        <button
            class="mobile-menu-btn p-2 rounded-md hover:bg-gray-100 dark:hover:bg-green-800 transition-colors duration-200"
            id="mobileMenuBtnScenario">
            <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        <div class="flex-1 text-center">
            <h1 class="text-lg font-bold text-gray-900 dark:text-white">API Scenarios</h1>
            <p class="text-xs text-gray-600 dark:text-gray-400">Test API Workflows</p>
        </div>
        <div class="flex gap-1">
            <button
                class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-green-800 transition-colors duration-200"
                onclick="resetToCleanCreateState(); openScenarioModal();" title="New Scenario">
                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </button>
            <button
                class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-green-800 transition-colors duration-200"
                onclick="exportAllScenarios()" title="Export All Scenarios">
                <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
            </button>
            <button
                class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-green-800 transition-colors duration-200"
                onclick="openImportModal()" title="Import Scenarios">
                <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                </svg>
            </button>
        </div>
    </div>
</div>

<div class="w-full mx-auto p-4 sm:p-6">
    
    <div class="mb-6">
        <!-- Desktop Header (hidden on mobile) -->
        <div class="hidden md:flex flex-col lg:flex-row lg:items-center lg:justify-between mb-4 gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">API Scenarios</h1>
                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-300 mt-1">Create and manage collections of API requests for comprehensive testing</p>
            </div>
            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                
                <button class="bg-purple-100 hover:bg-purple-200 dark:bg-purple-900 dark:hover:bg-purple-800 text-purple-700 dark:text-purple-300 font-medium px-3 sm:px-4 py-2 rounded-lg transition-colors duration-200 flex items-center gap-1 sm:gap-2 text-sm" onclick="exportAllScenarios()" title="Export all scenarios">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <span class="hidden sm:inline">Export All</span>
                    <span class="sm:hidden">Export</span>
                </button>
                
                <button class="bg-gray-100 hover:bg-gray-200 dark:bg-[#2c2d2d] dark:hover:bg-[#3c3d3d] text-gray-700 dark:text-gray-300 font-medium px-3 sm:px-4 py-2 rounded-lg transition-colors duration-200 flex items-center gap-1 sm:gap-2 text-sm" onclick="openImportModal()">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                    </svg>
                    <span class="hidden sm:inline">Import JSON</span>
                    <span class="sm:hidden">Import</span>
                </button>
                
                <button class="bg-accent hover:bg-accent-hover text-white font-semibold px-3 sm:px-4 py-2 rounded-lg transition-colors duration-200 flex items-center gap-1 sm:gap-2 text-sm" id="createScenarioBtn">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span class="hidden sm:inline">New Scenario</span>
                    <span class="sm:hidden">New</span>
                </button>
            </div>
        </div>
        
        <div class="w-full sm:max-w-md">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" id="scenarioSearchInput" class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-[#2c2d2d] rounded-lg bg-white dark:bg-black text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-accent focus:border-accent text-sm" placeholder="Search scenarios..." onkeyup="searchScenarios(this.value)">
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6" id="scenariosGrid">
        
        <div class="bg-white dark:bg-[#171717] border-2 border-dashed border-gray-300 dark:border-[#2c2d2d] rounded-lg p-4 sm:p-6 hover:border-accent transition-all duration-200 cursor-pointer flex flex-col items-center justify-center min-h-[180px] sm:min-h-[200px]" id="addScenarioCard">
            <div class="w-12 h-12 bg-gray-100 dark:bg-[#2c2d2d] rounded-lg flex items-center justify-center mb-3">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <h3 class="font-medium text-gray-600 dark:text-gray-300 mb-1 text-sm sm:text-base">Create New Scenario</h3>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 text-center">Build a sequence of API requests for comprehensive testing</p>
        </div>
    </div>
</div>