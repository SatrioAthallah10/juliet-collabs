<?php

namespace App\Repositories\Complaint;

use App\Models\Complaint;
use App\Repositories\Base\BaseRepository;

class ComplaintRepository extends BaseRepository implements ComplaintInterface
{
    public function __construct(Complaint $model)
    {
        parent::__construct($model);
    }
}
