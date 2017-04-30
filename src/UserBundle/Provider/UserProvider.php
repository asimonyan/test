<?php
/**
 * Created by PhpStorm.
 * User: aram
 * Date: 4/30/17
 * Time: 7:23 PM
 */

namespace UserBundle\Provider;

use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Security\UserProvider as BaseProvider;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class UserProvider
 * @package UserBundle\Provider
 */
class UserProvider extends BaseProvider
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var
     */
    private $captchaSecurity;

    /**
     * UserProvider constructor.
     * @param UserManagerInterface $userManager
     * @param RequestStack $requestStack
     * @param $captchaSecurity
     */
    public function __construct(UserManagerInterface $userManager,
                                RequestStack $requestStack,
                                $captchaSecurity)
    {
        parent::__construct($userManager);
        $this->requestStack = $requestStack;
        $this->captchaSecurity = $captchaSecurity;
    }
    /**
     * {@inheritDoc}
     */
    public function loadUserByUsername($username)
    {
        // get request
        $request = $this->requestStack->getCurrentRequest();

        // get captcha from request
        $captcha = $request->get('g-recaptcha-response');

        // generate validate url
        $googleValidateUrl = "https://www.google.com/recaptcha/api/siteverify";
        $googleValidateUrl.="?secret=" . $this->captchaSecurity ;
        $googleValidateUrl .= "&response=" . $captcha;

        // check validation from google
        $captchaResult = file_get_contents($googleValidateUrl);
        $captchaResult = json_decode($captchaResult);

        // check captcha result
        if(isset($captchaResult->success) && $captchaResult->success){
            return parent::loadUserByUsername($username);
        }

        throw new \Exception('Wrong captcha');
    }


}