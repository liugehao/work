
安装 CRF
======

    wget  http://crfpp.googlecode.com/files/CRF%2B%2B-0.58.tar.gz
    tar zxf CRF++-0.58.tar.gz
    cd CRF++-0.58
    ./configure
    make 
    make install
    echo "/usr/local/lib" >> /etc/ld.so.conf
    ldconfig

安装posgresql
===========

debian:

    apt-get install postgresql-9.1 postgresql-contrib-9.1 postgresql-server-dev-9.1

安装nlpbamboo
===========

    wget http://nlpbamboo.googlecode.com/files/nlpbamboo-1.1.2.tar.bz2
    tar jxf nlpbamboo-1.1.2.tar.bz2
    cd nlpbamboo-1.1.2
    mkdir build
    cd build
    cmake .. -DCMAKE_BUILD_TYPE=release
    make all

下载bamboo索引文件
------------

    wget http://nlpbamboo.googlecode.com/files/index.tar.bz2
    tar jxf index.tar.bz2 -C /opt/bamboo

安装postgresql中文分词模块
------------------

    cd /opt/bamboo/exts/postgres/pg_tokenize
    make && make install
    cd ../chinese_parse
    make && make install

    service postgresql restart
    su - postgres
    psql
    \i /usr/share/postgresql/9.1/contrib/pg_tokenize.sql
    \i /usr/share/postgresql/9.1/contrib/chinese_parser.sql


导入数据
====


安装python的mysql postgresql模块
---------------------------

debian:

    apt-get install python-mysql python-psycopg2

importdata.py:

    #!/usr/bin/env python
    #coding=utf-8
    import MySQLdb, psycopg2
    import sys

    #psycopg2.paramstyle='qmark' #psycopg2.paramstyle 失效 ，全用%s
    pconn = psycopg2.connect(host='172.16.147.133', user='postgres', password='l', database='address')
    pc = pconn.cursor()

    def insert(row, table):
        lens = len(row)
        str = "insert into %s values (%s);" % (table, (', %s' * lens)[1:] )
        try:
            pc.execute(str, row)
        except:
            pass

    if __name__ == "__main__":
        table = sys.argv[1]
        mconn = MySQLdb.connect(charset = 'gbk', host="192.168.1.16", user='caiwu', passwd='cai_xxxx', db='exp_address')
        mc = mconn.cursor()
        mc.arraysize = 10000
        mc.execute("select * from %s" % table)
        while 1:
            rows = mc.fetchmany()
            if not len(row):
                break
            for row in rows:
                insert(row, table)
        pconn.commit()

使用方法：

    python importdata.py 表名


数据整理：

    CREATE TABLE ldb1
    (
    crty integer,
    addr character varying(600),
    comp integer
    )

    insert into lab1 values
    select
        recv_ctry integer,
        recv_addr ,
        entry_comp
    from ldb
    group by recv_ctry integer,
        recv_addr ,
        entry_comp

删除空数据：

    delete from ldb1
    where recv_ctry =0
        or recv_addr is null
        or entry_comp = 0



    

    
