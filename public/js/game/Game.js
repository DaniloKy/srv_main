
export default class Game{

    countDown;
    gameStarted;
    keyboardEnabled;
    countdownTime;

    constructor(){
        this.countDown = 10;
        this.gameStarted = false;
        this.keyboardEnabled = false;
        this.countdownTime = null;
    }
      
}