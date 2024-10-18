start:
	@echo "Start Containers"
	./vendor/bin/sail up -d
	sleep 1
	./vendor/bin/sail ps

stop:
	@echo "Stop Containers"
	./vendor/bin/sail down
	sleep 1
	./vendor/bin/sail ps

restart: stop start

rebuild:
	@echo "Rebuilding Images"
	./vendor/bin/sail build --no-cache
	./vendor/bin/sail up
	sleep 1
	./vendor/bin/sail ps

rm: stop
	@echo "Remove Containers"
	./vendor/bin/sail rm
