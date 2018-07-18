<?php

namespace App\Fixtures;

use App\Entity\Profile;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->encoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        //Create profile Admin
        $profileAdmin = new Profile();
        $profileAdmin->setFirstname('Jack');
        $profileAdmin->setLastname('Sparrow');
        $manager->persist($profileAdmin);
        $manager->flush();

        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setEmail('admin@admin.fr');
        $password = $this->encoder->encodePassword($userAdmin, 'admin');
        $userAdmin->setPassword($password);
        $userAdmin->setRoles(['ROLE_ADMIN']);
        $userAdmin->setProfile($profileAdmin);

        $manager->persist($userAdmin);
        $manager->flush();

        //Create profile User
        $profile = new Profile();
        $profile->setFirstname('Forrest');
        $profile->setLastname('Gump');
        $manager->persist($profile);
        $manager->flush();

        $user = new User();
        $user->setUsername('gump');
        $user->setEmail('gump@gump.fr');
        $password = $this->encoder->encodePassword($user, 'gump');
        $user->setPassword($password);
        $user->setRoles(['ROLE_USER']);
        $user->setProfile($profile);

        $manager->persist($user);
        $manager->flush();
    }
}
