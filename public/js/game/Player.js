const IMAGE_PATH = "../../images/game/Heroes/";

export default class Player{
    //Player info
    #id;
    name;
    player_class;

    //Player stats
    health = 100;
    melee_damage;
    projectile_damage;
    melee_protection_percentage;
    projectile_protection_percentage;
    walk_vel = 1;
    run_vel = 0.5;

    //Player pos
    pos_axis = {x: 0, y: 0};
    rot_axis = {x: 0, y: 0};
    velocity = {vxl: 0, vxr: 0, vyl: 0, vyr: 0};

    //Player Frame/State
    currentFrame;
    currentState;
    states;

    constructor(id, name, player_class){
        this.#id = id;
        this.name = name;
        this.player_class = player_class;
        this.whatClassAmI(player_class);
        this.currentFrame = 0;
        this.currentState = "idle";
        this.states = {
            idle: {
                src: new Image(),
                totalFrames: 4,
                spriteWidth: 128,
                spriteHeight: 32,
            },
            run: {
                src: new Image(),
                totalFrames: 6,
                spriteWidth: 384,
                spriteHeight: 64,
            },
            death:{
                src: new Image(),
                totalFrames: 6,
                spriteWidth: 288,
                spriteHeight: 32,
            }
        };
        this.states.idle.src.src = IMAGE_PATH+this.player_class+"/Idle/Idle-Sheet.png";
        this.states.run.src.src = IMAGE_PATH+this.player_class+"/Run/Run-Sheet.png"
        this.states.death.src.src = IMAGE_PATH+this.player_class+"/Death/Death-Sheet.png";
    }

    update(){
        //x
        this.pos_axis.x += this.velocity.vxl;
        this.pos_axis.x += this.velocity.vxr;
        //y
        this.pos_axis.y += this.velocity.vyl;
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
                this.projectile_damage = 20;
                this.melee_protection_percentage = 25;
                this.projectile_protection_percentage = 50;
                this.walk_vel *= 2.15;
                break;
            case "archer":
                this.melee_damage = 20;
                this.projectile_damage = 40;
                this.melee_protection_percentage = 15;
                this.projectile_protection_percentage = 25;
                this.walk_vel *= 2.35;
                break;
            case "mage":
                this.melee_damage = 20;
                this.projectile_damage = 30;
                this.melee_protection_percentage = 25;
                this.projectile_protection_percentage = 25;
                this.walk_vel *= 2.25;
                break;
        }
    }
    
    setState(newState) {
        if (this.currentState !== newState) {
          this.currentState = newState;
          this.currentFrame = 0;
        }
    }

    render(ctx){
        
        const stateImagePath = this.states[this.currentState]['src'];
        const stateWidth = this.states[this.currentState]['spriteWidth'];
        const stateHeight = this.states[this.currentState]['spriteHeight'];
        this.totalFrames = this.states[this.currentState]['totalFrames'];

        ctx.drawImage(
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
    };

}