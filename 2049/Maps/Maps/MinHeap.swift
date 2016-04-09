//
//  MinHeap.swift
//  Goates
//
//  Created by Grendel Yang on 2/26/16.
//  Copyright © 2016 Grendel Yang. All rights reserved.
//

import Foundation

// a basic min-heap data strcture public
// - See more at: http://waynewbishop.com/swift/heaps#sthash.oEkc5EXS.dpuf
class MinIntHeap<T> {
    private var heap = [(p:Int, v:T)]()
    
    var count: Int {
        return self.heap.count
    }
    
    //sort shortest paths into a min-heap (heapify)
    func enQueue(pri : Int, val:T) {
        heap.append((p:pri, v:val))
        var childIndex: Int! = Int(heap.count) - 1
        var parentIndex: Int! = 0
        
        //calculate parent index
        if (childIndex != 0) {
            parentIndex = (childIndex - 1) / 2
        }
        
        //use the bottom-up approach
        while (childIndex != 0) {
            let childToUse = heap[Int(childIndex)]
            let parentToUse = heap[parentIndex]
            //swap child and parent positions
            if (childToUse.p < parentToUse.p) {
                heap.insert(childToUse, atIndex: parentIndex)
                heap.removeAtIndex(Int(childIndex) + 1)
                heap.insert(parentToUse, atIndex: Int(childIndex))
                heap.removeAtIndex(parentIndex + 1)
            }
            
            //reset indices
            childIndex = parentIndex
            if (childIndex != 0) {
                parentIndex = (childIndex - 1) / 2
            }
        } //end while
        
    } //end function - See more at: http://waynewbishop.com/swift/heaps#sthash.oEkc5EXS.dpuf
    
    //obtain the minimum path
    func peek() -> (p:Int, v:T)? {
        if (heap.count > 0) {
            return heap[0]
        } else {
            return nil
        }
    } // - See more at: http://waynewbishop.com/swift/heaps#sthash.oEkc5EXS.dpuf
    
    func deQueue() -> (p:Int, v:T)? {
        if (heap.count > 0) {
            // should be return heap.removeFirst()
            return heap.removeLast()
        } else {
            return nil
        }
    }
}

