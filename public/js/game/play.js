import { SERVER_URL } from "../env.js";
import Game from "./Game.js";

const UPS = 30;
const RPS = 30;

let socket;
let player;
let queue_list = new Map();
let players_list = new Map();

/*
  *GAME*
*/

import Player from "./Player.js";

window.onload = () => {

  const username = document.querySelector("input[name='username']");

  const class_name = document.querySelector("input[name='class_name']");

  const level = document.querySelector("input[name='level']");

  //Canvas
  const mainCanvas = document.getElementById("game");
  const ctx = mainCanvas.getContext("2d");
  const users_list = document.getElementById('users_list'); 

  mainCanvas.width = window.innerWidth;
  mainCanvas.height = window.innerHeight;

  const backgroundImage = new Image();
  backgroundImage.src = "../../images/game/Map/map.png";

  let lastRender = null;
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

    requestAnimationFrame(updatePlayerPos);
  }

  function update() {
    player.update();
  }
  var scale = 1.5;
  function render() {
    ctx.save();
    ctx.clearRect(0, 0, mainCanvas.width, mainCanvas.height);
    ctx.translate(-player.pos_axis.x + mainCanvas.width / 2, -player.pos_axis.y + mainCanvas.height / 2);
    ctx.drawImage(backgroundImage, 0, 0, backgroundImage.width * scale, backgroundImage.height *scale);
    for(const player of [...players_list.values()])
      player.render(ctx);
    ctx.restore();
  }

  /*
    INPUTS
  */

  // Define flags to track the key states
  let isKeyDPressed = false;
  let isKeyAPressed = false;
  let isKeySPressed = false;
  let isKeyWPressed = false;

  // Keydown event listener
  window.removeEventListener('keydown', keyDown)

  function keyDown(e){

    //USER LIST
    if (e.code == "KeyT") {
      users_list.style.left = `${window.innerWidth / 2}px`;
      users_list.classList.remove('hide');
      users_list.classList.add('show');
    }

    //MOVEMENT
    if (e.code == "KeyD" && !isKeyDPressed) {
      player.velocity.vxr = player.walk_vel;
      player.playerRotationAngle = 0;
      isKeyDPressed = true;
      player.setState("run");
    }
    if (e.code == "KeyA" && !isKeyAPressed) {
      player.velocity.vxl = -player.walk_vel;
      player.playerRotationAngle = Math.PI;
      isKeyAPressed = true;
      player.setState("run");
    }
    if (e.code == "KeyS" && !isKeySPressed) {
      player.velocity.vyr = player.walk_vel;
      isKeySPressed = true;
      player.setState("run");
    }
    if (e.code == "KeyW" && !isKeyWPressed) {
      player.velocity.vyl = -player.walk_vel;
      isKeyWPressed = true;
      player.setState("run");
    }
  }

  // Keyup event listener
  window.removeEventListener('keyup', keyUp);

  function keyUp(e){
    //USER LIST
    if (e.code == "KeyT") {
      users_list.classList.remove('show');
      users_list.classList.add('hide');
    }

    //MOVEMENT
    if (e.code == "KeyD") {
      player.velocity.vxr = 0;
      isKeyDPressed = false;
    }
    if (e.code == "KeyA") {
      player.velocity.vxl = 0;
      isKeyAPressed = false;
    }
    if (e.code == "KeyS") {
      player.velocity.vyr = 0;
      isKeySPressed = false;
    }
    if (e.code == "KeyW") {
      player.velocity.vyl = 0;
      isKeyWPressed = false;
    }
    
    // Check if any movement key is still pressed
    if (!isKeyDPressed && !isKeyAPressed && !isKeySPressed && !isKeyWPressed) {
      player.setState("idle");
    }
  }

  mainCanvas.removeEventListener('mousedown', clickScreen);

  function clickScreen(){
    const rect = mainCanvas.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    player.clickHandle({x, y});
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
            action: "connected",
            body: {
              player_info: {
                name: username.value,
                class_name: class_name.value,
                level: level.value,
                pos: {
                  x: 0,
                  y: 0
                }
              }
            }
        }
      )); 
  
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

  function setIntervalTimes(callable, ms) {
    setTimeout(() => {
      callable();
      setIntervalTimes(callable, ms);
    }, ms)
  };

  function sendUpdated(){
    socket.send(JSON.stringify(
      {
          action: "update",
          body: {
            player_info: {
              id: player.getId(),
              currentState: player.currentState,
              pos: player.pos_axis
            }
          }
      }
    ));
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
        player = new Player(me.name, me.my_class, me.level, me.pos_axis);
        player.setId(me.id);
        queue_list.set(player.getId(), player);
        const list = document.querySelector('#queue_list ul');
        if((response.users).length > 0){
          for(const i of response.users){
            queue_list.set(i.id, new Player(i.name, i.my_class, i.level, i.pos_axis));
            const li = document.createElement('li');
            li.appendChild(document.createTextNode(i.name + " - lvl."+i.level));
            li.setAttribute("data-id", i.id);
            list.appendChild(li);
          }
        }
        const li = document.createElement('li');
        li.appendChild(document.createTextNode(response.me.name + " - lvl."+response.me.level + " (me)"));
        li.classList.add("you");
        list.appendChild(li);
        break;
      }

      case "connection":{
        const player_info = response.player;
        queue_list.set(player_info.id, new Player(player_info.name, player_info.my_class, player_info.level, player_info.pos_axis));
        const list = document.querySelector('#queue_list ul');
        const li = document.createElement('li');
        li.appendChild(document.createTextNode(player_info.name + " - lvl."+i.level));
        li.setAttribute("data-id", player_info.id);
        list.appendChild(li);
        break;
      }

      case "update": {
        if((response.users).length > 0){
          for(const i of response.users){
            const player = players_list.get(i.id);
            player.currentState = i.currentState;
            player.pos_axis = i.pos_axis;
            players_list.set(i.id, player);
          }
        }
        break;
      }

      case "gameStarting": {
        console.log("Game will start in some seconds!");

        break;
      }

      case "startGame": {
        game.gameStarted = true;
        console.log(response.me, response.users);
        player = response.me;
        //players_list.set(player)
        const list = document.querySelector('#users_list ul');
        if((response.users).length > 0){
          for(const i of response.users){
            players_list.set(i.id, new Player(i.name, i.my_class, i.level, i.pos_axis));
            const li = document.createElement('li');
            li.appendChild(document.createTextNode(i.name));
            li.setAttribute("data-id", i.id);
            list.appendChild(li);
          }
        }
        /*setIntervalTimes(sendUpdated, 1000/24);
        updatePlayerPos();
        window.addEventListener("keydown", keyDown);
        window.addEventListener("keyup", keyUp);
        window.addEventListener("mousedown", clickScreen);*/
        break;
      }

      case "midGame": {
        const list = document.querySelector('#midGame_users_list ul');
        if((response.users).length > 0){
          for(const i of response.users){
            const li = document.createElement('li');
            li.appendChild(document.createTextNode(i.name));
            li.setAttribute("data-id", i.id);
            list.appendChild(li);
          }
        }
        break;
      }

      case "disconnect": {
        players_list.delete(response.id);
        document.querySelector(`li[data-id="${response.id}"]`).remove();
        break;
      }

      default: {
        console.log("default");
      }
    }
  }

};//On load