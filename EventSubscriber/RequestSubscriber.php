<?php

namespace Mh\PageBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class RequestSubscriber implements EventSubscriberInterface
{
    public function onKernelRequest(RequestEvent $event)
    {
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
