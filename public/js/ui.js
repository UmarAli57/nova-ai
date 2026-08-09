export default class UI {
    static icons = {
        error: `<svg width="55" height="55" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg"><title>Error</title><path class="clr-i-solid clr-i-solid-path-1" d="M18 6a12 12 0 1 0 12 12A12 12 0 0 0 18 6m-1.49 6a1.49 1.49 0 0 1 3 0v6.89a1.49 1.49 0 1 1-3 0ZM18 25.5a1.72 1.72 0 1 1 1.72-1.72A1.72 1.72 0 0 1 18 25.5" fill="currentColor"/></svg>`,

        success: `<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 52 52"><path d="M26 2C12.7 2 2 12.7 2 26s10.7 24 24 24 24-10.7 24-24S39.3 2 26 2m13.4 18L24.1 35.5c-.6.6-1.6.6-2.2 0L13.5 27c-.6-.6-.6-1.6 0-2.2l2.2-2.2c.6-.6 1.6-.6 2.2 0l4.4 4.5c.4.4 1.1.4 1.5 0L35 15.5c.6-.6 1.6-.6 2.2 0l2.2 2.2c.7.6.7 1.6 0 2.3" fill="currentColor"/></svg>`,

        brain: `<svg width="20" height="20" viewBox="0 0 100 100" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
                <path d="M93.998 45.312c0-3.676-1.659-7.121-4.486-9.414a8.3 8.3 0 0 0 .184-1.706c0-4.579-3.386-8.382-7.785-9.037a8 8 0 0 0 .149-1.556c0-4.875-3.842-8.858-8.655-9.111a1.5 1.5 0 0 0-.242-.024c-.04 0-.079.005-.12.006-.04-.001-.079-.006-.12-.006-.458 0-.919.041-1.406.126a9.594 9.594 0 0 0-9.437-7.825c-5.311 0-9.632 4.321-9.632 9.633v65.918c0 6.723 5.469 12.191 12.191 12.191 4.46 0 8.508-2.413 10.646-6.246.479.104.939.168 1.401.198 2.903.185 5.73-.766 7.926-2.693a10.87 10.87 0 0 0 3.7-7.51 10.6 10.6 0 0 0-.403-3.638c3.796-2.691 6.027-6.952 6.027-11.621 0-3.385-1.219-6.635-3.445-9.224 2.24-2.268 3.507-5.302 3.507-8.461m-3.06 17.687c0 3.484-1.582 6.68-4.295 8.819-2.008-3.196-5.57-5.237-9.427-5.237a1.5 1.5 0 0 0 0 3 8.17 8.17 0 0 1 7.582 5.208c.41 1.088.592 2.189.521 3.274a7.88 7.88 0 0 1-2.685 5.449 7.88 7.88 0 0 1-5.752 1.954c-.594-.039-1.208-.167-1.933-.402a1.5 1.5 0 0 0-1.846.84 9.18 9.18 0 0 1-8.465 5.604c-5.068 0-9.191-4.123-9.191-9.191V16.399a6.64 6.64 0 0 1 6.632-6.633c3.398 0 6.194 2.562 6.558 5.908-2.751 1.576-4.612 4.535-4.612 7.926a1.5 1.5 0 1 0 3 0c0-3.343 2.689-6.065 6.016-6.13 3.327.065 6.016 2.787 6.016 6.129 0 .622-.117 1.266-.359 1.971a1.5 1.5 0 0 0-.074.63l-.01.035a1.5 1.5 0 0 0 1.552 1.866 5 5 0 0 0 .392-.046 6.143 6.143 0 0 1 6.136 6.136c0 .572-.103 1.159-.322 1.849a1.5 1.5 0 0 0 .591 1.7 9.1 9.1 0 0 1 4.014 7.242l-.001.012c0 5.03-4.092 9.123-9.122 9.123s-9.123-4.093-9.123-9.123a1.5 1.5 0 1 0-3 0c0 6.685 5.438 12.123 12.123 12.123 2.228 0 4.31-.615 6.106-1.668 1.92 2.09 2.978 4.763 2.978 7.55M38.179 6.766a9.59 9.59 0 0 0-9.435 7.825 8 8 0 0 0-1.407-.126c-.04 0-.079.005-.12.006-.04-.001-.079-.006-.12-.006-.083 0-.163.011-.242.024-4.813.253-8.654 4.236-8.654 9.111q0 .769.149 1.556c-4.399.655-7.785 4.458-7.785 9.037 0 .554.061 1.118.184 1.706a12.1 12.1 0 0 0-4.486 9.414c0 3.159 1.266 6.193 3.505 8.463C7.541 56.365 6.322 59.615 6.322 63c0 4.669 2.231 8.929 6.027 11.621a10.6 10.6 0 0 0-.402 3.639 10.86 10.86 0 0 0 3.699 7.509 10.9 10.9 0 0 0 7.926 2.693q.68-.045 1.4-.199a12.17 12.17 0 0 0 10.646 6.247c6.722 0 12.191-5.469 12.191-12.191v-65.92c.002-5.312-4.319-9.633-9.63-9.633m6.632 75.551c0 5.068-4.123 9.191-9.191 9.191a9.18 9.18 0 0 1-8.464-5.604 1.5 1.5 0 0 0-1.846-.84c-.724.235-1.338.363-1.933.402a7.87 7.87 0 0 1-5.751-1.954 7.88 7.88 0 0 1-2.685-5.449c-.076-1.16.125-2.336.598-3.495.007-.017.005-.036.011-.053 1.342-3.056 4.225-4.953 7.597-4.953a1.5 1.5 0 1 0 0-3 11.26 11.26 0 0 0-9.548 5.239c-2.701-2.139-4.277-5.327-4.277-8.802 0-2.787 1.06-5.46 2.978-7.549a12.04 12.04 0 0 0 6.107 1.668c6.685 0 12.123-5.438 12.123-12.123a1.5 1.5 0 1 0-3 0c0 5.03-4.092 9.123-9.123 9.123s-9.123-4.093-9.123-9.123l-.001-.006a9.1 9.1 0 0 1 4.013-7.248 1.5 1.5 0 0 0 .591-1.699c-.22-.691-.322-1.278-.322-1.85 0-3.376 2.741-6.125 6.195-6.125h.022q.154.022.311.034a1.5 1.5 0 0 0 1.54-1.907q.005-.053.007-.095a1.5 1.5 0 0 0-.081-.529c-.242-.707-.359-1.352-.359-1.972 0-3.342 2.688-6.065 6.016-6.129 3.328.065 6.016 2.787 6.016 6.13a1.5 1.5 0 1 0 3 0c0-3.391-1.861-6.35-4.612-7.926.364-3.346 3.16-5.908 6.558-5.908a6.64 6.64 0 0 1 6.632 6.633z" stroke="currentColor" fill="currentColor"/>
        </svg>`,

        learn: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 32 32" xml:space="preserve"><path d="M29.906 12.916c.799-.349.799-1.483 0-1.832L17.202 5.526a3 3 0 0 0-2.404.001L2.094 11.084c-.799.35-.799 1.483 0 1.832L8 15.5v3.32a3 3 0 0 0 1.658 2.683 14.18 14.18 0 0 0 12.683 0A3 3 0 0 0 24 18.82V15.5l4-1.75V21a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1v-7.688zM23 18.82a1.99 1.99 0 0 1-1.106 1.789C20.074 21.519 18.035 22 16 22s-4.074-.481-5.894-1.391A1.99 1.99 0 0 1 9 18.82v-2.882l5.798 2.536a3 3 0 0 0 2.404 0L23 15.937zm-6.198-1.262a2 2 0 0 1-1.604 0L2.495 12l12.703-5.558a2 2 0 0 1 1.604.001L29.505 12l-1.118.489-11.493-.919A.994.994 0 0 0 15 12a1 1 0 0 0 1 1 .98.98 0 0 0 .802-.434l9.646.772z" stroke="currentColor" fill="currentColor"/></svg>`,

        write: `<svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 64 64" xml:space="preserve"><path stroke="currentColor" fill="none" stroke-width="2" stroke-miterlimit="10" d="M20 54 10 44m0 0L1 62l1 1 18-9 43-43L53 1zm44-24L44 10m14 6L48 6"/><path stroke="currentColor" stroke-width="2" stroke-miterlimit="10" d="m5 54 4 1 1 4"/></svg>`,

        code: `<svg width="20" height="20" viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg"><path d="M19.498 7c-.182 0-.35.1-.437.26l-8.987 15.988c-.328.58.542 1.072.87.492L19.93 7.75a.498.498 0 0 0-.432-.75m3.03 2c.112.006.22.05.304.125l6.994 5.996c.232.2.232.56 0 .76l-6.994 5.995c-.48.43-1.14-.352-.652-.757l6.554-5.618-6.554-5.618c-.368-.308-.132-.882.348-.882zM7.472 9a.5.5 0 0 0-.304.125L.174 15.12a.502.502 0 0 0 0 .76l6.994 5.995c.48.43 1.14-.352.652-.757L1.266 15.5 7.82 9.882C8.188 9.574 7.952 9 7.472 9"  fill="currentColor" stroke="currentColor"/></svg>`,

        logo: `<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke-width="1.8"
            stroke-linecap="round" stroke-linejoin="round">
            <path
                d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
        </svg>`
    };

    static createUserMessage(message, files = [], date = null, isFileUploaded = true){
        const userFiles = UI.createHTMLElement("div", { classes: ["user-file-body"] });
        files.forEach(file => {
            const imgURL = isFileUploaded ? URL.createObjectURL(file) : file;
            const img = UI.createHTMLElement("img", { src: imgURL });

            userFiles.append(UI.createHTMLElement("div", {
                classes: ["user-file"],
                childNodes: [img]
            }));

            if (isFileUploaded){
                img.onload = () => {
                    URL.revokeObjectURL(imgURL);
                }
            }
        })

        const datetime = (date ? new Date(date) : new Date()).toLocaleString("en-GB", {
            day: "2-digit",
            month: "long",
            year: "numeric",
            hour: "2-digit",
            hour12: true,
            minute: "2-digit"
        }).replace(" at", ",");

        const childNodes = [
            UI.createHTMLElement("div", { classes: ['user-message-body'], text: message }),
            UI.createHTMLElement("div", { classes: ['date'], text: datetime })
        ];

        if (files.length > 0){
            childNodes.unshift(userFiles);
        }

        return UI.createHTMLElement("div", {
            classes: ["message", "user-message"],
            childNodes: childNodes
        });
    }

    static createModelMessage(appends = []){
        return UI.createHTMLElement("div", {
            classes: ['message', "model-message"],
            appends: appends
        });
    }

    static createModelMessageBody(message, appends = []){
        return UI.createHTMLElement("div", { classes: ['model-message-body'], html: message, appends: appends });
    }
    
    static createMessagePlaceholder(enableThinking = false){
        const dots = Array.from({length: 3}, () => UI.createHTMLElement("span"));
        const placeholder = UI.createHTMLElement("div", { classes: ["message-placeholder"], childNodes: dots });

        let thinking = null;
        if (enableThinking) thinking = UI.createHTMLElement("div", { classes: ['thinking'], text: "Deep thinking for more accurate results." });

        return UI.createModelMessage([UI.createModelMessageBody(null, [thinking, placeholder])]);
    }

    static createRequestProcessingDot(){
        return UI.createHTMLElement("span", { classes: ['processing-request'] })
    }
    
    static createFilePreview(file, id){
        let span = UI.createHTMLElement("span");

        let fileURL = URL.createObjectURL(file);
        const img = UI.createHTMLElement("img", { src: fileURL });

        const attachment = UI.createHTMLElement("div", {
            classes: ["attachment", "skeleton"],
            attributes: {"data-id": id},
            childNodes: [span]
        });

        img.onload = () => {
            URL.revokeObjectURL(fileURL);
            attachment.classList.remove("skeleton");
            attachment.append(img);
            span.innerHTML = "&times";
            span.classList.add("remove");
        };

        return attachment;
    }

    static injectWrapperOnElement(el, wrapperOptions = {}){
        const wrapper = UI.createHTMLElement("div", wrapperOptions);

        el.parentNode.insertBefore(wrapper, el);
        wrapper.append(el);
    }

    static createAlertMessage(title, message, isError = false){
        const alert = isError ? "error" : "success";

        return UI.createHTMLElement("div", {
            classes: ["alert", `alert-${alert}`],
            childNodes: [
                UI.createHTMLElement("div", { classes: ["alert-icon"], html: UI.icons[alert]}),
                UI.createHTMLElement("div", {
                    classes: ["alert-content"],
                    childNodes: [
                        UI.createHTMLElement("p", { classes: ["alert-title"], text: title }),
                        UI.createHTMLElement("p", { classes: ["alert-message"], text: message }),
                    ]
                }),
            ]
        })
    }

    static createHTMLElement(name, options = {}){
        const {
            text = "",
            html = "",
            classes = [],
            id = null,
            attributes = {},
            src = null,
            href = null,
            placeholder = null,
            type = null,
            childNodes = [],
            appends = [],
            prepends = [],
        } = options;

        let el = document.createElement(name);

        if (text) el.textContent = text;
        if (html) el.innerHTML = html;
        if (classes && classes.length > 0) el.classList.add(...classes);
        if (id) el.id = id;
        if (attributes) Object.entries(attributes).forEach(([key, val]) => el.setAttribute(key, val));
        if (src) el.src = src;
        if (href) el.href = href;
        if (placeholder) el.placeholder = placeholder;
        if (type) el.type = type;
        if (childNodes && childNodes.length > 0) el.append(...childNodes.filter((node) => node));
        if (prepends && prepends.length > 0) el.prepends(...prepends.filter((node) => node));
        if (appends && appends.length > 0) el.append(...appends.filter((node) => node));

        return el;
    }

    static createWelcomeContainer(title){
        return UI.createHTMLElement("div", {
            id: "welcome-container",
            childNodes: [
                UI.createHTMLElement("div", { classes: ["welcome-logo"], html: this.icons.logo }),
                UI.createHTMLElement("h1", { id: "welcome-title", text: title }),
                UI.createHTMLElement("p", { classes: ["welcome-msg"], text: "How can i help you today?" }),

                UI.createHTMLElement("div", { 
                    classes: ["suggestions"],  
                    childNodes: [
                        UI.createHTMLElement("div", { classes: ['suggest'], html: this.icons.code, appends: [
                            UI.createHTMLElement("span", { classes: ["block"], text: "Code" })  
                        ] }),
                        
                        UI.createHTMLElement("div", { classes: ['suggest'], html: this.icons.write, appends: [
                            UI.createHTMLElement("span", { classes: ["block"], text: "Write" })  
                        ] }),
                        
                        UI.createHTMLElement("div", { classes: ['suggest'], html: this.icons.learn, appends: [
                            UI.createHTMLElement("span", { classes: ["block"], text: "Learn" })  
                        ] }),
                        
                        UI.createHTMLElement("div", { classes: ['suggest'], html: this.icons.brain, appends: [
                            UI.createHTMLElement("span", { classes: ["block"], text: "Nova's Choice" })  
                        ] }),
                    ]
                }),
            ]
        });
    }

    static createMessagesContainer()
    {
        return UI.createHTMLElement("div", { id: 'messages-container' });
    }
}