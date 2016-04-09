//
//  ViewController.swift
//  War
//
//  Created by Lillyan Pan on 8/11/15.
//  Copyright (c) 2015 Lillyan Pan. All rights reserved.
//

import UIKit

class ViewController: UIViewController {

    @IBOutlet weak var firstCardImageView: UIImageView!
    @IBOutlet weak var secondCardImageView: UIImageView!
    @IBOutlet weak var playRoundButton: UIButton!
    @IBOutlet weak var backgroundImageView: UIImageView!
    @IBOutlet weak var playerScore: UILabel!
    @IBOutlet weak var enemyScore: UILabel!
    
    
    
    
    var cardNamesArray:[String] = ["card1", "card2", "card3", "card4", "card5", "card6", "card7", "card8", "card9", "card10", "card11", "card12", "card13"]
    var playerScoreTotal = 0
    var enemyScoreTotal = 0
    
    
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
        
        // Do not want Title anymore because use image
        //self.playRoundButton.setTitle("Play", forState: UIControlState.Normal)
        
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }

    
    @IBAction func playRoundTapped(sender: UIButton) {
        
        // Randomize a number for imageview
        var firstRandomNumber:Int = Int(arc4random_uniform(13))
        
        // Returns 0-12 random number; cast to int
        var secondRandomNumber:Int = Int(arc4random_uniform(13))
        
        // Construct a string with the random number
        var firstCardString:String = self.cardNamesArray[firstRandomNumber]
        var secondCardString:String = self.cardNamesArray[secondRandomNumber]
        
        // Set the image biew to the asset coresponding to the randomized number
        self.firstCardImageView.image = UIImage(named: firstCardString)
        self.secondCardImageView.image = UIImage(named: secondCardString)
        
        // Determine higher number
        if firstRandomNumber > secondRandomNumber {
            playerScoreTotal++
            self.playerScore.text = String(playerScoreTotal)
        }
        else if firstRandomNumber == secondRandomNumber {
            // Tie
        }
        else {
            enemyScoreTotal++
            self.enemyScore.text = String(enemyScoreTotal)
        }
    }

}

