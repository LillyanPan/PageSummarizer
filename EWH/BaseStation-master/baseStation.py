from threading import Thread, Lock, Condition
import shelve
import os
import subprocess

#TODO: make this class thread safe
class Database(object):
    def __init__(self,filename):
        self.lock = Lock()
        self.database = shelve.open(filename, writeback=True) #loads file in dict form

    #saves database dict in filename
    def saveDatabase(self):
    	with self.lock:
            self.database.sync()
    
    #make data structure itself thread-safe
    #writes to database only if not locked
    def write(self, key, value):
        with self.lock:
            self.database[key] = value

    def read(self, key):
        if self.inDatabase(key):
            with self.lock:
                return self.database[key]

    def inDatabase(self, key):
        with self.lock:
            return self.database.has_key(key)

    def clear(self):
        self.database.clear()

    def isEmpty(self):
        return not self.database

    def isNew(self, key):
        return not self.database.has_key(key)

#TODO: make multi-thread safe + write: formatText, isNegStatusChange, saveInfo
class VitalDataHandler(Thread):
    def __init__(self, patientDb, nurseDb, dataInput, handlerId):
        Thread.__init__(self)
        self.patientDb = patientDb
        self.nurseDb = nurseDb
        self.dataInput = dataInput
        self.handlerId = handlerId

    #converts status to int
    def intStatus(self,status):
        print status
        return {'stable':1,'intermediate':0,'critical':-1}[status]

    #need to complete
    #Formats and returns the the text that will be sent out
    #inputs: String patientId, String location, String status
    def formatText(self, patientId, location, status):
        return "Patient " + patientId + " at " + location + " is " + status + "."

    #need to complete
    #checks database.db if there is a negative status change in the patient
    #returns false if the patient does not exist in the database
    #possible statuses: "stable","intermediate","critical"
    def isNegStatusChange(self, patientId, location, status):
        #handle case of new patient or empty database
        print status
        oldStatus = self.patientDb.read(patientId + ',' + location)
        print "old" + oldStatus
        return self.intStatus(oldStatus) > self.intStatus(status)

    #need to complete
    #sends text from pi to nurses
    def sendText(self,msg):
        os.system('echo ' + `msg`)
        #bash = "gammu sendsms text "
		
    #need to complete
    #saves all info in a database.db
    #KEY: patientId+','+location; VAL: status
    def saveInfo(self,patientId,location,status):
        self.patientDb.write(patientId + ',' + location, status)

    #thread's main function
    def run(self):
        data = self.dataInput.split(',')
        patientId, location, status = data[0], data[1], data[2]
        print patientId, location, status
        if not self.patientDb.isNew(patientId+','+location) and self.isNegStatusChange(patientId, location, status): 
            #self.sendText(self.formatText(patientId, locaiton, status))		#uncomment!
            print "sending text"
        self.saveInfo(patientId, location, status)

#TODO: make thread safe + addPhoneNum
class NurseHandler(object):
    def __init__(self,nurseDb):
        self.nurseDb = nurseDb

    #need to complete
    #adds KEY:name, VAL:num to nurseDb and saves to nurseDb
    def addPhoneNum(self, name, number):
        #if not self.hasNurse(name, number):
        if name not in self.nurseDb.database:
            self.nurseDb.database[name] = number
            print "Nurse added to database."

    #def hasNurse(self, name, number):
     #   return self.nurseDb.database.has_key(name) and self.nurseDb[name] == number #no key error because of operator precedence

    #need to complete
    #wait for external input (commandline text), then add to nurseDb
    def run(self):
        while True:
            nurseName = raw_input("Enter nurse name (s to skip): ")
            if nurseName == 's':
                break
            nurseNumber = raw_input("Enter nurse " + `nurseName` + "'s phone number: ")
            print "You've entered " + `nurseName` + " with number " + `nurseNumber` + "... storing to database..."
            self.addPhoneNum(nurseName, nurseNumber)
            addAnother = raw_input("Add another nurse? (y/n)")
            if addAnother != 'y':
                break

#========================================================================================================
#    Main
#========================================================================================================

if __name__ == "__main__":
    patientDb = Database("patients.db")
    nurseDb = Database("nurses.db")
    patientDataInputStream = ["p1,bed1,stable","p2,bed5,critical","p3,bed6,intermediate","p1,bed1,critical","p2,bed5,stable","p3,bed6,intermediate","p1,bed1,critical","p2,bed5,intermediate","p3,bed6,critical"]
    NurseHandler(nurseDb).run()
    for ii in range(9):
        vdh = VitalDataHandler(patientDb, nurseDb, patientDataInputStream[ii], ii)
        vdh.start()
        vdh.join()
    print "patient db: " + `patientDb.database`
    print "nurse db:" + `nurseDb.database`