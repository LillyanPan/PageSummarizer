from sys import argv
import urllib2
import collections

string = argv[1]

common =  open("/home/local/CORNELL/public/hw3_files/common_words.txt")

wordDic = {}
next(common)
for line in common:
    wlist = line.split()
    if wlist[1] in wordDic:
        wordDic[wlist[1]] += 1
    else:
        wordDic[wlist[1]] = 1

common.close()

response = urllib2.urlopen("http://en.wikipedia.org/wiki/" + string)
page_source = response.read()
page_source = page_source.split()

commonWord = {}
for w in page_source:
	if not (w in wordDic or (("<" in w) or (">" in w) or ("=" in w) or ("\"" in w) )):
		if w in commonWord:
			commonWord[w] += 1
		else:
			commonWord[w] = 1

a = sorted(commonWord, key=commonWord.get, reverse=True)[:10]

print 'The ten most common words for ' + "http://en.wikipedia.org/wiki/" + string + '\n'
for key in a:
	print key + "\n"



