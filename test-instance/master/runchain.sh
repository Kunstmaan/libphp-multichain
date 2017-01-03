#!/bin/bash

# Fallback for the $CHAINNAME variable
if [ -z "$CHAINNAME" ]; then
    CHAINNAME='DockerChain'
fi

# Fallback for the $NETWORK_PORT variable
if [ -z "$NETWORK_PORT" ]; then
    NETWORK_PORT=7447
fi

# Fallback for the $RPC_PORT variable
if [ -z "$RPC_PORT" ]; then
    RPC_PORT=8000
fi

# Fallback for the $RPC_USER variable
if [ -z "$RPC_USER" ]; then
    RPC_USER="multichainrpc"
fi

# Fallback for the $RPC_PASSWORD variable
if [ -z "$RPC_PASSWORD" ]; then
    RPC_PASSWORD="this-is-insecure-change-it"
fi

# Fallback for the $RPC_ALLOW_IP variable
if [ -z "$RPC_ALLOW_IP" ]; then
    RPC_ALLOW_IP="0.0.0.0/0.0.0.0"
fi

# Create the chain if it is not there yet
if [ ! -d /root/.multichain/$CHAINNAME ]; then
    multichain-util create $CHAINNAME

    # Set some required parameters in the params.dat
    sed -i "s/^default-network-port.*/default-network-port = $NETWORK_PORT/" /root/.multichain/$CHAINNAME/params.dat
    sed -i "s/^default-rpc-port.*/default-rpc-port = $RPC_PORT/" /root/.multichain/$CHAINNAME/params.dat
    sed -i "s/^chain-name.*/chain-name = $CHAINNAME/" /root/.multichain/$CHAINNAME/params.dat
    sed -i "s/^chain-description.*/chain-description = MultiChain $CHAINNAME/" /root/.multichain/$CHAINNAME/params.dat

    # Loop over all variables that start with PARAM_
    #   PARAM_BLOCKTIME='target-block-time|40';
    #   PARAM_CONNECT='anyone-can-connect|true';
    ( set -o posix ; set ) | sed -n '/^PARAM_/p' | while read PARAM; do
        IFS='=' read -ra KV <<< "$PARAM"
        IFS='|' read -ra KV <<< "${!KV[0]}"
        sed -i "s/^${KV[0]}.*/${KV[0]} = ${KV[1]}/" /root/.multichain/$CHAINNAME/params.dat
    done

fi

cat /root/.multichain/$CHAINNAME/params.dat

cat << EOF > /root/.multichain/$CHAINNAME/multichain.conf
rpcuser=$RPC_USER
rpcpassword=$RPC_PASSWORD
rpcallowip=$RPC_ALLOW_IP
rpcport=$RPC_PORT
EOF

if [ ! -z "$BLOCKNOTIFY_SCRIPT" ]; then
    echo "blocknotify=$BLOCKNOTIFY_SCRIPT %s" >> /root/.multichain/$CHAINNAME/multichain.conf
fi

cp /root/.multichain/$CHAINNAME/multichain.conf /root/.multichain/multichain.conf

multichaind -txindex -shrinkdebugfilesize -printtoconsole $CHAINNAME
