import { Projectile } from "./Projectile.js";

const IMAGE_PATH = "../../images/game/Heroes/";

export default class Player{
    ctx;

    //Player info
    #id;
    name;
    player_class;
    level;

    //Player stats
    hp;
    melee_damage;
    walk_vel;;

    //Player pos
    pos_axis = {x: 0, y: 0};
    velocity = {vxl: 0, vxr: 0, vyl: 0, vyr: 0};

    //Player Frame/State
    animationCount = 0;
    rotation = 0;
    currentFrame;
    currentState;
    states;

    projectiles = [];

    constructor(ctx = null, name, player_class, level, pos, hp, melee_damage, walk_vel){
        this.ctx = ctx;
        this.name = name;
        this.player_class = player_class;
        this.level = level;
        this.pos_axis = pos;
        this.hp = hp;
        this.melee_damage = melee_damage;
        this.walk_vel = walk_vel;
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
        this.states.idle.src.src = IMAGE_PATH+player_class+"/Idle/Idle-Sheet.png";
        this.states.run.src.src = IMAGE_PATH+player_class+"/Run/Run-Sheet.png"
        this.states.death.src.src = IMAGE_PATH+player_class+"/Death/Death-Sheet.png";
    }

    setStats(hp, melee_damage, walk_vel){
        this.hp = hp;
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

    whatClassAmI(player_class){
        switch(player_class){
            case "fighter":
                this.melee_damage = 40;
                this.walk_vel *= 2.15;
                break;
            case "archer":
                this.melee_damage = 40;
                this.walk_vel *= 2.35;
                break;
            case "mage":
                this.melee_damage = 20;
                this.walk_vel *= 2.25;
                break;
        }
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
        this.ctx.fillStyle = 'white';
        this.ctx.font = '12px Arial';
        this.ctx.textAlign = 'center';
        this.ctx.fillText(this.name, this.pos_axis.x + (stateWidth/this.totalFrames) / 2, this.pos_axis.y - 10);

        var speed = this.states[this.currentState]['animationSpeed']
        this.currentFrame = (Math.floor(this.animationCount /speed)) % this.totalFrames;

        this.animationCount++;

        for (const i of this.projectiles) {
            i.render(this.ctx);
        }
    };

    clickHandle({x, y}) {
        var canvasRect = this.ctx.canvas.getBoundingClientRect();
        //console.log("CLICK", this.melee_damage);
        console.log("CLICK", x, y);

        console.log("Y", this.pos_axis.y, "X", this.pos_axis.x)

        const radians = Math.atan2(-y , x);

        console.log("radians", radians)

        var degrees  = (radians * 180) / Math.PI;

        console.log("degrees before", degrees)

        degrees = (degrees + 360) % 360;

        console.log("degrees after", degrees)
        
        //this.projectiles.push(new Projectile(angle, this.pos_axis.x, this.pos_axis.y));
    }
}