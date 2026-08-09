import { MAX_UPLOADS_FILES } from "../constant.js";
import { attachments, input } from "../dom.js";
import { $$, generateString, showErrorMessage } from "../helpers.js";
import payload from "../payload.js";
import UI from "../ui.js";

export default function initAttachments(){
    input.chatarea.addEventListener("keydown", function (e){
        if (e.key === "/" && e.ctrlKey){
            attachments.input.click();
        }
    });

    attachments.trigger.addEventListener("click", function (){
        attachments.input.click();
    });

    attachments.input.addEventListener("change", function (e){
        let newFiles = Array.from(e.target.files);

        if ((Object.values(payload.files).length + newFiles.length) > MAX_UPLOADS_FILES){
            showErrorMessage("Max Files Upload Exceeded", `You can only attach ${MAX_UPLOADS_FILES} files at a time.`);
            this.value = "";
            return;
        }

        newFiles.forEach(file => {
            let key = generateString();
            payload.files[key] = file;
            attachments.previews.append(UI.createFilePreview(file, key))
        });
    });

    attachments.previews.addEventListener("click", function (e){
        if (e.target.classList.contains("remove")){
            const removeID = e.target.parentNode.dataset.id;
            delete payload.files[removeID];
    
            $$(".attachment", this).forEach(attach => {
                if (attach.dataset.id === removeID){
                    attach.remove();
                }
            });
        }
    });
}