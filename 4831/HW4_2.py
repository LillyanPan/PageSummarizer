import networkx as nx
import matplotlib as mpl
mpl.use('Agg')
import matplotlib.pyplot as plt
from scipy.cluster.vq import whiten
import numpy as np

# Returns the clusters and appropriate assignments
def getCluster(kvalue):
        clusterArrays = []
        dtm, clusterAssign = kmeans2(whitened, kvalue)
    # Create empty array for each cluster
        for x in range(0,kvalue):
                clusterArrays.append([])
        for c in range(0,len(clusterAssign)):
                clusterArrays[clusterAssign[c]].append(whitened[c])
        return clusterArrays

def getDistances(data):
        distances = []

        for node in data:
        	for secondNode in data:
        		if not np.array_equal(node, secondNode):
        			dist = np.linalg.norm( node  - secondNode )
        			distances.push(dist)
        return distances

def getEdges(data):
        edges = []

        for node in data:
        	for secondNode in data:
        		if not np.array_equal(node, secondNode):
        			edges.push(tuple(node, secondNode))
        return edges



# Read in data
data = [l.strip() for i, l in
enumerate(open('/home/local/CORNELL/public/hw4_files/gene_expression.txt'))]

newData = []
for read in data:
        tab = read.find('\t')
        newStr = read[tab+1:]
        newArr = newStr.split('\t')
        newArr = [float(i) for i in newArr]
        newData.append(newArr)

# Whiten data and kmeans
whitened = whiten(newData)

# data = getDistances(whitened)
edges = getEdges(whitened)
graph = nx.Graph(edges)
print graph.edges[:10]
# plt.hist(data, bins = 40)
# plt.show()