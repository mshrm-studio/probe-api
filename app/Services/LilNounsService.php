<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Web3\Web3;
use Web3\Contract;

class LilNounsService {
    protected $web3;
    protected $contract;

    public function __construct(Web3 $web3) {
        $this->web3 = $web3;

        $seconds = 300;

        $abi = Cache::remember('lil-nouns-abi-v2', $seconds, function () {
            $abiUrl = Storage::url('lils/lil-nouns-contract-abi-v2.json');
            // \Log::info('__construct, abiUrl: ' . $abiUrl);
            $response = Http::get($abiUrl);
            return $response->json();
        });

        $this->contract = new Contract($web3->provider, json_encode($abi));
        // \Log::info('__construct, contract');
        $contractAddress = config('services.lil_nouns_contract.address');
        // \Log::info('__construct, contractAddress: ' . $contractAddress);
        $this->contract->at($contractAddress);
        // \Log::info('__construct, contract->at()');
    }

    public function getTotalSupply(): int
    {
        $totalSupply = 0;
        // \Log::info('getTotalSupply handle()');

        $this->contract->call('totalSupply', [], function ($err, $result) use (&$totalSupply) {
            if ($err) {
                throw new \Exception($err->getMessage());
            }

            // \Log::info('getTotalSupply(), Total Supply Result:', ['result' => $result]);

            $resultBigInteger = $result[0] instanceof \phpseclib\Math\BigInteger ? $result[0] : null;
            
            if ($resultBigInteger) {
                $totalSupply = (int) $resultBigInteger->toString();
                // \Log::info('getTotalSupply(), Updated totalSupply:', ['totalSupply' => $totalSupply]);
            }
        });

        // \Log::info('getTotalSupply(), returning $totalSupply');

        return $totalSupply;
    }

    public function getTokenByIndex(int $index): ?int
    {
        $tokenId = null;

        // \Log::info('getTokenByIndex() index type: ' . gettype($index));

        $this->contract->call('tokenByIndex', $index, function ($err, $result) use (&$tokenId) {
            if ($err) {
                throw new \Exception($err->getMessage());
            }

            // \Log::info('getTokenByIndex(), Token ID Result:', ['result' => $result]);

            $resultBigInteger = $result[0] instanceof \phpseclib\Math\BigInteger ? $result[0] : null;

            if ($resultBigInteger) {
                $tokenId = (int) $resultBigInteger->toString();
                // \Log::info('getTokenByIndex(), Updated tokenId:', ['tokenId' => $tokenId]);
            }
        });

        // \Log::info('getTokenByIndex(), returning $tokenId: ' . $tokenId);

        return $tokenId;
    }

    public function getTokenURI(int $tokenId): ?string
    {
        $tokenURI = null;

        \Log::info('getTokenURI() tokenId: ' . gettype($tokenId));

        $this->contract->call('tokenURI', $tokenId, function ($err, $result) use (&$tokenURI) {
            if ($err) {
                throw new \Exception($err->getMessage());
            }

            \Log::info('getTokenURI(), tokenURI Result:', ['result' => $result]);

            \Log::info('gettype($result[0] ?? null):', ['type' => gettype($result[0] ?? null)]);

            if (gettype($result[0] ?? null) == 'string') {
                $tokenURI = $result[0];
            }
        });

        \Log::info('getTokenURI(), returning $tokenURI:', ['tokenURI' => $tokenURI]);

        return $tokenURI;
    }

    public function getSeeds(int $tokenId): array
    {
        $seeds = [];

        // \Log::info('getSeeds() tokenId: ' . gettype($tokenId));

        $this->contract->call('seeds', $tokenId, function ($err, $result) use (&$seeds) {
            if ($err) {
                throw new \Exception($err->getMessage());
            }

            // \Log::info('getSeeds(), seeds Result:', ['result' => $result]);

            foreach ($result as $key => $value) {
                // \Log::info('getSeeds(), seeds key/value:', [
                //     'key' => $key,
                //     'value' => $value
                // ]);

                if ($value instanceof \phpseclib\Math\BigInteger) {
                    $seeds[$key] = (int) $value->toString();
                }
            }
        });

        // \Log::info('getSeeds(), returning $seeds:', $seeds);

        return $seeds;
    }
}
