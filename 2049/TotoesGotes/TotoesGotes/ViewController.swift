//
//  ViewController.swift
//  TotoesGotes
//
//  Created by Lillyan Pan on 2/20/16.
//  Copyright © 2016 Lillyan Pan. All rights reserved.
//

import UIKit
import AVFoundation

//AVCaptureVideoDataOutputSampleBufferDelegate delegate can handle frame buffers

class ViewController: UIViewController, AVCaptureVideoDataOutputSampleBufferDelegate {
    
    
    @IBOutlet weak var button: UIButton!
    @IBOutlet weak var frontButton: UIButton!
    @IBOutlet weak var backButton: UIButton!
        var back = true
    
    @IBAction func backButtonClick(sender: AnyObject) {
        back = true
        changeCamera()
    }
    @IBAction func frontButtonClick(sender: AnyObject) {
        back = false
        changeCamera()
    }
    
    @IBAction func buttonClick(sender: AnyObject) {
        isProcessing = !isProcessing
        
        if isProcessing {
            processedLayer.hidden = false
            processedLayer.backgroundColor = nil // sets layer to be transparent, so we can see the video feed in preview layer
            previewLayer.hidden = false
        }
//      start color
        else {
//            processedLayer.hidden = true
//            previewLayer.hidden = false
            processedLayer.hidden = false
            processedLayer.backgroundColor = nil
            previewLayer.hidden = false
        }
    }
    
    
    
    
    var session = AVCaptureSession()
//  ! means don't instatiated until used
    var previewLayer : AVCaptureVideoPreviewLayer!
    var processedLayer : CALayer!
    
    var isProcessing = false
    var frameNumber = 0 // keep track of time
    
    var faceDetector = CIDetector(ofType: CIDetectorTypeFace, context: nil, options: [
        //high accuracy eats up battery
        CIDetectorAccuracy: CIDetectorAccuracyHigh,
        // only useful for video; not for pictures
        CIDetectorTracking: true
        ])
    
    lazy var goatFace : UIImage! = {
//        var path = NSBundle.mainBundle().pathForResource("goat", ofType: "png")!
        return UIImage(named: "bagel")
    }()
    
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        changeCamera()
////      array of all devices on phone
//        let devices = AVCaptureDevice.devicesWithMediaType(AVMediaTypeVideo) as! [AVCaptureDevice]
//        
//        // each device has orientation
//        // Pick front facing camera
////        let device = devices.filter ({ $0.position == .Front }).first!
//        
//        let device = devices.first!
//        
//        let input = try! AVCaptureDeviceInput(device: device)
////      Equivalent
////        do {
////            let input = AVCaptureDeviceInput(device: device)
////        } catch {
////            assert(false)
////        }
//        
////      assert(session.canAddInput(input)) when you want to let the user choose
//        
//        assert(session.canAddInput(input))
//        session.addInput(input)
//        
////      Create preview layer
//        previewLayer = AVCaptureVideoPreviewLayer(session: session)
//        previewLayer.frame = self.view.bounds
////      Add layer to current view; always have view which has layyer and append sublayer
//        self.view.layer.addSublayer(previewLayer)
//        
////      Create processed video layer
//        processedLayer = CALayer()
//        processedLayer.frame = self.view.bounds
//        processedLayer.hidden = true
////        processedLayer.backgroundColor = UIColor.redColor().CGColor
//        self.view.layer.addSublayer(processedLayer)
//        
//        
////      Create data output
//        
////      Queue that's different from main queue; every time delegate method is called its going to be in other queue
////      delegate know how to process specifically
//        let frameProcessingQueue = dispatch_queue_create("goatface.frameprocessing", DISPATCH_QUEUE_SERIAL)
//        let output = AVCaptureVideoDataOutput()
//        // Same pixel format
//        output.videoSettings = [ kCVPixelBufferPixelFormatTypeKey: Int(kCVPixelFormatType_32BGRA) ]
////      queue to not block UI; UI seems responsive
//        output.setSampleBufferDelegate(self, queue: frameProcessingQueue)
//        assert(session.canAddOutput(output))
//        session.addOutput(output)
//        
//        // Draw Button
//        button.layer.cornerRadius = button.frame.width / 2.0
//        self.view.bringSubviewToFront(button)
//        
//        frontButton.layer.cornerRadius = frontButton.frame.width / 2.0
//        self.view.bringSubviewToFront(frontButton)
//
////      To start running camera
//        session.startRunning()
    }
    
    func hackFixOrientation(img: UIImage) -> CGImageRef {
        let debug = CIImage(CGImage: img.CGImage!).imageByApplyingOrientation(6)
        let context = CIContext()
        let fixedImg = context.createCGImage(debug, fromRect: debug.extent)
        return fixedImg
    }
    
    func detectFaces(imageBuffer : CVImageBufferRef) {
        CVPixelBufferLockBaseAddress(imageBuffer, 0)
        
        
        let height = CVPixelBufferGetHeight(imageBuffer)
//      Now can detect faces!
        let ciImage = CIImage(CVImageBuffer: imageBuffer)
//      options: orientation is very important! will make or break face detection; can't rotate camera though with "6"
        let faces = faceDetector.featuresInImage(ciImage,
            options: [CIDetectorImageOrientation: 6]) as! [CIFaceFeature]
        
        //Draw rectangles around faces
        UIGraphicsBeginImageContext(ciImage.extent.size)
        
        let context = UIGraphicsGetCurrentContext()
        
        // Set line properties
        CGContextSetLineWidth(context, 30)
        CGContextSetStrokeColorWithColor(context, UIColor.redColor().CGColor)
        
        var T = CGAffineTransformIdentity
        // flip x-axis
        T = CGAffineTransformScale(T, 1, -1)
        T = CGAffineTransformTranslate(T, 0, -CGFloat(height))
        
        for face in faces {
            let faceLoc = CGRectApplyAffineTransform(face.bounds, T)
//            CGContextAddEllipseInRect(context, faceLoc)
            
            CGContextDrawImage(context, faceLoc, goatFace.CGImage)
        }
        
        CGContextStrokePath(context)
        
        let drawnFaces = UIGraphicsGetImageFromCurrentImageContext()
        UIGraphicsEndImageContext()
        
        CVPixelBufferUnlockBaseAddress(imageBuffer, 0)
        
        
        // Send to main queue to update UI
        dispatch_async(dispatch_get_main_queue()) {
            self.processedLayer.contents = self.hackFixOrientation(drawnFaces)
        }
        
        UIGraphicsEndImageContext()
        
        
        
        CVPixelBufferUnlockBaseAddress(imageBuffer, 0)
    }
    
    
    func colorFilter(imageBuffer : CVImageBufferRef) {
        CVPixelBufferLockBaseAddress(imageBuffer, 0)
        
        var pixels = UnsafeMutablePointer<UInt8>(CVPixelBufferGetBaseAddress(imageBuffer)) //converting pointer to 1 byte
//      pixels are stored as (blue, green, red, alpha), one byte per channel
        
        let width = CVPixelBufferGetWidth(imageBuffer)
        let height = CVPixelBufferGetHeight(imageBuffer)
        let bytesPerRow = CVPixelBufferGetBytesPerRow(imageBuffer)
        
        let blueValue = UInt8((1.0 + sin(Double(frameNumber) / 10.0)) * 0.5 * 255)
        
        // 0 to height - 1
        for _ in 0 ..< height {
            var idx = 0
            for _ in 0 ..< width {
                pixels[idx] = blueValue
                //pixels[idx + 1] = 0  // Green
                //pixels[idx + 2] = 0  // Red
                //pixels[idx + 3] = 0  // Alpha
                idx += 4
            }
            //pixels is pointer to beginning of row
            pixels += bytesPerRow
        }
        
        // Create an image with this buffer; CG: vector graphics, CI: pixels, sharpen, blur etc
        let context = CIContext()
        let ciImage = CIImage(CVImageBuffer: imageBuffer).imageByApplyingOrientation(6) //image coming out of buffer
        let cgImage = context.createCGImage(ciImage, fromRect: ciImage.extent)
        
        CVPixelBufferUnlockBaseAddress(imageBuffer, 0)
        
        dispatch_async(dispatch_get_main_queue()) {
            self.processedLayer.contents = cgImage
        }
                
    }
    
