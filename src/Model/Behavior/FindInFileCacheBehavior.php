<?php

namespace FindInFileCache\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\Event\Event;
use Cake\ORM\Query;
use ArrayObject;
use Cake\Cache\Cache;

class FindInFileCacheBehavior extends Behavior
{
    protected $_defaultConfig = [
        'className' => 'File',
        'prefix' => 'myapp_cake_static_record_',
        'path' => CACHE . 'static_records/',
        'duration' => '+15 minutes',
        'mask' => 0666,
    ];

    /**
     * beforeFind
     *
     * @param \Cake\Event\Event $event The beforeFind event that was fired.
     * @param \Cake\ORM\Query $query Query
     * @param \ArrayObject $options The options for the query
     * @param $primary
     * @return void
     */
    public function beforeFind(Event $event, Query $query, ArrayObject $options, $primary)
    {
        $key = $this->cacheKey($query);
        $config = $this->cacheConfig();

        // Set query cache parameter.
        // If cache does not hit and this parameter exist, select results from DB store in cache.
        $query->cache($key, $config);

        $results = Cache::read($key, $config);
        // Stop after events include find event
        if (isset($results)) {
            $query->setResult($results);
            $event->stopPropagation();
        }
    }

    /**
     * return cache key made by query
     *
     * @param \Cake\ORM\Query $query Query
     * @return string $key
     */
    private function cacheKey(Query $query)
    {
        $key = $this->_table->alias();
        $elements = ['select', 'where', 'group', 'order', 'limit'];
        foreach ($elements as $element) {
            if (!empty($query->clause($element))) {
                $key .= serialize($query->clause($element));
            }
        }
        return md5($key);
    }

    /**
     * return cache config name
     *
     * @return string $name
     */
    private function cacheConfig()
    {
        $name = 'find-in-file';
        // try to get user setting
        $config = Cache::config($name);
        if (empty($config)) {
            // default setting
            Cache::config($name, $this->_defaultConfig);
        }
        return $name;
    }
}
