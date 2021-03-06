#!/usr/bin/env python
#coding=utf-8

import MySQLdb
import sys
import re
from num import getResultForDigit
import pickle
import struct

reload(sys)
sys.setdefaultencoding('utf-8')
debug = False #True
res = {}
rst = {}
f = open('./psfw', 'w')

wronggs = open('./psfw_err','w')

pickle_tmp = {}


def result(i, str1='', bm='', szd=''):
    if rst.has_key(i):
        rst[i] += 1
    else:
        rst[i] = 1
    if i[-2:] != 'no':
        pickle_tmp_add(i, str1, bm, szd)


def pickle_tmp_add(i, str1, bm, szd):
    if pickle_tmp.has_key(str(szd)):
        if pickle_tmp[str(szd)].has_key(i):
            pickle_tmp[str(szd)][i].append((str1, bm))
        else:
            pickle_tmp[str(szd)][i] = [(str1, bm),]
    else:
        pickle_tmp[str(szd)] = {i:[(str1, bm), ]}
        
        
def procstr(str1):
    if str1 is None: return None
    
    tmp = str1.decode('utf-8')
    tmp = re.sub(u'[\[（(].*?[\)）\]]', lambda x: re.sub(u'[/、,，.;:。；：|#\s]','$$$',x.group(0)), tmp)
    tmp = re.sub(u'([\s\r\n·/、,，.;:。；：|#]|<.*?>|&.*?;|[◇◆《》★●]|&gt;|&lt;|&nbsp;|【.*?】|　)', ' ', tmp)
    tmp = re.sub(u'\s+', ' ', tmp)
    tmp = re.sub(u'\s*([(（])', '(', tmp)  
    tmp = re.sub(u'[\)）]', ') ', tmp)
    tmp = re.sub(u'\s*[\-－]\s*', '-', tmp)  
    tmp = re.sub(u'[\-－]+', '-', tmp)  
    tmp = re.sub('\s{2,}', ' ', tmp)
    tmp = re.sub(u'([一二三四五六七八九][一二三四五六七八九十百千万零]*)号', lambda x: str(getResultForDigit(x.group(1))), tmp)
    return tmp

