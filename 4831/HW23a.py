file =  open("/home/local/CORNELL/public/hw2_files/hiseq.fastq")

txt = file.readlines()

file.close()

parse = []
complement = []
values = []

for line in range(1, len(txt), 4):
	if "N" not in txt[line]:
		parse.append(txt[line].rstrip('\n'))

for l in parse:
	comDNA = []
	for i in l:
		if i == "A": comDNA.append('T')
		if i == "T": comDNA.append('A')
		if i == "G": comDNA.append('C')
		if i == "C": comDNA.append('G')
	comDNA.reverse()
	comDNA = "".join(comDNA)
	complement.append(comDNA)

for l in complement:
	each = []
	gc = float(l.count("G") + l.count("C"))
	ratio = round(gc / len(l), 3)
	each.append(ratio)
	each.append(l)
	values.append(each)

values = sorted(values, key=lambda l:l[0], reverse=True)

for item in values:
	print str(item[0]) + "\t" + str(item[1])
