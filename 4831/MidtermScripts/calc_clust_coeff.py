import sys
network_file = sys.argv[1]

# For each edge defined by two 

# Feed in netowork into dictionary
# Key is first column, value is neighbor nodes

lines = [l.strip() for i, l in enumerate(open(network_file))]

network = {}
clusterSum = 0

for line in lines:
    line = line.strip().split('\t')
    if line[0] in network and line[1] not in network[line[0]] and line[0] != line[1]:
		network[line[0]].append(line[1])
		if line[1] in network and line[0] not in network[line[1]]:
		    network[line[1]].append(line[0])
		elif line[0] != line[1]:
		    network[line[1]] = [line[0]]
    elif line[0] not in network:
		network[line[0]] = [line[1]]
		if line[1] in network and line[0] not in network[line[1]]:
		    network[line[1]].append(line[0])
		else:
		    network[line[1]] = [line[0]]

nodes = network.keys()

for n in nodes:
	numNeigh = 0
	neighbors = []
	neighEdge = []
	edgeWithin = 0
	if n in network[n]: continue
# 	# Find all neighbors
	for key, val in network.iteritems():
		# If not that node
		if (key == val): continue
		elif key != n:
			# If a neighbor of that node
			if n in val:
				numNeigh += 1
				neighbors.append(key)
	for loop in neighbors:
	    for inner in neighbors:
			if inner != loop:
				# if inner has an edge to n
				if inner in network[loop] and ((inner, loop) not in neighEdge and (loop, inner) not in neighEdge):
					edgeWithin += 1
					neighEdge.append((inner, loop))

	div = numNeigh * (numNeigh - 1) / 2.0

	# total number of edges
	clusterSum += edgeWithin / div

clusterSum = clusterSum / float(len(nodes))
print format(clusterSum, '.6f')