res['rep1'] = re.compile(u'(工业园|博物馆|局|[一二三四五]期|[一二三四五六七八九十]中|分院|学院|大学|嘉园|家园|家属[区楼院]|银行|小区|公司|市场|中心|花园|苑|广场|大[楼厦院]|学校|分校|宿舍|屯|庄|村|庄|镇|乡|幼儿园|小学|中学|号院|基地|政府|研究所|产业园|产业园区|集团|工业区|工业园区|开发区|体育馆|医院|汽车城|公寓|派出所|办公楼|综合楼|住宅楼|高中|法院|武装部|大队|软件园|检察院|[一二三四五六七八九十百千万零]+区|写字楼|家属楼|商场|宿舍楼|加油站|宾馆|[旅酒饭][店馆]|部队|城|厂)$')
res['rep2'] = re.compile(u'(街|胡同|弄|路|道|巷|里)$')
#res['rep3'] = re.compile(u'(街|胡同|弄|路|道|巷|里)')
#res['rep4'] = re.compile(u'(街|胡同|弄|路|道|巷)(单号|双号)?\d*?号?[\-到至]?\d*?号?(单号|双号)?$')
#((单号|双号)[一二三四五六七八九][一二三四五六七八九十百千万零]*)号(以上|以下)
def extractstr(str1, bm, szd):
    if str1 is None: return None
    if debug: print str1
    if len(re.findall(u'全[区境]不?派', str1)) >0:
        result('all', 'all', bm, szd )
        return
    for s in str1.split(' '):
            
        if len(s.strip()) <=1:
            continue
        try:
            int(s.strip())
            result('no')
            wronggs.write("%s\t%s\n" % (bm, s))
            continue
        except:
            pass
        """
        if  len(res['rep4'].findall(s))>0:
            result('rep4', s, bm, szd)
            if debug:  print s, 'rep4'
        elif len(res['rep1'].findall(s))>0:
        """
        if len(re.findall(u'^[\w\d]+$', s)) >0:
            result('no')
            wronggs.write("%s\t%s\n" % (bm, s))
            continue
        if len(re.findall(u'^(单号|双号)?\d+号?', s)) >0:
            result('no')
            wronggs.write("%s\t%s\n" % (bm, s))
            continue
        if len(re.findall(u'^\(', s)) > 0:
            result('no')
            wronggs.write("%s\t%s\n" % (bm, s))
            continue
        if len(re.findall(u'(城区内|市区内|县城内)', s)) > 0:
            result('no')
            wronggs.write("%s\t%s\n" % (bm, s))
            continue
        if len(re.findall(u'\(', s)) > 0:
            if len(re.findall(u'\(.*?(工业园|博物馆|局|[一二三四五]期|[一二三四五六七八九十]中|分院|学院|大学|嘉园|家园|家属[区楼院]|银行|小区|公司|市场|中心|花园|苑|广场|酒店|大[楼厦院]|学校|分校|宿舍|屯|庄|村|庄|镇|乡|幼儿园|小学|中学|号院|基地|政府|研究所|产业园|产业园区|集团|工业区|工业园区|开发区|体育馆|医院|汽车城|公寓|派出所|办公楼|综合楼|住宅楼|高中|法院|武装部|大队|软件园|检察院|[一二三四五六七八九十百千万零]+区|写字楼|家属楼|商场|宿舍楼|加油站|宾馆|旅[店馆]|孵化园|城)$', s)) >0:
                result('no')
                wronggs.write("%s\t%s\n" % (bm, s))
                continue
            if len(re.findall(u'\(.*?(街|胡同|弄|路|道|巷|里)', s))>0:
                result('no')
                wronggs.write("%s\t%s\n" % (bm, s))
                continue
        if len(re.findall(u'以[东南西北]', s)) > 0:
            result('no')
            wronggs.write("%s\t%s\n" % (bm, s))
            continue
        if len(res['rep1'].findall(s))>0:
            result('rep1', s, bm, szd)
            continue
        if len(res['rep2'].findall(s)) >0:
            result('rep2', s, bm, szd)
            continue
        #elif len(res['rep3'].findall(s)) >0:
        #   if debug: print s
        #print s
        if len(re.findall(u'(街|胡同|弄|路|道|巷|里)\d+号$', s)) > 0:
            result('rep3_6', s, bm, szd)
            continue
        if len(re.findall(u'(单[数号]|双[数号])?[^\d]\d{1,5}[^\d]号?(以上|以后|以下|之间|区间|单[数号]|双[数号])?', s)) >0:
            result('rep3_5', s, bm, szd)
            continue
        if len(s) < 6:
            result('rep<6', s, bm, szd)
            continue
        for x in s.split('$$$'):
            if str(szd) == '210007':
                print s,x
            if len(re.findall(u'(单[数号]|双[数号])?[^\d]\d{1,5}[^\d]号?(以内|以上|以下|之间|区间|单[数号]|双[数号])?', x)) >0:
                result('rep3_1', s, bm, szd)
                break
            if len(re.findall(u'[^\d]\d{1,5}[^\d]号?[\-－到至][^\d]\d{1,5}[^\d]号?', x)) >0:
                result('rep3_2', s, bm, szd)
                break

            if len(re.findall(u'[单双][数号]\)?$', x)) >0:
                result('rep3_4', s, bm, szd)
                break
            if len(re.findall(u'[^\d]\d{1,5}[^\d]号?$', x)) >0:
                result('rep3_3', s, bm, szd)
                break
            else:
                result('no')
                wronggs.write("%s\t%s\n" % (bm, s))
                break
            #if debug: 
            #print s, 'no'
            
            #result('rep3_no')
            #wronggs.write("%s\t%s\n" % (bm, s))
            #if debug: print x, 'rep3_no'
            #break
        
        #elif len(re.findall(u'\d+号?[\-－到至]\d+号?', s)) >0:
        #    result('rep4', s, bm, szd)
        

            
def psfw(row):
    extractstr(procstr(row[1]), row[0], row[3])
    #extractstr(procstr(row[2]), row[0], row[3])
                
                 
                 
def main():
    global res
    mconn = MySQLdb.connect(charset = 'gbk', host="192.168.1.16", user='caiwu', passwd='cai_Report', db='ydserver')
    mc = mconn.cursor()
    #mc.execute("select bm,psfw,bpsfw from gsjj WHERE bm=250073 ")
    #mc.execute("SELECT g.wdbm,j.psfw,j.bpsfw,g.bdqu FROM gsjj j INNER JOIN wdzzzbd g ON g.wdbm=j.bm ")
    mc.execute("SELECT j.bm,j.psfw,j.bpsfw,g.bdqu FROM gsjj j INNER JOIN wdzzzbd g ON g.wdbm=j.bm ")#where bm = 210260")
    for row in mc.fetchall():
        psfw(row)
    for a,b in rst.items():
        print a, b
    pickle.dump(pickle_tmp, f)
    #t = pickle.dumps(pickle_tmp)
    #f.write(struct.pack(">%ssi" % len(t), t, 100))
    
    print '结尾为“no”的行未匹配' 
    f.close() 


if __name__ == "__main__":
    main()
    #print pickle_tmp['320107']


