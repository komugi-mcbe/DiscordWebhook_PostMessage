<?php

// アイコン:http://flat-icon-design.com/?p=400

namespace xtakumatutix\dispm;

use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;
use bbo51dog\pmdiscord\Sender;
use bbo51dog\pmdiscord\element\Content;

Class postCommand extends Command 
{
    private $Main;

    public function __construct(Main $Main)
    {
        $this->Main = $Main;
        parent::__construct("post", "Discordにメッセージを送信します", "/post");
        $this->setPermission("dispm.command.post");
        $this->setDescription("Discordにメッセージを送信します");
        $this->setUsage("/post <送信したいメッセージ>");
    }

	public function execute(CommandSender $sender, string $commandLabel, array $args): bool
	{
		if($this->Main->post[$sender->getName()] == true)
		{
			$sender->sendMessage("aaaaaa");
		    $this->Main->post[$sender->getName()] = false;
		    return true;
		}
		if($this->Main->post[$sender->getName()] == false)
		{
			$sender->sendMessage("だめ");
			return true;
		}

        $task = new ClosureTask(function (int $currentTick) use ($sender): void {
            $this->Main->post[$sender->getName()] = true;
        });
        $plugin = Server::getInstance()->getPluginManager()->getPlugin("DiscordWebhookPostMessage");
        /** @var Plugin $plugin */
        $plugin->getScheduler()->scheduleDelayedTask($task, 20 * 3);//5秒後に実行
	}
}