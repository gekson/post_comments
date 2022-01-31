<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;

/**
 * Class Base
 * @package App\Models
 * @property-read Collection|Audit[] $audits
 * @property-read int|null $audits_count
 *                                      @method static \Illuminate\Database\Eloquent\Builder|Base newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Base newQuery()
 * @method static Builder|Base onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Base query()
 * @method static Builder|Base withTrashed()
 * @method static Builder|Base withoutTrashed()
 * @mixin Eloquent
 */
class Base extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable,
        Authenticatable,
        SoftDeletes,
        HasFactory;

    /**
     * @var string[]
     */
    protected $hidden = ['deleted_at', 'password'];
    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected array $auditInclude;

    /**
     * Base constructor.
     * @param array $attributes
     *
     *  Custom constructor to set up the auditable attributes
     *  dynamically.
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->auditInclude = $this->getFillable();
    }

    /**
     * Get the value of the model's primary key.
     *
     * This function is a overriding for issue
     * auditable_id is null on audits table.
     *
     * @override
     * @return mixed
     */
    public function getKey(): mixed
    {
        return $this->getAttribute($this->getKeyName()) ?? 0;
    }

    /**
     * @param $array
     * @param $request
     * @return LengthAwarePaginator
     */
    public function arrayPaginator($array, $request): LengthAwarePaginator
    {
        $page = $request->query("page") ?? 1;
        if ($request->query("per_page", null)) {
            $perPage = $request->query("per_page", 10);
        } else if ($request->query("perPage", null)) {
            $perPage = $request->query("perPage", 10);
        } else {
            $perPage = 10;
        }
        $perPage = (int)$perPage;

        $offset = ($page * $perPage) - $perPage;

        return new LengthAwarePaginator(array_slice($array, $offset, $perPage, true), count($array), $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()]);
    }

    /**
     * @return string
     */
    final public static function getTableName(): string
    {
        return with(new static)->getTable();
    }
}
