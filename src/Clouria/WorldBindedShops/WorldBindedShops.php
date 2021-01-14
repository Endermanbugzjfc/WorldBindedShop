<?php

/*

	$$\      $$\                     $$\       $$\        
	$$ | $\  $$ |                    $$ |      $$ |       
	$$ |$$$\ $$ | $$$$$$\   $$$$$$\  $$ | $$$$$$$ |       
	$$ $$ $$\$$ |$$  __$$\ $$  __$$\ $$ |$$  __$$ |       
	$$$$  _$$$$ |$$ /  $$ |$$ |  \__|$$ |$$ /  $$ |       
	$$$  / \$$$ |$$ |  $$ |$$ |      $$ |$$ |  $$ |       
	$$  /   \$$ |\$$$$$$  |$$ |      $$ |\$$$$$$$ |       
	\__/     \__| \______/ \__|      \__| \_______|       
	                                                      
	                                                      
	                                                      
	$$$$$$$\  $$\                 $$\                 $$\ 
	$$  __$$\ \__|                $$ |                $$ |
	$$ |  $$ |$$\ $$$$$$$\   $$$$$$$ | $$$$$$\   $$$$$$$ |
	$$$$$$$\ |$$ |$$  __$$\ $$  __$$ |$$  __$$\ $$  __$$ |
	$$  __$$\ $$ |$$ |  $$ |$$ /  $$ |$$$$$$$$ |$$ /  $$ |
	$$ |  $$ |$$ |$$ |  $$ |$$ |  $$ |$$   ____|$$ |  $$ |
	$$$$$$$  |$$ |$$ |  $$ |\$$$$$$$ |\$$$$$$$\ \$$$$$$$ |
	\_______/ \__|\__|  \__| \_______| \_______| \_______|
	                                                      
	                                                      
	                                                      
	 $$$$$$\  $$\                                         
	$$  __$$\ $$ |                                        
	$$ /  \__|$$$$$$$\   $$$$$$\   $$$$$$\   $$$$$$$\     
	\$$$$$$\  $$  __$$\ $$  __$$\ $$  __$$\ $$  _____|    
	 \____$$\ $$ |  $$ |$$ /  $$ |$$ /  $$ |\$$$$$$\      
	$$\   $$ |$$ |  $$ |$$ |  $$ |$$ |  $$ | \____$$\     
	\$$$$$$  |$$ |  $$ |\$$$$$$  |$$$$$$$  |$$$$$$$  |    
	 \______/ \__|  \__| \______/ $$  ____/ \_______/     
	                              $$ |                    
	                              $$ |                    
	                              \__|                    

	@ClouriaNetwork | GNU General Public License v2.1

*/

declare(strict_types=1);
namespace Clouria\WorldBindedShops;

use pocketmine\plugin\PluginBase;
use pocketmine\event\{
	Listener,
	plugin\PluginEnableEvent,
	level\ChunkLoadEvent,
	level\ChunkUnloadEvent,
	command\Command,
	command\CommandSender
};

use onebone\economyshop\{EconomyShop,
	provider\YamlProvider,
	provider\DataProvider
};

use Clouria\WorldBindedShops\custom\CustomItemDisplayer;

final class WorldBindedShops extends PluginBase implements EventListener {

	/**
	 * @var self
	 */
	private static $instance = null;

	/**
	 * @var null|DataProvider
	 */
	public $provider = null;

	public function onLoad() : void {
		self::$instance = $this;
	}

	public function onEnable() : void {
		$this->initConfig();
		$this->modifyProvider();
	}

	private function initConfig() : void {
		$this->saveDefaultConfig();
		$conf = $this->getConfig();
		foreach ($all = $conf->getAll() as $k => $v) $conf->remove($k);

		$conf->set('tile-provider', (bool)($all['tile-provider'] ?? true));

		$conf->save();
		$conf->reload();
	}

	public function onPluginEnable(PluginEnableEvent $ev) : void {
		if (!$ev->getPlugin() instanceof EconomyShop) return;
		$this->modifyProvider();
	}

	public function onChunkLoad(ChunkLoadEvent $ev) : void {
		if (!(bool)$this->getConfig()->get('tile-provider', true)) return;
		$chunk = $ev->getChunk();
		foreach ($chunk->getTiles() as $tile) if ($tile instanceof ShopTile) $tiles[] = $tile;
		if (empty($tiles ?? [])) return;

		$reflect = new \ReflectionProperty($this->getApi(), 'items');
		$reflect->setAccessible(true);
		$shops = $reflect->getValue($this->getApi());
		foreach ($tiles as $tile) $shops[] = $tile->asItemDisplayer();
		$reflect->setValue($ev->getPlugin(), $shops);
	}

	public function onChunkUnload(ChunkUnloadEvent $ev) : void {
		if (!(bool)$this->getConfig()->get('tile-provider', true)) return;
		$chunk = $ev->getChunk();
		$reflect = new \ReflectionProperty($this->getApi(), 'items');
		$reflect->setAccessible(true);
		$shops = $reflect->getValue($this->getApi());
		foreach ($shops as $id => $shop) if ($shop instanceof CustomItemDisplayer) {
			$pos = $shop->getLinked();
			if (($pos->getFloorX() >> 4) === $chunk->getX() and ($pos->getFloorZ() >> 4) === $chunk->getZ()) unset($shops[$id]);
		}
		$reflect->setValue($ev->getPlugin(), $shops);
	}

	public const ECONOMYSHOP_PLUGIN_NAME = 'EconomyShop';

	public function modifyProvider() : bool {
		if (!(bool)$this->getConfig()->get('tile-provider', true)) return true;

		if (!$this->getApi() instanceof EconomyShop) return false;

		$reflect = new \ReflectionProperty($this->getApi(), 'provider');
		$reflect->setAccessible(true);
		if (!$reflect->getValue($this->getApi()) instanceof YamlProvider) return false;
		if (!$this->provider instanceof DataProvider) $this->provider = new TileProvider;
		$reflect->setValue($ev->getPlugin(), $this->provider);
	}

	public function onCommand(CommandSender $sender, Command $cmd, string $alias, array $args) : bool {
		
	}

	public function getApi() : ?EconomyShop {
		return $this->getServer()->getPluginManager()->getPlugin(self::ECONOMYSHOP_PLUGIN_NAME);
	}

	public static function getInstance() : ?self {
		return self::$instance;
	}

}