//    Method that recieves the fram butters; only interested in sampleBuffer than contains pixel camera data
//    If you want to get rid of jitter drop frames, you can access only dropped frames with something
    func captureOutput(captureOutput: AVCaptureOutput!, didOutputSampleBuffer sampleBuffer: CMSampleBuffer!, fromConnection connection: AVCaptureConnection!) {
        
//      Assignment can return nil pointer; use gaurd - if return nil, execute else
        if (isProcessing) {
            guard let frameBuffer = CMSampleBufferGetImageBuffer(sampleBuffer) else { return }
            
            detectFaces(frameBuffer)
            frameNumber += 1
        }
        else {
            guard let frameBuffer = CMSampleBufferGetImageBuffer(sampleBuffer) else { return }
            colorFilter(frameBuffer)
            frameNumber += 1
        }
    }
    
    func changeCamera() {
        let devices = AVCaptureDevice.devicesWithMediaType(AVMediaTypeVideo) as! [AVCaptureDevice]
        
        if (session.inputs.count != 0) {
            let currentCameraInput: AVCaptureInput = session.inputs[0] as! AVCaptureInput
            session.removeInput(currentCameraInput)
            let currentCameraOutput: AVCaptureOutput = session.outputs[0] as! AVCaptureOutput
            session.removeOutput(currentCameraOutput)
        }
        
        var device : AVCaptureDevice?
        if (!back) {
            device = devices.filter({ $0.position == .Front }).first!
        }
        else {
//            device = AVCaptureDevice.defaultDeviceWithMediaType(AVMediaTypeVideo)
            device = devices.first!
        }
        
        let input = try! AVCaptureDeviceInput(device: device)
        assert(session.canAddInput(input))
        session.addInput(input)
        
        // Create preview layer
        previewLayer = AVCaptureVideoPreviewLayer(session: session)
        previewLayer.frame = self.view.bounds
        self.view.layer.addSublayer(previewLayer)
        
        // Create processed video layer
        processedLayer = CALayer()
        processedLayer.frame = self.view.bounds
        processedLayer.hidden = true
        self.view.layer.addSublayer(processedLayer)
        
        // Create data output
        let frameProcessingQueue = dispatch_queue_create("goatface.frameprocessing", DISPATCH_QUEUE_SERIAL);
        let output = AVCaptureVideoDataOutput()
        output.videoSettings = [ kCVPixelBufferPixelFormatTypeKey: Int(kCVPixelFormatType_32BGRA) ]
        output.setSampleBufferDelegate(self, queue: frameProcessingQueue)
        assert(session.canAddOutput(output))
        session.addOutput(output)
        
        // Button
//        button.layer.cornerRadius = button.frame.width / 2.0
        self.view.bringSubviewToFront(button)
        
//        frontButton.layer.cornerRadius = frontButton.frame.width / 2.0
        self.view.bringSubviewToFront(frontButton)
        
        self.view.bringSubviewToFront(backButton)
        
        // Start actually capturing
        session.startRunning()
    }


}

