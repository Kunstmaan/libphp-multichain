#!/bin/bash
echo '{ "block_hash": "'$1'" }'
curl -sS -X POST --header 'Content-Type: application/json' --header 'Accept: application/json' -d '{ "block_hash": "'$1'" }' $APIURL
