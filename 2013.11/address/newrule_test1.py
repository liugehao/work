#!/usr/bin/env python
#coding=utf-8

import MySQLdb
import sys
import re
from num import getResultForDigit
import pickle
from newrule_test import psre,psfw

reload(sys)
sys.setdefaultencoding('utf-8')

if __name__ == "__main__":
    yy =(
        ('320113', u'南京市龙蟠路179号化工院2221A室'),
        ('320113', u'南京市龙蟠路379号化工院2221A室'),
        ('320113', u'和燕路322-1号，创和电脑'),
        ('320102', u'南京中山南路124号'),
        ('320102', u'南京中山南路100号'),
        ('320102', u'南京中山南路99号'),
        ('320102', u'乌衣巷22号'),
        )
    for (xx,xy) in yy:
        j=psre(psfw, xx, xy)
    
        print xx, xy
        if j is None:
            print 'no'
            print '-' * 60
            continue
        print (','.join([str(x) for x in j]))
        print '-' * 60
        
"""
320113 南京市龙蟠路179号化工院2221A室
210113,rep4,龙蟠路159-191号
------------------------------------------------------------
320113 南京市龙蟠路379号化工院2221A室
no
------------------------------------------------------------
320113 和燕路322-1号，创和电脑
211134,rep4,和燕路
------------------------------------------------------------
320102 南京中山南路124号
no
------------------------------------------------------------
320102 南京中山南路100号
210030,rep4,南京中山南路1号到122号双号
------------------------------------------------------------
320102 南京中山南路99号
211133,rep4,中山南路单号
------------------------------------------------------------
"""
