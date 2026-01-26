<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use MongoDB\Laravel\Eloquent\Model;

/**
 * @property-read mixed $id
 * @property-read \App\Models\Post|null $post
 * @property-read \App\Models\User|null $uploader
 * @method static \MongoDB\Laravel\Eloquent\Builder<static>|SourceFile addHybridHas(\Illuminate\Database\Eloquent\Relations\Relation $relation, string $operator = '>=', string $count = 1, string $boolean = 'and', ?\Closure $callback = null)
 * @method static \MongoDB\Laravel\Eloquent\Builder<static>|SourceFile aggregate($function = null, $columns = [])
 * @method static \MongoDB\Laravel\Eloquent\Builder<static>|SourceFile getConnection()
 * @method static \MongoDB\Laravel\Eloquent\Builder<static>|SourceFile insert(array $values)
 * @method static \MongoDB\Laravel\Eloquent\Builder<static>|SourceFile insertGetId(array $values, $sequence = null)
 * @method static \MongoDB\Laravel\Eloquent\Builder<static>|SourceFile newModelQuery()
 * @method static \MongoDB\Laravel\Eloquent\Builder<static>|SourceFile newQuery()
 * @method static \MongoDB\Laravel\Eloquent\Builder<static>|SourceFile query()
 * @method static \MongoDB\Laravel\Eloquent\Builder<static>|SourceFile raw($value = null)
 * @method static \MongoDB\Laravel\Eloquent\Builder<static>|SourceFile search(\MongoDB\Builder\Type\SearchOperatorInterface|array $operator, ?string $index = null, ?array $highlight = null, ?bool $concurrent = null, ?string $count = null, ?string $searchAfter = null, ?string $searchBefore = null, ?bool $scoreDetails = null, ?array $sort = null, ?bool $returnStoredSource = null, ?array $tracking = null)
 * @method static \MongoDB\Laravel\Eloquent\Builder<static>|SourceFile vectorSearch(string $index, string $path, array $queryVector, int $limit, bool $exact = false, \MongoDB\Builder\Type\QueryInterface|array $filter = [], ?int $numCandidates = null)
 * @mixin \Eloquent
 */
class SourceFile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'filename',
        'file_path',
        'file_type',
        'file_size',
        'mime_type',
        'uploaded_by',
        'uploaded_session',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
        ];
    }

    public function post(): HasOne
    {
        return $this->hasOne(Post::class, 'source_file_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
