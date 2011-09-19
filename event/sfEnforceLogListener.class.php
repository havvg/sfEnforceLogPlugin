<?php

class sfEnforceLogListener
{
  /**
   * An event listener for application.log events.
   *
   * This will fire an event to define whether this entry should be forced to the log.
   * The event will be dispatched with the name 'enforcelog.define' and is subject to the log event.
   * If any listener processes this event, the log will be enforced, if required.
   *
   * @param sfEvent $event
   *
   * @return void
   */
  public static function listenToLogEvent(sfEvent $event)
  {
    if (!sfContext::hasInstance())
    {
      return;
    }

    // Do not act on dispatched application.log event.
    if (!empty($event['enforced']))
    {
      return;
    }

    $context = sfContext::getInstance();

    // The minimum log priority of a log entry to be logged without enforcement.
    $logLevel = $context->getLogger()->getLogLevel();

    $priority = sfLogger::INFO;
    if (isset($event['priority']))
    {
      $priority = $event['priority'];

      if (!is_int($priority))
      {
        $priority = constant('sfLogger::' . strtoupper($priority));
      }
    }

    // This log entry will not be logged by default.
    if ($logLevel < $priority)
    {
      $defineEvent = $context->getEventDispatcher()->notifyUntil(new sfEvent($event, 'enforcelog.define'));

      // At least one listener wants to force this entry to be logged.
      if ($defineEvent->isProcessed())
      {
        $event['priority'] = $logLevel;
        $event['enforced'] = true;

        $context->getEventDispatcher()->notify($event);
      }
    }
  }
}
