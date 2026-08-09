import { Marked } from "https://cdn.jsdelivr.net/npm/marked/lib/marked.esm.js";
import { markedHighlight } from "https://cdn.jsdelivr.net/npm/marked-highlight@2.2.4/+esm";
import hljs from "https://cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.11.1/build/es/highlight.min.js";
import DOMPurify from "https://cdn.jsdelivr.net/npm/dompurify@3/+esm";
import initOptionsMenu from "./modules/optionsMenu.js";
import initModelsMenu from "./modules/modelsMenu.js";
import initUserModal from "./modules/userModal.js";
import initAttachments from "./modules/attachments.js";
import UI from "./ui.js";
import { $, $$, disableBtn, enableBtn, resizeTextarea, showErrorMessage, toggleBtn, toggleStatusDot } from "./helpers.js";
import { attachments, chatsContainer, header, input } from "./dom.js";
import payload from "./payload.js";
import API from "./api.js";
import { ServerURLs } from "./constant.js";

initOptionsMenu();
initModelsMenu();
initUserModal();
initAttachments();

// Determine the states
const state = {
    _isRequestPending: false,
    rendering: true,
    isOnline: true,

    online(){
        toggleStatusDot(header.statusDot, true);
        state.isOnline = true;
    },
    offline(){
        toggleStatusDot(header.statusDot, false);
        showErrorMessage("Offline State Detected", "Your internet connection is lost. Please check your connection and try again.");
        state.isOnline = false;
    }
};

Object.defineProperty(state, "isRequestPending", {
    get (){
        return this._isRequestPending;
    },

    set (val){
        toggleBtn(input.send, !val);
        this._isRequestPending = val;
    }
});

// Online / Offline state
window.addEventListener("online", state.online);

window.addEventListener("offline", state.offline);

window.addEventListener("load", function (){
    $$("table", chatsContainer).forEach(table => {
        UI.injectWrapperOnElement(table, { classes: ["table-wrapper"] });
    });
    $$("pre", chatsContainer).forEach(pre => {
        UI.injectWrapperOnElement(pre, { classes: ["pre-wrapper"] });
    });

    // Disabled sendBtn
    disableBtn(input.send);

    this.scrollTo({
        behavior: "smooth",
        top: chatsContainer.scrollHeight 
    });

    // check is user internet working or not
    if (navigator.onLine) state.online();
    else state.offline();
});

window.addEventListener("load", async function (){
    const history = await API.get(ServerURLs.history);

    let container = null;

    if (history !== false && history.length > 0){
        container = UI.createMessagesContainer();

        history.forEach(chat => {
            if (chat.message_from.toLowerCase() === "user"){
                container.append(
                    UI.createUserMessage(chat.message, chat.files, chat.datetime, false)
                );
            }
            else{
                container.append(
                    UI.createModelMessage([
                        UI.createModelMessageBody(convertMarkdownToHTML(chat.message))
                    ])
                );
            }
        });
    }
    else{
        const { fullname, nickname } = payload.userinfo;

        let name = nickname !== "" ? nickname : (fullname !== "" ? fullname : "User");
        
        container = UI.createWelcomeContainer(`Hello, ${name}`);
    }

    chatsContainer.append(container);
});

// Observe whenever the chatsContainer subtree modified (means user send or recieve message)
const observer = new MutationObserver(() => {
    window.scrollTo({
        behavior: "smooth",
        top: chatsContainer.scrollHeight
    });
});

observer.observe(chatsContainer, {
    childList: true,
    subtree: true
});

input.chatarea.addEventListener("input", function (){
    resizeTextarea(this);
    const message = this.value.trim();
    toggleBtn(input.send, (message.length > 0 && !state.isRequestPending && state.isOnline));
    payload.message = message;
});

input.chatarea.addEventListener("keydown", function (e){
    if (e.key === "Enter" && !e.shiftKey){
        e.preventDefault();
        input.send.click();
    }
});

const marked = new Marked(
    markedHighlight({
        emptyLangClass: "hljs",
        langPrefix: "hljs language-",

        highlight(code, lang){
            const language = hljs.getLanguage(lang) ? lang : "plaintext";
            return hljs.highlight(code, { language }).value;
        }
    })
);

marked.use({ gfm: true, breaks: true });

function convertMarkdownToHTML(text){
    return DOMPurify.sanitize(
        marked.parse(text)
    );
}

function scheduleRenderingHTML(text, force = false, modelBodyNode){
    if (state.rendering || force){
        state.rendering = false;

        setTimeout(() => {
            state.rendering = true;

            modelBodyNode.innerHTML = convertMarkdownToHTML(text);

            // Apply wrapper elements
            $$("table", modelBodyNode).forEach(table => {
                UI.injectWrapperOnElement(table, { classes: ["table-wrapper"] });
            });
            $$("pre", modelBodyNode).forEach(table => {
                UI.injectWrapperOnElement(table, { classes: ["pre-wrapper"] });
            });
        }, 1000);
    }
}

input.send.addEventListener("click", async function (){
    if (input.chatarea.value.trim().length > 0 && !state.isRequestPending && state.isOnline){
        input.chatarea.value = "";
        attachments.previews.innerHTML = "";
        resizeTextarea(input.chatarea);

        const messagePlaceholder = UI.createMessagePlaceholder(payload.extended);
        const userMessage = UI.createUserMessage(payload.message, Object.values(payload.files), null, true);

        $("#welcome-container", chatsContainer)?.remove();

        createAndAppendMessagesInMessageContainer([
            userMessage,
            messagePlaceholder
        ]);
        
        state.isRequestPending = true;

        const formdata = new FormData();
        formdata.append("message", payload.message);
        formdata.append("model", payload.model);
        formdata.append("extended", payload.extended);
        formdata.append("userinfo", JSON.stringify(payload.userinfo));

        Object.values(payload.files).forEach(file => {
            formdata.append("files[]", file);
        });
        
        payload.message = "";
        payload.files = {};

        let buffer = "";
        let processingDot = null;

        const modelBody = UI.createModelMessageBody(null);
        const modelNode = UI.createModelMessage([modelBody]);
        createAndAppendMessagesInMessageContainer([modelNode]);

        const res = await API.post(ServerURLs.chat, formdata, (dataInChunk, step) => {
            if (step === 1) {
                messagePlaceholder.remove();
                processingDot = UI.createRequestProcessingDot();
                modelNode.append(processingDot);
            }

            buffer += dataInChunk;

            scheduleRenderingHTML(buffer, false, modelBody);

        }, (error) => {
            messagePlaceholder.remove();
            showErrorMessage(error.title, error.message)
        });
        
        if (buffer.length > 0){
            // forcefully re-render html
            scheduleRenderingHTML(buffer, true, modelBody);
        }
        processingDot?.remove();
        state.isRequestPending = false;
    }
});

function createAndAppendMessagesInMessageContainer(appends){
    let messagesContainer = $("#messages-container", chatsContainer);

    if (!messagesContainer){
        messagesContainer = UI.createMessagesContainer();
        chatsContainer.append(messagesContainer);
    }

    messagesContainer.append(...appends);
}