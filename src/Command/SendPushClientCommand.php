<?php
/**
 * Created by PhpStorm.
 * User: MOHAMEDfanane
 * Date: 06/06/2018
 * Time: 12:27
 */
namespace App\Command;
use App\Entity\Client;
use App\Entity\Device;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\Notification;
use App\Services\Push\Tokens;
use App\Services\Push\PushClient;
use Doctrine\ORM\EntityManagerInterface;

class SendPushClientCommand extends ContainerAwareCommand
{

    /**
     * @var EntityManagerInterface
     */
    private $entitymanager;

    /**
     * @var Tokens
     */
    private $tokens;

    /**
     * @var PushClient
     */
    private $pushClient;

    /**
     * SendPushClientCommand constructor.
     * @param EntityManagerInterface $entityManager
     * @param Tokens $tokens
     */
    public function __construct(EntityManagerInterface $entityManager, Tokens $tokens, PushClient $pushClient)
    {
        parent::__construct();
        $this->entitymanager = $entityManager;
        $this->tokens = $tokens;
        $this->pushClient = $pushClient;

    }

    /**
     *
     */
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('send:push_client')

            // the short description shown while running "php bin/console list"
            ->setDescription('Envoi push client.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to send push client...');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Envoi push client start ============ '.date('Y-m-d H:i:s')
        ]);
        $em = $this->getContainer()->get('doctrine')->getManager();
        $Notifications = $em->getRepository(Notification::class)->getPushClient();
        foreach ($Notifications as $Notification){
            $client = $Notification->getClient();
            if ($client instanceof Client){
                $device = $client->getDevice();
                if ($device instanceof Device){

                    $tokens[0] = $this->tokens->getTokenByClient($device);
                    if(empty($tokens[0])) continue;
                    $retour = $this->pushClient->sendPush(array(
                        'title' => $Notification->getSujet(),
                        'message' => $Notification->getMessage(),
                        'id' => $Notification->getId(),
                        'canal' => !is_null($device->getCanal())?$device->getCanal():'mamda',
                    ),$tokens);
                    if (isset($retour['success'])){
                        $output->writeln([
                            'Push notification envoi avec succès id : '.$Notification->getId()
                        ]);
                        $Notification->setStatut(1);
                        $em->flush();
                    }

                }

            }

        }

        $output->writeln([
            'Envoi push client end ============ '.date('Y-m-d H:i:s'),
            '==========================================================================='
        ]);
    }

}