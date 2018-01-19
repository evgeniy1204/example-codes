<?php

// src/Acme/ApiBundle/OAuth/BingoGrantExtension.php

namespace WideWeb\AppBundle\OAuth;

use FOS\OAuthServerBundle\Storage\GrantExtensionInterface;
use OAuth2\Model\IOAuth2Client;
use FOS\UserBundle\Doctrine\UserManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
* \brief Авторизация через FB
*
*/
class FbGrantExtension implements GrantExtensionInterface
{
    private $userManager;
    public function __construct(\FOS\UserBundle\Doctrine\UserManager $userManager)
    {
        $this->userManager = $userManager;
    }
    /*
     * {@inheritdoc}
     */
    public function checkGrantExtension(IOAuth2Client $client, array $inputData, array $authHeaders)
    {
        if (isset($inputData['email']) && $inputData['email'] != "" && isset($inputData['fb']) && $inputData['fb'] != "") {
            $email = $inputData['email'];
            $user = $this->userManager->findUserBy(array("username" => $email));

            if (!$user) {
                throw new AccessDeniedHttpException("User not registered");
            }

            if (!$user->isEnabled()) {
                throw new AccessDeniedException('User locked');
            }

            if (!$user->getFacebookID()) {
                throw new NotFoundHttpException('You already registered, but not connected your FB account. Login using your email and password and connect FB in your profile');
            }

            if ($user->getFacebookID() != $inputData['fb']) {
                throw new AccessDeniedException('Invalid fb');
            }

            if ($user->getFacebookID() == $inputData['fb']) {
                return array('data' => $user);
            }
        }
    }
}