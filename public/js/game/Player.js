class Player{
    #id;
    name;
    
    player_class;
    static MAX_PERKS = 2;
    static MAX_SKILLS = 2;
    perks = [];
    skills = [];

    width = 2;
    height = 3;
    health = 100;
    melee_damage;
    projectile_damage;
    melee_protection_percentage;
    projectile_protection_percentage;
    walk_vel;
    crouch_vel = 0.5;

    pos_axis = {x: 0, y: 0};
    rot_axis = {x: 0, y: 0};

    constructor(id, name, player_class){
        this.#id = id;
        this.name = name;
        this.player_class = player_class;
        this.whatClassAmI(player_class);
    }

    //Gets/Sets

    setId(id){
        this.#id = id;
    }

    getId(){
        return this.#id;
    }

}