import MySQLdb, psycopg2
import sys
import re

res['0'] = re.compile(r'<.*?>')
res['1'] = re.compile(u'[,.;:，。；：--||]')
mconn = MySQLdb.connect(charset = 'gbk', host="192.168.1.16", user='caiwu', passwd='cai_Report', db='ydserver')
mc = mconn.cursor()
mc.execute("select bm,mc,psfw,bpsfw from gsjj" )


def proc(m):
    tmp = res['0'].sub('', m)
    tmp = res['1'].sub(' ',tmp)
    return tmp

for m in mc.fetchall():
    print m[0], m[1]
    print proc(m[2])
    print proc(m[3])