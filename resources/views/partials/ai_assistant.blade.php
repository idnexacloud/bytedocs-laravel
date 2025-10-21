<div class="absolute left-0 top-0 w-1 h-full cursor-col-resize bg-transparent hover:bg-accent transition-colors duration-200 z-10"
                id="resizeHandle"></div>
            
            <div class="p-4 border-b border-gray-200 dark:border-[#2c2d2d] flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-accent rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">AI Assistant</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Ask about this API</p>
                    </div>
                </div>
                <button class="p-1 rounded hover:bg-gray-200 dark:hover:bg-[#212121] transition-colors"
                    id="closeChatSidebar">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            
            <div class="flex-1 overflow-y-auto p-4 space-y-4" id="chatMessages">
                <div class="flex items-start gap-3">
                    <div class="w-6 h-6 bg-accent rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div
                        class="bg-white dark:bg-[#171717] rounded-lg p-3 text-sm text-gray-900 dark:text-white max-w-xs">
                        <p>Hi! I'm your AI assistant. I can help you understand this API, generate code examples,
                            explain endpoints, and answer questions about the documentation.</p>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Try asking: "How do I authenticate?" or
                            "Show me a POST example"</p>
                    </div>
                </div>
            </div>
            
            <div class="p-4">
                <div class="relative">
                    <label for="chatInput" class="sr-only">Ask me anything about this API</label>
                    <textarea id="chatInput" rows="1" placeholder="Ketik pertanyaanmu..."
                        class="w-full resize-none pr-14 pl-4 py-2 bg-white dark:bg-[#212121] border border-gray-200 dark:border-[#2c2d2d] text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-accent transition-all rounded-xl"
                        style="height:50px; max-height:100px;"></textarea>
                    <button id="sendChatMessage" type="button" aria-label="Send" title="Send"
                        class="absolute right-2 top-[43%] transform -translate-y-1/2 w-9 h-9 p-1.5 bg-accent hover:bg-accent-hover text-white rounded-full shadow focus:outline-none">
                        
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" />
                        </svg>
                    </button>
                </div>
            </div>