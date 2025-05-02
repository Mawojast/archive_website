<?php
declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

/**
* Add and remove response header properties
**/ 
#[AsEventListener(event: 'kernel.response')]
class ResponseHeaderListener
{

    public function __invoke(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) return;
        
        $response = $event->getResponse();

        $response->headers->add([
            'Content-Language' => 'de-DE',
            'Strict-Transport-Security' => 'max-age=31536000; preload',
            'Cache-Control' => 'max-age=3600, must-revalidate',
            'Cross-Origin-Resource-Policy' => 'same-origin',
            'Permissions-Policy' => 'accelerometer=(), autoplay=(), camera=(), encrypted-media=(), fullscreen=(), geolocation=(), gyroscope=(), magnetometer=(), microphone=(), midi=(), payment=(), picture-in-picture=(), usb=(), xr-spatial-tracking=()',
        ]);

        header_remove('X-Powered-By');
        header_remove('X-Robots-Tag');
        header_remove('Server');  
    }
}
