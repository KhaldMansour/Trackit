<?php

namespace App\Repositories;

use App\Models\Task;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class TaskRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class TaskRepository extends BaseRepository implements TaskRepositoryInterface
{
    protected $searchJoin = 'and';

    protected $fieldSearchable = [
        'title',
        'status',
        'due_date' => 'between',
        'assignee.name' => 'like'

    ];

    public function model()
    {
        return Task::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $request = request();
        $request->merge(['searchJoin' => 'and']);

        $this->pushCriteria(app(RequestCriteria::class));
    }
}
