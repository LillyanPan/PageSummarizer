//
//  DetailsViewController.swift
//  Maps
//
//  Created by Lillyan Pan on 3/5/16.
//  Copyright © 2016 Lillyan Pan. All rights reserved.
//

import UIKit
import RealmSwift

class DetailsViewController: UIViewController {

    var goat : Goat!
    
    @IBOutlet weak var ageTextField: UITextField!
    @IBOutlet weak var nameTextField: UITextField!
    
    @IBAction func cancelButtonClick(sender: AnyObject) {
        self.dismissViewControllerAnimated(true, completion: nil)
    }
    
    @IBAction func saveButtonClick(sender: AnyObject) {
//      Get instance of DB
//      try! : aknowledges that Realm() could fail; in production should write try:... catch:...
        let realm = try! Realm()
        
        try! realm.write {
            goat.name = nameTextField.text!
            goat.age = Int(ageTextField.text!)!
            realm.add(goat)
        }
        
        let nGoats = realm.objects(Goat).count
        print("we have \(nGoats) goats in db")
        
        self.dismissViewControllerAnimated(true, completion: nil)
    }
    
    override func viewDidLoad() {
        super.viewDidLoad()
        self.nameTextField.text = goat.name
        self.ageTextField.text = "\(goat.age)"
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    

    /*
    // MARK: - Navigation

    // In a storyboard-based application, you will often want to do a little preparation before navigation
    override func prepareForSegue(segue: UIStoryboardSegue, sender: AnyObject?) {
        // Get the new view controller using segue.destinationViewController.
        // Pass the selected object to the new view controller.
    }
    */
    
//  To disimiss keyboard; resignFirstResponder: don't want to get input from keyboard anymore
//  Later implement dismiss keyboard on return
    override func touchesBegan(touches: Set<UITouch>, withEvent event: UIEvent?) {
        self.ageTextField.resignFirstResponder()
        self.nameTextField.resignFirstResponder()
    }

}
