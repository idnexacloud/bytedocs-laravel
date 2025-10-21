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