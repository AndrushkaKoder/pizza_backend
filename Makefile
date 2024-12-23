up:
	./vendor/bin/sail up -d

migrate:
	./vendor/bin/sail artisan migrate
	./vendor/bin/sail artisan optimize:clear

down:
	./vendor/bin/sail down

build: #for fast start
	./vendor/bin/sail up -d
	./vendor/bin/sail artisan migrate:fresh
	./vendor/bin/sail artisan app:parse
	./vendor/bin/sail artisan db:seed
	./vendor/bin/sail artisan optimize:clear

test:
	./vendor/bin/sail artisan test
