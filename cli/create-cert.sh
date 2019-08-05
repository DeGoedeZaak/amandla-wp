#!/bin/bash

set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[0;93m'
NC='\033[0m'


OS=`uname -s`

if [ "$OS" = "Darwin" ]; then
    openssl req \
        -newkey rsa:2048 \
        -x509 \
        -nodes \
        -keyout myapp.local.key \
        -new \
        -out myapp.local.crt \
        -subj /CN=myapp.local \
        -reqexts SAN \
        -extensions SAN \
        -config <(cat /System/Library/OpenSSL/openssl.cnf \
            <(printf '[SAN]\nsubjectAltName=DNS:myapp.local')) \
        -sha256 \
        -days 3650

elif [ "$OS" = "Linux" ]; then
    openssl req \
        -newkey rsa:2048 \
        -x509 \
        -nodes \
        -keyout myapp.local.key \
        -new \
        -out myapp.local.crt \
        -subj /CN=myapp.local \
        -reqexts SAN \
        -extensions SAN \
        -config <(cat /usr/lib/ssl/openssl.cnf \
            <(printf '[SAN]\nsubjectAltName=DNS:myapp.local')) \
        -sha256 \
        -days 3650
else
    echo "Failed to identify this OS"
    exit -1
fi

openssl req \
    -newkey rsa:2048 \
    -x509 \
    -nodes \
    -keyout amandla.local.key \
    -new \
    -out amandla.local.crt \
    -subj /CN=amandla.local \
    -reqexts SAN \
    -extensions SAN \
    -config <(cat /usr/lib/ssl/openssl.cnf \
        <(printf '[SAN]\nsubjectAltName=DNS:amandla.local')) \
    -sha256 \
    -days 3650

mkdir -p ../certs

mv *.crt ../certs/
mv *.key ../certs/

echo  -e ${GREEN}"Cert created in /cert! ${NC}";
