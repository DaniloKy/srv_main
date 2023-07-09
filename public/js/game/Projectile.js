export class Projectile {
    x;
    y;
    angle;

    constructor(angle, x, y) {
        this.angle = angle;
        this.x = x;
        this.y = y;
    }

    render(ctx) {

        const distance = 50;
        const endX = this.x + distance * Math.cos(this.angle);
        const endY = this.y + distance * Math.sin(this.angle);
        
        const rectWidth = 30;
        const rectHeight = 20;
        const rectX = angleDegrees - rectWidth / 2;
        const rectY = angleDegrees - rectHeight / 2;
        ctx.fillStyle = 'red';
        ctx.fillRect(rectX, rectY, rectWidth, rectHeight);
        
      }
}