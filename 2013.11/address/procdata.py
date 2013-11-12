#!/usr/bin/python
#coding=utf-8

import MySQLdb, psycopg2
import sys
import re
reload(sys)
sys.setdefaultencoding('utf-8')
from num import getResultForDigit

pconn = psycopg2.connect(host='172.16.147.133', user='postgres', password='l', database='address')
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
res = {}
res['rep0'] = re.compile(u'(对面|门口|旁边|楼上).*$')
res['rep1'] = re.compile(u'(.*?)\d*号(.*(博物馆|局|[一二三四五]期|分院|学院|大学|嘉园|家园|家属[区楼院]|银行|小区|公司|市场|中心|花园|苑|广场|酒店|大[楼厦院]|公司|学校|分校|宿舍|村屯|村庄|^村镇|^村街|^村路|庄村|^庄镇|^庄街|^庄路|庄屯|屯村|屯庄|^屯镇|村|庄|屯|幼儿园|小学|中学|号院|基地|政府|研究所|产业园|集团)).*$')

res['rep2'] = re.compile(u'(博物馆|局|[一二三四五]期|分院|学院|大学|嘉园|家园|家属[区楼院]|银行|小区|公司|市场|中心|花园|苑|广场|酒店|大[楼厦院]|公司|学校|分校|宿舍|村屯|村庄|^村镇|村街|村路|庄村|^庄镇|庄街|庄路|庄屯|屯村|屯庄|^屯镇|村|庄|屯|幼儿园|小学|中学|号院|基地|政府|研究所|产业园|集团).*$')

res['rep3'] = re.compile(u'(村|庄|屯).*$')
res['rep4'] = re.compile(u'(街|胡同|弄).*$')
res['rep5'] = re.compile(u'(路|大道).*$')

res['hao0'] = re.compile(u'(路|街|大道)([一二三四五六七八九][一二三四五六七八九十百千万零]*)')
res['hao1'] = re.compile(u'(.*?)(\d*)(-|号).*$')

def repnum(tmp1):
    return res['hao0'].sub(lambda x:x.group(1) + str(getResultForDigit(x.group(2))), tmp1)

def procsa(tmp1):
    tmp = res['rep1'].sub(lambda x:x.group(1)+x.group(2), tmp1)
    if tmp != tmp1: return tmp
    tmp = res['rep2'].sub(lambda x:x.group(1), tmp1)
    if tmp != tmp1: return tmp
    tmp = res['rep3'].sub(lambda x:x.group(1), tmp1)
    if tmp != tmp1: return tmp
    tmp = res['rep4'].sub(lambda x:x.group(1), tmp1)
    if tmp != tmp1: return tmp
    tmp = res['rep5'].sub(lambda x:x.group(1), tmp1)
    return tmp

def procsb(tmp1):
    return res['hao1'].sub(lambda x:x.group(1)+u'^^^'+x.group(2) , tmp1).split(u'^^^')


def proc(row):
    tmp2 = repnum(row[1].decode('utf-8'))
    tmp = procs(tmp2, prov1, prov2)
    tmp = procs(tmp, city1)
    tmp = procs(tmp, city2)
    tmp = procs(tmp, ctry )
    tmpa = procsa(tmp)
    tmpb = procsb(tmp)
    print '0',tmp2
    print '1', tmpa
    if len(tmpb)== 2:
        print '2',tmpb[0], tmpb[1]
    else:
        print '2', tmpb[0]
    print '-' * 20
    return
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
    print '-' * 20
    return tmp


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
