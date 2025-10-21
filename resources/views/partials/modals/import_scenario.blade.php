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