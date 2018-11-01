<?php declare(strict_types=1);

namespace Skyld\OatAssignment\Domain\User\Model;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use LimitIterator;

class UserCollection implements IteratorAggregate, Countable
{
    private const DEFAULT_LIMIT = 10;

    /** @var User[] */
    private $items = [];

    /** @var int */
    private $offset = 0;

    /** @var int */
    private $limit;

    public function getById(string $login): ?User
    {
        return $this->items[$login] ?? null;
    }

    public function add(User $user): self
    {
        $this->items[$user->getLogin()] = $user;

        return $this;
    }

    public function remove(User $user): self
    {
        unset($this->items[$user->getLogin()]);

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return ArrayIterator|LimitIterator
     */
    public function getIterator()
    {
        return new LimitIterator(
            new ArrayIterator($this->items),
            $this->offset,
            $this->limit ?? self::DEFAULT_LIMIT
        );
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        return count($this->items);
    }

    public function setOffset(int $offset): self
    {
        $this->offset = $offset;

        return $this;
    }

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }
}
