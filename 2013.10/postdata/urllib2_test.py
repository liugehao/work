#!/usr/bin/python  
#coding=utf-8  
  
import urllib  
import urllib2  
import datetime  
def post(req, data):  
    data = urllib.urlencode(data)  
    #enable cookie  
    opener = urllib2.build_opener(urllib2.HTTPCookieProcessor())  
    response = opener.open(req, data)
    del(data,req,opener)
    return response.read()  
  
def main():  
    posturl = urllib2.Request("http://127.0.0.1/test.php" ) 
    data = {'data':'myemail' } 
    st = datetime.datetime.now() 
    for i in xrange(10000):
        post(posturl, data)  
    print datetime.datetime.now()  - st
  
if __name__ == '__main__':  
    main() 
