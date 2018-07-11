<?php

class GANALYTICS_CLASS_EventHandler
{
    private static $classInstance;

    public static function getInstance()
    {
        if ( self::$classInstance === null )
        {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }

    private function __construct()
    {

    }

    public function init()
    {
        OW::getEventManager()->bind(OW_EventManager::ON_PLUGINS_INIT, [$this, 'afterInit']);
    }

    public function afterInit()
    {
        OW::getEventManager()->bind(OW_EventManager::ON_FINALIZE, [$this, 'googleAnalyticsAddCode']);
        OW::getEventManager()->bind('admin.add_admin_notification', [$this, 'googleAnalyticsAdminNotification']);
    }

    public function googleAnalyticsAddCode( OW_Event $event )
    {
        $webPropertyId = trim(OW::getConfig()->getValue('mganalytics', 'web_property_id'));

        if ( $webPropertyId !== null )
        {
            $code = "
            <!-- Google Analytics -->
            <script>
                window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
                ga('create', '" . $webPropertyId . "', 'auto');
                ga('send', 'pageview');
            </script>
            <script async src='https://www.google-analytics.com/analytics.js'></script>
            <!-- End Google Analytics -->";

            OW::getDocument()->appendBody($code);
        }
    }

    public function googleAnalyticsAdminNotification( BASE_CLASS_EventCollector $event )
    {
        $webPropertyId = OW::getConfig()->getValue('ganalytics', 'web_property_id');

        if ( empty($webPropertyId) )
        {
            $event->add(OW::getLanguage()->text('ganalytics', 'admin_notification_text', array('link' => OW::getRouter()->urlForRoute('ganalytics_admin'))));
        }
    }

}