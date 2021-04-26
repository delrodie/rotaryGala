<?php


namespace App\Utilities;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class Utils
{
    private $entityManager;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Initialisation des utilisateurs par la creation du compte du super admin
     *
     * @return bool
     */
    public function initalisationUser()
    {
        $user = new User();
        $user->setUsername('delrodie');
        $user->setPassword($this->passwordEncoder->encodePassword($user, '1243567890'));
        $user->setRoles(['ROLE_SUPER_ADMIN']);
        $user->setEmail('delrodieamoikon@gmail.com');

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return true;
    }
}