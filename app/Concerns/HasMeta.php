<?php

namespace App\Concerns;

use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * Trait HasMeta
 * @package App\Concerns
 */
trait HasMeta
{

    /**
     * @var string
     */
    protected $metaNamespace = 'App\Models\Metas\\';

    /**
     * Get the meta.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    protected function meta(): Relation
    {
        return $this->hasMany($this->metaNamespace . $this->getMetaClass(), $this->getMetaForeignKey());
    }

    /**
     * @return string
     * @throws \UnexpectedValueException
     */
    protected function getMetaClass()
    {
        return class_basename(get_class($this)) . 'Meta';
    }

    /**
     * @return string
     * @throws \UnexpectedValueException
     */
    protected function getMetaForeignKey(): string
    {
        return sprintf('%s_id', strtolower(class_basename(get_class($this))));
    }

    /**
     * Get the meta value
     *
     * @param string $key
     * @return mixed
     */
    protected function getMeta(string $key)
    {
        $value = $this->meta()->where('meta_key', $key)->value('meta_value');
        return maybe_unserialize($value);
    }
}
