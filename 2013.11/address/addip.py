#!/usr/bin/env python
#coding=utf-8

import MySQLdb, psycopg2
import sys
import re

res = {}
res['0'] = re.compile(u'(<.*?>|&gt;|&lt;|&nbsp;|【.*?】)')
res['1'] = re.compile(u'[/、◇●,.;:，。；：\-|]')
res['10'] = re.compile(u'全境派')
mconn = MySQLdb.connect(charset = 'gbk', host="192.168.1.16", user='caiwu', passwd='cai_Report', db='ydserver')
mc = mconn.cursor()
mc.execute("select bm,mc,psfw,bpsfw from gsjj" )


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
