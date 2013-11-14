#!/usr/bin/env python
#coding=utf-8

import re
import sys
reload(sys)
sys.setdefaultencoding('utf-8')
from num import getResultForDigit 
res = {}
res['rep0'] = re.compile(u'(对面|门口|旁边|楼上).*$')
res['rep1'] = re.compile(u'(.*?)\d*号(.*(博物馆|局|[一二三四五]期|分院|学院|大学|嘉园|家园|家属[区楼院]|银行|小区|公司|市场|中心|花园|苑|广场|酒店|大[楼厦院]|公司|学校|分校|宿舍|村屯|村庄|^村镇|^村街|^村路|庄村|^庄镇|^庄街|^庄路|庄屯|屯村|屯庄|^屯镇|村|庄|屯|幼儿园|小学|中学|号院|基地|政府|研究所|产业园|集团)).*$')

res['rep2'] = re.compile(u'(博物馆|局|[一二三四五]期|分院|学院|大学|嘉园|家园|家属[区楼院]|银行|小区|公司|市场|中心|花园|苑|广场|酒店|大[楼厦院]|公司|学校|分校|宿舍|村屯|村庄|^村镇|村街|村路|庄村|^庄镇|庄街|庄路|庄屯|屯村|屯庄|^屯镇|村|庄|屯|幼儿园|小学|中学|号院|基地|政府|研究所|产业园|集团).*$')

res['rep3'] = re.compile(u'(村|庄|屯).*$')
res['rep4'] = re.compile(u'(街|胡同|弄).*$')
res['rep5'] = re.compile(u'(路|大道).*$')

res['hao0'] = re.compile(u'(路|街|大道)([一二三四五六七八九][一二三四五六七八九十百千万零]*)')
res['hao1'] = re.compile(u'(.+?)(\d+)(-|号).*$')
res['hao2'] = re.compile(u'(.+?)(\d+).*$')

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
    tmp = res['hao1'].sub(lambda x:x.group(1)+u'^^^'+x.group(2) , tmp1).split(u'^^^')
    if tmp[0] != tmp1: return tmp
    return res['hao2'].sub(lambda x:x.group(1)+u'^^^'+x.group(2) , tmp1).split(u'^^^')



if __name__ == '__main__':
    data = {
    u'经济技术开发区经海3路18号':(u'经济技术开发区经海3路' , [u'经济技术开发区经海3路', '18']),
    u'辽宁沈阳市沈河区辽宁省沈阳市沈河区文艺路19甲2中信银行沈河支行':(u'辽宁沈阳市沈河区辽宁省沈阳市沈河区文艺路19甲2中信银行',['辽宁沈阳市沈河区辽宁省沈阳市沈河区文艺路19甲2中信银行沈河支行']),
    u'辽宁省大连市金州区大连市金州区永安大街108中学门口对面小店':(u'辽宁省大连市金州区大连市金州区永安大街108中学',[u'辽宁省大连市金州区大连市金州区永安大街108中学门口对面小店']),
    u'辽宁省锦州市科技路19号渤海大学三区五号楼405寝室':(u'辽宁省锦州市科技路渤海大学',[u'辽宁省锦州市科技路','19']),
    u'马陡路荆各庄矿工房51楼3门201号':(u'马陡路荆各庄',[u'马陡路荆各庄矿工房51楼3门','201']),
    u'海淀区学院路二十号石油大院13号楼1单元102号':(u'海淀区学院路石油大院',[u'海淀区学院路','20']),
    u'朝阳区香槟路66-2号上品折扣':(u'朝阳区香槟路',[u'朝阳区香槟路','66']),
    u'朝阳区霞光里66':(u'朝阳区霞光里66',[u'朝阳区霞光里','66']),
    }
    for x in data:
        print x
        print procsa(repnum(x))
        for i in procsb(repnum(x)):
            print i
        print '-' * 20
    
    for x in data:
        assert(procsa(repnum(x)) == data[x][0])
        assert(procsb(repnum(x)) == data[x][1])



