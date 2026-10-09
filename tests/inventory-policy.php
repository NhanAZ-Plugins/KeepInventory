<?php

declare(strict_types=1);

require dirname(__DIR__) . "/src/NhanAZ/KeepInventory/InventoryPolicy.php";

use NhanAZ\KeepInventory\InventoryPolicy;

$cases = [
	["blacklist", [], "world", true],
	["blacklist", ["spawn"], "spawn", false],
	["blacklist", ["spawn"], "survival", true],
	["whitelist", [], "world", false],
	["whitelist", ["spawn"], "spawn", true],
	["whitelist", ["spawn"], "Spawn", false],
];

foreach ($cases as [$mode, $worlds, $world, $expected]) {
	$actual = InventoryPolicy::fromConfig($mode, $worlds)->shouldKeep($world);
	if ($actual !== $expected) {
		throw new RuntimeException("Unexpected inventory policy result for $mode in $world.");
	}
}

$invalidCases = [
	[null, []],
	["unknown", []],
	["blacklist", null],
	["blacklist", ["name" => "spawn"]],
	["whitelist", [""]],
	["whitelist", [42]],
];

foreach ($invalidCases as [$mode, $worlds]) {
	try {
		InventoryPolicy::fromConfig($mode, $worlds);
	} catch (InvalidArgumentException) {
		continue;
	}
	throw new RuntimeException("Invalid inventory configuration was accepted.");
}

echo "PASS: 12 inventory policy cases\n";
