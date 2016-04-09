//
//  ResultsTableViewController.swift
//  FindMyGoat
//
//  Created by Daniel Hauagge on 3/5/16.
//  Copyright © 2016 Daniel Hauagge. All rights reserved.
//

import UIKit
import RealmSwift

class ResultsTableViewController: UITableViewController {

    @IBOutlet weak var addButton: UIBarButtonItem!
    
    var token : NotificationToken?
    
    @IBAction func addButtonPressed(sender: AnyObject) {
        performSegueWithIdentifier("modSegue", sender: sender)
    }
    override func viewDidLoad() {
        super.viewDidLoad()

        let realm = try! Realm()
        token = realm.addNotificationBlock { (notification, realm) -> Void in
            self.tableView.reloadData()
        }
        
        // Uncomment the following line to preserve selection between presentations
        // self.clearsSelectionOnViewWillAppear = false

        // Uncomment the following line to display an Edit button in the navigation bar for this view controller.
        // self.navigationItem.rightBarButtonItem = self.editButtonItem()
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }

    // MARK: - Table view data source

    override func numberOfSectionsInTableView(tableView: UITableView) -> Int {
        return 1
    }

    override func tableView(tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        
        let realm = try! Realm()
        let nGoats = realm.objects(Goat).count
        return nGoats
    }

    override func tableView(tableView: UITableView,
        cellForRowAtIndexPath indexPath: NSIndexPath) -> UITableViewCell {
        
        let cell = tableView.dequeueReusableCellWithIdentifier("GoatCell", forIndexPath: indexPath) as! GoatTableViewCell
        
        let idx = indexPath.row
            
        let realm = try! Realm()
        let goat = realm.objects(Goat)[idx]

        cell.goat = goat
            
        return cell
    }

    /*
    // Override to support conditional editing of the table view.
    override func tableView(tableView: UITableView, canEditRowAtIndexPath indexPath: NSIndexPath) -> Bool {
        // Return false if you do not want the specified item to be editable.
        return true
    }
    */

    /*
    // Override to support editing the table view.
    override func tableView(tableView: UITableView, commitEditingStyle editingStyle: UITableViewCellEditingStyle, forRowAtIndexPath indexPath: NSIndexPath) {
        if editingStyle == .Delete {
            // Delete the row from the data source
            tableView.deleteRowsAtIndexPaths([indexPath], withRowAnimation: .Fade)
        } else if editingStyle == .Insert {
            // Create a new instance of the appropriate class, insert it into the array, and add a new row to the table view
        }    
    }
    */

    /*
    // Override to support rearranging the table view.
    override func tableView(tableView: UITableView, moveRowAtIndexPath fromIndexPath: NSIndexPath, toIndexPath: NSIndexPath) {

    }
    */

    /*
    // Override to support conditional rearranging of the table view.
    override func tableView(tableView: UITableView, canMoveRowAtIndexPath indexPath: NSIndexPath) -> Bool {
        // Return false if you do not want the item to be re-orderable.
        return true
    }
    */

    // MARK: - Navigation

    // In a storyboard-based application, you will often want to do a little preparation before navigation
    override func prepareForSegue(segue: UIStoryboardSegue, sender: AnyObject?) {
        if (segue.identifier == "editSegue") {
//            self.nameTextField.text = goat.name
//            self.ageTextField.text = "\(goat.age)"
            let cell = sender as! GoatTableViewCell
            let dest = segue.destinationViewController as! DetailsViewController
        
            dest.goat = cell.goat
        }
    }
}
