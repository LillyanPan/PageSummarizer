//: Playground - noun: a place where people can play

import UIKit

var str = "Hello, playground"

class Person {
    
    // Property with initial value
    // Variable equivalents var a:Int = 10 and var a = 10
    var Name:String = "Initial Name"
    
    // Initialize to have Person object act correctly
    init () {
        self.sayCheese()
    }
    
    // Method
    func sayCheese () {
        println("Cheese")
    }
    
    func Walk() {
        println("I'm walking")
    }
    
}

// Creates Persion object
var a = Person()

//Access new variable
var firstPerson = Person()

firstPerson.Name
firstPerson.Name = "Alice"
firstPerson.Name

//Subclass
class Superhuman : Person {
    
    // Custom init; cannot access parent class methods/properties
    override init () {
        
        //Now can use parent class meth/prop
        super.init()
        
        super.Name = "Super Duper"
    }
    
    var AlterEgoName:String = "Clark"
    
    func Fly() {
        println("I'm flying")
    }
    
    // Overrides Walk method in Person
    override func Walk() {
        println("I'm walking super fast")
        
        // Uses parent's Walk method
        super.Walk()
    }
    
}
var b = Superhuman()
b.Name
b.Walk()
b.Fly()
