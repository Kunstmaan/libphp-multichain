# docker-multichain

This is the repository for the kunstmaan/*-multichain docker images.

## Images

* [kunstmaan/base-multichain](https://hub.docker.com/r/kunstmaan/base-multichain/): A base Ubuntu with the latest Multichain deamon installed
* [kunstmaan/master-multichain](https://hub.docker.com/r/kunstmaan/master-multichain/): Based on the "base" image running a master node, creates a blockchain and runs it. *Important: only for development since any node can connect, anyone can administer and the RPC interface is open to all.*
* [kunstmaan/node-multichain](https://hub.docker.com/r/kunstmaan/node-multichain/): Based on the same "base" image and connects to the master node
* [kunstmaan/explorer-multichain](https://hub.docker.com/r/kunstmaan/explorer-multichain/): A node with the Multichain explorer installed

## Running the cluster

Use this [docker-compose.yml](https://github.com/Kunstmaan/docker-multichain/blob/master/docker-compose.yml) and run:

```
sudo docker-compose up
```


## Persisting your chain

Add a volume

<somewhere>:/root/.multichain

## Configuration

### Masternode

To configure your chain, we use environment variables.

#### Required

* CHAINNAME: DockerChain
* NETWORK_PORT: 7447       # also expose this port!
* RPC_PORT: 8000           # also expose this port!
* RPC_USER: multichainrpc
* RPC_PASSWORD: 79pgKQusiH3VDVpyzsM6e3kRz6gWNctAwgJvymG3iiuz
* RPC_ALLOW_IP: 0.0.0.0/0.0.0.0

#### Optional

* PARAM_<something descriptive>='<variable>|<value>' e.g: `PARAM_TARGET_BLOCK_SIZE='target-block-time|15'`

These variables can be set:

```
# Basic chain parameters

chain-protocol = multichain             # Chain protocol: multichain (permissions, native assets) or bitcoin
chain-is-testnet = false                # Content of the 'testnet' field of API responses, for compatibility.
target-block-time = 15                  # Target time between blocks (transaction confirmation delay), seconds. (5 - 86400)
maximum-block-size = 1000000            # Maximum block size in bytes. (1000 - 1000000000)

# Global permissions

anyone-can-connect = false              # Anyone can connect, i.e. a publicly readable blockchain.
anyone-can-send = false                 # Anyone can send, i.e. transaction signing not restricted by address.
anyone-can-receive = false              # Anyone can receive, i.e. transaction outputs not restricted by address.
anyone-can-issue = false                # Anyone can issue new native assets.
anyone-can-mine = false                 # Anyone can mine blocks (confirm transactions).
anyone-can-activate = false             # Anyone can grant or revoke connect, send and receive permissions.
anyone-can-admin = false                # Anyone can grant or revoke all permissions.
allow-p2sh-outputs = true               # Allow pay-to-scripthash (P2SH) scripts, often used for multisig.
allow-multisig-outputs = true           # Allow bare multisignature scripts, rarely used but still supported.

# Consensus requirements

setup-first-blocks = 60                 # Length of initial setup phase in blocks, in which mining-diversity,
                                        # admin-consensus-* and mining-requires-peers are not applied. (1 - 31536000)
mining-diversity = 0.3                  # Miners must wait <mining-diversity>*<active miners> between blocks. (0 - 1)
admin-consensus-admin = 0.5             # <admin-consensus-admin>*<active admins> needed to change admin perms. (0 - 1)
admin-consensus-activate = 0.5          # <admin-consensus-activate>*<active admins> to change activate perms. (0 - 1)
admin-consensus-mine = 0.5              # <admin-consensus-mine>*<active admins> to change mining permissions. (0 - 1)
admin-consensus-issue = 0.0             # <admin-consensus-issue>*<active admins> to change issue permissions. (0 - 1)
mining-requires-peers = true            # Default for whether nodes only mine blocks if connected to other nodes.

# Native blockchain currency (likely not required)

initial-block-reward = 0                # Initial block mining reward in raw native currency units. (0 - 1000000000000000000)
first-block-reward = -1                 # Different mining reward for first block only, ignored if negative. (-1 - 1000000000000000000)
reward-halving-interval = 52560000      # Interval for halving of mining rewards, in blocks. (60 - 4294967295)
reward-spendable-delay = 1              # Delay before mining reward can be spent, in blocks. (1 - 100000)
minimum-per-output = 0                  # Minimum native currency per output (anti-dust), in raw units.
                                        # If set to -1, this is calculated from minimum-relay-fee. (-1 - 1000000000)
maximum-per-output = 100000000000000    # Maximum native currency per output, in raw units. (0 - 1000000000000000000)
minimum-relay-fee = 0                   # Minimum transaction fee, in raw units of native currency. (0 - 1000000000)
native-currency-multiple = 100000000    # Number of raw units of native currency per display unit. (0 - 1000000000)

# Advanced mining parameters

skip-pow-check = false                  # Skip checking whether block hashes demonstrate proof of work.
pow-minimum-bits = 16                   # Initial and minimum proof of work difficulty, in leading zero bits. (1 - 32)
target-adjust-freq = 86400              # Interval between proof of work difficulty adjustments, in seconds. (3600 - 4294967295)
allow-min-difficulty-blocks = false     # Allow lower difficulty blocks if none after 2*<target-block-time>.

# Standard transaction definitions

only-accept-std-txs = true              # Only accept and relay transactions which qualify as 'standard'.
max-std-tx-size = 100000                # Maximum size of standard transactions, in bytes. (1024 - 10000000)
max-std-op-return-size = 4096           # Maximum size of OP_RETURN metadata in standard transactions, in bytes. (0 - 8388608)
max-std-op-drops-count = 5              # Maximum number of OP_DROPs per output in standard transactions. (0 - 100)
max-std-element-size = 600              # Maximum size of data elements in standard transactions, in bytes. (128 - 32768)
```

### Slavenode

To configure your chain, we use environment variables.

#### Required

* CHAINNAME: DockerChain
* NETWORK_PORT: 7447       # also expose this port!
* RPC_PORT: 8000           # also expose this port!
* RPC_USER: multichainrpc
* RPC_PASSWORD: 79pgKQusiH3VDVpyzsM6e3kRz6gWNctAwgJvymG3iiuz
* RPC_ALLOW_IP: 0.0.0.0/0.0.0.0
* MASTERNODE: masternode   # IP address of the master node, or a docker compose link. Don't forget the links section!
