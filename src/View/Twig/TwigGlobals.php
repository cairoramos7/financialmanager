<?php

namespace CROFin\View\Twig;

use CROFin\Auth\AuthInterface;
use Twig_Extension;
use Twig_Extension_GlobalsInterface;

class TwigGlobals extends Twig_Extension implements Twig_Extension_GlobalsInterface
{
    /**
     * @var AuthInterface
     */
    private $auth;

    /**
     * TwigGlobals constructor.
     *
     * @param AuthInterface $auth
     */
    public function __construct(AuthInterface $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @return array
     */
    public function getGlobals()
    {
        return [
            'Auth' => $this->auth
        ];
    }

}