<?php

declare(strict_types=1);

namespace NhanAZ\KeepInventory;

final class InventoryPolicy {

	/** @param list<string> $worlds */
	private function __construct(private readonly string $mode, private readonly array $worlds) {}

	public static function fromConfig(mixed $mode, mixed $worlds): self {
		if (!is_string($mode) || !in_array($mode, ["blacklist", "whitelist"], true)) {
			throw new \InvalidArgumentException("KeepInventory mode must be blacklist or whitelist.");
		}
		if (!is_array($worlds) || !array_is_list($worlds)) {
			throw new \InvalidArgumentException("KeepInventory worlds must be a list of world names.");
		}
		$validatedWorlds = [];
		foreach ($worlds as $world) {
			if (!is_string($world) || $world === "") {
				throw new \InvalidArgumentException("KeepInventory worlds must contain nonempty names.");
			}
			$validatedWorlds[] = $world;
		}
		return new self($mode, $validatedWorlds);
	}

	public function shouldKeep(string $worldName): bool {
		$listed = in_array($worldName, $this->worlds, true);
		return $this->mode === "whitelist" ? $listed : !$listed;
	}
}
