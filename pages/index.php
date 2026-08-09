<!DOCTYPE html>
<html lang="en" class="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova AI Chat Bot</title>
    <link rel="stylesheet" href="<?= asset('css/output.css') ?>">
    <!-- Github Dark Themes --- Dracula -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/highlight.js/styles/base16/dracula.min.css">
</head>
<body class="w-full h-auto min-h-screen relative bg-light dark:bg-dark font-sans">
    <!-- ═══════════════════════════════
       Header Area
    ═══════════════════════════════ -->
    <header class="fixed top-0 left-0 right-0 z-100 w-full h-13 py-2 px-5 md:px-8 lg:px-10 flex-between bg-light/80 dark:bg-dark/80 backdrop-blur-xs">
        <div class="h-full flex-center">
            <h3 class="font-bold text-2xl text-primary dark:text-secondary">
                Nova AI
            </h3>
        </div>

        <div class="h-full flex-center gap-3 sm:gap-5 md:gap-7">
            <!-- Online status -->
            <div class="flex-center gap-2 px-3 py-1 rounded-lg border border-transparent active:border-black/20 active:dark:border-white/20">
                <span id="statusDot" class="w-2.5 h-2.5 rounded-full animate-pulse relative after:absolute after:content-[''] after:w-2.5 after:h-2.5 after:animate-ping after:rounded-full status-online"></span>

                <div class="relative group inline-block w-auto">
                    <span id="helloUser" class="text-xs font-medium text-gray-900 dark:text-white select-none cursor-pointer font-domine" aria-expanded="false" aria-haspopup="dialog" aria-controls="infoModal">Hello, 
                        <span id="username" class="text-gray-600 dark:text-white/80">Umar Ali</span>
                    </span>
                    <div class="tooltip tooltip-left">
                        Double click or press (Ctrl + Q) to change your info
                    </div>
                </div>
            </div>

            <div class="relative">
                <div class="relative group inline-block w-auto">
                    <button id="options" class="rounded-lg p-2 cursor-pointer hover:bg-dark/10 active:bg-dark/10 hover:dark:bg-light/30 active:dark:bg-light/30 text-dark dark:text-light" aria-expanded="false">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" class="text-deep/80">
                            <circle cx="5" cy="12" r="2" />
                            <circle cx="12" cy="12" r="2" />
                            <circle cx="19" cy="12" r="2" />
                        </svg>
                    </button>  
                    <div class="tooltip tooltip-bottom">
                        Options
                    </div>
                </div>

                <div id="options-menu" role="menu" class="absolute top-11 right-0 rounded-2xl border border-color-20 w-45 p-2 bg-white dark:bg-dark-muted transition-all origin-top is-menu-close **:select-none">
                    <div class="w-full p-2 pt-1 relative flex-center">
                        <input type="checkbox" name="theme" id="theme" 
                            class="appearance-none w-full h-11 peer rounded-lg relative bg-light dark:bg-dark cursor-pointer after:absolute after:content-[''] after:w-1/2 after:h-full after:rounded-lg after:inset-0 after:bg-secondary/25 dark:after:bg-light/10 after:backdrop-blur-xl checked:after:translate-x-full after:transition-all after:duration-200">

                        <!-- Sun Svg -->
                        <svg class="w-8 h-8 absolute left-7 transition-all pointer-events-none text-dark/90 dark:text-light/90 peer-checked:text-dark/60 dark:peer-checked:text-light/60 peer-checked:scale-90 scale-100" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12 3V4M12 20V21M4 12H3M6.31412 6.31412L5.5 5.5M17.6859 6.31412L18.5 5.5M6.31412 17.69L5.5 18.5001M17.6859 17.69L18.5 18.5001M21 12H20M16 12C16 14.2091 14.2091 16 12 16C9.79086 16 8 14.2091 8 12C8 9.79086 9.79086 8 12 8C14.2091 8 16 9.79086 16 12Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g>
                        </svg>

                        <!-- Moon Svg -->
                        <svg class="w-8 h-8 absolute right-6.5 transition-all text-dark/60 dark:text-light/60 peer-checked:text-dark/90 dark:peer-checked:text-light/90 pointer-events-none select-none peer-checked:scale-100 scale-90" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="1.5">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M21.0672 11.8568L20.4253 11.469L21.0672 11.8568ZM12.1432 2.93276L11.7553 2.29085V2.29085L12.1432 2.93276ZM21.25 12C21.25 17.1086 17.1086 21.25 12 21.25V22.75C17.9371 22.75 22.75 17.9371 22.75 12H21.25ZM12 21.25C6.89137 21.25 2.75 17.1086 2.75 12H1.25C1.25 17.9371 6.06294 22.75 12 22.75V21.25ZM2.75 12C2.75 6.89137 6.89137 2.75 12 2.75V1.25C6.06294 1.25 1.25 6.06294 1.25 12H2.75ZM15.5 14.25C12.3244 14.25 9.75 11.6756 9.75 8.5H8.25C8.25 12.5041 11.4959 15.75 15.5 15.75V14.25ZM20.4253 11.469C19.4172 13.1373 17.5882 14.25 15.5 14.25V15.75C18.1349 15.75 20.4407 14.3439 21.7092 12.2447L20.4253 11.469ZM9.75 8.5C9.75 6.41182 10.8627 4.5828 12.531 3.57467L11.7553 2.29085C9.65609 3.5593 8.25 5.86509 8.25 8.5H9.75ZM12 2.75C11.9115 2.75 11.8077 2.71008 11.7324 2.63168C11.6686 2.56527 11.6538 2.50244 11.6503 2.47703C11.6461 2.44587 11.6482 2.35557 11.7553 2.29085L12.531 3.57467C13.0342 3.27065 13.196 2.71398 13.1368 2.27627C13.0754 1.82126 12.7166 1.25 12 1.25V2.75ZM21.7092 12.2447C21.6444 12.3518 21.5541 12.3539 21.523 12.3497C21.4976 12.3462 21.4347 12.3314 21.3683 12.2676C21.2899 12.1923 21.25 12.0885 21.25 12H22.75C22.75 11.2834 22.1787 10.9246 21.7237 10.8632C21.286 10.804 20.7293 10.9658 20.4253 11.469L21.7092 12.2447Z" fill="currentColor"></path> </g>
                        </svg>
                    </div>

                    <div class="h-px bg-dark/15 dark:bg-light/25 mx-2 mt-1 mb-2"></div>

                    <button id="delete-chats" class="w-full flex-start-center gap-3 px-3 py-2.5 rounded-xl hover:bg-red-100 dark:hover:bg-red-500/20 text-left text-red-800/80 dark:text-red-500 select-none cursor-pointer active:scale-98 active:bg-red-100 dark:active:bg-red-500/20">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="block relative bottom-px">
                            <polyline points="3 6 5 6 21 6" />
                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                            <path d="M10 11v6M14 11v6" />
                            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                        </svg>
                        <span class="text-sm font-medium block font-domine">Delete chats</span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- ═══════════════════════════════
       CHAT AREA
    ═══════════════════════════════ -->

    <div id="chats-container" class="flex-1 pt-20 pb-50 min-h-screen absolute top-0 overflow-y-auto w-full">
        
    </div>

    <!-- ═══════════════════════════════
       Footer (Input) Area
    ═══════════════════════════════ -->
    <div class="fixed bottom-0 w-full h-auto px-3">
        <div class="w-full flex-center flex-col gap-1">
            <div class="z-101 max-w-205 w-full px-4 pb-2 pt-2 h-auto border border-color-10 rounded-2xl flex-center-start flex-col gap-0 bg-white/90 dark:bg-dark-muted/90 backdrop-blur-sm transition duration-200 focus-within:border-dark/20 focus-within:dark:border-white/25">
                
                <div id="attachments-container" class="w-full h-auto flex-start-center overflow-auto gap-3"></div>
                
                <textarea name="chatarea" id="chatarea" rows="1" placeholder="Ask Anything..."
                    class="block p-1 pt-2 appearance-none w-full h-auto text-sm md:text-base resize-none text-black dark:text-white outline-none placeholder:text-dark/70 dark:placeholder:text-light/70 mb-2"></textarea>

                <div id="actions" class="w-full p-1 flex-between-center">
                    <div class="relative group inline-block w-auto">
                        <button id="attachBtn" class="w-8 h-8 rounded-lg flex-center cursor-pointer text-black/80 dark:text-light/70 hover:bg-dark/10 hover:dark:bg-light/20">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48" />
                            </svg>
                            <input type="file" id="attachments" multiple max="3" accept="image/*" hidden>
                        </button>
                        <div class="tooltip tooltip-right">
                            Click or press (Ctrl + /) to select files
                        </div>
                    </div>

                    <div class="flex-center gap-4">
                        <div class="relative">
                            <button id="selectModel" class="group px-3 py-1.5 rounded-lg hover:bg-dark/10 hover:dark:bg-light/10 cursor-pointer flex-center gap-2 select-none"
                            aria-expanded="false">
                                <span id="modelLabel"
                                    class="block text-xs md:text-[0.8rem] tracking-wide text-black/80 dark:text-white/90 capitalize">Nova 2.0</span>
                                <span id="extendedLabel"
                                    class="text-dark/60 dark:text-light/60 text-xs md:text-[0.8rem] hidden">Extended</span>
                                <svg id="model-trigger-chevron"
                                    class=" text-dark/50 dark:text-light/70 transition group-aria-expanded:rotate-180"
                                    width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                                    class="text-mid/50">
                                    <polyline points="6 9 12 15 18 9" />
                                </svg>
                            </button>

                            <div id="models-menu"
                                class="absolute bottom-11 -right-14 sm:-right-6 rounded-2xl border border-color-10 shadow-sm w-73 p-3 bg-white dark:bg-dark-muted transition-all origin-bottom **:select-none space-y-1 is-menu-close z-99">
                                <span class="block text-xs text-dark/60 dark:text-light/70 tracking-wider mb-2">Select
                                    Model</span>

                                <button id="nova-default" data-model="nova-2.0"
                                    class="w-full flex-between-center px-3 py-1 rounded-lg cursor-pointer hover:bg-light/70 hover:dark:bg-light/20 checked">
                                    <div>
                                        <p class="model text-sm text-dark dark:text-light text-start">Nova 2.0</p>
                                        <span class="text-xs text-dark/50 dark:text-light/50 text-start block">
                                            Fast · Smart · Everyday tasks
                                        </span>
                                    </div>
                                    <span
                                        class="block text-dark dark:text-light text-sm opacity-0 font-bold [.checked_&]:opacity-100">&check;</span>
                                </button>

                                <button id="nova-pro" data-model="nova-pro"
                                    class="w-full flex-between-center px-3 py-1 rounded-lg cursor-pointer hover:bg-light/70 hover:dark:bg-light/15">
                                    <div>
                                        <p class="model text-sm text-dark dark:text-light text-start">Nova Pro</p>
                                        <span class="text-xs text-dark/50 dark:text-light/50 text-start block">
                                            Code · Maths · Complex tasks
                                        </span>
                                    </div>
                                    <span
                                        class="block text-dark dark:text-light font-bold text-sm opacity-0 [.checked_&]:opacity-100">&check;</span>
                                </button>

                                <button id="nova-writes" data-model="nova-writes"
                                    class="w-full flex-between-center px-3 py-1 rounded-lg cursor-pointer hover:bg-light/70 hover:dark:bg-light/15">
                                    <div>
                                        <p class="model text-sm text-dark dark:text-light text-start">Nova Writes</p>
                                        <span class="text-xs text-dark/50 dark:text-light/50 text-start block">
                                            Ideas · Content Writing · Novels 
                                        </span>
                                    </div>
                                    <span
                                        class="block text-dark dark:text-light font-bold text-sm opacity-0 [.checked_&]:opacity-100">&check;</span>
                                </button>

                                <div class="h-px bg-dark/15 dark:bg-light/25 mx-2 mt-1 mb-2"></div>

                                <div class="w-full flex-between-center px-3 py-1 rounded-lg relative">
                                    <div>
                                        <p class="text-sm text-dark dark:text-light text-start">Extended Thinking</p>
                                        <span class="text-xs text-dark/50 dark:text-light/50 text-start block">Think
                                            longer for complex tasks.</span>
                                    </div>
                                    <div>
                                        <input type="checkbox" id="extend-thinking" class="switch border-color-10">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button id="sendBtn"
                            class="rounded-full w-9 h-9 cursor-pointer flex-center bg-primary dark:bg-light text-light dark:text-dark active:scale-90 transition hover:bg-dark/90 hover:dark:bg-light/90">
                            <svg class="relative -top-px" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 19V5M5 12l7-7 7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div id="hint"
                class="z-101 w-full text-center text-[0.7rem] md:text-[0.75rem] py-1 text-dark/40 dark:text-light/25 bg-light dark:bg-dark">
                Nova AI can make mistake. Please double check the response.
            </div>
        </div>
    </div>

    <!-- Modal Section -->
    <div id="userModal" role="dialog" aria-modal="false" class="w-full h-full max-h-screen z-200 fixed top-0 left-0 bg-light/70 dark:bg-dark/70 flex-center px-4 origin-top transition-all is-modal-close font-domine pointer-events-none">
        <div class="rounded-xl w-full max-w-160 h-auto p-5 md:p-7 space-y-8 bg-dark dark:bg-white relative -top-8">
            <h2 class="font-bold text-start text-2xl md:text-3xl tracking-wide text-white dark:text-black">
                Fill Your Information
            </h2>

            <div class="space-y-2">
                <label for="fullname" class="text-white/90 dark:text-black/90 text-base px-1 block">Enter Full Name</label>
                <input type="text" name="fullname" id="fullname" class="block w-full border border-white/20 dark:border-dark/30 py-2 px-4 rounded-lg outline-none text-white/80 dark:text-black/80 text-base transition focus:border-white/40 dark:focus:border-dark/80" placeholder="e.g. Tommy Shelby">
            </div>

            <div class="space-y-2">
                <label for="nickname" class="text-white/90 dark:text-black/90 text-base px-1 block">How do people call you?</label>
                <input type="text" name="nickname" id="nickname" class="block w-full border border-white/20 dark:border-dark/30 py-2 px-4 rounded-lg outline-none text-white/80 dark:text-black/80 text-base transition focus:border-white/40 dark:focus:border-dark/80" placeholder="e.g. Thomas">
            </div>

            <div class="flex-end-center gap-4">
                <button id="cancelInfo" class="py-2 px-4 text-sm cursor-pointer rounded-lg text-gray-300 dark:text-gray-600">Cancel</button>
                <button id="saveInfo" class="py-2 px-4 text-base cursor-pointer rounded-lg bg-white text-black dark:bg-black dark:text-white hover:bg-white/90 dark:hover:bg-black/80">Save</button>
            </div>
        </div>
    </div>

    <script src="<?= asset('js/script.js') ?>" type="module"></script>
</body>
</html>