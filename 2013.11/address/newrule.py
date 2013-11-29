#!/usr/bin/env python
#coding=utf-8

import MySQLdb
import sys
import re
reload(sys)
sys.setdefaultencoding('utf-8')
debug = False #True
res = {}
res['rep2'] = re.compile(u'(工业园|博物馆|局|[一二三四五]期|分院|学院|大学|嘉园|家园|家属[区楼院]|银行|小区|公司|市场|中心|花园|苑|广场|酒店|大[楼厦院]|公司|学校|分校|宿舍|屯|庄|村|庄|镇|幼儿园|小学|中学|号院|基地|政府|研究所|产业园|产业园区|集团|工业区|工业园区|开发区|体育馆|医院|汽车城|公寓|派出所|办公楼|综合楼|住宅楼|高中)$')
res['rep3'] = re.compile(u'(街|胡同|弄|路|大道|巷)$')

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


def procstr(str1):
    tmp =str1.decode('utf-8')
    tmp = re.sub(u'[\[（(].*?[\)）\]]', lambda x: re.sub(u'[,，、.;:。；：\s]','$$$',x.group(0)), tmp)
    tmp = re.sub(u'([\r\n·/、,，.;:。；：|#]|<.*?>|&.*?;|◇|●|&gt;|&lt;|&nbsp;|【.*?】|　)', ' ', tmp)
    tmp = re.sub(u'\s*([(（])', '(', tmp)  
    tmp = re.sub(u'[\)）]', ') ', tmp)
    tmp = re.sub(u'\s*-\s*', '-', tmp)  
    tmp = re.sub('\s{2,}', ' ', tmp)
    return tmp
    
def psfw(row):
    if row[1] is not None:
        psfw_col = procstr(row[1])
        if debug: print psfw_col
        for s in psfw_col.split(' '):
            #if s.strip() != '' :print s
            if len(res['rep2'].findall(s))>0:
                result('rep2')
                if debug: print s, 'rep2'
            elif len(res['rep3'].findall(s)) >0:
                result('rep3')
                if debug: print s, 'rep3'
            elif len(s.strip()) == 0:
                return
            else:
                result('no')
                if debug: print s, 'no', row[0]
                
    if row[2] is not None:
        bpsfw_col = procstr(row[2])
        if debug:  print bpsfw_col
        for s in bpsfw_col.split(' '):
            #if s.strip() != '' :print s
            if len(res['rep2'].findall(s)) >0:
                result1('rep2')
                if debug: print s, 'rep2'
            elif len(res['rep3'].findall(s))>0: 
                result1('rep3') 
                if debug: print s, 'rep3'
            elif len(s.strip()) == 0:
                return
            else:
                result1('no')
                if debug: print s, 'no', row[0]
                
                 
                 
def main():
    global res
    mconn = MySQLdb.connect(charset = 'gbk', host="192.168.1.16", user='caiwu', passwd='cai_Report', db='ydserver')
    mc = mconn.cursor()
    #mc.execute("select bm,psfw,bpsfw from gsjj WHERE bm=110141 ")
    mc.execute("select bm,psfw,bpsfw from gsjj ")
    for row in mc.fetchall():
        psfw(row)
    adds = 0
    adds1 = 0
    for a,b in rst.items():
        print a, b
        adds -= b
    print '派送未匹配'  , rst.get('no',0) 
    
    for a,b in rst1.items():
        print a, b
        adds1 -= b
    print 'bu派送未匹配' , rst1.get('no',0) 

if __name__ == "__main__":
    main()


