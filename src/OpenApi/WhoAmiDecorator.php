<?php

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\Model;
use ApiPlatform\Core\OpenApi\OpenApi;

class UserDecorator implements OpenApiFactoryInterface
{
    public function __construct(
        private OpenApiFactoryInterface $decorated
    ) {
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);

        $this->addWhoamiPath($openApi);

        return $openApi;
    }

    private function addWhoamiPath(OpenApi $openApi): void
    {
        $pathItem = new Model\PathItem(
            ref: 'WhoAmI',
            get: new Model\Operation(
                operationId: 'whoami',
                tags: ['User'],
                responses: [
                    '200' => [
                        'description' => 'User informations',
                        'content' => [
                            'application/json' => [
                            ],
                        ],
                    ],
                    '401' => [
                        'description' => 'Unauthorized',
                    ],
                    '400' => [
                        'description' => 'Any error',
                    ],
                ],
                summary: 'Get User informations',
            ),
        );

        $openApi->getPaths()->addPath('/whoami', $pathItem);
    }
}
