class Point {
	constructor(x, y, radius, color) {
		this.x = x;
		this.y = y;
		this.rad = radius;
		this.color = color;

		this.vx = 0;
		this.vy = 0;
		this.ax = 0;
		this.ay = .05;
	}

	calcDistance(otherPntX, otherPntY) {
		var dx = this.x - otherPntX;
		var dy = this.y - otherPntY;
		var dist = Math.sqrt(Math.pow(dx, 2) + Math.pow(dy, 2));
		return dist;
	}

	update(gravPtX, gravPtY, gravDist) {
		if(this.x > gravPtX)
			this.ax = -.02;
		else
			this.ax = .02;

		if(this.y > gravPtY)
			this.ay = -.02;
		else
			this.ay = .02;

		this.vx += this.ax;
		this.vy += this.ay;
		this.x += this.vx;
		this.y += this.vy;

		this.color = "rgba(255, 255, 255, " + (600 - gravDist)/600 + ")";
	}

	draw(context) {
 		context.fillStyle = this.color;
 		context.beginPath();
    	context.arc(this.x, this.y, this.rad, 0, Math.PI * 2);
		context.fill();
	}

	static draw(context, x, y, rad, color) {
		context.fillStyle = color;
 		context.beginPath();
    	context.arc(x, y, rad, 0, Math.PI * 2);
		context.fill();
	}

	static randomColor() {
		var color = "rgba(" + (Math.floor(Math.random() * 105) + 150) + "," +
			(Math.floor(Math.random() * 105) + 150) + "," +
			(Math.floor(Math.random() * 105) + 150) + ", 0.9)";

	    return color;
	}
}
