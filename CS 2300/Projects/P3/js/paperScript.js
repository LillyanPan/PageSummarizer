// Source: http://paperjs.org/tutorials/ !
var count = 100;
var colors = ["#76B041", "#FFC914", "#E4572E", "#17BEBB", "#9975b9"];

/* OPTION 1 */
console.log("HERE");
var direction = [1, -1];
var rand = Math.floor(Math.random() * colors.length);
var path = new Path.Circle({
	center: [0, 0],
	radius: 5,
	fillColor: colors[rand],
	strokeColor: colors[rand]
});
path.sendToBack();

var symbol = new Symbol(path);

for (var i = 0; i < count; i++) {
	// The center position is a random point in the view:
	var center = Point.random() * view.size;
	var placedSymbol = symbol.place(center);
	placedSymbol.scale(i / count);
}

// /* OPTION 2 */
// var circle = new Path.Circle(new Point(0,0), 5);
// circle.fillColor = colors[0];

// for (var i = 0; i < count; i++) {
// 	var clone = circle.clone();
// 	var center = Point.random() * view.size;
// 	clone.position = center;

// 	var scale = (i + 1) / count
// 	clone.scale(scale);

// 	clone.data.vector = new Point ({
// 		angle: Math.random() * 360,
// 		length: scale * Math.random()
// 	});
// 	clone.fillColor = colors[i % colors.length];
// }

function onFrame(event) {
	// Run through the active layer's children list and change
	// the position of the placed symbols:
	for (var i = 0; i < count; i++) {
		var item = project.activeLayer.children[i];
		if (i % 2 == 0) {
			
			// Move the item to the right. This way
			// larger circles move faster than smaller circles:
			item.position.x += item.bounds.width / 60;
			item.position.y += item.bounds.height / 40;

			// If the item move off screen, move it back
			if (item.bounds.left > view.size.width) {
				item.position.x = -item.bounds.width * Math.random();
			}
			if (item.bounds.top > view.size.height) {
				item.position.y = -item.bounds.height * Math.random();
			}
		}

		else {
			item.position.x -= item.bounds.width / 30;
			item.position.y -= item.bounds.height / 70;

			// If the item move off screen, move it back
			if (item.bounds.left < 0) {
				item.position.x = view.bounds.width;
			}
			if (item.bounds.top < 0) {
				item.position.y = view.bounds.height;
			}
		}
	}
}