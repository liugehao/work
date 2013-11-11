#!/usr/bin/python
#coding=utf-8

import MySQLdb, psycopg2
import sys
import re
reload(sys)
sys.setdefaultencoding('utf-8')

pconn = psycopg2.connect(host='172.16.147.133', user='postgres', password='l', database='address')
pconn.set_client_encoding('utf-8')
pcw = pconn.cursor()
pc = pconn.cursor()

def procs(strtmp, a, b=None):
    for x in a:
        if strtmp.find(x) == 0:
            return re.sub(r'^%s' % x, '', strtmp)
    if b is not None:
        for x in b:
            if strtmp.find(x) == 0:
                return re.sub(r'^%s' % x, '', strtmp)
    return strtmp

def proc(row):
    tmp = procs(row[1].decode('utf-8'), prov1, prov2)
    tmp = procs(tmp, city1)
    tmp = procs(tmp, city2)
    tmp = procs(tmp, ctry )
    tmp = procsa(tmp)
    
    print row[1],'|', tmp
    return tmp

res = {}
res['num']= re.compile('\d*?-*?\d*?-\d*.*$')
res['num1'] = re.compile(u'\d*?-*?\d*?[号栋幢室].*$')
res['rep'] = re.compile(u'(大[楼厦院]|公司|学校|分校|宿舍|[a-zA-Z0-9]{1,3}座).*$')

def procsa(tmp):
    tmp = res['num'].sub('',tmp)
    tmp = res['num1'].sub('',tmp)
    tmp = res['rep'].sub(lambda x:x.group(1), tmp)
    return tmp

#pc.execute("""CREATE TABLE ldb2
#(
#  id serial NOT NULL,
#  ctry integer,
#  addr character varying(600),
#  comp integer
#)""")
#pconn.commit()

pc.execute("""select prov_coding,
        prov_name
    from prov""")

prov1 = []
prov2 = []
for row in pc.fetchall():
    prov1.append(row[1])
    try:
        prov2.append(row[1].replace(u'省','').replace(u'市','').replace(u'自治区',''))
    except:
        print 'error:1:', row[1]

pc.execute("""select city_name
    from city""")
city1 = []
city2 = []
for row in pc.fetchall():
    city1.append(row[0])
    if row[0] not in ['朝阳市']:
        city2.append(row[0].replace(u'','市').replace(u'县',''))

ctry = []

pc.execute("""select ctry_name
    from ctry""")
for row in pc.fetchall():
    ctry.append(row[0])

pc.execute("""select count(1) from ldb1""")
num, = pc.fetchone()
pagesize = 10000.0
for i in range(2): #range(int(ceil(num/pagesize))):
    pc.execute("""select ctry, 
        addr, 
        comp 
    from ldb1 limit %s offset %s""", (pagesize, pagesize * i))
    for row in pc.fetchall():
        proc(row)
