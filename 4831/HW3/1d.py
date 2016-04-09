from sys import argv
from ldp54_helper import net_stats, degree

file1 =  open("/home/local/CORNELL/public/hw3_files/SpOrth.txt")
file2 =  open("/home/local/CORNELL/public/hw3_files/StressNet.txt")

# nodeDic holds all orthologs or 0
nodeDic = {}
for line in file1:
    plist = line.split()
    #plist[-1] = plist[-1].rstrip('\n')
    if plist[1] == 'None': nodeDic[plist[0]] = 0
    else:
        nodeDic[plist[0]] = plist[1:]

#print nodeDic

# Makes network of tuples
network = []
for line in file2:
    (key,val) = line.split()
    network.append((key,val.rstrip('\n')))
print network

flist = set()
for e in network:
	# if has orthologs
	if (e[0] in nodeDic and e[1] in nodeDic) and (nodeDic[e[0]] > 0 and nodeDic[e[1]] > 0):
		# homolog
		if e[0] == e[1]:
			for p in nodeDic[e]:
				flist.add((p[0],p[0]))
		else:
			# loop through orthologs
			for p1 in nodeDic[e[0]]:
				for p2 in nodeDic[e[1]]:
					if not ((p1,p2) in flist or (p2,p1) in flist):
						flist.add(p1,p2)

fout = open("1dout.txt", "w")
for pro in flist:
	fh.write(pro[0] + "\t" + pro[1])
fout.close()

