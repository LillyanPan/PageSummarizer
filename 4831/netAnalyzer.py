from sys import argv
from ldp54_helper import net_stats, degree


script,filename = argv

network = []
fin = open(filename)
for line in fin:
        (key,val) = line.split("\t")
        network.append((key,val.rstrip('\n')))

stats = net_stats(network);
for a,b,c,d in stats:
        print "nodes: " + str(a)
        print "edges: " + str(b)
        print "homodimers: " + str(c)
        print "avgDegree: " + str(round(d,2))

fin.close()