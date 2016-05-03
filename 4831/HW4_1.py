from scipy.cluster.vq import kmeans2, whiten
import numpy as np

# Global Variables
#aVals = []
#bVals = []

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


# Gets A value for genes in each cluster
# Returns array  of a Values for each cluster
def getAValue(clusterArrays, kvalue):
        aVals = []
        for k in range(0, kvalue):
                aClus = []
                for point in clusterArrays[k]:
                        geneDistance = 0
                        for secPoint in clusterArrays[k]:
                                if not np.array_equal(point, secPoint):
                                        geneDistance += np.linalg.norm( point  - secPoint )
                        geneDistance = geneDistance / float(len(clusterArrays[k]))
                        aClus.append(geneDistance)
                aVals.append(aClus)
        return aVals

def getBValue(clusterArrays, kvalue):
        bVals = []
        for k in range(0, kvalue):
                bClus = []
                for point in clusterArrays[k]:
                        bValstemp = []
                        # if not the same cluster
                        for cluster in clusterArrays:
                                bDist = 1000
                                if not np.array_equal(cluster, clusterArrays[k]):
                                        bDist = 0
                                        for outPoint in cluster:
                                                bDist += np.linalg.norm( point  - outPoint )
                                bDist = bDist / float(len(cluster))
                                #average b distance for one point in a cluster; appends to bVals(holds for point point)
                                bValstemp.append(bDist)
                        #find minimum value among all cluster for point point
                        minB = min(bValstemp)
                        bClus.append(minB)
                #append the final b_i for all point in the cluster to bVals
                bVals.append(bClus)
        return bVals

# Calculate s(i)
def calcSil(a, b, k):
        totalSil = []
        avgSil = 0
        for i in range(0, k):
                for index in range(0, len(a[i])):
                        si = (b[i][index] - a[i][index]) / max(b[i][index], a[i][index])
                        totalSil.append(si)
        for val in totalSil:
                avgSil += val
                avgSil = avgSil / len(totalSil)
        return avgSil

# output.close()

# Calculate s(i)

# Use numpy euclidean distance for dissimilarity between point

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
#print whitened

#cl = getCluster(3)
#a = getAValue(cl,3)
#b = getBValue(cl,3)
for kval in range(3, 10):
        cl = getCluster(kval)
        a = getAValue(cl,kval)
        b = getBValue(cl,kval)
        print(calcSil(a,b,kval))
        print("\n")

