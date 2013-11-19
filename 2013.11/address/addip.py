#!/usr/bin/env python
#coding=utf-8
import MySQLdb, psycopg2
import sys

psycopg2.paramstyle='qmark'
pconn = psycopg2.connect(host='172.16.147.133', user='postgres', password='l', database='address')
pc = pconn.cursor()
import re

res = {}
res['0'] = re.compile(u'(<.*?>|&gt;|&lt;|&nbsp;|【.*?】)')
res['1'] = re.compile(u'[/、◇●,.;:，。；：\-|]')
res['10'] = re.compile(u'全境派')
pc.execute("select bm,psfw,bpsfw,szd from wd" )


def proc(m):
    if m is None: return ''
    try:
        tmp = res['0'].sub('', m)
        tmp = res['1'].sub(' ',tmp)
    except Exception, e:
        print m
        print e
        exit(1)
    return tmp

for m in mc.fetchall():
    print m[0], m[1]
    print proc(m[2])
    print proc(m[3])
