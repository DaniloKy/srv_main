export class Projectile {
    x;
    y;
    radians;
    currentOpacity;

    constructor(radians, x, y) {
        this.currentOpacity = 0.8;
        this.radians = radians;
        this.x = x;
        this.y = y;
    }

    render(ctx) {

        const distance = 75;
        const endX = this.x + distance * Math.cos(this.radians);
        const endY = this.y + distance * Math.sin(this.radians);
        
        const rectWidth = 30;
        const rectHeight = 20;
        
        ctx.fillStyle = "rgba(255, 0, 0, "+this.currentOpacity +")";

        if(this.currentOpacity > 0){
            this.currentOpacity -= 0.01;
        }
        ctx.fillRect(endX, endY, rectWidth, rectHeight);
        
      }
}