<?php
require_once 'Doctrine/Common/ClassLoader.php';

use Doctrine\ORM\EntityManager,
    Doctrine\ORM\Configuration;

class Doctrine
{	
	
	public function init($doctrineOptions)
	{	
		$options = $doctrineOptions;
		
		// Doctrine (use include_path)
		$classLoader = new \Doctrine\Common\ClassLoader('Doctrine');
        $classLoader -> register();
        
        // Entities
        $classLoader = new \Doctrine\Common\ClassLoader(
            'Application\Models',
            dirname(APPLICATION_PATH)
        );
        $classLoader -> register();
        
        // Proxies
        $classLoader = new \Doctrine\Common\ClassLoader(
            'Application\Proxies',
            dirname(APPLICATION_PATH)
        );
        $classLoader -> register();
        
        // Repositories
        $classLoader = new \Doctrine\Common\ClassLoader(
            'Application\Repositories',
            dirname(APPLICATION_PATH)
        );
        $classLoader -> register();
        
        // Now configure doctrine
        if ('development' == APPLICATION_ENV) {
        	$cacheClass = isset($options['cacheClass']) ? $options['cacheClass'] : 'Doctrine\Common\Cache\ArrayCache';
        } else {
        	$cacheClass = isset($options['cacheClass']) ? $options['cacheClass'] : 'Doctrine\Common\Cache\ApcCache';
        }
        $cache = new $cacheClass();
        
        $config = new Configuration();
        $config -> setMetadataCacheImpl($cache);
        $config -> setMetadataDriverImpl(\Doctrine\ORM\Mapping\Driver\AnnotationDriver::create(array($options['entitiesPath'])));
        $config -> setQueryCacheImpl($cache);
        $config -> setProxyDir($options['proxiesPath']);
        $config -> setProxyNamespace('Application\Proxies');
        $config -> setAutoGenerateProxyClasses(('development' == APPLICATION_PATH));
        $em = EntityManager::create(
            $this -> _buildConnectionOptions($options['connection']),
            $config
        );
                
        // end
        return $em;
	}
	
	/**
	 * A method to build the connection options, for a Doctrine
	 * EntityManager/Connection. Sure, we can find a more elegant solution to build
	 * the connection options. A builder class could be applied. Sure you can with
	 * some refactor :)
	 * TODO: refactor to build some other, more elegant, solution to build the conn
	 * ection object.
	 * @param Array $options The options array defined on the application.ini file
	 * @return Array
	 */
	protected function _buildConnectionOptions(array $options)
	{
		$connectionSpec = array(
            'pdo_sqlite' => array('user', 'password', 'path', 'memory'),
            'pdo_mysql'  => array(
                'user', 'password', 'host', 'port', 'dbname', 'unix_socket'
            ),
            'pdo_pgsql'  => array('user', 'password', 'host', 'port', 'dbname'),
            'pdo_oci'    => array(
                'user', 'password', 'host', 'port', 'dbname', 'charset'
            )
		);
		
		$connection = array(
            'driver' => $options['driver']
		);
		
		foreach ($connectionSpec[$options['driver']] as $driverOption) {
			if (isset($options[$driverOption]) && !is_null($driverOption)) {
				$connection[$driverOption] = $options[$driverOption];
			}
		}
		
		if (isset($options['driverOptions'])
            && !is_null($options['driverOptions'])) {
			$connection['driverOptions'] = $options['driverOptions'];
		}
		
		return $connection;
	}
}