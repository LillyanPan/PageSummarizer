//
//  ViewController.swift
//  CocoaLab
//
//  Created by Lillyan Pan on 2/27/16.
//  Copyright © 2016 Lillyan Pan. All rights reserved.
//

import UIKit
import Alamofire

class ViewController: UIViewController {

    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
        Alamofire.request(.GET, "https://httpbin.org/get", parameters: ["foo": "bar"])
            .responseJSON { response in
                print(response.request)  // original URL request
                print(response.response) // URL response
                print(response.data)     // server data
                print(response.result)   // result of response serialization
                
                if let JSON = response.result.value {
                    print("JSON: \(JSON)")
                }
        }
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }


}

/* 
Debugging
(lldb) e self : will evaluate self
Profiling tools
    Instrament: can show all of memory allocation/deallocation of app
    Can look at memory leak check
OpenGL library (File -> New -> Create an instrament)
*/

