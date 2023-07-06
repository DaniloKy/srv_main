export class Projectile {
    x;
    y;
    dx;
    dy;
    speed;

    constructor(x, y,/* angle*/ dx, dy) {
        this.x = x;
        this.y = y;
        this.speed = 5;
        this.dx = dx;
        this.dy = dy;
        //this.dx = Math.cos(angle) * this.speed;
        //this.dy = Math.sin(angle) * this.speed;
    }

    render(ctx) {
        /*
        this.x += this.dx;
        this.y += this.dy;

        ctx.beginPath();
        ctx.arc(this.x+12, this.y+12, 5, 0, Math.PI * 2);
        ctx.fillStyle = 'red';
        ctx.fill();
      */
        ctx.strokeStyle = 'red';
        ctx.lineWidth = 5;
        ctx.beginPath();
        ctx.moveTo(this.x + 25, this.y + 25);
        ctx.lineTo(this.dx, this.dy);
        ctx.stroke();
      }
}