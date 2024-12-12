<?php
namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

use App\Entity\Security\User;

class ApiUserProvider implements UserProviderInterface {

    protected $user;

    public function __construct (UserInterface $user) {
        $this->user = $user;
        var_dump("using API provider");
    }

    /**
     * @throws \Symfony\Component\Security\Core\Exception\UserNotFoundException if the user is not found
     * @param string $username The username
     *
     * @return TUser
     */
    public function loadUserByIdentifier(string $identifier): UserInterface {
        var_dump("using API provider");
        $user = User::find(['apiKey'=>$identifier]);
        if(empty($user)){
            throw new \Symfony\Component\Security\Core\Exception\UserNotFoundException('Could not find user. Sorry!');
        }

        $this->user = $user;
        return $user;
    }

    /**
     *
     *
     * @psalm-return TUser
     *
     * @throws UnsupportedUserException if the user is not supported
     * @throws UserNotFoundException    if the user is not found
     *
     *
     * @return UserInterface
     */
    public function refreshUser(UserInterface $user) {
        return $user;
    }

    /**
     * @param string $class
     *
     * @return Boolean
     */
    public function supportsClass($class) {
        return $class === 'App\Entity\Security\User';
    }
}
