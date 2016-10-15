<?php

namespace EnderStats\API;

use EnderStats\Main;
use pocketmine\Player;
use pocketmine\utils\Config;

class EnderStats {
    protected $plugin;
    
    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
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
        $this->getStats($player)->set("kills", $current + $deaths);
        $this->saveStats($player);
    }
    
    public function addBreaks(Player $player, $breaks){
        $current = $this->getStats($player)["breaks"];
        $this->getStats($player)->set("kills", $current + $breaks);
        $this->saveStats($player);
    }
    
    public function addPlaces(Player $player, $places){
        $current = $this->getStats($player)["places"];
        $this->getStats($player)->set("kills", $current + $places);
        $this->saveStats($player);
    }
}
