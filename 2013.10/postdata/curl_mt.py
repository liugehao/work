#-*- coding:utf-8 -*-
import os
import pycurl
import urllib
import StringIO
from datetime import datetime
import thread
import time


import threading  
import time   
class myThread(threading.Thread):  
	def __init__(self, threadname):  
		threading.Thread.__init__(self, name=threadname)  
          
	def run(self):  
		html = StringIO.StringIO()
		c = pycurl.Curl()
		url = r'http://127.0.0.1:8080/test.php'
		post_data_dic = {'data':'hello'}
		str1 = urllib.urlencode(post_data_dic)
		for i in xrange(10000):
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
		print datetime.now() - s

threadnum =8
if __name__ == "__main__":
	s = datetime.now()
	t = {}
	for i in range(threadnum):
		t[i]=myThread('son thread')  
		t[i].start()
		if t[i].isDaemon():
		    print datetime.now() - s
		else:  
		    print "the father thread is waiting the son thread····" 

	print "the parent's parent process: %s" % (os.getppid())  
	


