<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\LilNoun;

class SyncLilNounTokenSeedNames implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $lilNouns = LilNoun::query()
            ->whereNull('background_name')
            ->orWhereNull('body_name')
            ->orWhereNull('accessory_name')
            ->orWhereNull('head_name')
            ->orWhereNull('glasses_name')
            ->limit(25)
            ->get();

        $traits = collect([
            [ "layer" => "background", "seedId" => 0, "trait" => "d5d7e1" ],
            [ "layer" => "background", "seedId" => 1, "trait" => "e1d7d5" ],
            [ "layer" => "head", "seedId" => 0, "trait" => "aardvark" ],
            [ "layer" => "head", "seedId" => 1, "trait" => "abstract" ],
            [ "layer" => "head", "seedId" => 2, "trait" => "ape" ],
            [ "layer" => "head", "seedId" => 3, "trait" => "bag" ],
            [ "layer" => "head", "seedId" => 4, "trait" => "bagpipe" ],
            [ "layer" => "head", "seedId" => 5, "trait" => "banana" ],
            [ "layer" => "head", "seedId" => 6, "trait" => "bank" ],
            [ "layer" => "head", "seedId" => 7, "trait" => "baseball-gameball" ],
            [ "layer" => "head", "seedId" => 8, "trait" => "basketball" ],
            [ "layer" => "head", "seedId" => 9, "trait" => "bat" ],
            [ "layer" => "head", "seedId" => 10, "trait" => "bear" ],
            [ "layer" => "head", "seedId" => 11, "trait" => "beer" ],
            [ "layer" => "head", "seedId" => 12, "trait" => "beet" ],
            [ "layer" => "head", "seedId" => 13, "trait" => "bell" ],
            [ "layer" => "head", "seedId" => 14, "trait" => "bigfoot-yeti" ],
            [ "layer" => "head", "seedId" => 15, "trait" => "bigfoot" ],
            [ "layer" => "head", "seedId" => 16, "trait" => "blackhole" ],
            [ "layer" => "head", "seedId" => 17, "trait" => "blueberry" ],
            [ "layer" => "head", "seedId" => 18, "trait" => "bomb" ],
            [ "layer" => "head", "seedId" => 19, "trait" => "bonsai" ],
            [ "layer" => "head", "seedId" => 20, "trait" => "boombox" ],
            [ "layer" => "head", "seedId" => 21, "trait" => "boot" ],
            [ "layer" => "head", "seedId" => 22, "trait" => "box" ],
            [ "layer" => "head", "seedId" => 23, "trait" => "boxingglove" ],
            [ "layer" => "head", "seedId" => 24, "trait" => "brain" ],
            [ "layer" => "head", "seedId" => 25, "trait" => "bubble-speech" ],
            [ "layer" => "head", "seedId" => 26, "trait" => "bubblegum" ],
            [ "layer" => "head", "seedId" => 27, "trait" => "burger-dollarmenu" ],
            [ "layer" => "head", "seedId" => 28, "trait" => "cake" ],
            [ "layer" => "head", "seedId" => 29, "trait" => "calculator" ],
            [ "layer" => "head", "seedId" => 30, "trait" => "calendar" ],
            [ "layer" => "head", "seedId" => 31, "trait" => "camcorder" ],
            [ "layer" => "head", "seedId" => 32, "trait" => "cannedham" ],
            [ "layer" => "head", "seedId" => 33, "trait" => "car" ],
            [ "layer" => "head", "seedId" => 34, "trait" => "cash-register" ],
            [ "layer" => "head", "seedId" => 35, "trait" => "cassettetape" ],
            [ "layer" => "head", "seedId" => 36, "trait" => "cat" ],
            [ "layer" => "head", "seedId" => 37, "trait" => "cd" ],
            [ "layer" => "head", "seedId" => 38, "trait" => "chain" ],
            [ "layer" => "head", "seedId" => 39, "trait" => "chainsaw" ],
            [ "layer" => "head", "seedId" => 40, "trait" => "chameleon" ],
            [ "layer" => "head", "seedId" => 41, "trait" => "chart-bars" ],
            [ "layer" => "head", "seedId" => 42, "trait" => "cheese" ],
            [ "layer" => "head", "seedId" => 43, "trait" => "chefhat" ],
            [ "layer" => "head", "seedId" => 44, "trait" => "cherry" ],
            [ "layer" => "head", "seedId" => 45, "trait" => "chicken" ],
            [ "layer" => "head", "seedId" => 46, "trait" => "chilli" ],
            [ "layer" => "head", "seedId" => 47, "trait" => "chipboard" ],
            [ "layer" => "head", "seedId" => 48, "trait" => "chips" ],
            [ "layer" => "head", "seedId" => 49, "trait" => "chocolate" ],
            [ "layer" => "head", "seedId" => 50, "trait" => "cloud" ],
            [ "layer" => "head", "seedId" => 51, "trait" => "clover" ],
            [ "layer" => "head", "seedId" => 52, "trait" => "clutch" ],
            [ "layer" => "head", "seedId" => 53, "trait" => "coffeebean" ],
            [ "layer" => "head", "seedId" => 54, "trait" => "cone" ],
            [ "layer" => "head", "seedId" => 55, "trait" => "console-handheld" ],
            [ "layer" => "head", "seedId" => 56, "trait" => "cookie" ],
            [ "layer" => "head", "seedId" => 57, "trait" => "cordlessphone" ],
            [ "layer" => "head", "seedId" => 58, "trait" => "cottonball" ],
            [ "layer" => "head", "seedId" => 59, "trait" => "cow" ],
            [ "layer" => "head", "seedId" => 60, "trait" => "crab" ],
            [ "layer" => "head", "seedId" => 61, "trait" => "crane" ],
            [ "layer" => "head", "seedId" => 62, "trait" => "croc-hat" ],
            [ "layer" => "head", "seedId" => 63, "trait" => "crown" ],
            [ "layer" => "head", "seedId" => 64, "trait" => "crt-bsod" ],
            [ "layer" => "head", "seedId" => 65, "trait" => "crystalball" ],
            [ "layer" => "head", "seedId" => 66, "trait" => "diamond-blue" ],
            [ "layer" => "head", "seedId" => 67, "trait" => "diamond-red" ],
            [ "layer" => "head", "seedId" => 68, "trait" => "dictionary" ],
            [ "layer" => "head", "seedId" => 69, "trait" => "dino" ],
            [ "layer" => "head", "seedId" => 70, "trait" => "dna" ],
            [ "layer" => "head", "seedId" => 71, "trait" => "dog" ],
            [ "layer" => "head", "seedId" => 72, "trait" => "doughnut" ],
            [ "layer" => "head", "seedId" => 73, "trait" => "drill" ],
            [ "layer" => "head", "seedId" => 74, "trait" => "duck" ],
            [ "layer" => "head", "seedId" => 75, "trait" => "ducky" ],
            [ "layer" => "head", "seedId" => 76, "trait" => "earth" ],
            [ "layer" => "head", "seedId" => 77, "trait" => "egg" ],
            [ "layer" => "head", "seedId" => 78, "trait" => "faberge" ],
            [ "layer" => "head", "seedId" => 79, "trait" => "factory-dark" ],
            [ "layer" => "head", "seedId" => 80, "trait" => "fan" ],
            [ "layer" => "head", "seedId" => 81, "trait" => "fence" ],
            [ "layer" => "head", "seedId" => 82, "trait" => "film-35mm" ],
            [ "layer" => "head", "seedId" => 83, "trait" => "film-strip" ],
            [ "layer" => "head", "seedId" => 84, "trait" => "fir" ],
            [ "layer" => "head", "seedId" => 85, "trait" => "firehydrant" ],
            [ "layer" => "head", "seedId" => 86, "trait" => "flamingo" ],
            [ "layer" => "head", "seedId" => 87, "trait" => "flower" ],
            [ "layer" => "head", "seedId" => 88, "trait" => "fox" ],
            [ "layer" => "head", "seedId" => 89, "trait" => "frog" ],
            [ "layer" => "head", "seedId" => 90, "trait" => "garlic" ],
            [ "layer" => "head", "seedId" => 91, "trait" => "gavel" ],
            [ "layer" => "head", "seedId" => 92, "trait" => "ghost-B" ],
            [ "layer" => "head", "seedId" => 93, "trait" => "big" ],
            [ "layer" => "head", "seedId" => 94, "trait" => "gnome" ],
            [ "layer" => "head", "seedId" => 95, "trait" => "goat" ],
            [ "layer" => "head", "seedId" => 96, "trait" => "goldcoin" ],
            [ "layer" => "head", "seedId" => 97, "trait" => "goldfish" ],
            [ "layer" => "head", "seedId" => 98, "trait" => "grouper" ],
            [ "layer" => "head", "seedId" => 99, "trait" => "hair" ],
            [ "layer" => "head", "seedId" => 100, "trait" => "hardhat" ],
            [ "layer" => "head", "seedId" => 101, "trait" => "heart" ],
            [ "layer" => "head", "seedId" => 102, "trait" => "helicopter" ],
            [ "layer" => "head", "seedId" => 103, "trait" => "highheel" ],
            [ "layer" => "head", "seedId" => 104, "trait" => "hockeypuck" ],
            [ "layer" => "head", "seedId" => 105, "trait" => "horse-deepfried" ],
            [ "layer" => "head", "seedId" => 106, "trait" => "hotdog" ],
            [ "layer" => "head", "seedId" => 107, "trait" => "house" ],
            [ "layer" => "head", "seedId" => 108, "trait" => "icepop-b" ],
            [ "layer" => "head", "seedId" => 109, "trait" => "igloo" ],
            [ "layer" => "head", "seedId" => 110, "trait" => "island" ],
            [ "layer" => "head", "seedId" => 111, "trait" => "jellyfish" ],
            [ "layer" => "head", "seedId" => 112, "trait" => "jupiter" ],
            [ "layer" => "head", "seedId" => 113, "trait" => "kangaroo" ],
            [ "layer" => "head", "seedId" => 114, "trait" => "ketchup" ],
            [ "layer" => "head", "seedId" => 115, "trait" => "laptop" ],
            [ "layer" => "head", "seedId" => 116, "trait" => "lightning-bolt" ],
            [ "layer" => "head", "seedId" => 117, "trait" => "lint" ],
            [ "layer" => "head", "seedId" => 118, "trait" => "lips" ],
            [ "layer" => "head", "seedId" => 119, "trait" => "lipstick2" ],
            [ "layer" => "head", "seedId" => 120, "trait" => "lock" ],
            [ "layer" => "head", "seedId" => 121, "trait" => "macaroni" ],
            [ "layer" => "head", "seedId" => 122, "trait" => "mailbox" ],
            [ "layer" => "head", "seedId" => 123, "trait" => "maze" ],
            [ "layer" => "head", "seedId" => 124, "trait" => "microwave" ],
            [ "layer" => "head", "seedId" => 125, "trait" => "milk" ],
            [ "layer" => "head", "seedId" => 126, "trait" => "mirror" ],
            [ "layer" => "head", "seedId" => 127, "trait" => "mixer" ],
            [ "layer" => "head", "seedId" => 128, "trait" => "moon" ],
            [ "layer" => "head", "seedId" => 129, "trait" => "moose" ],
            [ "layer" => "head", "seedId" => 130, "trait" => "mosquito" ],
            [ "layer" => "head", "seedId" => 131, "trait" => "mountain-snowcap" ],
            [ "layer" => "head", "seedId" => 132, "trait" => "mouse" ],
            [ "layer" => "head", "seedId" => 133, "trait" => "mug" ],
            [ "layer" => "head", "seedId" => 134, "trait" => "mushroom" ],
            [ "layer" => "head", "seedId" => 135, "trait" => "mustard" ],
            [ "layer" => "head", "seedId" => 136, "trait" => "nigiri" ],
            [ "layer" => "head", "seedId" => 137, "trait" => "noodles" ],
            [ "layer" => "head", "seedId" => 138, "trait" => "onion" ],
            [ "layer" => "head", "seedId" => 139, "trait" => "orangutan" ],
            [ "layer" => "head", "seedId" => 140, "trait" => "orca" ],
            [ "layer" => "head", "seedId" => 141, "trait" => "otter" ],
            [ "layer" => "head", "seedId" => 142, "trait" => "outlet" ],
            [ "layer" => "head", "seedId" => 143, "trait" => "owl" ],
            [ "layer" => "head", "seedId" => 144, "trait" => "oyster" ],
            [ "layer" => "head", "seedId" => 145, "trait" => "paintbrush" ],
            [ "layer" => "head", "seedId" => 146, "trait" => "panda" ],
            [ "layer" => "head", "seedId" => 147, "trait" => "paperclip" ],
            [ "layer" => "head", "seedId" => 148, "trait" => "peanut" ],
            [ "layer" => "head", "seedId" => 149, "trait" => "pencil-tip" ],
            [ "layer" => "head", "seedId" => 150, "trait" => "peyote" ],
            [ "layer" => "head", "seedId" => 151, "trait" => "piano" ],
            [ "layer" => "head", "seedId" => 152, "trait" => "pickle" ],
            [ "layer" => "head", "seedId" => 153, "trait" => "pie" ],
            [ "layer" => "head", "seedId" => 154, "trait" => "piggybank" ],
            [ "layer" => "head", "seedId" => 155, "trait" => "pill" ],
            [ "layer" => "head", "seedId" => 156, "trait" => "pillow" ],
            [ "layer" => "head", "seedId" => 157, "trait" => "pineapple" ],
            [ "layer" => "head", "seedId" => 158, "trait" => "pipe" ],
            [ "layer" => "head", "seedId" => 159, "trait" => "pirateship" ],
            [ "layer" => "head", "seedId" => 160, "trait" => "pizza" ],
            [ "layer" => "head", "seedId" => 161, "trait" => "plane" ],
            [ "layer" => "head", "seedId" => 162, "trait" => "pop" ],
            [ "layer" => "head", "seedId" => 163, "trait" => "porkbao" ],
            [ "layer" => "head", "seedId" => 164, "trait" => "potato" ],
            [ "layer" => "head", "seedId" => 165, "trait" => "pufferfish" ],
            [ "layer" => "head", "seedId" => 166, "trait" => "pumpkin" ],
            [ "layer" => "head", "seedId" => 167, "trait" => "pyramid" ],
            [ "layer" => "head", "seedId" => 168, "trait" => "queencrown" ],
            [ "layer" => "head", "seedId" => 169, "trait" => "rabbit" ],
            [ "layer" => "head", "seedId" => 170, "trait" => "rainbow" ],
            [ "layer" => "head", "seedId" => 171, "trait" => "rangefinder" ],
            [ "layer" => "head", "seedId" => 172, "trait" => "raven" ],
            [ "layer" => "head", "seedId" => 173, "trait" => "retainer" ],
            [ "layer" => "head", "seedId" => 174, "trait" => "rgb" ],
            [ "layer" => "head", "seedId" => 175, "trait" => "ring" ],
            [ "layer" => "head", "seedId" => 176, "trait" => "road" ],
            [ "layer" => "head", "seedId" => 177, "trait" => "robot" ],
            [ "layer" => "head", "seedId" => 178, "trait" => "rock" ],
            [ "layer" => "head", "seedId" => 179, "trait" => "rosebud" ],
            [ "layer" => "head", "seedId" => 180, "trait" => "ruler-triangular" ],
            [ "layer" => "head", "seedId" => 181, "trait" => "saguaro" ],
            [ "layer" => "head", "seedId" => 182, "trait" => "sailboat" ],
            [ "layer" => "head", "seedId" => 183, "trait" => "sandwich" ],
            [ "layer" => "head", "seedId" => 184, "trait" => "saturn" ],
            [ "layer" => "head", "seedId" => 185, "trait" => "saw" ],
            [ "layer" => "head", "seedId" => 186, "trait" => "scorpion" ],
            [ "layer" => "head", "seedId" => 187, "trait" => "shark" ],
            [ "layer" => "head", "seedId" => 188, "trait" => "shower" ],
            [ "layer" => "head", "seedId" => 189, "trait" => "skateboard" ],
            [ "layer" => "head", "seedId" => 190, "trait" => "skeleton-hat" ],
            [ "layer" => "head", "seedId" => 191, "trait" => "skilift" ],
            [ "layer" => "head", "seedId" => 192, "trait" => "smile" ],
            [ "layer" => "head", "seedId" => 193, "trait" => "snowglobe" ],
            [ "layer" => "head", "seedId" => 194, "trait" => "snowmobile" ],
            [ "layer" => "head", "seedId" => 195, "trait" => "spaghetti" ],
            [ "layer" => "head", "seedId" => 196, "trait" => "sponge" ],
            [ "layer" => "head", "seedId" => 197, "trait" => "squid" ],
            [ "layer" => "head", "seedId" => 198, "trait" => "stapler" ],
            [ "layer" => "head", "seedId" => 199, "trait" => "star-sparkles" ],
            [ "layer" => "head", "seedId" => 200, "trait" => "steak" ],
            [ "layer" => "head", "seedId" => 201, "trait" => "sunset" ],
            [ "layer" => "head", "seedId" => 202, "trait" => "taco-classic" ],
            [ "layer" => "head", "seedId" => 203, "trait" => "taxi" ],
            [ "layer" => "head", "seedId" => 204, "trait" => "thumbsup" ],
            [ "layer" => "head", "seedId" => 205, "trait" => "toaster" ],
            [ "layer" => "head", "seedId" => 206, "trait" => "toiletpaper-full" ],
            [ "layer" => "head", "seedId" => 207, "trait" => "tooth" ],
            [ "layer" => "head", "seedId" => 208, "trait" => "toothbrush-fresh" ],
            [ "layer" => "head", "seedId" => 209, "trait" => "tornado" ],
            [ "layer" => "head", "seedId" => 210, "trait" => "trashcan" ],
            [ "layer" => "head", "seedId" => 211, "trait" => "turing" ],
            [ "layer" => "head", "seedId" => 212, "trait" => "ufo" ],
            [ "layer" => "head", "seedId" => 213, "trait" => "undead" ],
            [ "layer" => "head", "seedId" => 214, "trait" => "unicorn" ],
            [ "layer" => "head", "seedId" => 215, "trait" => "vent" ],
            [ "layer" => "head", "seedId" => 216, "trait" => "void" ],
            [ "layer" => "head", "seedId" => 217, "trait" => "volcano" ],
            [ "layer" => "head", "seedId" => 218, "trait" => "volleyball" ],
            [ "layer" => "head", "seedId" => 219, "trait" => "wall" ],
            [ "layer" => "head", "seedId" => 220, "trait" => "wallet" ],
            [ "layer" => "head", "seedId" => 221, "trait" => "wallsafe" ],
            [ "layer" => "head", "seedId" => 222, "trait" => "washingmachine" ],
            [ "layer" => "head", "seedId" => 223, "trait" => "watch" ],
            [ "layer" => "head", "seedId" => 224, "trait" => "watermelon" ],
            [ "layer" => "head", "seedId" => 225, "trait" => "wave" ],
            [ "layer" => "head", "seedId" => 226, "trait" => "weed" ],
            [ "layer" => "head", "seedId" => 227, "trait" => "weight" ],
            [ "layer" => "head", "seedId" => 228, "trait" => "werewolf" ],
            [ "layer" => "head", "seedId" => 229, "trait" => "whale-alive" ],
            [ "layer" => "head", "seedId" => 230, "trait" => "whale" ],
            [ "layer" => "head", "seedId" => 231, "trait" => "wine" ],
            [ "layer" => "head", "seedId" => 232, "trait" => "wizardhat" ],
            [ "layer" => "head", "seedId" => 233, "trait" => "zebra" ],
            [ "layer" => "body", "seedId" => 0, "trait" => "bege-bsod" ],
            [ "layer" => "body", "seedId" => 1, "trait" => "bege-crt" ],
            [ "layer" => "body", "seedId" => 2, "trait" => "blue-sky" ],
            [ "layer" => "body", "seedId" => 3, "trait" => "bluegrey" ],
            [ "layer" => "body", "seedId" => 4, "trait" => "cold" ],
            [ "layer" => "body", "seedId" => 5, "trait" => "computerblue" ],
            [ "layer" => "body", "seedId" => 6, "trait" => "darkbrown" ],
            [ "layer" => "body", "seedId" => 7, "trait" => "darkpink" ],
            [ "layer" => "body", "seedId" => 8, "trait" => "foggrey" ],
            [ "layer" => "body", "seedId" => 9, "trait" => "gold" ],
            [ "layer" => "body", "seedId" => 10, "trait" => "grayscale-1" ],
            [ "layer" => "body", "seedId" => 11, "trait" => "grayscale-7" ],
            [ "layer" => "body", "seedId" => 12, "trait" => "grayscale-8" ],
            [ "layer" => "body", "seedId" => 13, "trait" => "grayscale-9" ],
            [ "layer" => "body", "seedId" => 14, "trait" => "green" ],
            [ "layer" => "body", "seedId" => 15, "trait" => "gunk" ],
            [ "layer" => "body", "seedId" => 16, "trait" => "hotbrown" ],
            [ "layer" => "body", "seedId" => 17, "trait" => "magenta" ],
            [ "layer" => "body", "seedId" => 18, "trait" => "orange-yellow" ],
            [ "layer" => "body", "seedId" => 19, "trait" => "orange" ],
            [ "layer" => "body", "seedId" => 20, "trait" => "peachy-B" ],
            [ "layer" => "body", "seedId" => 21, "trait" => "peachy-a" ],
            [ "layer" => "body", "seedId" => 22, "trait" => "purple" ],
            [ "layer" => "body", "seedId" => 23, "trait" => "red" ],
            [ "layer" => "body", "seedId" => 24, "trait" => "redpinkish" ],
            [ "layer" => "body", "seedId" => 25, "trait" => "rust" ],
            [ "layer" => "body", "seedId" => 26, "trait" => "slimegreen" ],
            [ "layer" => "body", "seedId" => 27, "trait" => "teal-light" ],
            [ "layer" => "body", "seedId" => 28, "trait" => "teal" ],
            [ "layer" => "body", "seedId" => 29, "trait" => "yellow" ],
            [ "layer" => "accessory", "seedId" => 0, "trait" => "1n" ],
            [ "layer" => "accessory", "seedId" => 1, "trait" => "aardvark" ],
            [ "layer" => "accessory", "seedId" => 2, "trait" => "axe" ],
            [ "layer" => "accessory", "seedId" => 3, "trait" => "belly-chameleon" ],
            [ "layer" => "accessory", "seedId" => 4, "trait" => "bird-flying" ],
            [ "layer" => "accessory", "seedId" => 5, "trait" => "bird-side" ],
            [ "layer" => "accessory", "seedId" => 6, "trait" => "bling-anchor" ],
            [ "layer" => "accessory", "seedId" => 7, "trait" => "bling-anvil" ],
            [ "layer" => "accessory", "seedId" => 8, "trait" => "bling-arrow" ],
            [ "layer" => "accessory", "seedId" => 9, "trait" => "bling-cheese" ],
            [ "layer" => "accessory", "seedId" => 10, "trait" => "bling-gold-ingot" ],
            [ "layer" => "accessory", "seedId" => 11, "trait" => "bling-love" ],
            [ "layer" => "accessory", "seedId" => 12, "trait" => "bling-mask" ],
            [ "layer" => "accessory", "seedId" => 13, "trait" => "bling-rings" ],
            [ "layer" => "accessory", "seedId" => 14, "trait" => "bling-scissors" ],
            [ "layer" => "accessory", "seedId" => 15, "trait" => "bling-sparkles" ],
            [ "layer" => "accessory", "seedId" => 16, "trait" => "body-gradient-checkerdisco" ],
            [ "layer" => "accessory", "seedId" => 17, "trait" => "body-gradient-dawn" ],
            [ "layer" => "accessory", "seedId" => 18, "trait" => "body-gradient-dusk" ],
            [ "layer" => "accessory", "seedId" => 19, "trait" => "body-gradient-glacier" ],
            [ "layer" => "accessory", "seedId" => 20, "trait" => "body-gradient-ice" ],
            [ "layer" => "accessory", "seedId" => 21, "trait" => "body-gradient-pride" ],
            [ "layer" => "accessory", "seedId" => 22, "trait" => "body-gradient-redpink" ],
            [ "layer" => "accessory", "seedId" => 23, "trait" => "body-gradient-sunset" ],
            [ "layer" => "accessory", "seedId" => 24, "trait" => "carrot" ],
            [ "layer" => "accessory", "seedId" => 25, "trait" => "chain-logo" ],
            [ "layer" => "accessory", "seedId" => 26, "trait" => "checker-RGB" ],
            [ "layer" => "accessory", "seedId" => 27, "trait" => "checker-bigwalk-blue-prime" ],
            [ "layer" => "accessory", "seedId" => 28, "trait" => "checker-bigwalk-greylight" ],
            [ "layer" => "accessory", "seedId" => 29, "trait" => "checker-bigwalk-rainbow" ],
            [ "layer" => "accessory", "seedId" => 30, "trait" => "checker-spaced-black" ],
            [ "layer" => "accessory", "seedId" => 31, "trait" => "checker-spaced-white" ],
            [ "layer" => "accessory", "seedId" => 32, "trait" => "checker-vibrant" ],
            [ "layer" => "accessory", "seedId" => 33, "trait" => "checkers-big-green" ],
            [ "layer" => "accessory", "seedId" => 34, "trait" => "checkers-big-red-cold" ],
            [ "layer" => "accessory", "seedId" => 35, "trait" => "checkers-black" ],
            [ "layer" => "accessory", "seedId" => 36, "trait" => "checkers-blue" ],
            [ "layer" => "accessory", "seedId" => 37, "trait" => "checkers-magenta-80" ],
            [ "layer" => "accessory", "seedId" => 38, "trait" => "chicken" ],
            [ "layer" => "accessory", "seedId" => 39, "trait" => "cloud" ],
            [ "layer" => "accessory", "seedId" => 40, "trait" => "clover" ],
            [ "layer" => "accessory", "seedId" => 41, "trait" => "collar-sunset" ],
            [ "layer" => "accessory", "seedId" => 42, "trait" => "cow" ],
            [ "layer" => "accessory", "seedId" => 43, "trait" => "decay-gray-dark" ],
            [ "layer" => "accessory", "seedId" => 44, "trait" => "decay-pride" ],
            [ "layer" => "accessory", "seedId" => 45, "trait" => "dinosaur" ],
            [ "layer" => "accessory", "seedId" => 46, "trait" => "dollar-bling" ],
            [ "layer" => "accessory", "seedId" => 47, "trait" => "dragon" ],
            [ "layer" => "accessory", "seedId" => 48, "trait" => "ducky" ],
            [ "layer" => "accessory", "seedId" => 49, "trait" => "eth" ],
            [ "layer" => "accessory", "seedId" => 50, "trait" => "eye" ],
            [ "layer" => "accessory", "seedId" => 51, "trait" => "flash" ],
            [ "layer" => "accessory", "seedId" => 52, "trait" => "fries" ],
            [ "layer" => "accessory", "seedId" => 53, "trait" => "logo-sun" ],
            [ "layer" => "accessory", "seedId" => 54, "trait" => "logo" ],
            [ "layer" => "accessory", "seedId" => 55, "trait" => "glasses" ],
            [ "layer" => "accessory", "seedId" => 56, "trait" => "grid-simple-bege" ],
            [ "layer" => "accessory", "seedId" => 57, "trait" => "heart" ],
            [ "layer" => "accessory", "seedId" => 58, "trait" => "hoodiestrings-uneven" ],
            [ "layer" => "accessory", "seedId" => 59, "trait" => "id" ],
            [ "layer" => "accessory", "seedId" => 60, "trait" => "infinity" ],
            [ "layer" => "accessory", "seedId" => 61, "trait" => "insignia" ],
            [ "layer" => "accessory", "seedId" => 62, "trait" => "leaf" ],
            [ "layer" => "accessory", "seedId" => 63, "trait" => "lightbulb" ],
            [ "layer" => "accessory", "seedId" => 64, "trait" => "lines-45-greens" ],
            [ "layer" => "accessory", "seedId" => 65, "trait" => "lines-45-rose" ],
            [ "layer" => "accessory", "seedId" => 66, "trait" => "lp" ],
            [ "layer" => "accessory", "seedId" => 67, "trait" => "marsface" ],
            [ "layer" => "accessory", "seedId" => 68, "trait" => "matrix-white" ],
            [ "layer" => "accessory", "seedId" => 69, "trait" => "moon-block" ],
            [ "layer" => "accessory", "seedId" => 70, "trait" => "none" ],
            [ "layer" => "accessory", "seedId" => 71, "trait" => "oldshirt" ],
            [ "layer" => "accessory", "seedId" => 72, "trait" => "pizza-bling" ],
            [ "layer" => "accessory", "seedId" => 73, "trait" => "pocket-pencil" ],
            [ "layer" => "accessory", "seedId" => 74, "trait" => "rain" ],
            [ "layer" => "accessory", "seedId" => 75, "trait" => "rainbow-steps" ],
            [ "layer" => "accessory", "seedId" => 76, "trait" => "rgb" ],
            [ "layer" => "accessory", "seedId" => 77, "trait" => "robot" ],
            [ "layer" => "accessory", "seedId" => 78, "trait" => "safety-vest" ],
            [ "layer" => "accessory", "seedId" => 79, "trait" => "scarf-clown" ],
            [ "layer" => "accessory", "seedId" => 80, "trait" => "secret-x" ],
            [ "layer" => "accessory", "seedId" => 81, "trait" => "shirt-black" ],
            [ "layer" => "accessory", "seedId" => 82, "trait" => "shrimp" ],
            [ "layer" => "accessory", "seedId" => 83, "trait" => "slimesplat" ],
            [ "layer" => "accessory", "seedId" => 84, "trait" => "small-bling" ],
            [ "layer" => "accessory", "seedId" => 85, "trait" => "snowflake" ],
            [ "layer" => "accessory", "seedId" => 86, "trait" => "stains-blood" ],
            [ "layer" => "accessory", "seedId" => 87, "trait" => "stains-zombie" ],
            [ "layer" => "accessory", "seedId" => 88, "trait" => "stripes-and-checks" ],
            [ "layer" => "accessory", "seedId" => 89, "trait" => "stripes-big-red" ],
            [ "layer" => "accessory", "seedId" => 90, "trait" => "stripes-blit" ],
            [ "layer" => "accessory", "seedId" => 91, "trait" => "stripes-blue-med" ],
            [ "layer" => "accessory", "seedId" => 92, "trait" => "stripes-brown" ],
            [ "layer" => "accessory", "seedId" => 93, "trait" => "stripes-olive" ],
            [ "layer" => "accessory", "seedId" => 94, "trait" => "stripes-red-cold" ],
            [ "layer" => "accessory", "seedId" => 95, "trait" => "sunset" ],
            [ "layer" => "accessory", "seedId" => 96, "trait" => "taxi-checkers" ],
            [ "layer" => "accessory", "seedId" => 97, "trait" => "tee-yo" ],
            [ "layer" => "accessory", "seedId" => 98, "trait" => "text-yolo" ],
            [ "layer" => "accessory", "seedId" => 99, "trait" => "think" ],
            [ "layer" => "accessory", "seedId" => 100, "trait" => "tie-black-on-white" ],
            [ "layer" => "accessory", "seedId" => 101, "trait" => "tie-dye" ],
            [ "layer" => "accessory", "seedId" => 102, "trait" => "tie-purple-on-white" ],
            [ "layer" => "accessory", "seedId" => 103, "trait" => "tie-red" ],
            [ "layer" => "accessory", "seedId" => 104, "trait" => "txt-a2+b2" ],
            [ "layer" => "accessory", "seedId" => 105, "trait" => "txt-cc" ],
            [ "layer" => "accessory", "seedId" => 106, "trait" => "txt-cc2" ],
            [ "layer" => "accessory", "seedId" => 107, "trait" => "txt-copy" ],
            [ "layer" => "accessory", "seedId" => 108, "trait" => "txt-dao-black" ],
            [ "layer" => "accessory", "seedId" => 109, "trait" => "txt-doom" ],
            [ "layer" => "accessory", "seedId" => 110, "trait" => "txt-dope-text" ],
            [ "layer" => "accessory", "seedId" => 111, "trait" => "txt-foo-black" ],
            [ "layer" => "accessory", "seedId" => 112, "trait" => "txt-ico" ],
            [ "layer" => "accessory", "seedId" => 113, "trait" => "txt-io" ],
            [ "layer" => "accessory", "seedId" => 114, "trait" => "txt-lmao" ],
            [ "layer" => "accessory", "seedId" => 115, "trait" => "txt-lol" ],
            [ "layer" => "accessory", "seedId" => 116, "trait" => "txt-mint" ],
            [ "layer" => "accessory", "seedId" => 117, "trait" => "txt-nil-grey-dark" ],
            [ "layer" => "accessory", "seedId" => 118, "trait" => "txt-noun-f0f" ],
            [ "layer" => "accessory", "seedId" => 119, "trait" => "txt-noun-green" ],
            [ "layer" => "accessory", "seedId" => 120, "trait" => "txt-noun-multicolor" ],
            [ "layer" => "accessory", "seedId" => 121, "trait" => "txt-noun" ],
            [ "layer" => "accessory", "seedId" => 122, "trait" => "txt-pi" ],
            [ "layer" => "accessory", "seedId" => 123, "trait" => "txt-pop" ],
            [ "layer" => "accessory", "seedId" => 124, "trait" => "txt-rofl" ],
            [ "layer" => "accessory", "seedId" => 125, "trait" => "txt-we" ],
            [ "layer" => "accessory", "seedId" => 126, "trait" => "txt-yay" ],
            [ "layer" => "accessory", "seedId" => 127, "trait" => "wall" ],
            [ "layer" => "accessory", "seedId" => 128, "trait" => "wave" ],
            [ "layer" => "accessory", "seedId" => 129, "trait" => "wet-money" ],
            [ "layer" => "accessory", "seedId" => 130, "trait" => "woolweave-bicolor" ],
            [ "layer" => "accessory", "seedId" => 131, "trait" => "woolweave-dirt" ],
            [ "layer" => "accessory", "seedId" => 132, "trait" => "yingyang" ],
            [ "layer" => "accessory", "seedId" => 133, "trait" => "bege" ],
            [ "layer" => "accessory", "seedId" => 134, "trait" => "gray-scale-1" ],
            [ "layer" => "accessory", "seedId" => 135, "trait" => "gray-scale-9" ],
            [ "layer" => "accessory", "seedId" => 136, "trait" => "ice-cold" ],
            [ "layer" => "accessory", "seedId" => 137, "trait" => "grease" ],
            [ "layer" => "accessory", "seedId" => 138, "trait" => "tatewaku" ],
            [ "layer" => "accessory", "seedId" => 139, "trait" => "uroko" ],
            [ "layer" => "glasses", "seedId" => 0, "trait" => "hip-rose" ],
            [ "layer" => "glasses", "seedId" => 1, "trait" => "square-black-eyes-red" ],
            [ "layer" => "glasses", "seedId" => 2, "trait" => "square-black-rgb" ],
            [ "layer" => "glasses", "seedId" => 3, "trait" => "square-black" ],
            [ "layer" => "glasses", "seedId" => 4, "trait" => "square-blue-med-saturated" ],
            [ "layer" => "glasses", "seedId" => 5, "trait" => "square-blue" ],
            [ "layer" => "glasses", "seedId" => 6, "trait" => "square-frog-green" ],
            [ "layer" => "glasses", "seedId" => 7, "trait" => "square-fullblack" ],
            [ "layer" => "glasses", "seedId" => 8, "trait" => "square-green-blue-multi" ],
            [ "layer" => "glasses", "seedId" => 9, "trait" => "square-grey-light" ],
            [ "layer" => "glasses", "seedId" => 10, "trait" => "square-guava" ],
            [ "layer" => "glasses", "seedId" => 11, "trait" => "square-honey" ],
            [ "layer" => "glasses", "seedId" => 12, "trait" => "square-magenta" ],
            [ "layer" => "glasses", "seedId" => 13, "trait" => "square-orange" ],
            [ "layer" => "glasses", "seedId" => 14, "trait" => "square-pink-purple-multi" ],
            [ "layer" => "glasses", "seedId" => 15, "trait" => "square-red" ],
            [ "layer" => "glasses", "seedId" => 16, "trait" => "square-smoke" ],
            [ "layer" => "glasses", "seedId" => 17, "trait" => "square-teal" ],
            [ "layer" => "glasses", "seedId" => 18, "trait" => "square-watermelon" ],
            [ "layer" => "glasses", "seedId" => 19, "trait" => "square-yellow-orange-multi" ],
            [ "layer" => "glasses", "seedId" => 20, "trait" => "square-yellow-saturated" ]
        ]);

        foreach ($lilNouns as $lilNoun) {
            if (is_null($lilNoun->head_name) && is_int($lilNoun->head_index)) {
                $headTrait = $traits
                    ->where('layer', 'head')
                    ->where('seedId', $lilNoun->head_index)
                    ->first();
                
                if (!empty($headTrait)) {
                    $lilNoun->update(['head_name' => $headTrait['trait']]);
                } else {
                    throw new \Exception('Unable to find head trait in collection, head index: ' . $lilNoun->head_index);
                }
            }

            if (is_null($lilNoun->background_name) && is_int($lilNoun->background_index)) {
                $backgroundTrait = $traits
                    ->where('layer', 'background')
                    ->where('seedId', $lilNoun->background_index)
                    ->first();
                
                if (!empty($backgroundTrait)) {
                    $lilNoun->update(['background_name' => $backgroundTrait['trait']]);
                } else {
                    throw new \Exception('Unable to find background trait in collection, background index: ' . $lilNoun->background_index);
                }
            }

            if (is_null($lilNoun->accessory_name) && is_int($lilNoun->accessory_index)) {
                $accessoryTrait = $traits
                    ->where('layer', 'accessory')
                    ->where('seedId', $lilNoun->accessory_index)
                    ->first();
                
                if (!empty($accessoryTrait)) {
                    $lilNoun->update(['accessory_name' => $accessoryTrait['trait']]);
                } else {
                    throw new \Exception('Unable to find accessory trait in collection, accessory index: ' . $lilNoun->accessory_index);
                }
            }

            if (is_null($lilNoun->body_name) && is_int($lilNoun->body_index)) {
                $bodyTrait = $traits
                    ->where('layer', 'body')
                    ->where('seedId', $lilNoun->body_index)
                    ->first();
                
                if (!empty($bodyTrait)) {
                    $lilNoun->update(['body_name' => $bodyTrait['trait']]);
                } else {
                    throw new \Exception('Unable to find body trait in collection, body index: ' . $lilNoun->body_index);
                }
            }

            if (is_null($lilNoun->glasses_name) && is_int($lilNoun->glasses_index)) {
                $glassesTrait = $traits
                    ->where('layer', 'glasses')
                    ->where('seedId', $lilNoun->glasses_index)
                    ->first();
                
                if (!empty($glassesTrait)) {
                    $lilNoun->update(['glasses_name' => $glassesTrait['trait']]);
                } else {
                    throw new \Exception('Unable to find glasses trait in collection, glasses index: ' . $lilNoun->glasses_index);
                }
            }
        }
    }
}
