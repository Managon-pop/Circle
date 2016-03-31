<?php

namespace Managon;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\math\Vector3;
use pocketmine\level\Level;
use pocketmine\level\particle\RedStoneParticle;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\PluginTask;

class circle extends PluginBase implements Listener{
    public $tapper;//こんな英語あるのか分からないけど通じるよね
	public function onEnable(){
		Server::getInstance()->getPluginManager()->registerEvents($this,$this);
	}

	public function onCommand(CommandSender $sender, Command $c,$label,array $args){
		if(count($args) < 1 || count($args) > 5){//feuturekj
			$sender->sendMessage("§bIf you make circle, Send /cir redius\n§aIf you make tornado, Send /trn redius height");
			return;
		}else{
		switch($c->getName()){
		case "cir":
           $hypo = $args[0];
           if($hypo < 15){
           	$sender->sendMessage("The radius must be bigger than 15");
           	return;
           }
           $this->tapper[$sender->getName()]["cir"] = $hypo;
           $sender->sendMessage("Tap the ground! I will make circle.");
           break;
        case "trn":
           $hypo = $args[0];
           if($hypo < 15){
           	$sender->sendMessage("The radius must be bigger than 15");
           	return;
           }
           $height = $args[1];
           if($height > 30){
           	$sender->sendMessage("The height must be lower than 30");
           	return;
           }
           $this->tapper[$sender->getName()]["trn"][$hypo] = $height;
           $sender->sendMessage("Tap the ground! I will make tornado.");
           break;
		}
}
}

    public function onTap(PlayerInteractEvent $event){
    	if(isset($this->tapper[$event->getPlayer()->getName()])){
    		$type = $this->tapper[$event->getPlayer()->getName()];
    		$block = $event->getBlock();
    		$x = $block->x;
    		$y = $block->y;
    		$z = $block->z;
    		$level = $event->getPlayer()->getLevel();
    foreach($type as $t => $hypo){
      switch($t){
        case "cir":
          $hypo = $type["cir"];
    	    for($r = 0; $r <= 720; $r++){
    	    	$a = cos(deg2rad($r/2))*(Int) $hypo;
    	    	$b = sin(deg2rad($r/2))*(Int) $hypo;
    	    	$pos = new Vector3($x + $a, $y, $z + $b);
                $particle = new RedStoneParticle($pos, 20);
                $level->addParticle($particle, $level->getPlayers());
    	    }
          unset($this->tapper[$event->getPlayer()->getName()]);
    	    break;
    	case "trn":
           foreach($hypo as $h => $height){
           var_dump($this->tapper);
           $hh = $height*4;
           for($s = 1; $s <= 3600; $s++){
           	   $a = cos(deg2rad($s/2))*(Int) $h;//the Base
               $b = sin(deg2rad($s/2))*(Int) $h;//the highest
               $pos = new Vector3($x + $a, $y+($s/$hh), $z + $b);
               $particle = new RedStoneParticle($pos, 20);
               $level->addParticle($particle,  $level->getPlayers());
           }
        unset($this->tapper[$event->getPlayer()->getName()]);
        break;
    	}
    }
}
}
}
}