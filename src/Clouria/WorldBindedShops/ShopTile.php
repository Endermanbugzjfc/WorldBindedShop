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

use pocketmine\{
	level\Level,
	tile\Spawnable
};
use pocketmine\nbt\tag\{
	CompoundTag
}

class ShopTile extends Spawnable {

	private $data;

	public function __construct(Level $level, CompoundTag $nbt, array $data = null) {
		if (isset($data)) $this->data = $data;
		parent::__construct($level, $nbt);
	}

	protected function readSaveData(CompoundTag $nbt) : void {
		$data[0] = $this->x;
		$data[0] = $this->y;
		$data[0] = $this->z;
		$data[0] = $this->level;
		if ($nbt->hasTag('item')) $data[4] = $nbt->getShort('item');
		if ($nbt->hasTag('meta')) $data[5] = $nbt->getInt('meta');
		if ($nbt->hasTag('itemName')) $data[6] = $nbt->getString('itemName');
		if ($nbt->hasTag('amount')) $data[7] = $nbt->getInt('amount');
		if ($nbt->hasTag('price')) $data[8] = $nbt->getDouble('price');
		if ($nbt->hasTag('side')) $data[9] = $nbt->getByte('side');
	}

	protected function writeSaveData(CompoundTag $nbt) : void {
		$nbt->setShort('item', (int)($this->data['item'] ?? $this->data[4]));
		$nbt->setInt('meta', (int)($this->data['meta'] ?? $this->data[5]));
		$nbt->setString('itemName', (string)($this->data['itemName'] ?? $this->data[6]));
		$nbt->setInt('amount', (int)($this->data['amount'] ?? $this->data[7]));
		$nbt->setDouble('price', (float)$this->data['price'] ?? $this->data[8]);
		$nbt->setByte('side', (int)($this->data['side'] ?? $this->data[9]));
	}


}