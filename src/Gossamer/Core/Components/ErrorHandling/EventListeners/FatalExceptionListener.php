<?php
/*
 *  This file is part of the Quantum Unit Solutions development package.
 *
 *  (c) Quantum Unit Solutions <http://github.com/dmeikle/>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

/**
 * Created by PhpStorm.
 * User: user
 * Date: 3/5/2017
 * Time: 3:53 PM
 */

namespace Gossamer\Core\Components\ErrorHandling\EventListeners;


use Gossamer\Core\EventListeners\AbstractListener;
use Gossamer\Horus\EventListeners\Event;

class FatalExceptionListener extends AbstractListener
{

    public function on_error_occurred(Event $event) {
        die('error - in exception listener');
    }
}