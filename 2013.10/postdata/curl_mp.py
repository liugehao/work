#-*- coding:utf-8 -*-
import os
import pycurl
import urllib
import StringIO
from datetime import datetime
from multiprocessing import Process  
import os  

html = StringIO.StringIO()
url = r'http://127.0.0.1:8080/test.php'
post_data_dic = {'data':'hello'}


def proc():
	c = pycurl.Curl()
	str1 = urllib.urlencode(post_data_dic)
	for i in xrange(30000):
		c.setopt(pycurl.URL,url)
		c.setopt(pycurl.SSL_VERIFYHOST, False)
		c.setopt(pycurl.SSL_VERIFYPEER,False)
		#c.setopt(pycurl.USERAGENT,r"User-Agent: Dalvik/1.4.0 (Linux; U; Android 2.3.7; Milestone Build/SHOLS_U2_05.26.3)")
		c.setopt(pycurl.WRITEFUNCTION, html.write)
		c.setopt(pycurl.POSTFIELDS,  str1)
		c.setopt(pycurl.FOLLOWLOCATION, 1)
		c.perform()
		html.seek(0)
		html.truncate()
		#print c.getinfo(pycurl.HTTP_CODE), c.getinfo(pycurl.EFFECTIVE_URL)
	#print html.getvalue(),"\n"
	c.close()


procnum = 100
if __name__ == "__main__":
	s = datetime.now()
	child_proc = {}
	for i in range(procnum):
		child_proc[i] = Process(target=proc)  
		child_proc[i] .start()  
	for i in range(procnum):
		child_proc[i] .join()  
	print "the parent's parent process: %s" % (os.getppid())  
	print datetime.now() - s
