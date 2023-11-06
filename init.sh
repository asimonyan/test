#!/bin/bash

bin/console doctrine:migrations:migrate --no-interaction
bin/console app:init-data