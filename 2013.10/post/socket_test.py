import socket
from datetime import datetime        
param_data = 'param1=a&param2=b'
param_lenth = str(len(param_data))



request_str = '''POST //test.php HTTP/1.1\r\nHost: *.*.*.*\r\nContent-Type: application/x-www-form-urlencoded\r\nContent-Length: '''+param_lenth+'''\r\n\r\n'''+param_data

s = datetime.now()
for i in xrange(10000):
	sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
	sock.connect(('127.0.0.1', 80))
	sock.send(request_str)
	data = sock.recv(4096)
	#print data
sock.close()
	#print 'POST method fetch data:'
	#print data.split('\r\n\r\n')[1]

	#print 'DELETE method fetch data:'
	#print data.split('\r\n\r\n')[1]

print datetime.now() - s 
