#-*- coding:utf-8 -*-
import os
import pycurl
import StringIO
from datetime import datetime
import urllib

html = StringIO.StringIO()
url = r'http://127.0.0.1/test.php'
post_data_dic = {'data':'hello'}

s = datetime.now()
c = pycurl.Curl()
for i in xrange(10000):
	c.setopt(pycurl.URL,url)
	c.setopt(pycurl.SSL_VERIFYHOST, False)
	c.setopt(pycurl.SSL_VERIFYPEER,False)
	#c.setopt(pycurl.USERAGENT,r"User-Agent: Dalvik/1.4.0 (Linux; U; Android 2.3.7; Milestone Build/SHOLS_U2_05.26.3)")
	c.setopt(pycurl.WRITEFUNCTION, html.write)
	c.setopt(pycurl.POSTFIELDS,  urllib.urlencode(post_data_dic))
	c.setopt(pycurl.FOLLOWLOCATION, 1)
	c.perform()
	html.seek(0)
	html.truncate()
	#print c.getinfo(pycurl.HTTP_CODE), c.getinfo(pycurl.EFFECTIVE_URL)
#print html.getvalue(),"\n"
c.close()
print datetime.now() - s
