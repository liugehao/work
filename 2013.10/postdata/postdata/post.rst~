=======================
post数据测试
=======================

环境
======

rhel5.7 

2核虚拟机

4G内存


安装pycurl
===============

.. code-block:: bash

    yum install python-pycurl.x86_64


安装PHP
=================

.. code-block:: bash

    wget http://us1.php.net/get/php-5.5.5.tar.gz/from/cn2.php.net/mirror
    tar zxf php-5.5.5.tar.gz 
    yum install libxml2-devel.x86_64
    cd php-5.5.5
    ./configure
    make -j 4
    make install


安装php的curl扩展
==============================

.. code-block:: bash

    cd php-5.5.5/ext/curl
    phpize
    ./configure
    make -j 4
    make install


测试方法 
========================

把test.php放至网站root下，使url http://127.0.0.1/test.php可以访问到。

开10个进程同时测试：

.. code-block:: bash
    
    php curl_test.php & php curl_test.php & php curl_test.php & php curl_test.php & php curl_test.php & php curl_test.php & php curl_test.php & php curl_test.php & php curl_test.php & php curl_test.php & 
    
    python curl_test.py & python curl_test.py & python curl_test.py & python curl_test.py & python curl_test.py & python curl_test.py & python curl_test.py & python curl_test.py & python curl_test.py & python curl_test.py & 


urllib2_test.py, socket_test.py测试方法相同

每个进程发送10000条post消息，程序结束后出现的秒数除post的总数量，即每秒发送的消息数

 

测试结果
==========

每秒处理10000左右数据，


增加测试文件
=================

多线程CURL测试文件 curl_mt.py 

多进程测试文件 curl_mp.py

测试方法：直接运行 python filename
