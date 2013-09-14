Jenkins CI Integration Note
===========================

Related Repositories
--------------------

Jenkins CI Trigger CPAN Module:

    git@git.corneltek.com:Net-Jenkins.git
    git@git.corneltek.com:Jenkins-Trigger.git

Jenkins Job Template:

    git@git.corneltek.com:php-jenkins-template.git

Jenkins
-------

Steps:

1. Install Java, Setup Jenkins on remote server
2. Install PHP build tools, and Ant build system (apache-ant, ant-contrib) on local machine and remote machine.
3. Setup build.xml for ANT, and phpdox.xml, phpunit.xml (phpunit-ci.xml) configuration files.
4. Check `ant build` works.
5. Let Jenkins execute `ant build` to run build tasks.

### Requirements

#### Oracle Java

For Ubuntu Linux:

    curl https://raw.github.com/flexiondotorg/oab-java6/master/oab-java6.sh | bash
    apt-get install sun-java6-bin \
        sun-java6-jdk \
        sun-java6-jre sun-java6-plugin \
        sun-java6-source

Update default java:

    update-alternatives --config java
    update-alternatives --config javac

For Gentoo Linux:

    emerge dev-java/oracle-jdk-bin

### Install Apache ANT Build Tool


For Gentoo Linux:

    emerge dev-java/ant
    emerge dev-java/ant-contrib

Install Manually

Apache Ant

    wget http://apache.cdpa.nsysu.edu.tw//ant/binaries/apache-ant-1.8.3-bin.zip
    unzip apache-ant-1.8.3-bin.zip
    cd apache-ant-1.8.3
    export ANT_HOME=$(pwd)

Apache ANT contrib

Download Ant-contrib.jar

    wget "http://downloads.sourceforge.net/project/ant-contrib/ant-contrib/1.0b3/ant-contrib-1.0b3-bin.zip?r=http%3A%2F%2Fwww.google.com%2Furl%3Fsa%3Dt%26rct%3Dj%26q%3D%26esrc%3Ds%26source%3Dweb%26cd%3D4%26ved%3D0CGMQjBAwAw%26url%3Dhttp%253A%252F%252Fsourceforge.net%252Fprojects%252Fant-contrib%252Ffiles%252Flatest%252Fdownload%26ei%3DRBS6T4rwBIibmQXw58i2Cw%26usg%3DAFQjCNGPB2u9BKpIrnnihcfJ-chbtyIz4w%26sig2%3DbVfRj1UUaFWw2ZyQAl39-w&ts=1337594973&use_mirror=nchc"

Put the ant-contrib.jar into ANT_HOME/lib or java lib path.

#### Debian packages

debian packages for running tests and static analysis

    apt-get install imagemagick
    apt-get install php5-xdebug php5-svn php5-intl php5-mcrypt php5-geoip \
        php5-fpm php5-pgsql php5-sqlite php5-mysql php5-gd php5-curl php5-cli \
        php5-imagick
    apt-get install php5-xsl
    apt-get install ant ant-contrib
    apt-get install graphviz-dev graphviz

#### PEAR packages for CI environment

PEAR packages for running tests and static analysis

    pear config-set auto_discover 1
    pear update-channels
    pear channel-discover pear.phpunit.de
    pear channel-discover components.ez.no
    pear channel-discover pear.symfony-project.com
    pear channel-discover pear.pdepend.org
    pear channel-discover pear.phpmd.org
    pear channel-discover pear.pdepend.org
    pear install -a -f PHP_CodeSniffer
    pear install -a -f phpunit/PHPUnit
    pear install -a -f pdepend/PHP_Depend-beta
    pear install -a -f phpmd/PHP_PMD
    pear install -a -f pear.phpunit.de/phpcpd
    pear install -a -f pear.phpunit.de/phploc
    pear install -a -f pear.phpunit.de/PHP_CodeBrowser
    pear install -a -f pear.netpirates.net/phpDox
    
#### Jenkins

Jenkins PHP: http://jenkins-php.org/

Download jenkins.war.

    http://mirrors.jenkins-ci.org/war/latest/jenkins.war

Start jenkins and download `jenkins-cli`:

    java -jar jenkins.war

    wget http://localhost:8080/jnlpJars/jenkins-cli.jar

    java -jar jenkins-cli.jar -s http://localhost:8080 install-plugin \
        checkstyle cloverphp dry htmlpublisher jdepend plot pmd violations xunit

    java -jar jenkins-cli.jar -s http://localhost:8080 safe-restart

### Configure Apache ANT build.xml for Jenkins Job

#### Download build.xml

Download build.xml in your project directory:

    wget http://jenkins-php.org/download/build.xml

Add this line to build.xml, to use tasks from ant-contrib

    <taskdef resource="net/sf/antcontrib/antlib.xml"/>

Configure git for jenkins:

    git config --global user.email "jenkins@corneltek.com"
    git config --global user.name "Jenkins"

#### Configure PHPUnit

copy phpunit.xml to phpunit-ci.xml

Add this logging configuration:

    <logging>
        <log type="coverage-html" target="build/coverage" title="Name of Project"
            charset="UTF-8" yui="true" highlight="true"
            lowUpperBound="35" highLowerBound="70"/>
        <log type="coverage-clover" target="build/logs/clover.xml"/>
        <log type="junit" target="build/logs/junit.xml"
            logIncompleteSkipped="false"/>
    </logging>

#### Configure phpDox

Init phpdox:

    phpdox --skel > phpdox.xml

Configuration:

    <phpdox xmlns="http://phpdox.de/config">
        <project name="name-of-project" source="src" workdir="build/phpdox">
            <collector publiconly="false">
                <include mask="*.php" />
                <exclude mask="*Autoload.php" />
            </collector>

            <generator output="build">
                <build engine="html" enabled="true" output="api"/>
            </generator>
        </project>
    </phpdox>

#### Check build

Run `ant build` to make sure it works:

    ant build


#### Add Task to Jenkin

Poll SCM, schedule:

    */15 * * * * 

#### Reference

- https://wiki.jenkins-ci.org/display/JENKINS/Jenkins+and+PHP


Jenkins API

- http://build.corneltek.com:8080/api/?
- http://build.corneltek.com:8080/job/SQLBuilder/11/console

Jenkins Notification Plugin

https://wiki.jenkins-ci.org/display/JENKINS/Notification+Plugin
