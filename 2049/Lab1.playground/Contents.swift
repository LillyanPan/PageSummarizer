//: Playground - noun: a place where people can play

import UIKit

var str = "Hello, playground"

//like ocaml

let pi : Double = 3.14

//can define array to have type
//if use let - array is immutable; don't dfeine array without knowing types beforehand

var mutableintlist : [Int] = [1,2,3,4]
mutableintlist.append(5)

let immutableList : [Int] = [1,2,3,4]
//immutableList.append(5)

var intList = [Int]() // ArrayList<Int>

// Dictionary

// let: use only for contant
let immutableDict = [:]
//immutableDict["a"] = 1

var mutableDict = [String:Int]()
mutableDict["a"] = 1

// Control Flow

if true {
    print("first")
}
else {
    print("nope")
}

for x in 1...4 {
    print(x)
} // 1,2,3,4

for x in 1..<10 {
    x*x
} // left eye visualizes points

// Functional Feautures
// Optional Types

var optionX : String? = "String"
optionX! // if you know optionX is there, will throw error otherwise

var optionY : String?
// If optionY was not initialized / returns null, then all code afterwords will not be executed

// optionY =  "Init"
    //to do anything with the optional, you need to unwrap it to make sure it was initialized
    //to unwrap use ! or "if let"
if let y = optionY {
    print("all good")
}
else {
    print("optionY was not initialized")
}

//Tuple
var t = (1, "String", [1:2], [1,2,3])

let tuple = (name: "Me", age: 10)
tuple.name
tuple.age

// Function
class Demo {
    
//    Return type: -> <type>
    private func demoFunction1(param1: Int, param2: String) -> Int {
        return 1
    }
    
    func anotherFunction() {
        self.demoFunction1(1, param2: "Hello")
        self.multipleReturn().hours
        self.multipleReturn().name
    }
    
    func multipleReturn() -> (hours:Int, name:String) {
        return (1, "A")
    }
}

let instance = Demo()
instance.anotherFunction()

// Closure - good for multithreaded programming
let add1 = { (x:Int) -> Int in x + 1 }
let addition = { (x:Int, y:Int) -> Int in return (x + y) }
let factorial = {
    (x:Int) -> Int in
    var ret = 1
    for i in 1...x {
        ret *= i
    }
    return ret
}

add1
add1(1)
factorial(5)

// Can now stick funcitonv into variable
// f,g -> f (g x)
func compose(f: (Int -> Int), g: (Int -> Int), x: Int) -> Int {
    return f(g(x))
}

// factorial(add1 3) = fact(4) = 4 * 3 * 2 * 1 = 24
compose(factorial, g:add1, x:3)

// f, g -> return a function h = f compose g
func compose1(f:(Int->Int), g:(Int->Int)) -> (Int->Int) {
    return {
        (x:Int)->Int in
        f(g(x))
    }
}

let h = compose1(factorial, g:add1)
h(3)

/*GCD - grand central dispatch
iOS app -> process within iOS process system
Main queue: list of closure
Worker queue: in response to an event will create closure to execute task in worker Q
programmer can specify queue but not thread
Closure: throws things to the worker queue

Push main UI updated into main queue

closure: an environement with all variables in scope and actions we can take
*/


