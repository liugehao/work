#!/usr/bin/env python
#coding=utf-8

if __name__ == "__main__":
    print 'ok'
    with open('./adds.txt','r') as txt:
        while 1:
            line = txt.readline()
            if line is None:
                break
            print line
