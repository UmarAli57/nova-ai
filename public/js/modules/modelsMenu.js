import { AVAILABLE_MODELS } from "../constant.js";
import { models } from "../dom.js";
import { $$, closeMenu, toggleMenu } from "../helpers.js";
import payload from "../payload.js";

export default function initModelsMenu(){
    document.addEventListener("click", function (e){
        if (!e.target.contains(models.menu)){
            closeMenu(models.menu, models.trigger);
        }
    });

    models.trigger.addEventListener("click", function (e){
        e.stopPropagation();
        toggleMenu(models.menu, this);
    });

    models.menu.addEventListener("click", (e) => e.stopPropagation());

    $$("button", models.menu).forEach((model) => {
        model.addEventListener("click", function (){
            if (Object.values(AVAILABLE_MODELS).includes(model.dataset.model)){
                $$("button", models.menu).forEach(m => m.classList.remove("checked"));

                model.classList.add("checked");
                models.label.textContent = model.dataset.model.replace("-",  " ");
                payload.model = model.dataset.model;
            }
        });
    });

    models.extended.addEventListener("change", function (){
        payload.extended = this.checked;
        models.extendLabel.classList.toggle("hidden", !this.checked);
    });
}