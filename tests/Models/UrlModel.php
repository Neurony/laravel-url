<?php

namespace Zbiller\Url\Tests\Models;

use Zbiller\Url\Traits\HasUrl;
use Zbiller\Url\Options\UrlOptions;
use Illuminate\Database\Eloquent\Model;
use Zbiller\Url\Tests\Controllers\UrlsController;

class UrlModel extends Model
{
    use HasUrl;

    /**
     * The database table.
     *
     * @var string
     */
    protected $table = 'url_models';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the options for the UrlOptions trait.
     *
     * @return UrlOptions
     */
    public function getUrlOptions() : UrlOptions
    {
        return UrlOptions::instance()
            ->routeUrlTo(UrlsController::class, 'show')
            ->generateUrlSlugFrom('name')
            ->saveUrlSlugTo('slug');
    }
}
