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
		if ($sender instanceof Player)
		{
			if($this->Main->post[$sender->getName()] == true)
			{
				if(isset($args[0]))
				{
				$sender->sendMessage("§a >> §fDiscordに送信しました！！ [内容:".$args[0]."]");
		        $this->Main->post[$sender->getName()] = false;

                $token = $this->Main->config->get('token');
                $name = $sender->getName();
                $content = new Content();
                $content->setText(">> Post ".$name." \n Message > ".$args[0]);
                $webhook = Sender::create($token)
                    ->add($content)
                    ->setCustomName("ChatPost")
                    ->setCustomAvatar("https://user-images.githubusercontent.com/47268002/82459927-7c380d00-9af3-11ea-9020-1352e23ab192.png");
                Sender::send($webhook);

                $task = new ClosureTask(function (int $currentTick) use ($sender): void {
                    $this->Main->post[$sender->getName()] = true;
                });
                $plugin = Server::getInstance()->getPluginManager()->getPlugin("DiscordWebhookPostMessage");
                $plugin->getScheduler()->scheduleDelayedTask($task, 20 * 15);
                return true;
            }else{
            	$sender->sendMessage("§c >> §f送信したいメッセージを入力してください");
            	return true;
            }
        }else{
        	$sender->sendMessage("§c >> §f送信したら15秒待ってから送信してください");
        	return true;
        }
    }else{
    	$sender->sendMessage("ゲーム内で使用してください");
    	return true;
    }
}
}