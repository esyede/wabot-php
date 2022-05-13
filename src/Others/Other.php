<?php

namespace Esyede\Wabot\Others;

use Esyede\Wabot\Http\Initiator as HttpInitiator;
use Esyede\Wabot\Http\Request as HttpRequest;
use Esyede\Wabot\Helpers\Common;
use Closure;

class Other
{
    private $initiator;
    private $request;

    public function __construct(HttpInitiator $initiator, HttpRequest $request)
    {
        $this->initiator = $initiator;
        $this->request = $request;
    }

    public function isOnWhatsapp($phoneNumber, Closure $callback = null)
    {
        $phoneNumber = Common::toIndonesianPhonePrefix($phoneNumber);

        return $this->request
            ->withCallback($callback)
            ->get('misc/onwhatsapp?id=' . $phoneNumber);
    }

    public function downloadProfilePicture($phoneNumber, Closure $callback = null)
    {
        $phoneNumber = Common::toIndonesianPhonePrefix($phoneNumber);

        return $this->request
            ->withCallback($callback)
            ->get('misc/downProfile?id=' . $phoneNumber);
    }

    public function getUserStatus($phoneNumber, Closure $callback = null)
    {
        $phoneNumber = Common::toIndonesianPhonePrefix($phoneNumber);

        return $this->request
            ->withCallback($callback)
            ->get('misc/getStatus?id=' . $phoneNumber);
    }

    public function blockUser($phoneNumber, Closure $callback = null)
    {
        $phoneNumber = Common::toIndonesianPhonePrefix($phoneNumber);

        return $this->request
            ->withCallback($callback)
            ->get('misc/blockUser?id=' . $phoneNumber);
    }
}
