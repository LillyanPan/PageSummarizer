//: Playground - noun: a place where people can play

import UIKit

var str = "Hello, playground"

// Declare string array and access array
var myArray:[String] = ["book", "cat"]
myArray[0]

// Length
myArray.count

// Insert
myArray.insert("dog", atIndex: 0)

// Add to end of array
myArray.append("tree")
myArray += ["elephant", "mud"]

myArray.count

// Remove
myArray.removeLast()

// Declare empty int array
var intArray:[Int] = [Int]()
intArray.append(5)
