# Unofficial PHP library for interacting with the Multichain JsonRPC interface

This library provides a simple way to work with Multichain JsonRPC interface. The entire API is implemented and the tests contain easy to follow examples on performing most of the operations. 

[![Build Status](https://travis-ci.org/Kunstmaan/libphp-multichain.svg?branch=master)](https://travis-ci.org/Kunstmaan/libphp-multichain)
[![Latest Stable Version](https://poser.pugx.org/kunstmaan/libphp-multichain/v/stable)](https://packagist.org/packages/kunstmaan/libphp-multichain)
[![Latest Unstable Version](https://poser.pugx.org/kunstmaan/libphp-multichain/v/unstable)](https://packagist.org/packages/kunstmaan/libphp-multichain)
[![Total Downloads](https://poser.pugx.org/kunstmaan/libphp-multichain/downloads)](https://packagist.org/packages/kunstmaan/libphp-multichain)
[![Monthly Downloads](https://poser.pugx.org/kunstmaan/libphp-multichain/d/monthly)](https://packagist.org/packages/kunstmaan/libphp-multichain)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Kunstmaan/libphp-multichain/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Kunstmaan/libphp-multichain/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/Kunstmaan/libphp-multichain/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Kunstmaan/libphp-multichain/?branch=master)
[![License](https://poser.pugx.org/kunstmaan/libphp-multichain/license)](https://packagist.org/packages/kunstmaan/libphp-multichain)

## Example usage

    $client = MultichainClient("http://<chainurl>:8000", 'multichainrpc', '79pgKQusiH3VDVpyzsM6e3kRz6gWNctAwgJvymG3iiuz', 3);
    $address = $client->setDebug(true)->getNewAddress();

See more examples and documentation in the tests folder.

## Installing libphp-multichain

The recommended way to install this library through Composer. Run the Composer command to install the latest stable version:

    composer require kunstmaan/libphp-multichain

## Need a development Blockchain and like Docker?

Take a look at [these Docker images](https://github.com/Kunstmaan/docker-multichain) we have built.
