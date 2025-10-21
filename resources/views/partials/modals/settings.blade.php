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
        
    </div>
</div>