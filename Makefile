build:
	docker build -t "aoc-php" .
.PHONY: build

run:
	docker run -it --rm -v ${PWD}:/aoc -e "PHP_IDE_CONFIG=serverName=aoc" aoc-php
.PHONY: run

sh:
