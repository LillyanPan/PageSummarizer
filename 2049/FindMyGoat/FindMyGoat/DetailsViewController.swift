//
//  DetailsViewController.swift
//  FindMyGoat
//
//  Created by Daniel Hauagge on 3/5/16.
//  Copyright © 2016 Daniel Hauagge. All rights reserved.
//

import UIKit
import RealmSwift

class DetailsViewController: UIViewController, UITextFieldDelegate {

    var goat : Goat!
    
    @IBOutlet weak var saveButton: UIButton!
    override func viewDidLoad() {
        super.viewDidLoad()
        
        nameTextField.delegate = self
        
//        checkValidGoatName()
        
//        self.nameTextField.text = goat.name ?? ""
//        self.ageTextField.text = "\(goat.age)" ?? ""
    }

    @IBOutlet weak var nameTextField: UITextField!
    @IBOutlet weak var ageTextField: UITextField!
    
    @IBAction func cancelButtonClicked(sender: AnyObject) {
        self.dismissViewControllerAnimated(true, completion: nil)
    }
    
    @IBAction func saveButtonClicked(sender: AnyObject) {
        let goat = Goat()
        
        let realm = try! Realm()
        try! realm.write {
            
            goat.name = nameTextField.text!
            goat.age = Int(ageTextField.text!)!
            

            realm.add(goat)
        }

        let nGoats = realm.objects(Goat).count
        print("We have \(nGoats) goats in the database")
        
        self.dismissViewControllerAnimated(true, completion: nil)
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
    
    override func touchesBegan(touches: Set<UITouch>, withEvent event: UIEvent?) {
        self.ageTextField.resignFirstResponder()
        self.nameTextField.resignFirstResponder()
    }
    
    func textFieldDidBeginEditing(textField: UITextField) {
        // Disable the Save button while editing.
        saveButton.enabled = false
    }
    
    func textFieldDidEndEditing(textField: UITextField) {
        checkValidGoatName()

    }
    
    func checkValidGoatName() {
        // Disable the Save button if the text field is empty.
        let nameText = nameTextField.text ?? ""
        let ageText = ageTextField.text ?? ""
        var validInt = true
        if let intVal = Int(ageText) {
            // Text field converted to an Int
            if (intVal > 0 && intVal < 1000) {
                validInt = true
            }
        } else {
            // Text field is not an Int
            validInt = false
        }
        print(nameText.isEmpty,ageText.isEmpty,validInt)
        print(ageTextField.text)
        saveButton.enabled = !nameText.isEmpty && !ageText.isEmpty && validInt
    }
    

}
