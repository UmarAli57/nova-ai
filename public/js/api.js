import { ServerURLs } from "./constant.js";
import { showErrorMessage, showSuccessMessage } from "./helpers.js";

export default class API {
    static async post(endpoint, formData, onSuccessCallback, onErrorCallback)
    {
        try {
            const response = await fetch(endpoint, {
                method: "POST",
                body: formData
            });

            if (!response.ok){
                const json = await response.json();
                onErrorCallback(json);
                return false;
            }
            
            const reader = response.body.getReader();
            const decoder = new TextDecoder("UTF-8");
            let stepsCount = 0;

            while (true){
                const { done, value } = await reader.read();

                if (done) break;

                let chunk = decoder.decode(value, { stream: true });

                onSuccessCallback(chunk, ++stepsCount);
            }

            // release all incomplete bytes
            decoder.decode();

            return true;
        } catch (e){
            console.log(e);
            onErrorCallback({
                title: "Connection Error",
                message: "Something went wrong. Please try later"
            });
            return false;
        }
    }
    
    static async get(endpoint)
    {
        try {
            const response = await fetch(endpoint);

            const json = await response.json();

            if (!response.ok){
                showErrorMessage(json.title, json.message);
                return false;
            }

            const { code, data } = json;

            return data;

        } catch (e){
            showErrorMessage("Connection Error", "Something went wrong. Please try later");
            return false;
        }
    }

    static async delete(endpoint, delete_id)
    {
        try {
            const response = await fetch(`${endpoint}?delete_id=${delete_id}`, { method: "DELETE" });

            const json = await response.json();

            if (!response.ok){
                showErrorMessage(json.title, json.message);
                return false;
            }

            // Instead of this, reload the entire page
            // showSuccessMessage(json.title, json.message);
            return true;

        } catch (e){
            showErrorMessage("Connection Error", "Something went wrong. Please try later");
            return false;
        }   
    }

    static async getDeleteID()
    {
        try {
            const res = await fetch(ServerURLs.delete_id);

            const json = await res.json();

            if (!res.ok){
                showErrorMessage(json.title, json.message);
                return false;
            }

            return json.data.delete_id;
        } catch (e){
            showErrorMessage("Connection Error", "Something went wrong. Please try later");
            return false;
        }
    }
}