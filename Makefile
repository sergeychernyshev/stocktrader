all:    testcoverage

test:
	phpunit --configuration tests/tests.xml

testcoverage:
	php -d zend_extension="/usr/lib/php/modules/xdebug.so" /usr/local/bin/phpunit --configuration tests/tests.xml --coverage-html code_coverage
