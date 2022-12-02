#!/bin/bash

# This is just a convenient script to run command in container
docker exec -it -u `id -u` advent_app "$@"