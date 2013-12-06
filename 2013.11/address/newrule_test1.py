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


#bpsfw = pickle.load(open('./bpsfw', 'r'))
#psfw = pickle.load(open('./psfw', 'r'))



if __name__ == "__main__":
    yy =(
        ('320113', u'南京市龙蟠路179号化工院2221A室'),
        ('320113', u'和燕路322-1号，创和电脑'),
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
