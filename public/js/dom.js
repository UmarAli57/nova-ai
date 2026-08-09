import { $ } from "./helpers.js";

export const header = {
    statusDot: $("#statusDot"),
    username: $("#helloUser #username"),
};

export const options = {
    trigger: $("#options"),
    menu: $("#options-menu"),

    theme: $("#theme"),
    delete: $("#delete-chats"),
};

export const chatsContainer = $("#chats-container");

export const attachments = {
    trigger: $("#attachBtn"),
    input: $("#attachments"),
    previews: $("#attachments-container"),
};

export const models = {
    trigger: $("#selectModel"),
    menu: $("#models-menu"),
    label: $("#modelLabel"),
    extendLabel: $("#extendedLabel"),
    extended: $("#extend-thinking"),
};

export const input = {
    chatarea: $("#chatarea"),
    send: $("#sendBtn")
};

export const userModal = {
    trigger: $("#helloUser"),
    modal: $("#userModal"),
    fields: {
        fullname: $("#fullname"),
        nickname: $("#nickname")
    },
    cancel: $("#cancelInfo"),
    save: $("#saveInfo")
};