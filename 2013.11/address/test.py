#!/usr/bin/env python
#coding=utf-8

import re
import sys
reload(sys)
sys.setdefaultencoding('utf-8')

res = {}
res['num']= re.compile('\d*?-*?\d*?-\d*.*$')
res['num1'] = re.compile(u'\d*?-*?\d*?([号栋幢室楼]|单元).*$')

res['rep0'] = re.compile(u'对面.*$')
res['rep1'] = re.compile(u'(路|街|道).*号(.*(博物馆|局|[一二三四五]期|分院|学院|大学|家园|家属[区楼院]|银行|小区|公司|市场|中心|花园|苑|广场|酒店|大[楼厦院]|公司|学校|分校|宿舍|村屯|村庄|^村镇|庄村|^庄镇|庄屯|屯村|屯庄|^屯镇|村|庄|屯|幼儿园|小学|中学|号院|基地)).*$')

res['rep2'] = re.compile(u'(博物馆|局|[一二三四五]期|分院|学院|大学|家园|家属[区楼院]|银行|小区|公司|市场|花园|苑|广场|酒店|大[楼厦院]|公司|学校|分校|宿舍|村屯|村庄|^村镇|庄村|^庄镇|庄屯|屯村|屯庄|^屯镇|村|庄|屯|幼儿园|小学|中学|号院).*$')

res['rep3'] = re.compile(u'(村|庄|屯).*$')
res['rep4'] = re.compile(u'(街|胡同|弄).*$')
res['rep5'] = re.compile(u'(路|大道).*$')

tmp1 = sys.argv[1].decode('utf-8')
tmp1 = res['rep0'].sub('', tmp1)
tmp = res['rep1'].sub(lambda x:x.group(1)+x.group(2), tmp1)
print 'rep1',tmp
tmp = res['rep2'].sub(lambda x:x.group(1), tmp1)
print 'rep2',tmp
print 'rep3', res['rep3'].sub(lambda x:x.group(1), tmp1)
print 'rep4', res['rep4'].sub(lambda x:x.group(1), tmp1)
print 'rep5', res['rep5'].sub(lambda x:x.group(1), tmp1)
print 'num1', res['num1'].sub('', tmp1)
print '-' * 20
res['hao1'] = re.compile(u'(.*?)(-?\d*?)号.*$')

print res['hao1'].sub(lambda x:x.group(1) + x.group(2), tmp1)
