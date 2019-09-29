<?php

namespace Mh\PageBundle\EventSubscriber;

use Psr\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class RequestSubscriber implements EventSubscriberInterface
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $site = $this->container->get('Mh\PageBundle\Helper\SiteHelper');
        $site->setDefaults();

        $request = $event->getRequest();
        $server = $request->server;

        $attr = new \StdClass();
        $attr->key = $request->get('key');
        $attr->user_agent = $server->get('HTTP_USER_AGENT');
        $attr->http_referer = $server->get('HTTP_REFERER');

        if ($request->get('key')) {
            $request->getSession()->set('referral', $attr);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            RequestEvent::class => 'onRequestEvent',
        ];
    }
}
