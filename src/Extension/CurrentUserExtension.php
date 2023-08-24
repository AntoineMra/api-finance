<?php

namespace App\Extension\CurrentUserExtension;

class CurrentUserExtension implements QueryCollectionExtensionInterface
{
    public function __construct(private readonly Security $security)
    {
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
        $user = $this->security->getUser();
        // Needs implementation of createdBy on all entities
        // HERE I must check if createdBy and current user match => to be used by all entities
    }
}
