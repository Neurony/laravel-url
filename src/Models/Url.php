<?php

namespace Zbiller\Url\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\Builder;
use Zbiller\Url\Contracts\UrlModelContract;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Url extends Model implements UrlModelContract
{
    /**
     * The database table.
     *
     * @var string
     */
    protected $table = 'urls';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url',
        'urlable_id',
        'urlable_type',
    ];

    /**
     * Get all of the owning urlable models.
     *
     * @return MorphTo
     */
    public function urlable() : MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Filter the query by url.
     *
     * @param Builder $query
     * @param string $url
     */
    public function scopeWhereUrl(Builder $query, string $url)
    {
        $query->where('url', $url);
    }

    /**
     * Filter the query by the urlable morph relation.
     *
     * @param Builder $query
     * @param int $id
     * @param string $type
     */
    public function scopeWhereUrlable(Builder $query, int $id, string $type)
    {
        $query->where([
            'urlable_id' => $id,
            'urlable_type' => $type,
        ]);
    }

    /**
     * Sort the query alphabetically by url.
     *
     * @param Builder $query
     */
    public function scopeInAlphabeticalOrder(Builder $query)
    {
        $query->orderBy('url', 'asc');
    }

    /**
     * Get the model instance correlated with the accessed url.
     *
     * @param bool $silent
     * @return Model|null
     * @throws ModelNotFoundException
     */
    public static function getUrlable(bool $silent = true): ?Model
    {
        $model = Request::route()->action['model'] ?? null;

        if ($model && $model instanceof Model && $model->exists) {
            return $model;
        }

        if ($silent === false) {
            throw new ModelNotFoundException;
        }
    }

    /**
     * Get the model instance correlated with the accessed url.
     * Throw a ModelNotFoundException if the model doesn't exist.
     *
     * @return Model|null
     * @throws ModelNotFoundException
     */
    public static function getUrlableOrFail(): ?Model
    {
        return static::getUrlable(false);
    }
}
