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

writefile = open('./all.txt','w')

def oddoreven(str1, gatenum):
    try:
        oddoreven_gatenum = gatenum % 2
        tmp = re.search(u'([单|双])号', str1) 
        if tmp is None:
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

    if addr is None: return None
    if fw.has_key(str(ctry)) is False: return None
    for x,y in fw[str(ctry)].items():

        if x == 'all':
            return 'all',y[0][1]
        if x =='rep1' or x == 'rep<6' or x=='rep2':
            for str1, gs in y:
                if addr.find(str1) != -1:
                    return gs, x, str1
        else:
            tmp = re.search(u'(\d+)[\-－]?(\d+)?号', addr)
            if tmp is None: continue
            gatenum = int(tmp.group(1))
            for str1, gs in y:
                tmp = re.search(u'.*(街|胡同|弄|路|大道|巷)', str1)
                if tmp is None: continue

                if re.search(re.sub('[\[\]()]','', tmp.group(0)), addr) is None: 
                    continue
                elif len(re.findall(u'.*(街|胡同|弄|路|大道|巷)$', str1)) > 0:
                    return gs, x, str1

                
                tmp = re.findall(u'[单|双]?号?\d+号?[\-－到至]\d+号?[单|双]?号?', str1)
                if len(tmp) > 0: 
                    for tmp1 in tmp:
                        [(n1, n2)] = re.findall(u'(\d+)号?[\-－到至](\d+)号?', str1)
                        oe = oddoreven(str1, gatenum) 
                        if oe and gatenum >= int(n1) and gatenum <= int(n2):
                            return gs, x, str1

                tmp = re.findall(u'(单号|双号)?\d+号(以上|以下|之间|区间|单号|双号)?', str1)
                if len(tmp) > 0: 
                    for tmp1 in tmp:
                        try:
                            n1, n2 = re.findall(u'(\d+)号?(以上|以下)', str1)
                        except:
                            #print tmp, str1
                            #print re.findall(u'(\d+)号?(以上|以下)', str1)
                            continue
                            #raise 'a'
                        oe = oddoreven(str1, gatenum) 
                        if oe and gatenum >= n1 and n2 == u'以上':
                            return gs, x, str1
                        if oe and gatenum <= n1 and n2 == u'以下':
                            return gs, x, str1
                else:
                    tmp = re.findall(u'(单号|双号)', str1)
                    if len(tmp) > 0:
                        if oddoreven(str1, gatenum) : return gs, x, str1

    return None
            
                
        

mconn = MySQLdb.connect(charset = 'utf8', host="127.0.0.1", user='root', passwd='l', db='addr')


def procall(strqu):
    mc = mconn.cursor()
    if psfw.has_key(strqu) is False: 
        print strqu, 'no content'
        print >>writefile,strqu, 'no content'
        return 
        #exit(1)    
    mc.execute("SELECT a.num, w.qu,a.addr, s.pj FROM wdqu w INNER JOIN sd s ON s.pj=w.`bm` INNER JOIN address a ON a.num=s.`num` WHERE w.qu=%s LIMIT 10000 " , strqu)
    """            
    if psfw.has_key(strqu):
        for x,y in psfw[strqu].items():
            for z,e in y:
                print x,z,e
    print '+' * 80
    if bpsfw.has_key(strqu):
        for x,y in bpsfw[strqu].items():
            for z,e in y:
                print x,z,e
    print '+' * 80
    """
    xx = [0,0,0]
    for row in mc.fetchall():
        #print row[0], row[1], row[3]
        #print row[2]
        ps=psre(psfw, row[1], row[2])
        bps=psre(bpsfw, row[1], row[2])
        
        if bps is None and ps is None:
            #print u'不确定'
            xx[0] += 1

                
        if bps is not None:
            #print u'不派送:', ','.join([str(x) for x in bps])
            if bps[0] == 'all' and ps[0] is not None:
                xx[2] += 1
            else:
                xx[1] += 1
        elif ps is not None:
            #print u'派送:', ','.join([str(x) for x in ps])
            xx[2] += 1
        #print '-' * 60
        
    print  strqu, u'不确定', xx[0],  u'不派送:', xx[1],  u'派送:', xx[2]
    print >>writefile, strqu, u'不确定', xx[0],  u'不派送:', xx[1],  u'派送:', xx[2]
    writefile.flush()

if __name__ == "__main__":
    #print re.search('街',u'良乡北潞园小区北潞芳家园21号楼5单元102') is None
    #exit(0)
    mc1 = mconn.cursor()
    #mc.execute("SELECT bdqu FROM ydserver.wdzzzbd WHERE wdbm=%s LIMIT 1" , strqu)
    #ctry = str(mc.fetchone()[0])
    mc1.execute("SELECT DISTINCT qu FROM wdqu;")
    for row in mc1.fetchall():
         procall(str(row[0]))

