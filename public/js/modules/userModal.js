import { header, userModal } from "../dom.js";
import { closeModal, getUserinfo, openModal, sanitizeInput, setUserinfo } from "../helpers.js";
import payload from "../payload.js";

export default function initUserModal(){
    window.addEventListener("DOMContentLoaded", function (){
        const {
            fullname = "",
            nickname = ""
        } = getUserinfo();

        userModal.fields.fullname.value = fullname;
        userModal.fields.nickname.value = nickname;
        payload.userinfo.fullname = fullname;
        payload.userinfo.nickname = nickname;

        header.username.textContent = nickname || "User";
    });

    document.addEventListener("keydown", function (e){
        if (e.key === "Escape"){
            userModal.cancel.click();
        }
        else if (e.key === "q" && e.ctrlKey){
            userModal.trigger.dispatchEvent(new Event("dblclick"));
        }
    });

    userModal.trigger.addEventListener("dblclick", function (){
        openModal(userModal.modal, this);
        userModal.fields.fullname.focus()
    });

    userModal.cancel.addEventListener("click", function (){
        closeModal(userModal.modal, userModal.trigger);
    });

    Object.values(userModal.fields).forEach(field => {
        field.addEventListener("input", function (){
            sanitizeInput(this);
        });

        field.addEventListener("keydown", function (e){
            if (e.key === "Enter" && !e.shiftKey){
                userModal.save.click();
            }
        });
    });

    userModal.save.addEventListener("click", function (){
        const fullname = userModal.fields.fullname.value.trim();
        const nickname = userModal.fields.nickname.value.trim();

        setUserinfo(fullname, nickname);
        payload.userinfo.fullname = fullname;
        payload.userinfo.nickname = nickname;
        
        header.username.textContent = nickname || "User";
        
        userModal.cancel.click();
    });
}