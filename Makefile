dev:
	php ./src/app/setup_db.php
	APP_DEBUG=1 php -S localhost:8000 -t ./src/app/public

prod:
	echo "NOT CONFIGURED"

test:
	echo "NOT CONFIGURED"