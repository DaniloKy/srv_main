import { SERVER_URL, AUTHORIZATION_TOKEN } from "./env.js";

console.log(AUTHORIZATION_TOKEN)

const url = new URL(SERVER_URL);

window.onload = () => {

    var interval;

    function setIntervalTimes(callable, ms) {
      interval = setTimeout(() => {
        callable();
        setIntervalTimes(callable, ms);
      }, ms)
    };

    connectPromise().then((data) => {
        console.log("THEN", data);
        //processMessage(data);
        //interval = setIntervalTimes(sendAndRequest, 1000/rps);
    })

    async function connectPromise(){
        try{
            const currDate = new Date();
            const response = await fetch(url+"lobby", {
                method: "POST",
                body: JSON.stringify({
                    username: "asd",
                    timestamp: currDate
                }),
                headers: {
                    "Content-Type": "application/json; charset=UTF-8",
                    'Authorization': AUTHORIZATION_TOKEN,
                },
            });
            return response.json();
        }catch(e){
            console.error("error", e);
            return;
        }
    }

    async function sendAndRequest(){
        try{
            await fetch(url+"/lobby/update", {
                method: "GET",
            }).then((response) => {
                return response.json();
            }).then((data) => {
                processMessage(data);
            });
        }catch(e){
            console.error("error: ", e);
            clearInterval(interval);
            return;
        }
    }
}//onload


function processMessage(message) {

    //const data = JSON.parse(message.data);
    console.log(`[message] Data received from server: ${JSON.stringify(message)}`);

    const { type, response } = message;

    console.log(`[response] Response: ${response}`);

    switch (type) {
        case "connected": {

            break;
        }
        case "sendAndRequest": {

            break;
        }default: {
            console.log("default");
        }//case
    }//switch
}