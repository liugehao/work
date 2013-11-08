#!/usr/bin/python
#coding=utf-8

import MySQLdb, psycopg2
import sys
import re

pconn = psycopg2.connect(host='172.16.147.133', user='postgres', password='l', database='address')
pconn.set_client_encoding('utf-8')
pcw = pconn.cursor()
pc = pconn.cursor()

def procs(strtmp, a, b=None):
	for x in a:
		if strtmp.find(x) == 0:
			return re.sub(r'^%s' % x, '', strtmp)
	if b is not None:
		for x in b:
			if strtmp.find(x) == 0:
				return re.sub(r'^%s' % x, '', strtmp)
	return strtmp

def proc(row):
	tmp = procs(row[1], prov1, prov2)
	tmp = procs(tmp, city1)
	tmp = procs(tmp, ctry )
	
	print row[1],'|', tmp
	return tmp

#pc.execute("""CREATE TABLE ldb2
#(
#  id serial NOT NULL,
#  ctry integer,
#  addr character varying(600),
#  comp integer
#)""")
#pconn.commit()

pc.execute("""select prov_coding,
		prov_name
	from prov""")

prov1 = []
prov2 = []
for row in pc.fetchall():
	prov1.append(row[1])
	try:
		prov2.append(row[1].replace('省','').replace('市','').replace('自治区',''))
	except:
		print 'error:1:', row[1]

pc.execute("""select city_name
	from city""")
city1 = []
for row in pc.fetchall():
	city1.append(row[0])

ctry = []

pc.execute("""select ctry_name
	from ctry""")
for row in pc.fetchall():
	ctry.append(row[0])

pc.execute("""select count(1) from ldb1""")
num, = pc.fetchone()
pagesize = 10000.0
for i in range(2): #range(int(ceil(num/pagesize))):
	pc.execute("""select ctry, 
		addr, 
		comp 
	from ldb1 limit %s offset %s""", (pagesize, pagesize * i))
	for row in pc.fetchall():
		proc(row)	
