About HttpServer
================

Nginx
-----

Setup client max body size:

    http {
        client_max_body_size 12M;
    }

Create `/etc/init.d/php-fastcgi` file:

    #!/bin/bash
    BIND=127.0.0.1:9000
    USER=www-data
    PHP_FCGI_CHILDREN=15
    PHP_FCGI_MAX_REQUESTS=1000
     
    # PHP_CGI=/usr/bin/php-cgi
    PHP_CGI=/opt/phpbrew/php/php-5.4.5/bin/php-cgi
    PHP_CGI_NAME=`basename $PHP_CGI`
    PHP_CGI_ARGS="- USER=$USER PATH=/usr/bin PHP_FCGI_CHILDREN=$PHP_FCGI_CHILDREN PHP_FCGI_MAX_REQUESTS=$PHP_FCGI_MAX_REQUESTS $PHP_CGI -b $BIND"
    RETVAL=0
     
    start() {
          echo -n "Starting PHP FastCGI: "
          start-stop-daemon --quiet --start --background --chuid "$USER" --exec /usr/bin/env -- $PHP_CGI_ARGS
          RETVAL=$?
          echo "$PHP_CGI_NAME."
    }
    stop() {
          echo -n "Stopping PHP FastCGI: "
          killall -q -w -u $USER $PHP_CGI
          RETVAL=$?
          echo "$PHP_CGI_NAME."
    }
     
    case "$1" in
        start)
          start
      ;;
        stop)
          stop
      ;;
        restart)
          stop
          start
      ;;
        *)
          echo "Usage: php-fastcgi {start|stop|restart}"
          exit 1
      ;;
    esac
    exit $RETVAL


Enable your fastcgi in nginx

    server {
        listen 80;
        server_name drshine.corneltek.com;
        root /home/drshine/system/webroot;
    
        access_log  /var/log/nginx/drshine.access.log;
        error_log   /var/log/nginx/drshine.error.log;
    
        location / {
            if (!-e $request_filename) {
                rewrite ^/(.*)$ /index.php/$1 last;
            }
        }
    
        fastcgi_intercept_errors on;
    
        location ~ \.php {
            include /etc/nginx/fastcgi_params;
    
            fastcgi_split_path_info ^(.+\.php)(/.+)$;
            fastcgi_param   SCRIPT_FILENAME    $document_root$fastcgi_script_name;
            fastcgi_param	SCRIPT_NAME		$fastcgi_script_name;
            fastcgi_param   PATH_INFO          $fastcgi_path_info;
            fastcgi_pass   127.0.0.1:9000;
    
            # if you're using socket
            # fastcgi_pass unix:/var/run/fcgiwrap.sock;
            fastcgi_index index.php;
        }
    }
