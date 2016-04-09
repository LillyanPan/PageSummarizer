//
//  Models.swift
//  Maps
//
//  Created by Lillyan Pan on 3/5/16.
//  Copyright © 2016 Lillyan Pan. All rights reserved.
//

import Foundation
import RealmSwift
import RealmMapView

// Random number [0,1]
func rand() -> Double {
    return Double(arc4random()) / Double(UINT32_MAX)
}



// Goat subclass of Object
class Goat : Object {
//   Create a primary key
    dynamic var id = NSUUID().UUIDString
//  dynamic: helps realm keep track of changes
    dynamic var name = ""
    dynamic var age = 0
    
    dynamic var latitude = 40.7127 + rand() / 1000.0
    dynamic var longitude = -74.0059 + rand() / 1000.0
    
    override static func primaryKey() -> String? {
        return "id"
    }
}

func countGoat() -> Int {
    let realm = try! Realm()
    let total = realm.objects(Goat).count
    return total
}

