<?php

declare(strict_types=1);

namespace NhanAZ\KeepInventory;

use pocketmine\event\EventPriority;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

	private InventoryPolicy $policy;

	protected function onEnable(): void {
		$this->saveDefaultConfig();
		try {
			$this->policy = InventoryPolicy::fromConfig($this->getConfig()->get("mode"), $this->getConfig()->get("worlds"));
		} catch (\InvalidArgumentException $e) {
			$this->getLogger()->error($e->getMessage());
			$this->getServer()->getPluginManager()->disablePlugin($this);
			return;
		}

		$pluginMgr = $this->getServer()->getPluginManager();
		try {
			$onDeath = \Closure::fromCallable([$this, "onPlayerDeath"]);
			$pluginMgr->registerEvent(PlayerDeathEvent::class, $onDeath, EventPriority::LOWEST, $this);
		} catch (\ReflectionException $e) {
			$this->getLogger()->critical($e->getMessage());
			$this->getServer()->getPluginManager()->disablePlugin($this);
		}
	}

	public function onPlayerDeath(PlayerDeathEvent $event): void {
		$worldName = $event->getPlayer()->getWorld()->getDisplayName();
		if ($this->policy->shouldKeep($worldName)) {
			$event->setKeepInventory(true);
		}
	}
}
