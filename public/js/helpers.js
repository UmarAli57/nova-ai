import { MAX_TEXTAREA_LINES, THEME_KEY, USER_INFO_KEY } from "./constant.js";
import UI from "./ui.js";

/* ═══════════════════════════════
       DOM Selection Helpers
    ═══════════════════════════════ */
export const $ = (el, parent = document) => parent.querySelector(el);
export const $$ = (el, parent = document) => parent.querySelectorAll(el);


/* ═══════════════════════════════
       Open/Close And Togglers Helpers
    ═══════════════════════════════ */

export function openModal(modal, trigger){
    modal.classList.add("is-modal-open");
    modal.classList.remove("is-modal-close");
    if (trigger){
        trigger.setAttribute("aria-expanded", true);
    }
}

export function closeModal(modal, trigger){
    modal.classList.add("is-modal-close");
    modal.classList.remove("is-modal-open");
    if (trigger){
        trigger.setAttribute("aria-expanded", false);
    }
}

export function openMenu(menu, trigger){
    menu.classList.add("is-menu-open");
    menu.classList.remove("is-menu-close");
    if (trigger){
        trigger.setAttribute("aria-expanded", true);
    }
}

export function closeMenu(menu, trigger){
    menu.classList.add("is-menu-close");
    menu.classList.remove("is-menu-open");
    if (trigger){
        trigger.setAttribute("aria-expanded", false);
    }
}

export function enableBtn(btn){
    btn.classList.remove("btn-disabled");
    btn.disabled = false;
}

export function disableBtn(btn){
    btn.classList.add("btn-disabled");
    btn.disabled = true;
}


export function toggleModal(modal, trigger){
    let expand = trigger.getAttribute("aria-expanded") === "true";
    expand ? closeModal(modal, trigger) : openModal(modal, trigger);
}

export function toggleMenu(menu, trigger){
    let expand = trigger.getAttribute("aria-expanded") === "true";
    expand ? closeMenu(menu, trigger) : openMenu(menu, trigger);
}

export function toggleBtn(btn, enable){
    enable ? enableBtn(btn) : disableBtn(btn);
}

export function toggleStatusDot(statusDot, isOnline = true){
    statusDot.classList.toggle("status-online", isOnline);
    statusDot.classList.toggle("status-offline", !isOnline);
}


/* ═══════════════════════════════
       Other Helpers
    ═══════════════════════════════ */

export function resizeTextarea(textarea){
    textarea.style.height = "auto";
    const lineHeight = parseInt(getComputedStyle(textarea).lineHeight);
    const maxHeight = lineHeight * MAX_TEXTAREA_LINES;
    textarea.style.height = Math.min(textarea.scrollHeight, maxHeight) + "px";
    textarea.style.overflowY = textarea.scrollHeight > maxHeight ? "auto" : "hidden"; 
}

export function applyTheme(toggle = false, checkbox = null){
    const isCurrThemeDark = localStorage.getItem(THEME_KEY) === "true";
    const root = document.documentElement;
    
    if (toggle){
        root.classList.toggle("dark", !isCurrThemeDark);
        localStorage.setItem(THEME_KEY, !isCurrThemeDark);
    }
    else{
        root.classList.toggle("dark", isCurrThemeDark)
        if (checkbox) checkbox.checked = isCurrThemeDark;
    }
}

export function sanitizeInput(input){
    input.value = input.value.replace(/^[\s]/, "");
    input.value = input.value.replace(/[\s]{2,}/, " ");
}

export function setUserinfo(fullname, nickname){
    localStorage.setItem(USER_INFO_KEY, JSON.stringify({fullname, nickname}));
}

export function getUserinfo(){
    return JSON.parse(localStorage.getItem(USER_INFO_KEY) || '{}');
}

export function generateString(length = 8){
    const characters = [
        "A","B","C","D","E","F","G","H","I","J","K","L","M",
        "N","O","P","Q","R","S","T","U","V","W","X","Y","Z",
        "a","b","c","d","e","f","g","h","i","j","k","l","m",
        "n","o","p","q","r","s","t","u","v","w","x","y","z",
        "0","1","2","3","4","5","6","7","8","9",
        "!","@","#","$","%","^","&","*","(",")",
        "_","+","-","=","[","]","{","}","|",
        ";",":",",",".","<",">","?","~",
        ";",":",",",".","<",">","?"
    ];

    let string = "";
    for(let i = 0; i < length; i++){
        let index = Math.floor(Math.random() * characters.length);
        string += characters[index];
    }
    return string;
}

export function showSuccessMessage(title, message){
    // Remove previous alert message
    $("div.alert")?.remove();

    const success = UI.createAlertMessage(title, message, false);
    document.body.prepend(success);
    
    success.addEventListener("animationend", () => success.remove());
}

export function showErrorMessage(title, message){
    // Remove previous alert message
    $("div.alert")?.remove();

    const error = UI.createAlertMessage(title, message, true);
    document.body.prepend(error);
    
    error.addEventListener("animationend", () => error.remove());
}