all: userbase

userbase:
	rm -rf __users_export
	svn export http://svn.github.com/sergeychernyshev/UserBase.git __users_export
	rm -rf users
	mv __users_export/users .
	rm -rf __users_export

test:
	phpunit tests

testcoverage:
	php -d zend_extension="/usr/lib/php/modules/xdebug.so" /usr/local/bin/phpunit --coverage-html code_coverage tests
