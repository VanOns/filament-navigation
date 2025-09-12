<?php

namespace VanOns\FilamentNavigation\Traits;

use Illuminate\Support\MessageBag;

trait FixesLivewireErrorBag
{
    /**
     * This is a workaround for the bag being `null` during tests.
     */
    public function getErrorBag(): MessageBag
    {
        $bag = parent::getErrorBag();

        return $bag instanceof MessageBag ? $bag : new MessageBag((array) $bag);
    }
}
