<?php

namespace App\Services;

use App\Contracts\ERC721ServiceContract;
use App\Contracts\NounsServiceContract;
use Web3\Utils;

class BaseNounsService implements ERC721ServiceContract, NounsServiceContract {
    protected $web3;
    protected $auctionHouseContractAddress;
    protected $tokenContractAddress;
    protected $tokenContract;
    
    public function getMintEvent(int $tokenId): array 
    {
        $mintEvent = [];

        $filters = [
            'fromBlock' => '0x0',  // Start searching from the first block
            'toBlock' => 'latest', // Search until the latest block
            'address' => $this->tokenContractAddress, // The token contract address
            'topics' => [
                Utils::sha3('Transfer(address,address,uint256)'), // This is the topic for the Transfer event
                null, // The 'from' address, which is null for mint events (zero address)
                null, // The 'to' address, which we're not filtering by
                '0x' . str_pad(dechex($tokenId), 64, '0', STR_PAD_LEFT), // The token ID, padded to 32 bytes
            ],
        ];

        $this->web3->eth->getLogs($filters, function ($err, $logs) use (&$mintEvent) {
            if ($err) {
                throw new \Exception($err->getMessage());
            }

            if (count($logs) > 0) {
                $mintEvent = is_object($logs[0]) ? (array) $logs[0] : $logs[0];
            }
        });

        if (empty($mintEvent)) {
            throw new \Exception("No mint event found for token ID {$tokenId}");
        }

        return $mintEvent;
    }

    public function getBlock(string $blockNumber): array 
    {
        $blockDetails = [];

        $this->web3->eth->getBlockByNumber($blockNumber, true, function ($err, $block) use (&$blockDetails) {
            if ($err !== null) {
                throw new \Exception($err->getMessage());
            }

            $blockDetails = is_object($block) ? (array) $block : $block;
        });

        return $blockDetails;
    }

    public function getTotalSupply(): int
    {
        $totalSupply = 0;

        $this->tokenContract->call('totalSupply', [], function ($err, $result) use (&$totalSupply) {
            if ($err) {
                throw new \Exception($err->getMessage());
            }

            $resultBigInteger = $result[0] instanceof \phpseclib\Math\BigInteger ? $result[0] : null;
            
            if ($resultBigInteger) {
                $totalSupply = (int) $resultBigInteger->toString();
            }
        });

        return $totalSupply;
    }

    public function getTokenByIndex(int $index): ?int
    {
        $tokenId = null;

        $this->tokenContract->call('tokenByIndex', $index, function ($err, $result) use (&$tokenId) {
            if ($err) {
                throw new \Exception($err->getMessage());
            }

            $resultBigInteger = $result[0] instanceof \phpseclib\Math\BigInteger ? $result[0] : null;

            if ($resultBigInteger) {
                $tokenId = (int) $resultBigInteger->toString();
            }
        });

        return $tokenId;
    }

    public function getTokenURI(int $tokenId): ?string
    {
        $tokenURI = null;

        $this->tokenContract->call('tokenURI', $tokenId, function ($err, $result) use (&$tokenURI) {
            if ($err) {
                throw new \Exception($err->getMessage());
            }

            if (gettype($result[0] ?? null) == 'string') {
                $tokenURI = $result[0];
            }
        });

        return $tokenURI;
    }

    public function getSeeds(int $tokenId): array
    {
        $seeds = [];

        $this->tokenContract->call('seeds', $tokenId, function ($err, $result) use (&$seeds) {
            if ($err) {
                throw new \Exception($err->getMessage());
            }

            foreach ($result as $key => $value) {
                if ($value instanceof \phpseclib\Math\BigInteger) {
                    $seeds[$key] = (int) $value->toString();
                }
            }
        });

        return $seeds;
    }

    public function getAuctionLogs(string $blockNumber): array
    {
        $logs = [];

        $filters = [
            'fromBlock' => hexdec($blockNumber),
            'toBlock' => hexdec($blockNumber),
            'address' => $this->auctionHouseContractAddress,
            'topics' => [
                Utils::sha3('AuctionSettled(uint256,uint256,address)'),
                Utils::sha3('AuctionSettledWithClientId(uint256,uint32)')
            ],
        ];

        \Log::info('Auction logs filters: ' . json_encode($filters));
        

        $this->web3->eth->getLogs($filters, function ($err, $result) use (&$logs) {
            if ($err) {
                throw new \Exception($err->getMessage());
            }

            $logs = $result;
        });

        return $logs;
    }

    public function getTransaction(string $transactionHash): array
    {
        $transactionDetails = [];

        $this->web3->eth->getTransactionByHash($transactionHash, function ($err, $transaction) use (&$transactionDetails) {
            if ($err) {
                throw new \Exception($err->getMessage());
            }

            $transactionDetails = is_object($transaction) ? (array) $transaction : $transaction;
        });

        return $transactionDetails;
    }
}
