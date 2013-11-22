
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


测试步骤
====

建立数据表
-----

.. codeblock:: postgresql

    CREATE TABLE wd
    (
        bm integer NOT NULL,
        sheng integer,
        shi integer,
        szd integer,
        psfw text,
        bpsfw text,
        mc character varying(100),
        CONSTRAINT wd_pkey PRIMARY KEY (bm)
    );

    CREATE TABLE psfw
    (
        id integer NOT NULL DEFAULT nextval('areas_id_seq'::regclass),
        bm integer,
        dz character varying(80),
        szd integer,
        pslb integer, -- 1全送排除 2全不送排除 3送 4不送
        CONSTRAINT areas_pkey PRIMARY KEY (id)
    )；
    CREATE INDEX psfw_dz_idx
        ON psfw
        USING btree
        (dz COLLATE pg_catalog."default");


    CREATE TABLE psfw_lu
    (
        id integer NOT NULL DEFAULT nextval('roadnums_id_seq'::regclass),
        psfw_id integer,
        xiao integer,
        da integer,
        dsh integer, -- 0 不区分单双号 1 单号 2双号
        CONSTRAINT roadnums_pkey PRIMARY KEY (id)
    )
    CREATE INDEX psfw_lu_xiao_da_idx
        ON psfw_lu
        USING btree
        (xiao, da);

psfw表存储szd-区县 dz-地址 pslb 类别 dzidx测试索引列

psfw_lu 表为 psfw子表，当psfw表中dz为路且派送或者不派送范围不为全路段时，在

psfw_lu表中记录门牌号码，xiao起始号码 da终止号码 ，dsh，标记奇偶数号码

用imdata1.py把mysql gsjj表导入到pgsqldb中

用addip.py处理pgsqldb表，数据进psfw表

然后，增加一个tsquery列，做全文索引查询用：

.. code-block:: sql

    ALTER TABLE psfw ADD COLUMN dzidx tsquery;
    CREATE INDEX psfw_dzidx_idx
        ON psfw
        USING gist
        (dzidx);
    update psfw set dzidx=to_tsquery('chinesecfg',dz);


由于测试数据不规范，只进行bm=213300 szd=320481的公司进行测试，从ldb表中处理路、小区数据进入psfw psfw_lu 表，然后 用ldb表中数据进行测试

用procdata1.py 处理ldb表中bm=213300数据进入psfw .
