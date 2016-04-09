import os, os.path
from sys import argv

#print argv
filename,mypath = argv

path, dirs, files = os.walk(mypath).next()

fileDic = {}
for f in files:
    name, end = os.path.splitext('file.txt')
    #print end
    if end in fileDic:
        fileDic[end] += 1
    else:
        fileDic[end] = 1
#print fileDic
sorted(fileDic.values(), reverse = True)
for key, value in fileDic.iteritems():
        print key + "\t" + str(value)