import { SERVER_URL } from "../env.js";
import Game from "./Game.js";

const UPS = 24;
const RPS = 60;

let socket;
let interval;
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

  let animationFrame;

  const backgroundImage = new Image();
  backgroundImage.src = "../../images/game/Map/map.png";

  backgroundImage.onload = () => {
    mainCanvas.width = backgroundImage.width;
    mainCanvas.height = backgroundImage.height;
    mainCanvas.style.width = backgroundImage.width;
    mainCanvas.style.height = backgroundImage.height;
  }

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

    animationFrame = requestAnimationFrame(updatePlayerPos);
  }

  function update() {
    player.update();
  }
  var scale = 1.5;
  function render() {
    ctx.save();
    ctx.clearRect(0, 0, mainCanvas.width, mainCanvas.height);
    ctx.translate(mainCanvas.width / 3 - player.pos_axis.x, mainCanvas.height / 5 -player.pos_axis.y);
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
      users_list.classList.remove('visually-hidden');
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
      users_list.classList.add('visually-hidden');
    }

    //MOVEMENT
    if (e.code == "KeyD") {
      player.velocity.vxr = 0;
      player.rotation = 0;
      isKeyDPressed = false;
    }
    if (e.code == "KeyA") {
      player.velocity.vxl = 0;
      player.rotation = 180;
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

  function clickScreen(e){
    if(e.button === 0){
      const rect = mainCanvas.getBoundingClientRect();
      const mouseX  = e.clientX;
      console.log(window.innerHeight, rect.height)
      const mouseY = window.innerHeight - e.clientY;
      //player.clickHandle({x: mouseX, y: mouseY});
      const centerX = window.innerWidth / 2;
      const centerY = window.innerHeight / 2;
      const deltaX = mouseX - centerX;
      const deltaY = centerY - mouseY;
      player.clickHandle({x: deltaX, y: deltaY});

      socket.send(JSON.stringify(
        {
            action: "click",
            body: {
              mouse: {
                x: deltaX,
                y: deltaY,
              },
            }
        }
      ));
    }
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
          alert("Connection lost! Refresh your page.");
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
    interval = setTimeout(() => {
      callable();
      setIntervalTimes(callable, ms);
    }, ms)
  };

  function sendUpdated(){
    console.log("SEND")
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
        console.log(me)
        player = new Player(ctx ,me.name, me.my_class, me.level, me.pos_axis);
        player.setStats(me.currentHp, me.maxHp, me.melee_damage, me.walk_vel);
        const spanMaxHP = document.getElementById('max_hp');
        spanMaxHP.innerHTML = player.maxHp;
        const currentHp = document.getElementById('current_hp');
        currentHp.innerHTML = player.currentHp;
        const progressHp = document.getElementById('hp_progress');
        progressHp.max = player.maxHp;
        progressHp.value = player.currentHp;
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
        li.appendChild(document.createTextNode(player_info.name + " - lvl."+player_info.level));
        li.setAttribute("data-id", player_info.id);
        list.appendChild(li);
        break;
      }

      case "update": {
        if((response.users).length > 0){
          console.log(response.users)
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
        const gameStatus = document.querySelector('.game_status');
        gameStatus.innerHTML = "Game starting in a few seconds!"
        console.log("Game will start in a few seconds!");
        break;
      }

      case "startGame": {
        const gameCanvas = document.getElementById('game');
        gameCanvas.classList.remove('visually-hidden');
        const lists = document.querySelector('.lists');
        lists.classList.add('visually-hidden');
        const hpUI = document.querySelector('.UI .hpUI');
        hpUI.classList.remove('visually-hidden');
        const list = document.querySelector('#users_list ul');
        if((response.users).length > 0){
          for(const i of response.users){
            if(player.getId() == i.id){
              player.pos_axis = i.pos_axis;
              players_list.set(player.getId(), player);
            }else{
              players_list.set(i.id, new Player(ctx, i.name, i.my_class, i.level, i.pos_axis));
            }
            const li = document.createElement('li');
            li.appendChild(document.createTextNode(i.name+ "- lvl." + i.level));
            li.setAttribute("data-id", i.id);
            list.appendChild(li);
          }
        }
        console.log("PLAYERS LIST", players_list, player)
        setIntervalTimes(sendUpdated, 1000/24);
        updatePlayerPos();
        window.addEventListener("keydown", keyDown);
        window.addEventListener("keyup", keyUp);
        mainCanvas.addEventListener("mousedown", clickScreen);
        break;
      }

      case "midGame": {
        const gameStatus = document.querySelector('.game_status');
        gameStatus.innerHTML = "Waiting for game to end."
        const list = document.querySelector('#midGame_list ul');
        if((response.users).length > 0){
          list.innerHTML = "";
          for(const i of response.users){
            console.log(i)
            const li = document.createElement('li');
            li.appendChild(document.createTextNode(i.name + "- lvl." + i.level));
            li.setAttribute("data-id", i.id);
            list.appendChild(li);
          }
        }
        break;
      }

      case "gameEnded": {
        const gameStatus = document.querySelector('.game_status');
        gameStatus.innerHTML = "Waiting for more players to join."
        const list = document.querySelector('#midGame_list ul');
        list.innerHTML = "";

        break;
      }

      case "takeHit":{
        player.currentHp = response.newHP;
        const currentHp = document.getElementById('current_hp');
        currentHp.innerHTML = player.currentHp;
        const progressHp = document.getElementById('hp_progress');
        progressHp.value = player.currentHp;
        
        break;
      }

      case "playerDied": {

        if(player.getId() == response.playerId){
          player.currentHp = 0;
          const currentHp = document.getElementById('current_hp');
          currentHp.innerHTML = player.currentHp;
          const progressHp = document.getElementById('hp_progress');
          progressHp.value = player.currentHp;
          window.removeEventListener("keydown", keyDown);
          window.removeEventListener("keyup", keyUp);
          mainCanvas.removeEventListener("mousedown", clickScreen);
  
          clearTimeout(interval);
          cancelAnimationFrame(animationFrame);
        }
        
        players_list.delete(response.playerId);

        break;
      }

      case "youLost": {
        console.log("YOU LOST")
        gameStatus(false, response.kills, response.points);
        break;
      }

      case "youWon": {
        console.log("YOU WON");
        window.removeEventListener("keydown", keyDown);
        window.removeEventListener("keyup", keyUp);
        mainCanvas.removeEventListener("mousedown", clickScreen);
        clearTimeout(interval);
        cancelAnimationFrame(animationFrame);
        gameStatus(true, response.kills, response.points);
        break;
      }

      case "disconnect": {
        queue_list.delete(response.id);
        players_list.delete(response.id);
        document.querySelector(`li[data-id="${response.id}"]`).remove();
        break;
      }

      default: {
        console.log("default");
      }
    }
  }

  function gameStatus(status, kills, points){
    const hpUI = document.querySelector('.UI .hpUI');
    hpUI.classList.remove('visually-hidden');
    const pointsTable = document.querySelector('.pointsTable');
    pointsTable.classList.remove("visually-hidden");
    const game_status = document.getElementById('game_status');
    var textStatus;
    if(status)
      textStatus = "YOU WON!"
    else
      textStatus = "YOU LOST!"
    game_status.innerHTML = textStatus;
    const player_kills = document.getElementById('player_kills');
    player_kills.innerHTML = kills;
    const xp_gained = document.getElementById('xp_gained');
    xp_gained.innerHTML = points;
  }

};//On load