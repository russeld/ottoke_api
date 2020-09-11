<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use App\Tag;

trait HasTags
{
    protected $queuedTags = [];
    
    public static function getTagClassName(): string
    {
        return Tag::class;
    }

    public static function bootHasTags()
    {
        static::created(function (Model $taggableModel) {
            if (count($taggableModel->queuedTags) > 0) {
                $taggableModel->attachTags($taggableModel->queuedTags);

                $taggableModel->queuedTags = [];
            }
        });

        static::deleted(function (Model $deletedModel) {
            $tags = $deletedModel->tags()->get();

            $deletedModel->detachTags($tags);
        });
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(self::getTagClassName(), 'taggable');
    }

    public function attachTags($tags)
    {
        $className = static::getTagClassName();

        $tags = collect($className::findOrCreate($tags));

        $this->tags()->syncWithoutDetaching($tags->pluck('id')->toArray());

        return $this;
    }

    public function attachTag($tag)
    {
        return $this->attachTags([$tag]);
    }

    public function syncTags($tags)
    {
        $className = static::getTagClassName();

        $tags = collect($className::findOrCreate($tags));

        $this->tags()->sync($tags->pluck('id')->toArray());

        return $this;
    }
}
