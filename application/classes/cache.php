<?php
class Cache
{

    private $_result = '';

    public $cacheLifetime = '';

    private $EnableCache = NULL;

    /*Define the below for preventing the error where directly used this class added by PP on 16/11/2012 */
    /**
     * Directory folder where the cache is saved
     *
     * @var string
     */
    public $cacheDirectory;

    public function __construct() {
        $this->EnableCache = Zend_Registry::get('ENABLE_CACHE');

        $application = Zend_Registry::get('application');

        $this->cacheLifetime = 3600;

        //$this->setCache();
    }

    /*public function setCache()
    {
        if(!file_exists($this->cacheDirectory))
            mkdir ($this->cacheDirectory, 0777, true);

        $frontendOptions = array('automatic_serialization' => TRUE);
        $backendOptions = array('cache_dir' => $this->cacheDirectory);
        $cacheObj = Zend_Cache::factory('Core', 'file', $frontendOptions, $backendOptions);
        Zend_Registry::set('cacheObj', $cacheObj);
    }*/

    /**
     * Gets the Results from cache of datasource
     * $cacheDir - directory path to store the cache file
     * $cacheKey - Cache Identifier
     */
    public function getResult($cacheKey)
    {
       if(empty($this->_result) && $this->EnableCache == TRUE)
            $this->_result = Zend_Registry::get('cacheObj')->load($cacheKey);
        return $this->_result;
    }

    /**
     * Checks the cache if exists
     *
     * * @param $cacheKey - Cache Identifier
     */
    public function checkCache($cacheKey)
    {
        $result = FALSE;
        if($this->EnableCache == TRUE) {
            $this->_result = Zend_Registry::get('cacheObj')->load($cacheKey);
            if ($this->_result) {
               $result = TRUE;
            }
        }
        return $result;
    }

    /*public function setCacheDirectory()
    {
        $appCachePath = Zend_Registry::get('cachePath');
        if(!file_exists($this->cacheDirectory))
        {
            $cachePath = substr($this->cacheDirectory, strlen($appCachePath)+1);
            $partsArray = explode("/", $cachePath);
            foreach ($partsArray as $part) {
                if (!is_dir($appCachePath. "/". $part)) {
                    @mkdir($appCachePath. "/". $part, 0777);
                    @chmod($appCachePath. "/". $part, 0777);
                    $appCachePath = $appCachePath. "/".$part;
                }
            }
            $cacheObj = Zend_Registry::get('cacheObj');
            echo "SEtoption <br>";
            $cacheObj->setOption("cache_dir", $this->cacheDirectory);
            exit;
            Zend_Registry::set('cacheObj', $cacheObj);
        }
    }*/

    /**
     * Gets the Results from cache of datasource
     *
     * @param $cacheKey - Cache Identifier
     * @param $cacheValue - Cache value
     * @param $tags - Array of tags used to map with the cache
     */
    public function createCache($cacheValue, $cacheKey, $tags = array(), $specificLifeTime = '')
    {

        if($this->EnableCache == TRUE) {
            /*if(empty($specificLifeTime) && empty($this->cacheLifetime))
                $specificLifeTime = Zend_Registry::get ('cacheLargeTimeOut');
            else if(empty($specificLifeTime) && $this->cacheLifetime > 0)*/

            //$this->setCache();
            $specificLifeTime = $this->cacheLifetime;

            Zend_Registry::get('cacheObj')->save($cacheValue, $cacheKey, $tags, $specificLifeTime);

                        /* The below code is used to add the cache name in requested cache file */
           /* if(Zend_Registry::isRegistered("removeCacheFileName") != NULL) {
                $removeCacheFileName = Zend_Registry::get("removeCacheFileName");
                if(!$allCacheArray = $this->getResult($removeCacheFileName))
                {
                    $allCacheArray = array();
                }
                array_push($allCacheArray, $cacheKey);
                $finalAllCacheArray = array_unique($allCacheArray);
                Zend_Registry::get('cacheObj')->save($finalAllCacheArray, $removeCacheFileName, array(), Zend_Registry::get('cacheTagTimeOut'));
            }*/
            /* The below code is used to add the cache name in requested cache file */
        }
    }

    /**
     * Removes/Deletes a particular cache with a cache key
     *
     * @param $cacheKey - Cache key which is to be deleted
     */
    public function removeCache($cacheKey)
    {
        if($this->EnableCache == TRUE) {
           Zend_Registry::get('cacheObj')->remove($cacheKey);
        }
    }

    /**
     * Removes/Deletes a cache using tags
     *
     * @param $tags - Array of tag values
     */
    public function removeCacheMatchingTags($tags = array())
    {
        if($this->EnableCache == TRUE) {
            return Zend_Registry::get('cacheObj')->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, $tags);
        }
    }

    public function removeallCache()
    {
        if($this->EnableCache == TRUE) {
            Zend_Registry::get('cacheObj')->clean(Zend_Cache::CLEANING_MODE_ALL);
        }
    }

    public function getCacheLifeTime()
    {
        if (!empty($this->cacheLifetime)) {
            return $this->cacheLifetime;
        }
        return '';
    }

    /**
     * Destructor
     *
     */
    public function __destruct()
    {
        unset($this);
    }
}

