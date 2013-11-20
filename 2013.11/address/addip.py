#!/usr/bin/env python
#coding=utf-8
import MySQLdb, psycopg2
import sys

psycopg2.paramstyle='qmark'
pconn = psycopg2.connect(host='172.16.147.133', user='postgres', password='l', database='address')
pc = pconn.cursor()
pcw = pconn.cursor()

pconn.autocommit =True
import re

res = {}
res['0'] = re.compile(u'(<.*?>|&.*?;|◇|●|&gt;|&lt;|&nbsp;|【.*?】)')
res['1'] = re.compile(u'[()·/、,.;:，。；：\-|]')
res['10'] = re.compile(u'全境派')
pc.execute("select bm,szd,psfw,bpsfw from wd" )


def proc(m):
    if m is None: return ''
    try:
        tmp = res['0'].sub(' ', m.decode('utf-8'))
        tmp = res['1'].sub(' ',tmp)
        tmp = re.sub('  ',' ', tmp)
    except Exception, e:
        print m
        print e
        return ''
        #exit(1)
    return tmp

for m in pc.fetchall():
    #print m[0], m[1]
    #print 1,proc(m[2])
    #print 2,proc(m[3])
    if m[2] is None and m[2] is None: continue
    try:
        if m[2] is not None and len(res['10'].findall(m[2].decode('utf-8'))) > 0:
            pcw.execute("insert into psfw (bm,dz,szd,pslb) values (%s, %s, %s, %s)",(m[0], '', m[1], 1))
        else:
            for x in proc(m[2]).split():
                if x !='':
                    pcw.execute("insert into psfw (bm,dz,szd,pslb) values (%s, %s, %s, %s)",(m[0],x, m[1], 3))
        for x in proc(m[3]).split():
            pcw.execute("insert into psfw (bm,dz,szd,pslb) values (%s, %s, %s, %s)",(m[0],x, m[1], 4))
    except Exception, e:
        print m
        raise e
