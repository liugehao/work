import MySQLdb, psycopg2
import sys
table = sys.argv[1]  #'address'

psycopg2.paramstyle='qmark'
pconn = psycopg2.connect(host='172.16.147.133', user='postgres', password='l', database='address')
pc = pconn.cursor()

def insert(row, table):
    lens = len(row)
    str = "insert into %s values (%s);" % (table, (', %s' * lens)[1:] ) 
    try:
        pc.execute(str, row)
    except:
        pass

mconn = MySQLdb.connect(charset = 'gbk', host="192.168.1.16", user='caiwu', passwd='cai_Report', db='exp_address')
mc = mconn.cursor()
mc.execute("select * from %s" % table)
while 1:
    row = mc.fetchone()
    if row is None:
        break
    insert(row, table)



pconn.commit()
