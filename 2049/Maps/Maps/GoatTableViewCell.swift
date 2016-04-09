//
//  GoatTableViewCell.swift
//  Maps
//
//  Created by Lillyan Pan on 3/5/16.
//  Copyright © 2016 Lillyan Pan. All rights reserved.
//

import UIKit
import RealmSwift
import CoreLocation

class GoatTableViewCell: UITableViewCell, CLLocationManagerDelegate {
    
    var goat : Goat! {
//      Called everytime goat data changes
        didSet(newValue) {
            updateUI()
        }
    }

    @IBOutlet weak var nameTextField: UITextField!
    @IBOutlet weak var ageTextField: UITextField!
    
    
    override func awakeFromNib() {
        super.awakeFromNib()
    }
    
    func updateUI() {
        nameTextField.text = goat.name
        
        ageTextField.text = "\(goat.age)"
    }

    override func setSelected(selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)

        // Configure the view for the selected state
    }

}
