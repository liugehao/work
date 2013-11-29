#!/usr/bin/env python
#coding=utf-8

import MySQLdb
import sys
import re
reload(sys)
sys.setdefaultencoding('utf-8')

res = {}
res['-1']=re.compile(u'\s*[(（]')
res['-2']=re.compile(u'[)）]\s*')
res['-3']=re.compile(u'(.*?)')

res['0'] = re.compile(u'([\r\n·/、,，.;:。；：\-|#]|<.*?>|&.*?;|◇|●|&gt;|&lt;|&nbsp;|【.*?】|　)')

res['rep2'] = re.compile(u'(工业园|博物馆|局|[一二三四五]期|分院|学院|大学|嘉园|家园|家属[区楼院]|银行|小区|公司|市场|中心|花园|苑|广场|酒店|大[楼厦院]|公司|学校|分校|宿舍|村屯|村庄|^村镇|村街|村路|庄村|^庄镇|庄街|庄路|庄屯|屯村|屯庄|^屯镇|村|庄|屯|幼儿园|小学|中学|号院|基地|政府|研究所|产业园|集团)$')
res['rep3'] = re.compile(u'(街|胡同|弄|路|大道|村|庄|屯)$')

"""
res['hao0'] = re.compile(u'(路|街|大道)([一二三四五六七八九][一二三四五六七八九十百千万零]*)')
res['hao1'] = re.compile(u'(.+?)(\d+)(-|号).*$')
res['hao2'] = re.compile(u'(.+?)(\d+).*$')
"""
rst = {}
rst1 = {}

def result(i):
    if rst.has_key(i):
        rst[i] += 1
    else:
        rst[i] = 1


def result1(i):
    if rst1.has_key(i):
        rst1[i] += 1
    else:
        rst1[i] = 1

def psfw(row):
    if row[1] is not None:
        tmp1 = res['0'].sub(' ',row[1].decode('utf-8'))
        for s in tmp1.split(' '):
            #if s.strip() != '' :print s
            if len(res['rep2'].findall(s))>0:
                result('rep2')
                print s, 'rep2'
            elif len(res['rep3'].findall(s)) >0:
                result('rep3')
                print s, 'rep3'
            else:
                result('no')
                
    if row[2] is not None:
        tmp1 = res['0'].sub(' ',row[2].decode('utf-8'))
        for s in tmp1.split(' '):
            #if s.strip() != '' :print s
            if len(res['rep2'].findall(s)) >0:
                result1('rep2')
                print s, 'rep2'
            elif len(res['rep3'].findall(s))>0: 
                result1('rep3') 
                print s, 'rep3'
            else:
                result1('no')     
def main():
    global res
    mconn = MySQLdb.connect(charset = 'gbk', host="192.168.1.16", user='caiwu', passwd='cai_Report', db='ydserver')
    mc = mconn.cursor()
    mc.execute("select bm,psfw,bpsfw from gsjj limit 2000,80 ")
    for row in mc.fetchall():
        psfw(row)
    adds = 0
    adds1 = 0
    for a,b in rst.items():
        print a, b
        adds -= b
    print '派送未匹配' , rst['no'] 
    
    for a,b in rst1.items():
        print a, b
        adds1 -= b
    print 'bu派送未匹配' , rst1['no'] 

if __name__ == "__main__":
    main()
    for x in res['rep2'].findall(u'白华路（只到大宇家园为止）'):
        print x

