<?php

/**
 * Additional functionality for extending the TimberPost object
 */
namespace Skela\Models;

use Timber\Post;
use Timber\Timber;
use Timber\Image;

class SkelaPost extends Post
{
    /**
     * Example Function
     *
     * @return void
     */
    public function getFormattedAuthors()
    {
        return "Jane Doe, Roger Smith";
    }
}
