import sys

def degree(network,	node):
	edgeCounter = 0
	for (pair1, pair2) in network:
		if ((pair1 == node) or (pair2 == node)): edgeCounter += 1
	return edgeCounter

def net_stats(network):
	numNodes = 0
	numEdges = 0
	numHomodimers = 0
	avgDegree = 0

	edges = {}
	n = set()
	for e in network:
		n.add(e[0])
		n.add(e[1])
		# Duplicate edges
		if e in edges or (e[1],e[0]) in edges:
			edges[e] += 1
		else:
			edges[e] = 1;
		if e[0] == e[0]: numHomodimers += 1

	for nd in n:
		avgDegree += degree(network, nd)
	
	numNodes = len(n)
	numEdges = sum(edges.values())
	avgDegree = avgDegree / float(numNodes)

	stats = [(numNodes, numEdges, numHomodimers, avgDegree)]
	return stats