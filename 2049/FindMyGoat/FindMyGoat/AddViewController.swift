//
//  AddViewController.swift
//  FindMyGoat
//
//  Created by Lillyan Pan on 4/16/16.
//  Copyright © 2016 Daniel Hauagge. All rights reserved.
//

import UIKit
import CoreMotion
import Fluent

class AddViewController: UIViewController {
    
    /* Fluent */
    let boxView = UIView(frame: CGRect(x: 0, y: 0, width: 100, height: 100))
    var expanded = false
    
//    @IBAction func handleTap(sender: AnyObject) {
//        print("IN TAP")
//        boxView
//            .animate(0.5)
//            .rotate(0.5)
//            .scale(2)
//            .backgroundColor(UIColor.blueColor())
//            .waitThenAnimate(0.5)
//            .scale(1)
//            .backgroundColor(UIColor.redColor())
//    }
    
    func handleTap(tap: UITapGestureRecognizer) {
        print("In tapp")
        boxView
            .animate(0.5)
            .rotate(0.5)
            .scale(2)
            .backgroundColor(UIColor.blueColor())
            .waitThenAnimate(0.5)
            .scale(1)
            .backgroundColor(UIColor.redColor())
    }

    @IBOutlet weak var imageView: UIImageView!
    
    var session = CMMotionManager()
    
    var currPoint = CGPointMake(0, 0)
    
    var red = CGFloat(1)
    var green = CGFloat(0)
    var blue = CGFloat(0)
    
    var shouldClearDrawing = false
    

    override func viewDidLoad() {
        super.viewDidLoad()
        
        boxView.backgroundColor = .redColor()
        boxView.center = CGPoint(x: view.bounds.width/2, y: view.bounds.height/2)
        view.addSubview(boxView)
        view.addGestureRecognizer(UITapGestureRecognizer(target: self, action: "handleTap:"))

        // Do any additional setup after loading the view.
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
        session.accelerometerUpdateInterval = 1.0/60.0 // seconds
        session.startAccelerometerUpdatesToQueue(
            NSOperationQueue.mainQueue()) {
                (data: CMAccelerometerData?, error: NSError?) in
                
                
                let accel = CGPoint(
                    x: data!.acceleration.x,
                    y: data!.acceleration.y
                )
                let s = CGFloat(5.0)
                
                /*************************** Off Screen ********************************/
                
                var nextX = self.currPoint.x + s * accel.x
                var nextY = self.currPoint.y + s * accel.y

                
                
                let imageViewBottom = -155.0 as CGFloat
                let imageViewTop = 125.0 as CGFloat
                let imageViewLeft = -260.0 as CGFloat
                let imageViewRight = 200.0 as CGFloat
                
                if (nextX < imageViewBottom) {
                    nextX = imageViewBottom
                }
                
                if (nextX > imageViewTop) {
                    nextX = imageViewTop
                }
                
                if (nextY < imageViewLeft) {
                    nextY = imageViewLeft
                }
                if (nextY > imageViewRight) {
                    nextY = imageViewRight
                }
                
                let nextPoint = CGPointMake(nextX, nextY)
                
                
                self.drawLine(
                    fromPoint: self.currPoint,
                    toPoint: nextPoint
                )
                
                self.currPoint = nextPoint
        }
        
    }
    
    
    func drawLine(fromPoint a: CGPoint, toPoint b:CGPoint) {
        UIGraphicsBeginImageContext(self.imageView.frame.size)
        
        let context = UIGraphicsGetCurrentContext()
                
        if !shouldClearDrawing {
            self.imageView.image?.drawInRect(
                CGRectMake(0, 0,
                    self.imageView.frame.size.width,
                    self.imageView.frame.size.height))
        }
        shouldClearDrawing = false
        
        let origin = CGPointMake(
            self.imageView.frame.origin.x + self.imageView.frame.size.width/2.0,
            self.imageView.frame.origin.y + self.imageView.frame.size.height/2.0
        )
        
        // Draw line
        CGContextMoveToPoint(context, a.x + origin.x, a.y + origin.y)
        CGContextAddLineToPoint(context, b.x + origin.x, b.y + origin.y)
        
        // Set line params
        CGContextSetLineWidth(context, 5.0)
        CGContextSetRGBStrokeColor(context, red, green, blue, 1.0)
        CGContextSetLineCap(context, .Round)
        
        // Render the drawing
        CGContextStrokePath(context)
        
        // Put the newly rendered image into the image view
        self.imageView.image = UIGraphicsGetImageFromCurrentImageContext()
        
        // End the graphics context
        UIGraphicsEndImageContext()
    }
    

    /*
    // MARK: - Navigation

    // In a storyboard-based application, you will often want to do a little preparation before navigation
    override func prepareForSegue(segue: UIStoryboardSegue, sender: AnyObject?) {
        // Get the new view controller using segue.destinationViewController.
        // Pass the selected object to the new view controller.
    }
    */

}
