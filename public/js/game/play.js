import { SERVER_URL } from "../env.js";

const url = SERVER_URL;

let socket;

let player;

/*
  *GAME*
*/

window.onload = () => {

  spinBlade_skill = new Skill('spinBlade');
  seflHeal_skill = new Skill('selfHeal');
  protectionOrb_skill = new Skill('protectionOrb');

  fighter_skills = [
    spinBlade_skill, seflHeal_skill, protectionOrb_skill
  ];

  fighter_class = new Player_class("fighter", fighter_skills);

  for(const skill of fighter_class.skills){
    
  }

  player = new Player(123, "Ben", fighter_class.class_name)

  console.log(player);

  let positions = {x: 0, y: 0};
  const MAX_VEL = 0.5;
  let velocity = {vxl: 0, vxr: 0, vyl: 0, vyr: 0};

  //Canvas
  const mainCanvas = document.getElementById("game");
  const ctx = mainCanvas.getContext("2d");
  const users_list = document.getElementById('users_list'); 

  mainCanvas.width = window.innerWidth;
  mainCanvas.height = window.innerHeight;

  function updatePlayerPos(){
    ctx.save();
    ctx.clearRect(0, 0, mainCanvas.width, mainCanvas.height);
    
    //x
    positions.x += velocity.vxl;
    positions.x += velocity.vxr;
    //y
    positions.y += velocity.vyl;
    positions.y += velocity.vyr;
    ctx.fillRect(positions.x, positions.y, 30, 50);
    ctx.restore();
    requestAnimationFrame(updatePlayerPos)
  }
  
  updatePlayerPos();

  /*
    INPUTS
  */

  window.addEventListener('keydown', function(e){
    //USER LIST
    if (e.keyCode == 84) {
        users_list.style.left = `${window.innerWidth/2}px`;
        users_list.classList.remove('hide');
        users_list.classList.add('show');
    }
    //MOVEMENT
    if(e.code == "KeyD") velocity.vxr = MAX_VEL;
    if(e.code == "KeyA") velocity.vxl = -MAX_VEL;
    if(e.code == "KeyS") velocity.vyr = MAX_VEL;
    if(e.code == "KeyW") velocity.vyl = -MAX_VEL;
  });
  window.addEventListener('keyup', function(e){
    //USER LIST
    if (e.keyCode == 84) {
        users_list.classList.remove('show');
        users_list.classList.add('hide');
    }
    //MOVEMENT
    if(e.code == "KeyD") velocity.vxr = 0;
    if(e.code == "KeyA") velocity.vxl = 0;
    if(e.code == "KeyS") velocity.vyr = 0;
    if(e.code == "KeyW") velocity.vyl = 0;
  });


};//On load

function createPlayer(){
  const mainCanvas = document.getElementById("game");
  const ctx = mainCanvas.getContext("2d");
  ctx.fillRect(50, 50, 30, 50);

}

/*

  *SOCKET*

*/

/*connectToWebSocket(`ws://${url}/ws`).then(webSocket => {

    socket = webSocket;

    socket.onmessage = i => processMessage(i);

    socket.onerror = message => {
      console.log('ERROR BEn')
      console.log(`[error] ${message.message}`);
    };

    socket.onclose = message => {
        alert("You got disconnected");
        if (message.wasClean)
            console.log(`[close] Connection closed cleanly, code=${message.code} reason=${message.reason}`);
        else
            console.log("[close] Connection died");
        socket = null;
    };

    socket.send(JSON.stringify(
      {
          action: "connected"
      }
    ));

    socket.send(JSON.stringify(
      {
          action: "connection"
      }
    ));

}).catch(e => {
  console.error(e);
});*/

function connectToWebSocket(url, onopen = function() {}) {
  return new Promise(async (resolve, reject) => {
    var uri = new URL(url);
    
    let params = (new URL(document.location)).searchParams;
    let server = params.get("server_id");
    console.log(server, uri);
    uri.searchParams.set("server_id", server?server:null);

    if (!["ws:", "wss:"].includes(uri.protocol))
      return reject(new Error ("Unsupported URL"));

    try {
      socket = new WebSocket(uri.href);
    } catch (e) {
      console.error('ERROR BEn')
      if (e instanceof SyntaxError)
        return reject(new Error("https://developer.mozilla.org/en-US/docs/Web/API/WebSocket/WebSocket#exceptions"));
      else
        return reject(new Error("Unknown error"));
    }

    if (!socket)
      return reject(new Error("Unknown error"));

    socket.onopen = message => {
      console.log("[open] Connection established");
      onopen(socket, message);
      resolve(socket);
    };
  });
}

async function closeConnection(socket, force = false, code, reason) {
  try {
    socket.close(code, reason);
  } catch (e) {
    if (e instanceof InvalidAccessError) {
      if (!force)
        throw new Error("Invalid code");
      closeConnection(socket, force, 1000, reason);
    } else if (e instanceof SyntaxError) {
      if (!force)
        throw new Error("Invalid reason");

      const reader = new FileReader(), utf8 = new Blob([reason]).slice(0, 123);

      reader.onload = () => closeConnection(socket, force, 100000, reader.result);

      reader.readAsText(utf8);
    } else
      throw new Error("Unknown error");
  }
}

function processMessage(message) {
  const data = JSON.parse(message.data);
  console.log(`[message] Data received from server: ${JSON.stringify(data)}`);
  const { type, response } = data;

  switch (type) {

    case "connected":{
      var me = response.me;
      player = new Player(me.id, me.name);

      const list = document.querySelector('#users_list ul');
      if((response.users).length > 0){
        createPlayer();
        for(const i of response.users){
          const li = document.createElement('li');
          li.appendChild(document.createTextNode(i.name));
          li.setAttribute("data-id", i.id);
          list.appendChild(li);
        }
      }
      const li = document.createElement('li');
      li.appendChild(document.createTextNode(response.me.name + " (me)"));
      li.classList.add("you");
      list.appendChild(li);
      break;
    }

    case "connection":{
      const list = document.getElementById('users_list');
      const li = document.createElement('li');
      li.appendChild(document.createTextNode(response.player.name));
      li.setAttribute("data-id", response.player.id);
      list.appendChild(li);
      break;
    }

    case "moving": {
      break;
    }

    case "disconnect": {
      document.querySelector(`li[data-id="${response.id}"]`).remove();
      break;
    }

    default: {
      console.log("default");
    }
  }
}