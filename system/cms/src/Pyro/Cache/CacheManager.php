<?php namespace Pyro\Cache;

use Illuminate\Cache\CacheManager as IlluminateCacheManager;
use Illuminate\Cache\FileStore;
use Illuminate\Cache\RedisStore;
use Illuminate\Filesystem\Filesystem;

class CacheManager extends IlluminateCacheManager
{
	/**
	 * Create an instance of the file cache driver.
	 *
	 * @return \Illuminate\Cache\FileStore
	 */
	protected function createFileDriver()
	{
		$path = $this->app['config']['cache.path'];

		return $this->repository(new FileStore(new Filesystem, $path));
	}

	/**
	 * Create an instance of the Redis cache driver.
	 *
	 * @return \Illuminate\Cache\RedisStore
	 */
	protected function createRedisDriver()
	{
		return $this->repository(new RedisStore($this->app['config']['redis'], $this->getPrefix()));
	}


	/**
	 * Get a cache collection of keys or set the keys to be indexed
	 * @param  string $collectionKey
	 * @param  array  $keys
	 * @return object
	 */
	public function collection($collectionKey, $keys = array())
	{
		if ($cached = ci()->cache->get($collectionKey) and is_array($cached)) {
			$keys = array_merge($keys, $cached);
		}

		$collection = CacheCollection::make($keys);

		return $collection->setKey($collectionKey);
	}
}