<?php

namespace App\Security;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Ldap\Entry;
use Symfony\Component\Ldap\Security\LdapUserProvider as BaseLdapUserProvider;
use Symfony\Component\Ldap\Security\LdapUser;
use App\Entity\Security\UserRepositoryInterface;

class CustomLdapUserProvider extends BaseLdapUserProvider implements ContainerAwareInterface
{
    private $container;
    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof LdapUser) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        if ($user == \NONE){
            throw new UserNotFoundException();
        }

        return new LdapUser($user->getEntry(), $user->getUsername(), null, $user->getRoles());
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass(string $class): bool
    {
	return LdapUser::class === $class || is_subclass_of($class, LdapUser::class);
    }

    /**
     * {@inheritdoc}
     */
    protected function loadUser(string $identifier, Entry $entry): UserInterface
    {

        $user = parent::loadUser($identifier, $entry);
        
        // Fetch the user's actual roles from our database
        $userRepository = $this->container->get('doctrine.orm.entity_manager')->getRepository('App\Entity\Security\User');        
        $databaseRoles = $userRepository->getDatabaseRoles($identifier);
        
        return new LdapUser($entry, $identifier, $user->getPassword(), $databaseRoles);
    }


    /**
     * @param ContainerInterface|NULL $container
     */
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }


    /**
     * @return ContainerInterface
     */
    protected function getContainer()
    {
        return $this->container;
    }

}
