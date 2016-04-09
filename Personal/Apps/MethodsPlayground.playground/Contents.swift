//: Playground - noun: a place where people can play

import UIKit

var str = "Hello, playground"

class Person {
    
    var Name:String
    var Age:Int
    
    init () {
        self.Name = "Initial Name"
        self.Age = 10
    }
    
    func UpdateNameAndAge (nameToSet:String, ageToSet:Int) {
        self.Name = nameToSet
        self.Age = ageToSet
    }
    
    // -> implies return
    func IncreaseAge(ageToIncreaseBy:Int) -> Int {
        self.Age += ageToIncreaseBy
        
        return self.Age
    }
    
    // Class Method- used for calc that don't deal with objects
    class func AvgAge () -> Int {
        return 50
    }
    
}

var a = Person()
a.UpdateNameAndAge("Lillyan", ageToSet: 19)

var newAge = a.IncreaseAge(5)

var Av = Person.AvgAge()
