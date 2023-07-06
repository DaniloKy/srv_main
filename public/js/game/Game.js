
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
    
    startCountdown(n) {
        this.count(n);
    }
    
    stopCountdown() {
        clearTimeout(this.countdownTime);
        this.countdownTime = null;
    }

    count(n){
        this.countdownTime = setTimeout(() => {
            console.log("GAME STARTS IN -> "+n)
            n--;
            if (n > 0) {
                this.count(n);
            }
        }, 1000)
    }

    enableKeyboard() {
        if (!this.keyboardEnabled) {
            this.keyboardEnabled = true;
            window.addEventListener("keydown", this.handleKeyDown);
            window.addEventListener("keyup", this.handleKeyUp);
        }
    }
    
    disableKeyboard() {
        if (this.keyboardEnabled) {
            this.keyboardEnabled = false;
            window.removeEventListener("keydown", this.handleKeyDown);
            window.removeEventListener("keyup", this.handleKeyUp);
        }
    }
      
}