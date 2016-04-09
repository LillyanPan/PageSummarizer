# Gene A1 is RBH of Gene B1 if
# 		A1, B1 from dif genomes
# 		Of all genes in Genome A, A1 is the closest homolog to B1
# 		Of all genes in Genome B, B1 is the closest homolog to A1
# 			Determined by BLAST e-val test - col[10]

# Blast commands: just reverse the order of the files in the -query?

from sys import argv
import os

script, fastaA, fastaB = argv


os.system("makeblastdb -dbtype nucl -in " + fastaA)
os.system("makeblastdb -dbtype nucl -in " + fastaB)
os.system("blastn -query " + fastaA + " -db "+ fastaB + " -outfmt 6 -out resultsA.txt")
os.system("blastn -query " + fastaB + " -db "+ fastaA + " -outfmt 6 -out resultsB.txt")

######### Determine if two genes are from dif genomes #########
blastA = [l.strip() for i, l in
enumerate(open('/home/local/CORNELL/ldp54/midterm/resultsA.txt'))]

blastB = [l.strip() for i, l in
enumerate(open('/home/local/CORNELL/ldp54/midterm/resultsB.txt'))]

######### Determine if all genes in Genome A, A1 is the closest homolog to B1 #########

# FORMAT: 'FD64A': [('FOXG1', '5e-35')]
# Ex. 'FZ2': [('FZD8', '3e-50'), ('FZD5', '1e-44')]
dictBlastA = {} 
dictBlastB = {}

# FORMAT: 'FD64A': ['5e-35', 'FOXG1', 'FOXG2',...]
bestA = {}
bestB = {}

# DICTIONARY FORMAT: gene name: [(hom1, evalue), (hom2,evalue)...]
for line in blastA:
    line = line.strip().split('\t')
    if line[0] in dictBlastA:
    	dictBlastA[line[0]].append((line[1],line[10]))
    else:
    	dictBlastA[line[0]] = [(line[1],line[10])]

for line in blastB:
    line = line.strip().split('\t')
    if line[0] in dictBlastB:
    	dictBlastB[line[0]].append((line[1],line[10]))
    else:
    	dictBlastB[line[0]] = [(line[1],line[10])]

for key, value in dictBlastA.iteritems():
    evaluesA = []
    for el in value:
        evaluesA.append(float(el[1]))
    minEvalA = str(min(evaluesA))
    for pair in dictBlastA[key]:
		if pair[1] == minEvalA:
			if key in bestA:
				bestA[key].append(pair[0]) #append (h1)
			else:
				bestA[key] = [minEvalA, pair[0]]

for key, value in dictBlastB.iteritems():
	evaluesB = []
	for el in value:
		evaluesB.append(float(el[1]))
    minEvalB = str(min(evaluesB))
    for pair in dictBlastB[key]:
		if pair[1] == minEvalB:
			if key in bestB:
				bestB[key].append(pair[0]) #append (h1)
			else:
				bestB[key] = [minEvalB, pair[0]]

outputA = open('best_homologsA.txt', 'w')
outputB = open('best_homologsB.txt', 'w')

for key,val in sorted(bestA.items()):
	valString = "\t".join(val)
	outputA.write(key + "\t" + valString + "\n");


for key,val in sorted(bestB.items()):
	valString = "\t".join(val)
	outputB.write(key + "\t" + valString+ "\n");

outputA.close()
outputB.close()


######### Determine if all genes in Genome B, B1 is the closest homolog to A1 #########

######### Output best_homologs1.txt: #########
# 	Column 1: genes from Genome A 
# 	Column 2: e value
# 	Column 3->n: all closest homolog(s) from Genome B

######### Output best_homologs2.txt: #########
# 	Column 1: genes from Genome B
# 	Column 2: closest homolog(s) from Genome A
# 	Column 3->n: all closest homolog(s) from Genome A

data1 = [l.strip() for i, l in enumerate(open('/home/local/CORNELL/ldp54/midterm/best_homologsA.txt'))]
data2 = [l.strip() for i, l in enumerate(open('/home/local/CORNELL/ldp54/midterm/best_homologsB.txt'))]

# FORMAT: data1 = [geneA, e1, h1, h2, h3]

# DICTIONARY FORMAT {gene: array of homolog matches}
dictA = {}
dictB = {}
for line in data1:
    line = line.strip().split('\t')
    numberMatch = len(line) - 2
    homologMatch = []
    for index in range(numberMatch):
    	homologMatch.append(line[index + 2])
    # Push key: gene A and closest homologs gene B
    dictA[line[0]] = homologMatch

for line in data2:
    line = line.strip().split('\t')
    numberMatch = len(line) - 2
    homologMatch = []
    for index in range(numberMatch):
    	homologMatch.append(line[index + 2])
    # Push key: gene A and array of closest homologs gene B
    dictB[line[0]] = homologMatch

matches = {}
for aOne, value in dictA.iteritems():
	matches[aOne] = [];
	# Iterate through homologs of gene a1
	for bOne in value:
		# gene A1 in the list of most closely related homologs in b 
		if bOne in dictB and aOne in dictB[bOne]:
			matches[aOne].append(bOne);


# Output tab-delimited file that lists genes from GEN A in 1st col 
# 	and as many reciprocol best homologs from Gen B using BLAST in cols 2->n
# Only inclusde genes from Gen A that have homologs in Gen B

output_file = open('reciprocal_best.txt', 'w')

for geneA in sorted(matches.keys()):
	if not matches[geneA]: continue
	else:
		homologs = "\t".join(matches[geneA])
		output_file.write(geneA + "\t" + homologs + "\n")

output_file.close()
