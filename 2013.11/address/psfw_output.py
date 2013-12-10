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


if __name__ == "__main__":
    bpsfw = pickle.load(open('./%s' % sys.argv[1], 'r'))
    for x, y in bpsfw.items():
        print x, ":"
        for a,b in y.items():
            print "\t", a, ":" , "\t".join(dict(b).keys())
        print '-' * 80
                

