
init: install-onion
	git submodule init
	git submodule update
	php onion -q bundle

buildbot-test: init
	php -v
	cp -v config/database.yml.buildbot config/database.yml
	cp -v config/framework.yml.buildbot config/framework.yml
	php scripts/phifty.php build-conf
	php main.php
	lazy build-schema 
	lazy build-sql
	php scripts/phifty.php export
	phpunit --coverage-html build/tests_coverage --colors tests/Phifty

install-onion:
	curl -O https://raw.github.com/c9s/Onion/master/onion
	chmod +x onion

install:
	bash scripts/compile.sh
	chmod +x phifty.phar
	cp phifty.phar /usr/bin/

doc: force
	phpwiki doc build/wiki
	doxygen 

sync:
	git remote update --prune
	git pull origin HEAD
	git push origin HEAD
	git gc --aggressive --prune=now

test:
	phpunit --coverage-html build/tests_coverage --colors tests

clean:
	find cache -type f -exec rm -v {} \;
	rm -rf build

coffee:
	coffee -c applications/Core/assets/action-js \
			applications/Core/assets/region-js \
			plugins/AdminUI/assets \
			plugins/CRUD/assets

clean-upload:
	rm -rf webroot/static/upload/*
	chmod -R og+rw webroot/static/upload

force: ;

