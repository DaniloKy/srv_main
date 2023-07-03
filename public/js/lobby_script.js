import { SERVER_URL, AUTHORIZATION_TOKEN } from "./env.js";

console.log(AUTHORIZATION_TOKEN)

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
        console.log("THEN", data);
        processMessage(data);
        interval = setIntervalTimes(update, 1000*rps);
    })

    async function connection(){
        console.log("CONNECTIPN");
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
        console.log("UPDATE");
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

        //const data = JSON.parse(message.data);
        console.log(`[message] Data received from server: ${JSON.stringify(message)}`);
    
        const { type, response } = message;
    
        console.log(`[response] Response: ${response}`);
    
        switch (type) {
            case "connection": {
                console.log("connection", response.online);
                const list = document.querySelector("#players_list");
                list.innerHTML = "";
                if(response.online && (response.online).length > 0){
                    console.log("AFTER IF")
                    for (var user of response.online) {
                        console.log("USER", user)
    //<p>xXGamerXx<i class='bx bxs-circle online_circle'></i></p>
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