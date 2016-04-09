def problem2a():
	monty_python = ["John Cleese", "Terry Gilliam", "Eric Idle", "Terry Jones",
	"Michael Palin", "Graham Chapman"]
	acc = 0

	for name in monty_python:
		acc += len(name)
	average = (0.0 + acc) / len(monty_python)
	return average

def problem2b():
	monty_python = ["John Cleese", "Terry Gilliam", "Eric Idle", "Terry Jones",
	"Michael Palin", "Graham Chapman"]
	for name in monty_python:
		print name + "'s anem contains " + str(len(name)) + " characters \n" 

def problem2c():
	monty_python.sort()
	for name in monty_python:
		space = name.index(" ")
		lastChar = name[space+1]
		if (ord(lastChar) < 77): # int(M) = 77
			print name + "\n"

def problem3a():
	random_codons = ['TTA', 'TGT', 'TCT', 'AAT', 'GTA', 'TGT',
	'GGG', 'TGT', 'AAG', 'GGA', 'CCT', 'CGC', 'CTC','AAT', 'TGT',
	'TAA']
	new = []
	for codon in random_codons:
		if (codon not in new):
			new.append(codon)
	new.sort()
	for c in new[::-1]:
		print c

def problem3b():
	random_codons = ['TTA', 'TGT', 'TCT', 'AAT', 'GTA', 'TGT',
	'GGG', 'TGT', 'AAG', 'GGA', 'CCT', 'CGC', 'CTC','AAT', 'TGT',
	'TAA']
	stop_codons = ['TAG','TAA','TGA']
	new = []
	for codon in random_codons:
		if codon in stop_codons:
			new.append("STOP")
		elif codon not in new:
			new.append(codon)
	for codon in new:
		print codon

def problem3c():
	stop_codons = ['TAG','TAA','TGA']
	seq1 = 'ATGTTATGCTAGCTTACTACTGCGCACTGTCGTGGCTAGCTGATCGATCGATCGCTGATCGTAGCTAAA'
	seq2 = seq1[1:]
	seq3 = seq1[2:]
	newSeq1 = ""
	newSeq2 = ""
	newSeq3 = ""
	for n in xrange(0, len(seq1), 3):
		codon = seq1[n:n+3]
		if (len(codon) == 3):
			if (codon not in stop_codons):
				newSeq1 += (".")
			else: 
				newSeq1 += ("!")
				break
	for n in xrange(0, len(seq2), 3):
		codon = seq2[n:n+3]
		if (len(codon) == 3):
			if (codon not in stop_codons):
				newSeq2 += (".")
			else: 
				newSeq2 += ("!")
				break
	for n in xrange(0, len(seq3), 3):
		codon = seq3[n:n+3]
		if (len(codon) == 3):
			if (codon not in stop_codons):
				newSeq3 += (".")
			else: 
				newSeq3 += ("!")
				break
	print(newSeq1 + "\n" + newSeq2 + "\n" + newSeq3)
	

	