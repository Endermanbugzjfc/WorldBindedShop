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
	plugin\PluginEnableEvent
}

use onebone\economyshop\EconomyShop;

final class WorldBindedShops extends PluginBase implements EventListener {

	/**
	 * @var self
	 */
	private static $instance = null;

	public function onLoad() : void {
		self::$instance = $this;
	}

	public function onEnable() : void {

	}

	public function onPluginEnable(PluginEnableEvent $ev) : void {
		if (!$ev->getPlugin() instanceof EconomyShop) return;
		$reflect = new \ReflectionProperty($ev->getPlugin(), 'provider');
		$reflect->setAccessible(true);
		$reflect->setValue($ev->getPlugin(), new TileProvider);
	}

	public static function getInstance() : ?self {
		return self::$instance;
	}

}