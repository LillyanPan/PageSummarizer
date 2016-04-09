//
//  ViewController.swift
//  SimpleChatApp
//
//  Created by Lillyan Pan on 8/17/15.
//  Copyright (c) 2015 Lillyan Pan. All rights reserved.
//

import UIKit
import Parse

class ViewController: UIViewController, UITableViewDelegate, UITableViewDataSource{

    @IBOutlet weak var messageTableView: UITableView!
    
    var messagesArray:[String] = [String]()
    
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
    
        self.messageTableView.delegate = self
        self.messageTableView.dataSource = self
        
        // Add sample data to see something; need self to refer to viewController; don't need self
        self.messagesArray.append("Test 1")
        self.messagesArray.append("Test 2")
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }

    func tableView(tableView: UITableView, cellForRowAtIndexPath indexPath: NSIndexPath) -> UITableViewCell {
        // Create a table cell
        let cell = self.messageTableView.dequeueReusableCellWithIdentifier("messageCell") as! UITableViewCell
        
        // Customize the cell
        cell.textLabel?.text = self.messagesArray[indexPath.row]
        // ? is optional chaining; could or could not exist; safe way to access something if something may not exist
        
        // Return the cell
        return cell
    }
    
    func tableView(tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return messagesArray.count
    }
    
}

