#!/usr/bin/python
#coding=utf-8

from test import repnum, procsa, procsb
import MySQLdb, psycopg2
import sys
import re
from math import ceil
reload(sys)
sys.setdefaultencoding('utf-8')
from num import getResultForDigit

pconn = psycopg2.connect(host='172.16.147.133', user='postgres', password='l', database='address')
#pconn.autocommit = True
pconn.set_client_encoding('utf-8')
pcw = pconn.cursor()
pc = pconn.cursor()

def procs(strtmp, a, b=None):
    for x in a:
        if strtmp.find(x) != -1 :
            return re.sub(x, '', strtmp)
    if b is not None:
        for x in b:
            if strtmp.find(x) == 0:
                return re.sub(r'^%s' % x, '', strtmp)
    return strtmp



sql_ldb2 = "insert into ldb2 (ctry,addr,comp) values (%s, %s, %s) returning id"
sql_ldb2_num = "insert into ldb2_num (id, nums, comp) values (%s, %s, %s)"

def proc(row):
    tmp2 = repnum(row[1].decode('utf-8')).replace(' ','').replace(' ', '' )
    tmp = procs(tmp2, prov1, prov2)
    tmp = procs(tmp, city1, city2)
    tmp2 = procs(tmp, ctry )
    tmpa = procsa(tmp).replace('-','')
    tmpb = procsb(tmp)
    try:
        pcw.execute(sql_ldb2, (row[0], tmpa, row[2]))
        #rowid = pcw.fetchone()[0]
    except Exception, e:
        print e
        pconn.rollback()
        print '1',row[0], row[1], row[2]
    else:
        pconn.commit()
    
    
    if len(tmpb)== 2:
        try:
            pcw.execute(sql_ldb2, (row[0], tmpb[0], 0))
            rowid = pcw.fetchone()[0]
            pcw.execute(sql_ldb2_num, (rowid, tmpb[1], row[2]))
        except Exception, e:
            print e
            print '2', tmpb
            pconn.rollback
        else:
            pconn.commit()

    """
    if len(tmp1) >1:
        tmp1,tmp2 = tmp1
        if tmp != tmp1:
            #tmp1,tmp insert db
            print '1', tmp
            print '2', tmp1, tmp2
            return
        else:
            #tmp1 insert db
            print '2',tmp1
    else:
        #tmp insert db
        print '1',tmp
    """



pc.execute("""select prov_coding,
        prov_name
    from prov""")

prov1 = []
prov2 = []
for row in pc.fetchall():
    prov1.append(row[1].decode('utf-8'))
    try:
        prov2.append(row[1].decode('utf-8').replace(u'省','').replace(u'市','').replace(u'自治区',''))
    except:
        print 'error:1:', row[1]

pc.execute("""select city_name
    from city""")
city1 = []
city2 = []
for row in pc.fetchall():
    city1.append(row[0].decode('utf-8'))
    if row[0] not in ['朝阳市']:
        city2.append(row[0].decode('utf-8').replace(u'','市').replace(u'县',''))

ctry = []

pc.execute("""select ctry_name
    from ctry""")
for row in pc.fetchall():
    ctry.append(row[0].decode('utf-8'))

pc.execute("""select count(1) from ldb1""")
num, = pc.fetchone()
pagesize = 10000.0
for i in range(int(ceil(num/pagesize))):
    pc.execute("""select ctry, 
        addr, 
        comp 
    from ldb1 limit %s offset %s""", (pagesize, pagesize * i))
    for row in pc.fetchall():
        proc(row)
