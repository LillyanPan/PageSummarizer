#include "Point.h"
#include <iostream>
#include <list>
using namespace std;

//Create map of labels with key: label, value: coord point
public Dictionary<int, list<Pixel>> storeLabel(int width, int height) {
	Dictionary<int, list<Point>> dLabel = new Dictionary<int, list<Point>>();
	for (y = 0 ; y <= height ; y++) { 						//run through image
		for (x = 0; x <= width; x++) {
        	int coordLabel = im.u[y][x]; 					//get label number at pixel WRONG
        	if (coordLabel != 0) {
        		if (!storeLabel.ContainsKey(coordLabel)) { 	//if dict doesn't contain label
        			coordLabel.Add(coordLabel, new list<Point>());
        		}
        	storeLabel[coordLabel].Add(new Point(x,y)); 	//add new point to list
        	}
        }
    }
    return coordLabel;
}

public Point getCenter (Dictionary<int, list<Point>> map) {
	list value = {};
	double xsum = 0;
	double xmass = 0;
	double ysum = 0;
	double ymass = 0;
	double xcenter = 0;
	double ycenter = 0;
	for each (/* pixel in specific label*/) {
		xsum += x * pixel.intensity; // pseudo
		xmass += x;
		ysum += y * pixel.intensity; // pseudo
		ymass += y;
		xcenter = xsum / xmass;
		ycenter = ysum / ymass;
	}
	Point center = new Point (xcenter, ycenter);
	return center;
}