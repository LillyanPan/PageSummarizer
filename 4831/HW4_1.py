i from scipy.cluster.vq import kmeans2, whiten
import numpy as np

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
                        geneDistance = geneDistance / len(clusterArrays[k])
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
                                bDist = 0
                                if not np.array_equal(cluster, clusterArrays[k]):
                                        for outPoint in cluster:
                                                bDist += np.linalg.norm( point  - outPoint )
                                bDist = bDist / len(cluster)
                                bValstemp.append(bDist)
                        minB = min(bValstemp)
                        bClus.append(minB)
                bVals.append(bClus)
        return bVals
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
print whitened

cl = getCluster(3)
getAValue(cl,3)



# output.close()



# output.close()

# Calculate s(i)

# Use numpy euclidean distance for dissimilarity between points

