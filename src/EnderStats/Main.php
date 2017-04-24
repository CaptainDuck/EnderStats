<?php

namespace EnderStats;

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
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\item\Item;
use pocketmine\utils\TextFormat as C;
use pocketmine\utils\Config;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\BlockBreakEvent;

class Main extends PluginBase implements Listener{
    
    public function onEnable(){
        $this->getLogger()->info("EnderStats by CaptainDuck enabled!");
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        @mkdir($this->getDataFolder());
        $this->config = new Config($this->getDataFolder(). "config.yml", Config::YAML, array(
            "EnderStats Config File",
            "#Whether true or false, this disables Break, Place, Kill, and Deaths, it also disables the addition of it.",
            "Breaks" => "true",
            "Places" => "true",
            "Kills" => "true",
            "Deaths" => "true"
        ));
    }
    
    public function onDisable(){
        $this->getLogger()->info("EnderStats by CaptainDuck disabled! :o");
    }
    
    public function onJoin(PlayerJoinEvent $event){
        if(!$this->playerHasStats($event->getPlayer())){
            $this->addPlayer($event->getPlayer());
        }
    }
    
    public function playerHasStats(Player $player){
        return file_exists($this->plugin->getDataFolder(). "players/". strtolower($player->getName()). ".yml");
    }
    
    public function getStats(Player $player){
        if($this->playerHasStats($player)){
            return (new Config($this->plugin->getDataFolder(). "players/". strtolower($player->getName()). ".yml", Config::YAML))->getAll();
        }
    }
    
    public function saveStats(Player $player){
        return (new Config($this->plugin->getDataFolder(). "players/". strtolower($player->getName()). ".yml", Config::YAML))->save();
    }
    
    public function addPlayer(Player $player){
        return new Config($this->plugin->getDataFolder(). "players/". strtolower($player->getName()). ".yml", Config::YAML, array(
            "playername" => $player->getName(),
            "kills" => "0",
            "deaths" => "0",
            "breaks" => "0",
            "places" => "0"
        ));
    }
    
    public function addKills(Player $player, $kills){
        $current = $this->getStats($player)["kills"];
        $this->getStats($player)->set("kills", $current + $kills);
        $this->saveStats($player);
    }
    
    public function addDeaths(Player $player, $deaths){
        $current = $this->getStats($player)["deaths"];
        $this->getStats($player)->set("deaths", $current + $deaths);
        $this->saveStats($player);
    }
    
    public function addBreaks(Player $player, $breaks){
        $current = $this->getStats($player)["breaks"];
        $this->getStats($player)->set("breaks", $current + $breaks);
        $this->saveStats($player);
    }
    
    public function addPlaces(Player $player, $places){
        $current = $this->getStats($player)["places"];
        $this->getStats($player)->set("places", $current + $places);
        $this->saveStats($player);
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
                $this->addKills($event->$cause->getDamager(), 1);
            }
            if($player instanceof Player){
                $this->addDeaths($player->getName(), 1);
            }
        }
    }
}
