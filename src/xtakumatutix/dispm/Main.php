<?php

// アイコン:http://flat-icon-design.com/?p=400

namespace xtakumatutix\dispm;

use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\Listener;
use xtakumatutix\dispm\postCommand;
use bbo51dog\pmdiscord\Sender;
use bbo51dog\pmdiscord\element\Content;

Class Main extends PluginBase implements Listener
{

    public function onEnable() 
    {
        $this->getLogger()->notice("起動メッセージを送信しました - ver.".$this->getDescription()->getVersion());
        $this->getServer()->getCommandMap()->register("post", new postCommand($this));
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML, [
            'token' => 'https://discordapp.com/api/webhooks/00000/xxxxx'
        ]);
    }
    public function onJoin(PlayerJoinEvent $event)
    {
    	$this->post[$event->getPlayer()->getName()] = true;
    }
}