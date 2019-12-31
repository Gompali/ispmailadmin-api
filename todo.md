# docker images for:
    - dev = symfony local server with latest fpm and min tool pack
    - prod = apache-alpine

# Makefile
    - makefile should help launch dependencies, build dev, launch env

# CI config
    - ci should build test env, run tests and build docker prod image, then push to registry