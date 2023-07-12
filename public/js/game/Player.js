import { Projectile } from "./Projectile.js";

const IMAGE_PATH = "../../images/game/";

export default class Player{
    ctx;

    //Player info
    #id;
    name;
    player_class;
    level;

    //Player stats
    currentHp;
    maxHp;
    melee_damage;
    walk_vel;

    //Player pos
    pos_axis = {x: 0, y: 0};
    velocity = {vxl: 0, vxr: 0, vyl: 0, vyr: 0};

    //Player Frame/State
    animationCount = 0;
    rotation = 0;
    currentFrame;
    currentState;
    states;

    swordImg;
    swordX;
    swordY;
    projectiles = [];

    constructor(ctx = null, name, player_class, level, pos){
        this.ctx = ctx;
        this.name = name;
        this.player_class = player_class;
        this.level = level;
        this.pos_axis = pos;
        this.currentFrame = 0;
        this.currentState = "idle";
        this.states = {
            idle: {
                src: new Image(),
                totalFrames: 4,
                animationSpeed: 6,
            },
            run: {
                src: new Image(),
                totalFrames: 6,
                animationSpeed: 4,
            },
            death:{
                src: new Image(),
                totalFrames: 6,
                animationSpeed: 6,
            }
        };
        this.states.idle.src.src = IMAGE_PATH+"Heroes/"+player_class+"/Idle/Idle-Sheet.png";
        this.states.run.src.src = IMAGE_PATH+"Heroes/"+player_class+"/Run/Run-Sheet.png"
        this.states.death.src.src = IMAGE_PATH+"Heroes/"+player_class+"/Death/Death-Sheet.png";

        this.swordImg = new Image();
        this.swordImg.src = IMAGE_PATH+"Weapons/Wood/sword.png";
    }

    setStats(currentHp, maxHp, melee_damage, walk_vel){
        this.currentHp = currentHp;
        this.maxHp = maxHp;
        this.melee_damage = melee_damage;
        this.walk_vel = walk_vel;
    }

    update(){
        const stateImagePath = this.states[this.currentState]['src'];
        const stateWidth = stateImagePath.width;
        const stateHeight = stateImagePath.height;
        //x
        if(this.pos_axis.x > 0)
            this.pos_axis.x += this.velocity.vxl;
        if(this.pos_axis.x < (this.ctx.canvas.width + this.ctx.canvas.width / 2) - stateWidth/this.totalFrames) 
            this.pos_axis.x += this.velocity.vxr;

        //y
        if(this.pos_axis.y > 0)
            this.pos_axis.y += this.velocity.vyl;
        if(this.pos_axis.y < (this.ctx.canvas.height + this.ctx.canvas.height / 2) - stateHeight)
            this.pos_axis.y += this.velocity.vyr;
    }

    //Gets/Sets

    setId(id){
        this.#id = id;
    }

    getId(){
        return this.#id;
    }
    
    setState(newState) {
        if (this.currentState !== newState) {
            this.animationCount = 0;
            this.currentState = newState;
            this.currentFrame = 0;
        }
    }

    render(){
        
        const stateImagePath = this.states[this.currentState]['src'];
        const stateWidth = stateImagePath.width;
        const stateHeight = stateImagePath.height;
        this.totalFrames = this.states[this.currentState]['totalFrames']; 

        this.ctx.drawImage(
            stateImagePath,
            this.currentFrame * stateWidth/this.totalFrames,
            0,
            stateWidth/this.totalFrames,
            stateHeight,
            this.pos_axis.x,
            this.pos_axis.y,
            stateWidth/this.totalFrames,
            stateHeight
        );
        
        var speed = this.states[this.currentState]['animationSpeed']
        
        if(this.currentState !== "death"){
            this.currentFrame = (Math.floor(this.animationCount /speed)) % this.totalFrames;
            this.animationCount++;
        }
        
        const rectWidth = 45;
        const rectHeight = 6;
        this.ctx.fillStyle = "rgba(255, 0, 0, 0.2)";
        this.ctx.fillRect(this.pos_axis.x + ((stateWidth/this.totalFrames) /2)/rectHeight/2, this.pos_axis.y - 20, rectWidth, rectHeight);
        const currentHpPercentage = (this.currentHp * 100)/this.maxHp;
        const currentHpWidth = (currentHpPercentage * rectWidth)/100;
        this.ctx.fillStyle = "rgba(255, 0, 0, 1)";
        this.ctx.fillRect(this.pos_axis.x + ((stateWidth/this.totalFrames) /2)/rectHeight/2, this.pos_axis.y - 20, currentHpWidth, rectHeight);

        this.ctx.fillStyle = 'white';
        this.ctx.font = '12px Arial';
        this.ctx.textAlign = 'center';
        this.ctx.fillText(this.name, this.pos_axis.x + (stateWidth/this.totalFrames) / 2, this.pos_axis.y - 25);
        this.updateSwordPosition();
        this.ctx.drawImage(this.swordImg, this.swordX, this.swordY);

        for (const i of this.projectiles) {
            i.render(this.ctx);
        }
    };

    updateSwordPosition(){
        this.swordX = this.pos_axis.x + 35 - this.swordImg.width / 2;
        this.swordY = this.pos_axis.y + 8 - this.swordImg.height / 2;
    }

    clickHandle({x, y}) {
        const stateImagePath = this.states[this.currentState]['src'];
        const stateWidth = stateImagePath.width;
        const stateHeight = stateImagePath.height;
        x += (stateWidth/this.totalFrames)/2;
        y += stateHeight/2;
        const radians = Math.atan2(y, x);
        
        this.projectiles.push(new Projectile(radians, this.pos_axis.x, this.pos_axis.y));
    }

    deadAnimation(){
        this.setState("death")
    }
}