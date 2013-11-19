import MySQLdb, psycopg2
import sys

psycopg2.paramstyle='qmark'
pconn = psycopg2.connect(host='172.16.147.133', user='postgres', password='l', database='address')
pc = pconn.cursor()

def insert(row ):
    lens = len(row)
    str = "insert into wd values (%s);" %  (', %s' * lens)[1:]  
    try:
        pc.execute(str, row)
        pconn.commit()
    except:
        pconn.rollback()

mconn = MySQLdb.connect(charset = 'gbk', host="192.168.1.16", user='caiwu', passwd='cai_Report', db='ydserver')
mc = mconn.cursor()
mc.execute("select j.bm,g.sheng,g.shi,g.szd,j.psfw,j.bpsfw,j.mc from gsjj j, gs g where j.bm=g.bm and g.szd<>'' and g.lb in (2,22,21)" )

while 1:
    row = mc.fetchone()
    if row is None:
        break
    insert(row)



pconn.commit()
