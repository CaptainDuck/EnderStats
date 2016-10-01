<?php

namespace EnderStats;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\Plugin;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\block\Block;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\command\Command\Executor;
use pocketmine\item\Item;
use pocketmine\utils\TextFormat as C;
use pocketmine\utils\Config;
use pocketmine\event\block\BlockBreakEvent;

class Main extends PluginBase implements Listener{
    
    public function onEnable(){
        $this->getLogger()->info("EnderStats by CaptainDuck enabled!");
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->getCommand("stats")->setExecutor(new StatsCommand($this), $this);
    }
    
    public function onDisable(){
        $this->getLogger()->info("EnderStats by CaptainDuck disabled! :o");
    }
    
    public function getPlayerFile(Player $player){
        return new Config($this->getDataFolder(). "players/". $player->getName().".yml");
    }
}
