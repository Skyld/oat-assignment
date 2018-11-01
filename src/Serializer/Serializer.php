<?php declare(strict_types=1);

namespace Skyld\OatAssignment\Serializer;

use League\Fractal\Serializer\DataArraySerializer;

class Serializer extends DataArraySerializer
{
    /**
     * @inheritDoc
     */
    public function item($resourceKey, array $data): array
    {
        return $data;
    }

    /**
     * @inheritDoc
     */
    public function collection($resourceKey, array $data): array
    {
        return $data;
    }
}
