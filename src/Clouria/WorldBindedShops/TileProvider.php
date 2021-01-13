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

use pocketmine\{Server,
	level\Level,
	level\Position,
	math\Vector3
};

use onebone\economyshop\provider\DataProvider;

class TileProvider extends DataProvider {

	public function __construct($file, $save){
	}

	/**
	 * @return bool|Tile
	 */
	public function addShop($x, $y = 0, $z = 0, $level = null, $data = []) {
		if ($x instanceof Position) {
			$level = $x->getLevel();
			$vec = $x;
		}
		if (!$level instanceof Level) return false;
		else $vec = new Vector3((int)$x, (int)$y, (int)$z);
		return ShopTile::createTile(ShopTile::TYPE_NAME, $level, ShopTile::createNBT($vec), $data) ?? false;
	}

	/**
	 * Only works on loaded chunks
	 * @return array|bool
	 */
	public function getShop($x, $y = 0, $z = 0, $level = null) {
		self::extractPosition($x, $x, $y, $z, $level);
		if (!$level instanceof Level) return false;
		if (!($tile = $level->getTileAt($x, $y, $z)) instanceof ShopTile) return false;
		return $tile->getShopData();
	}

	/**
	 * Only works on loaded chunks
	 * @return bool
	 */
	public function removeShop($x, $y = 0, $z = 0, $level = null) {
		self::extractPosition($x, $x, $y, $z, $level);
		if (($tile = $this->getShop($x, $y, $z, $level)) === null) return false;
		$level->removeTile($tile);
		return true;
	}

	/**
	 * Only works on loaded worlds and chunks
	 * @return ShopTile[]
	 */
	public function getAll() : array {
		foreach (Server::getInstance()->getLevels() as $w) foreach ($w->getTiles() as $tile) if ($tile instanceof ShopTile) $tiles[] = $tile;
		return $tiles;
	}

	public function getProviderName() : string {
		return 'Tile';
	}

	public function save() {}
	public function close() {}

	protected static function extractPosition($position, &$x = 0, &$y = 0, &$z = 0, &$level = null) : bool {
		if (!$position instanceof Position) return false;
		$x = $position->getFloorX();
		$y = $position->getFloorY();
		$z = $position->getFloorZ();
		$level = $position->getLevel();
		return true;
	}

}