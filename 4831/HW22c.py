codon_mapping = {'CTT': 'L', 'ATG': 'M', 'AAG': 'K', 'AAA': 'K', 'ATC': 'I', 
'AAC': 'N', 'ATA': 'I', 'AGG': 'R', 'CCT': 'P', 'ACT': 'T', 'AGC': 'S', 'ACA': 'T',
 'AGA': 'R', 'CAT': 'H', 'AAT': 'N', 'ATT': 'I', 'CTG': 'L', 'CTA': 'L', 
 'CTC': 'L', 'CAC': 'H', 'ACG': 'T', 'CAA': 'Q', 'AGT': 'S', 'CAG': 'Q', 
 'CCG': 'P', 'CCC': 'P', 'TAT': 'Y', 'GGT': 'G', 'TGT': 'C', 'CGA': 'R', 
 'CCA': 'P', 'TCT': 'S', 'GAT': 'D', 'CGG': 'R', 'TTT': 'F', 'TGC': 'C', 
 'GGG': 'G', 'TAG': '*', 'GGA': 'G', 'TAA': '*', 'GGC': 'G', 'TAC': 'Y', 
 'GAG': 'E', 'TCG': 'S', 'TTA': 'L', 'GAC': 'D', 'TCC': 'S', 'GAA': 'E', 
 'TCA': 'S', 'GCA': 'A', 'GTA': 'V', 'GCC': 'A', 'GTC': 'V', 'GCG': 'A', 
 'GTG': 'V', 'TTC': 'F', 'GTT': 'V', 'GCT': 'A', 'ACC': 'T', 'TGA': '*', 
 'TTG': 'L', 'CGT': 'R', 'TGG': 'W', 'CGC': 'R'}
seq = 'ATGTATGGCTAGCTTACTACTGCGCACTGATGTGGCTATCGATCGCTGGTCGTTGCTGACCGAGCTAAA'
total = []

for i in range(0, len(seq)):
	translate = []
	codon = seq[i : i+3]
	if len(codon) != 3: break
	if codon_mapping[codon] == 'M':
		startSeq = seq[i:]
		for j in range(0, len(startSeq), 3):
			codon = seq[j : j+3]
			if len(codon) != 3: break
			if codon_mapping[codon] == '*':
				break
			else: translate.append(codon_mapping[codon])
	translate = "".join(translate)
	total.append(translate)

print max(total, key=len)