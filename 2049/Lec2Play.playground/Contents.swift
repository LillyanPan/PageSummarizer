//: Playground - noun: a place where people can play

import UIKit

var str = "Hello, playground"

//Over 2 seconds; 60 frames per
for frameNo in 0 ... 120 {
    // vary 0 - 2
    var blueVal = (1.0 + sin(Double(frameNo) / 10.0)) * 0.5 * 255
//    Press side + by eye to plot value
    print(blueVal)
}