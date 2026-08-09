import API from "../api.js";
import { ServerURLs } from "../constant.js";
import { options } from "../dom.js";
import { applyTheme, closeMenu, disableBtn, enableBtn, showSuccessMessage, toggleMenu } from "../helpers.js";

export default function initOptionsMenu(){
    window.addEventListener("DOMContentLoaded", function (){
        applyTheme(false, options.theme);
    });

    document.addEventListener("click", function (e){
        if (!e.target.contains(options.menu)){
            closeMenu(options.menu, options.trigger);
        }
    });

    options.trigger.addEventListener("click", function (e){
        e.stopPropagation();
        toggleMenu(options.menu, this);
    });

    options.menu.addEventListener("click", (e) => e.stopPropagation());

    options.theme.addEventListener("change", function (){
        applyTheme(true, null);
    });

    options.delete.addEventListener("click", async function (){
        disableBtn(this);
        
        if (confirm("Are you sure you want to delete your chat history?")){
            const delete_id = await API.getDeleteID();
    
            if (!delete_id) return;
    
            await API.delete(ServerURLs.delete, delete_id);

            // reload the entire page after the successful deleted history
            window.location.reload();
        }

        options.trigger.click();
        enableBtn(this);
    });
}