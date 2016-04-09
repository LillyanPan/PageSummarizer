//
//  SettingsViewController.swift
//  AccelDraw2
//
//  Created by Daniel Hauagge on 2/6/16.
//  Copyright © 2016 Daniel Hauagge. All rights reserved.
//

import UIKit

protocol SettingsViewControllerDelegate: class {
    func finalChosenSettings(red : CGFloat, green : CGFloat, blue : CGFloat)
}

class SettingsViewController: UIViewController {
    var delegate : SettingsViewControllerDelegate?

    @IBOutlet weak var redSlider: UISlider!
    @IBOutlet weak var greenSlider: UISlider!
    @IBOutlet weak var blueSlider: UISlider!
    
    @IBOutlet weak var colorPreview: UIView!
    
    var red = CGFloat(0)
    var green = CGFloat(0)
    var blue = CGFloat(0)
    
    override func viewDidLoad() {
        redSlider.value = Float(red)
        greenSlider.value = Float(green)
        blueSlider.value = Float(blue)
        updateColorPreview()
    }
    
    @IBAction func redSliderChanged(sender: UISlider) {
        red = CGFloat(sender.value)
        updateColorPreview()
    }
    
    @IBAction func greenSliderChanged(sender: UISlider) {
        green = CGFloat(sender.value)
        updateColorPreview()
    }
    
    @IBAction func blueSliderChanged(sender: UISlider) {
        blue = CGFloat(sender.value)
        updateColorPreview()
    }
    
    func updateColorPreview() {
        colorPreview.backgroundColor = UIColor(
            colorLiteralRed: Float(red), green: Float(green), blue: Float(blue), alpha: 1)
    }
    
    @IBAction func doneButtonClicked(sender: AnyObject) {
        delegate?.finalChosenSettings(red, green: green, blue: blue)
        dismissViewControllerAnimated(true, completion: nil)
    }
}
