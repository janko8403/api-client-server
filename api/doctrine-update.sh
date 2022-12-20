#!/bin/bash
rm -rf data/cache/*
rm -rf data/DoctrineModule
rm -rf data/DoctrineORMModule
docker exec -it komfort_api_1 vendor/bin/doctrine-module orm:schema-tool:update --dump-sql > doctrine.sql