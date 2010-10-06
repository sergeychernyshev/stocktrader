all:    testcoverage

test:
	phpunit tests

testcoverage:
	php -d zend_extension="/usr/lib/php/modules/xdebug.so" /usr/local/bin/phpunit --coverage-html code_coverage tests
