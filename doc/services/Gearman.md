GearmanService
==============

## Requirement

### libgearman

libgearman-0.21 (for Mac OS)

    ./configure --prefix=/opt --with-boost=/opt/local/include --with-libevent-prefix=/opt/local
    make 
    make install

### php-gearman

    wget http://pecl.php.net/get/gearman-1.0.2.tgz
    tar xvf gearman-1.0.2.tgz
    cd gearman-1.0.2
    ./configure --with-gearman=/opt
