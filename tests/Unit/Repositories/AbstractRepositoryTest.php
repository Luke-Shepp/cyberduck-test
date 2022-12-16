<?php

namespace Tests\Unit\Repositories;

use App\Repositories\EloquentRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Tests\TestCase;

class AbstractRepositoryTest extends TestCase
{
    public function testCreatesEntity()
    {
        $model = $this->mock(Model::class);
        $model->shouldReceive('create')
            ->once()
            ->with(['test' => 1])
            ->andReturnSelf();

        $repo = $this->getMockForAbstractClass(EloquentRepository::class, [$model]);

        $repo->create(['test' => 1]);
    }

    public function testGetsAllData()
    {
        $model = $this->mock(Model::class);
        $model->shouldReceive('all')
            ->once()
            ->andReturn(new Collection());

        $repo = $this->getMockForAbstractClass(EloquentRepository::class, [$model]);

        $this->assertInstanceOf(Collection::class, $repo->all());
    }
}
