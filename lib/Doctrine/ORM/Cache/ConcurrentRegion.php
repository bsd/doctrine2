<?php


declare(strict_types=1);

namespace Doctrine\ORM\Cache;

/**
 * Defines contract for concurrently managed data region.
 * It should be able to lock an specific cache entry in an atomic operation.
 *
 * When a entry is locked another process should not be able to read or write the entry.
 * All evict operation should not consider locks, even though an entry is locked evict should be able to delete the entry and its lock.
 *
 * @since   2.5
 * @author  Fabio B. Silva <fabio.bat.silva@gmail.com>
 */
interface ConcurrentRegion extends Region
{
    /**
     * Attempts to read lock the mapping for the given key.
     *
     * @param \Doctrine\ORM\Cache\CacheKey $key The key of the item to lock.
     *
     * @return \Doctrine\ORM\Cache\Lock A lock instance or NULL if the lock already exists.
     *
     * @throws \Doctrine\ORM\Cache\LockException Indicates a problem accessing the region.
     */
    public function lock(CacheKey $key);

    /**
     * Attempts to read unlock the mapping for the given key.
     *
     * @param \Doctrine\ORM\Cache\CacheKey $key  The key of the item to unlock.
     * @param \Doctrine\ORM\Cache\Lock     $lock The lock previously obtained from {@link readLock}
     *
     * @return void
     *
     * @throws \Doctrine\ORM\Cache\LockException Indicates a problem accessing the region.
     */
    public function unlock(CacheKey $key, Lock $lock);
}
