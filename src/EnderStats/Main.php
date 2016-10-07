<?php

namespace EnderStats;

use EnderStats\API\EnderStats;
use EnderStats\StatsCommand;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\Plugin;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\block\Block;
use pocketmine\Entity;
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
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\BlockBreakEvent;

class Main extends PluginBase implements Listener{
    
    /**@var EnderStats*/
    private $enderstats;
    
    public function onEnable(){
        $this->getLogger()->info("EnderStats by CaptainDuck enabled!");
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        @mkdir($this->getDataFolder());
        $this->getCommand("stats")->setExecutor(new StatsCommand($this), $this);
        $this->config = new Config($this->getDataFolder(). "config.yml", Config::YAML, array(
            "EnderStats Config File",
            "#Whether true or false, this disables Break, Place, Kill, and Deaths, it also disables the addition of it.",
            "Breaks" => "true",
            "Places" => "true",
            "Kills" => "true",
            "Deaths" => "true"
        ));
    }
    
    public function enderStats(){
        return $this->enderstats;
    }
    
    public function onDisable(){
        $this->getLogger()->info("EnderStats by CaptainDuck disabled! :o");
    }
    
    public function onJoin(PlayerJoinEvent $event){
        if(!$this->enderStats()->playerHasStats($event->getPlayer())){
            $this->enderStats()->addPlayer($event->getPlayer());
        }
    }
    
    public function onBreak(BlockBreakEvent $event){
        if($this->config->get("Breaks") == "true"){
            $this->enderStats()->addBreaks($event->getPlayer(), 1);
        }
    }
    
    public function onDeath(PlayerDeathEvent $event){
        $player = $event->getEntity();
        $cause = $event->getLastDamageCause();
        if($this->config->get("Kills") == "true"){
            if($event->$cause->getDamager() instanceof Player){
                $this->enderStats()->addKills($event->$cause->getDamager(), 1);
            }
            if($player instanceof Player){
                $this->enderStats()->getDeaths($player->getName(), 1);
            }
        }
    }
    
    public function onPlace(BlockPlaceEvent $event){
        if($this->config->get("Places") == "true"){
            $this->enderStats()->addPlaces($event->getPlayer(), 1);
        }
    }
}
