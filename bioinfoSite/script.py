import MySQLdb
mydb = MySQLdb.connect(host="localhost", user="ldp54", passwd="9HGUQB", db="LDP54_db")
cursor = mydb.cursor()

data = [l.strip().split('\t') for l in open("/cgi-bin/data/CervBinaryHQ.txt")]
for d in data:
	cursor.execute('INSERT INTO interP VALUES("%s", "%s");' %(d[2], d[3]))