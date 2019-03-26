<?php
/**
 * Created by PhpStorm.
 * User: gbtux
 * Date: 26/03/19
 * Time: 14:36
 */

namespace App\EventListener;

use App\Entity\Repertoire;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class HomeIDListener
 * @package App\EventListener
 */
class HomeIDListener implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_COMPLETED => 'onRegistrationSuccess',
        );
    }

    public function onRegistrationSuccess(FilterUserResponseEvent $event)
    {
        $userId = $event->getUser()->getId();
        $user = $this->manager->getRepository('App:Utilisateur')->findOneBy(['id' => $userId]);
        $homeId = uniqid("home", true);
        $user->setHomeID($homeId);
        $user->addRole('ROLE_USER');
        $this->manager->persist($user);

        $home = new Repertoire();
        $home->setNom('Home de '. $user->getUsername());
        $home->setHash($homeId);
        $this->manager->persist($home);

        $this->manager->flush();

    }

}