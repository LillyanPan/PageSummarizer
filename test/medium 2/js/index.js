//initialize canvas size
var width = $(window).width();
var height = $(window).height() - $("#navBar").height();

//setup the canvas
var animateCanvas = document.getElementById("animateCanvas");
animateCanvas.setAttribute('width', width);
animateCanvas.setAttribute('height', height);
var animateContext = animateCanvas.getContext("2d");
animateContext.mozImageSmoothingEnabled = false;
animateContext.imageSmoothingEnabled = true; /// future

var logo = document.getElementById("mainLogo");
var smallLogos = [];

//load in 'm' logos
var numLogos = 34;
for(i = 1; i <= numLogos; i++) {
	$("body").append("<img class='logo' id='logo" + i + "' src='logos/" + i + ".png'>");
	smallLogos.push(document.getElementById(("logo" + i)));
}


//initialize "program" variables
var dots = [];
var gravPts = [];
var gravLogoIds = [];
var timer;

function init() {
	//create starting gravity points
	const gravPntCntr = new Point(width/2, height/2 - 100, 100, 'white');
	gravPts.push(gravPntCntr);

	const gravPnt = new Point(Math.floor((Math.random() * (width/2 - 300)) + 100),
							Math.floor((Math.random() * (height - 200)) + 100),
							30, Point.randomColor()); //Point.randomColor()
	gravPts.push(gravPnt);
	gravLogoIds.push(Math.floor(Math.random() * numLogos));

	const gravPnt2 = new Point(Math.floor((Math.random() * (width/2 - 300)) + 200 + width/2),
							Math.floor((Math.random() * (height - 200)) + 100),
							30, Point.randomColor());
	gravPts.push(gravPnt2);
	gravLogoIds.push(Math.floor(Math.random() * numLogos));

	timer = setInterval(dotTimer, 170);
}

function dotTimer() {
	const pnt = new Point(Math.floor((Math.random() * width) + 1),
						  Math.floor((Math.random() * height) + 1),
						  2, 'white');
	dots.push(pnt);
    
    if(dots.length > 300)
    	clearInterval(timer);
}

function draw() {
	//redraw background
	animateContext.fillStyle = 'black';
	animateContext.fillRect(0, 0, width, height);

	for(i = 0; i < dots.length; i++) {
		var nearestGrav = gravPts[0];
		var nearestDist = dots[i].calcDistance(nearestGrav.x, nearestGrav.y);
		for(j = 1; j < gravPts.length; j++) {
			var dist = dots[i].calcDistance(gravPts[j].x, gravPts[j].y);
			if(dist < nearestDist) {
				nearestDist = dist;
				nearestGrav = gravPts[j];
			}
		}

		dots[i].update(nearestGrav.x, nearestGrav.y, nearestDist);
		dots[i].draw(animateContext);
	}

	for(i = 0; i < gravPts.length; i++) {
		gravPts[i].draw(animateContext);
		if(i > 0)
			animateContext.drawImage(smallLogos[gravLogoIds[i - 1]], gravPts[i].x - 20, gravPts[i].y - 20);
		//animateContext.drawImage(smallLogos[numLogos s], gravPts[i].x - 10, gravPts[i].y - 10);
	}
	animateContext.drawImage(logo, gravPts[0].x - 100, gravPts[0].y - 100);

	requestAnimationFrame(draw);
}

$('#animateCanvas').mousedown(function(e) {
	//var rad = Math.floor((Math.random() * 35 + 10));
	var rad = 30;

	var xSelect = e.pageX;
	var ySelect = e.pageY - $("#navBar").height();
	var invalid = false;
	for(i = 0; i < gravPts.length; i++) {
		var dist = Math.sqrt(Math.pow(xSelect - gravPts[i].x, 2) + Math.pow(ySelect - gravPts[i].y, 2));
		if(dist < gravPts[i].rad + rad) {
			invalid = true;
			break;
		}
	}

	if(!invalid) {
		const gravPnt = new Point(xSelect, ySelect,
							  rad, Point.randomColor());
		gravPts.push(gravPnt);
		gravLogoIds.push(Math.floor(Math.random() * numLogos));
	}
});

$( window ).resize(function() {
	width = $(window).width();
	height = $(window).height() - $("#navBar").height();
});

init();
draw();