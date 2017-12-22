<?php

namespace LwtHelper\Redis;

use LwtHelper\Redis\ARedisEntity;

class ARedisString extends ARedisEntity
{

    public function get()
    {
        if ($this->name === null) {
            throw new CException("No name specified for " . get_class($this));
        }

        return $this->getClient()->get($this->name);
    }

    public function set($value, $exptime = 0)
    {
        if ($this->name === null) {
            throw new CException("No name specified for " . get_class($this));
        }
        return $exptime == 0 ? $this->getClient()->set($this->name, $value) : $this->getClient()->setex($this->name, $exptime, $value);
    }

    public function delete()
    {
        if ($this->name === null) {
            throw new CException(get_class($this) . " requires a name!");
        }

        return $this->getClient()->delete($this->name);
    }

}
