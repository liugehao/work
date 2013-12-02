#!/usr/bin/env python
#coding=utf-8

import MySQLdb
import sys
import re
from num import getResultForDigit
import pickle


reload(sys)
sys.setdefaultencoding('utf-8')
debug = False #True
res = {}
rst = {}

bpsfw = pickle.load(open('./bpsfw', 'r'))
psfw = pickle.load(open('./psfw', 'r'))


def oddoreven(str1, gatenum):
    try:
        oddoreven_gatenum = gatenum % 2
        tmp = re.search(u'([单|双])号', str1) 
        if tmp is not None:
            return True
        else:
            if tmp.group(1) == u'单' and oddoreven_gatenum == 1:                
                return True
            if tmp.group(1) == u'双' and oddoreven_gatenum == 0:
                return True
                
    except:
        return None
    return None

def psre(fw, ctry, addr):
    if fw.has_key(str(ctry)) is False: return None
    for x,y in fw[str(ctry)].items():
        if x == 'all':
            return y[1]
        if x =='rep1' or x == 'rep<6' or x=='rep2':
            for str1, gs in y:
                if addr.find(str1) != -1:
                    return gs
        else:
            tmp = re.search(u'(\d+)号', addr)
            if tmp is None: return None
            
            gatenum = int(tmp.group(1))
            
            for str1, gs in y:
                tmp = re.search(u'.*(街|胡同|弄|路|大道|巷)', str1)
                if tmp is None: continue
                if re.search(tmp.group(0), addr) is None: continue
                
                tmp = re.findall(u'[单|双]?号?\d+号?[\-－到至]\d+号?[单|双]?号?', str1)
                if len(tmp) > 0: 
                    for tmp1 in tmp:
                        (n1, n2) = re.findall(u'(\d+)号?[\-－到至](\d+)号?', str1)
                        oe = oddoreven(str1, gatenum) 
                        if oe and gatenum >= n1 and gatenum <= n2:
                            return gs
                        
                tmp = re.findall(u'(单号|双号)?\d+号(以上|以下|之间|区间|单号|双号)?', str1)
                if len(tmp) > 0: 
                    for tmp1 in tmp:
                        n1, n2 = re.findall(u'(\d+)号?(以上|以下)', str1)
                        oe = oddoreven(str1, gatenum) 
                        if oe and gatenum >= n1 and n2 == u'以上':
                            return gs
                        if oe and gatenum <= n1 and n2 == u'以下':
                            return gs
                else:
                    tmp = re.findall(u'(单号|双号)', str1)
                    if len(tmp) > 0:
                        if oddoreven(str1, gatenum) : return gs
                    
    return None
            
                
        


if __name__ == "__main__":
    mconn = MySQLdb.connect(charset = 'gbk', host="192.168.1.16", user='caiwu', passwd='cai_Report', db='exp_address')
    mc = mconn.cursor()
    mc.execute("SELECT `num`, `recv_ctry`, `recv_addr`, `gs` from address where `recv_ctry` = %s " , sys.argv[1])
    if psfw.has_key(sys.argv[1]) is False: 
        print 'no'
        exit(1)
    for x,y in psfw[sys.argv[1]].items():
        for z,e in y:
            print x,z,e
    print '+' * 80
    for row in mc.fetchall():
        print row[0], row[1], row[3]
        print row[2]
        print psre(psfw, row[1], row[2])
        print '-' * 60
