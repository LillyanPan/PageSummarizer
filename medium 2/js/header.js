//initialize canvas size
var width = $(window).width();
var height = 6;

//setup the canvas
var headerCanvas = document.getElementById('indicatorCanvas');
headerCanvas.setAttribute('width', width);
headerCanvas.setAttribute('height', height);

var headerContext = headerCanvas.getContext("2d");

//initialize dot variables
var selectedX = $("#startLink").position().left + ($("#startLink").width() + 40)/2;

var mvDrctn = 1;
var pntCntr = 0;
var pntTimer;
var targetNavLink;

var pntX = [];
var pntMidX = [];
var pntDestX = [];
var pntVx = [];
var pntAx = [];

for(i = 0; i < 3; i++) {
	pntX[i] = selectedX + (7 * (i - 1));
	pntMidX[i] = pntX[i];
	pntDestX[i] = pntX[i];
	pntVx[i] = 0;
	pntAx[i] = 0;
}

//draw loop/function
function drawHeader() {
	//redraw the background for new frame
	headerContext.fillStyle = 'white';
	headerContext.fillRect(0, 0, width, height);

	//draw "selection" indicator
	for(i = 0; i < 3; i++) {
		headerContext.fillStyle = 'lightgrey';
		headerContext.beginPath();
		headerContext.arc(selectedX + 7 * (i - 1), 3, 2, Math.PI * 2, false);
		headerContext.fill();
	}

	for(i = 0; i < 3; i++) {
		//update
		if(pntDestX[i] > pntX[i] && pntX[i] < pntMidX[i])
			pntAx[i] = 1.0;
		else if(pntDestX[i] > pntX[i] && pntX[i] > pntMidX[i])
			pntAx[i] = -1.0;
		else if(pntDestX[i] < pntX[i] && pntX[i] > pntMidX[i])
			pntAx[i] = -1.0;
		else if(pntDestX[i] < pntX[i] && pntX[i] < pntMidX[i])
			pntAx[i] = 1.0;

		if(Math.abs(pntVx[i]) < 4) {
			if((pntVx[i] > 0 && pntAx[i] < 0) || (pntVx[i] < 0 && pntAx[i] > 0)) {
				pntMidX[i] = (pntX[i] + pntDestX[i])/2;
				//pntVx[i] = 0;
			}

			if(Math.abs(pntX[i] - pntDestX[i]) < 4) {
				pntAx[i] = 0;
				pntVx[i] = 0;
				pntX[i] = pntDestX[i];
			}
		}

		pntVx[i] += pntAx[i];
		pntX[i] += pntVx[i];

		//draw
		headerContext.fillStyle = 'black';
		headerContext.beginPath();
		headerContext.arc(pntX[i], 3, 2, Math.PI * 2, false);
		headerContext.fill();
	}

	requestAnimationFrame(drawHeader);
}

function adjustNavPnt() {
	pntDestX[pntCntr] = targetNavLink.position().left + (targetNavLink.width() + 40)/2 + (7 * (pntCntr - 1));
	pntMidX[pntCntr] = (pntX[pntCntr] + pntDestX[pntCntr])/2;
	pntCntr += direction;

	if(pntCntr >= 3 || pntCntr < 0) {
		clearInterval(pntTimer);
	}
}

//navigation bar events
$(".navLink").mouseover(function() {
	targetNavLink = $(this);

	if(targetNavLink.position().left < pntX[0]) {
		pntCntr = 0;
		direction = 1;
	} else {
		pntCntr = 2;
		direction = -1;
	}
	pntTimer = setInterval(adjustNavPnt, 40);
});

$("#navBar").mouseleave(function() {
	targetNavLink = $("#startLink");

	if(targetNavLink.position().left < pntX[0]) {
		pntCntr = 0;
		direction = 1;
	} else {
		pntCntr = 2;
		direction = -1;
	}
	pntTimer = setInterval(adjustNavPnt, 130);
});

drawHeader();
