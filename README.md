# sfEnforceLogPlugin

The sfEnforceLogPlugin provides a mechanism to enforce logging of log entries that will not be logged by sfLogger.

## Configuration

First enable the sfEnforceLogPlugin within your project's configuration.

```php
    // config/ProjectConfiguration.class.php
    class ProjectConfiguration extends sfProjectConfiguration
    {
      public function setup()
      {
        $this->enablePlugins(array(
          // ..
          'sfEnforceLogPlugin',
        ));
      }
    }
```

Afterwards you have to register the listener to the ``application.log`` event.

```php
    // config/ProjectConfiguration.class.php
    class ProjectConfiguration extends sfProjectConfiguration
    {
      public function setup()
      {
        // ..
        
        $this->getEventDispatcher()->connect('application.log', array('sfEnforceLogListener', 'listenToLogEvent'));
      }
    }
```

## Defining enforced log entries

Now you can add enforcement listeners to the ``enforcelog.define`` event.
There is an example available within the ``sfEnforceLogListener``. It will ensure logging of mails being sent.

How you define a log entry, that will be enforced, is completely up to you!

```php
    // config/ProjectConfiguration.class.php
    class ProjectConfiguration extends sfProjectConfiguration
    {
      public function setup()
      {
        // ..
        
        $this->getEventDispatcher()->connect('enforcelog.define', array('sfEnforceLogListener', 'enforceLogMail'));
      }
    }
```

What will happen? The priority of the given log entry will be set to the log level configured, if required.