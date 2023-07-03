import { SERVER_URL } from "./env.js";

const url = new URL(SERVER_URL);

const rps = 10;

window.onload = () => {

    const username = document.querySelector("#playerUsername");

    var interval;

    function setIntervalTimes(callable, ms) {
      interval = setTimeout(() => {
        callable();
        setIntervalTimes(callable, ms);
      }, ms)
    };

    connection().then((data) => {
        processMessage(data);
        interval = setIntervalTimes(update, 1000*rps);
    })

    async function connection(){
        try{
            const currDate = new Date();
            const response = await fetch(url+"lobby/connection", {
                method: "POST",
                body: JSON.stringify({
                    username: username.value,
                    timestamp: currDate
                }),
                headers: {
                    "Content-Type": "application/json; charset=UTF-8"
                },
            });
            return response.json();
        }catch(e){
            console.error("error", e);
            return;
        }
    }

    async function update(){
        try{
            const currDate = new Date();
            await fetch(url+"lobby/update", {
                method: "POST",
                body: JSON.stringify({
                    username: username.value,
                    timestamp: currDate
                }),
                headers: {
                    "Content-Type": "application/json; charset=UTF-8"
                }
            }).then((response) => {
                return response.json();
            }).then((data) => {
                processMessage(data);
            });
        }catch(e){
            console.error("error", e);
            return;
        }
    }

    function processMessage(message) {
    
        const { type, response } = message;
    
        switch (type) {
            case "connection": {
                const list = document.querySelector("#players_list");
                list.innerHTML = "";
                if(response.online && (response.online).length > 0){
                    for (var user of response.online) {
                        const li = document.createElement("li");
                        const p = document.createElement("p");
                        const i = document.createElement("i");
                        i.classList.add("bx", "bxs-circle", "online_circle");
                        p.appendChild(document.createTextNode(user));
                        p.appendChild(i)
                        li.appendChild(p);
                        list.appendChild(li);
                    }
                }
                break;
            }default: {
                console.log("default");
            }//case
        }//switch
    }

}//onload