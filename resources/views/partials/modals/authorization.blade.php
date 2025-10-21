<div
    class="bg-white dark:bg-[#171717] rounded-xl p-6 w-full max-w-md max-h-[80vh] overflow-y-auto border dark:border-[#2c2d2d]">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Authentication</h3>
        <button class="text-gray-500 dark:text-gray-400 hover:text-[#2c2d2d] dark:hover:text-gray-200 text-2xl"
            id="closeAuth">×</button>
    </div>
    <div>
        <div class="bg-gray-50 dark:bg-[#212121] border border-gray-200 dark:border-0 rounded-lg p-4">
            <select
                class="w-full px-3 py-2 border border-gray-300 dark:border-0 rounded-md bg-white dark:bg-[#212121] text-gray-900 dark:text-white mb-4"
                id="authType">
                <option value="none">No Authentication</option>
                <option value="bearer">Bearer Token</option>
                <option value="basic">Basic Auth</option>
                <option value="apikey">API Key</option>
            </select>
            <div id="authInputs" class="mb-4">
                
            </div>
            <button
                class="w-full bg-accent hover:bg-accent-hover text-white font-semibold px-4 py-2 rounded-md transition-colors duration-200"
                id="saveAuth">Save Authentication</button>
        </div>
    </div>
</div>