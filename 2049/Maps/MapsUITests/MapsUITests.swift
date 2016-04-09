//
//  MapsUITests.swift
//  MapsUITests
//
//  Created by Lillyan Pan on 3/5/16.
//  Copyright © 2016 Lillyan Pan. All rights reserved.
//

import XCTest
import MinHeap

class MapsUITests: XCTestCase {
    
    var heap : MinIntHeap
    
        
    override func setUp() {
        super.setUp()
        
        // Put setup code here. This method is called before the invocation of each test method in the class.
        
        // In UI tests it is usually best to stop immediately when a failure occurs.
        continueAfterFailure = false
        // UI tests must launch the application that they test. Doing this in setup will make sure it happens for each test method.
        XCUIApplication().launch()

        // In UI tests it’s important to set the initial state - such as interface orientation - required for your tests before they run. The setUp method is a good place to do this.
    }
    
    override func tearDown() {
        // Put teardown code here. This method is called after the invocation of each test method in the class.
        super.tearDown()
    }
    
    func testExample() {
        // Use recording to get started writing UI tests.
        // Use XCTAssert and related functions to verify your tests produce the correct results.
        heap!.enQueue(0, val: "Age 0")
        heap!.enQueue(0, val: "Age 1")
        XCTAssert(heap!.peak()!.v == "Age 0")
    }
    
    func testPerformanceExample() {
        self.measureBlock { () -> Void in
            var pri = 0
            while pri < 1000 {
                let value = "Age \(pri)"
                self.heap!.enQueue(pri, val: value)
                pri = pri + 1
            }
        }
    }
    
    // UI Testing use XCT Assert and related functions to verify your tests; use recording to write UI tests
    
}
