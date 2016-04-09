from sys import argv
import re

string = argv[1]
#print argv

pattern1 = re.compile("[O,P,Q][0-9][A-Z|0-9][A-Z|0-9][0-9]")
pattern2 = re.compile("[A-N|R-Z][0-9][A-Z][[A-Z|0-9][A-Z|0-9][0-9]")
pattern3 = re.compile("[A-N|R-Z][0-9][A-Z][[A-Z|0-9][A-Z|0-9][0-9][A-Z][A-Z|0-9][A-Z|0-9][0-9]")

pos = string.find('-')

if pos != -1:
    if pattern1.match(string) or pattern2.match(string) or pattern3.match(string):
        print "Isoform"
	else:
        print "Unknown"
else:
if pattern1.match(string) or pattern2.match(string) or pattern3.match(string):
        print "Uniprot"
else:
        print "Unknown"