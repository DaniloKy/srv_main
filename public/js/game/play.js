import { SERVER_URL } from "../env.js";

const UPS = 30;
const RPS = 30;

let socket;
let player;

/*
  *GAME*
*/

import Player from "./Player.js";

window.onload = () => {

  player = new Player(123, "Ben", "mage")

  console.log(player);


  //Canvas
  const mainCanvas = document.getElementById("game");
  const ctx = mainCanvas.getContext("2d");
  const users_list = document.getElementById('users_list'); 

  mainCanvas.width = window.innerWidth;
  mainCanvas.height = window.innerHeight;

  const backgroundImage = new Image();
  backgroundImage.src = "../../images/game/Map/Pd3a0K.png";
  const scaleX = mainCanvas.width / backgroundImage.width;
  const scaleY = mainCanvas.height / backgroundImage.height;
  const scale = Math.max(scaleX, scaleY);
  const backgroundWidth = backgroundImage.width * scale;
  const backgroundHeight = backgroundImage.height * scale;
  const offsetX = (mainCanvas.width - backgroundWidth) / 2;
  const offsetY = (mainCanvas.height - backgroundHeight) / 2;

  let lastRender = null;
  let lastAnimationRender = null
  let lastUpdate = null;

  function updatePlayerPos(frame){
    if (lastUpdate == null || frame - lastUpdate >= (1000 / UPS)) {
        lastUpdate = frame;
        update();
    }
    
    if (lastRender == null || frame - lastRender >= (1000 / RPS)) {
        lastRender = frame;
        render();
    }

    if (lastAnimationRender == null || frame - lastAnimationRender >= (1000/4)) {
      lastAnimationRender = frame;
      player.currentFrame++;
      if(player.currentFrame >= player.totalFrames)
        player.currentFrame = 0;
      
    }
    requestAnimationFrame(updatePlayerPos);
  }

  function update() {
    // FOR EACH PLAYER update()
    player.update();
  }

  function render() {
    ctx.save();
    ctx.clearRect(0, 0, mainCanvas.width, mainCanvas.height);
    ctx.drawImage(backgroundImage, 0, 0, backgroundImage.width/1.5, backgroundImage.height/1.5);
    //ctx.drawImage(backgroundImage, offsetX, offsetY, backgroundWidth, backgroundHeight);
    // FOR EACH PLAYER render()
    player.render(ctx);
    ctx.restore();
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
        player.setState("run");
    }
    //MOVEMENT
    if(e.code == "KeyD") player.velocity.vxr = player.walk_vel;
    if(e.code == "KeyA") player.velocity.vxl = -player.walk_vel;
    if(e.code == "KeyS") player.velocity.vyr = player.walk_vel;
    if(e.code == "KeyW") player.velocity.vyl = -player.walk_vel;
  });
  window.addEventListener('keyup', function(e){
    //USER LIST
    if (e.keyCode == 84) {
        users_list.classList.remove('show');
        users_list.classList.add('hide');
        player.setState("idle");
    }
    //MOVEMENT
    if(e.code == "KeyD") player.velocity.vxr = 0;
    if(e.code == "KeyA") player.velocity.vxl = 0;
    if(e.code == "KeyS") player.velocity.vyr = 0;
    if(e.code == "KeyW") player.velocity.vyl = 0;
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

connectToWebSocket(`ws://${SERVER_URL}/ws`).then(webSocket => {

    socket = webSocket;

    socket.onmessage = i => processMessage(i);

    socket.onerror = message => {
      console.log(`[error] ${message.message}`);
    };

    socket.onclose = message => {
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
/*
    socket.send(JSON.stringify(
      {
          action: "connection"
      }
    ));
*/
}).catch(e => {
  console.error(e);
});

function connectToWebSocket(url, onopen = function() {}) {
  return new Promise(async (resolve, reject) => {
    var uri = new URL(url);
    
    if (!["ws:", "wss:"].includes(uri.protocol))
      return reject(new Error ("Unsupported URL"));
    console.log(uri);
    try {
      socket = new WebSocket(uri.href);
    } catch (e) {
      console.error('ERROR BEn')
      if (e instanceof SyntaxError)
        return reject(new Error("https://developer.mozilla.org/en-US/docs/Web/API/WebSocket/WebSocket#exceptions"));
      else
        return reject(e);
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
        //createPlayer();
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