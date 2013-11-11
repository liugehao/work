#!/usr/bin/env python
#coding=utf-8

import re
import sys
reload(sys)
sys.setdefaultencoding('utf-8')

res = {}
res['num']= re.compile('\d*?-*?\d*?-\d*.*$')
res['num1'] = re.compile(u'\d*?-*?\d*?([号栋幢室楼]|单元).*$')


res['rep1'] = re.compile(u'(路|街|道).*号(.*([一二三四五]期|分院|学院|大学|家园|家属[区楼院]|银行|小区|公司|市场|花园|苑|广场|酒店|大[楼厦院]|公司|学校|分校|宿舍|村[^屯庄镇]|庄[^村镇屯]|屯[^村庄镇]|幼儿园|小学|中学)).*$')

res['rep2'] = re.compile(u'([一二三四五]期|分院|学院|大学|家园|家属[区楼院]|银行|小区|公司|市场|花园|苑|广场|酒店|大[楼厦院]|公司|学校|分校|宿舍|村[^屯庄镇]|庄[^村镇屯]|屯[^村庄镇]|幼儿园|小学|中学).*$')

res['rep3'] = re.compile(u'(村|庄|屯).*$')
res['rep4'] = re.compile(u'(街|路|胡同|弄).*$')

tmp1 = sys.argv[1].decode('utf-8')
tmp = res['rep1'].sub(lambda x:x.group(1)+x.group(2), tmp1)
print 'rep1',tmp
tmp = res['rep2'].sub(lambda x:x.group(1), tmp1)
print 'rep2',tmp
print 'rep3', res['rep3'].sub(lambda x:x.group(1), tmp1)
print 'rep4', res['rep4'].sub(lambda x:x.group(1), tmp1)
print 'num1', res['num1'].sub('', tmp1)
