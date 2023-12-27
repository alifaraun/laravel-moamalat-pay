<?php

namespace MoamalatPay\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DisallowedRequestEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
}
