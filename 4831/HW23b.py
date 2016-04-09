file1 =  open("/home/local/CORNELL/public/hw2_files/file1.txt")
file2 =  open("/home/local/CORNELL/public/hw2_files/file2.txt")

txt1Dic = {}
txt2Dic = {}

for line in file1:
	(key,val) = line.split("\t")
	txt1Dic[key] = val.rstrip('\n')

for line in file2:
	(key,val) = line.split("\t")
	txt2Dic[val.rstrip('\n')] = key

file1.close()
file2.close()

combined = []

print txt2Dic

for key in txt1Dic:
	el = []
	el.append(key)
	el.append(txt1Dic[key])
	el.append(txt2Dic[str(key)])
	combined.append(el)

combined = sorted(combined, key=lambda l:l[0])

fh = open("amino_acids.txt", "w")
for el in combined:
	fh.write(el[0] + "\t" + el[1] + "\t" + el[2] + "\n")
fh.close()

