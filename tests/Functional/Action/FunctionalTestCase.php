<?php declare(strict_types=1);

namespace Skyld\OatAssignment\Tests\Functional\Action;

use DI\Bridge\Slim\App;
use DI\Container;
use DI\ContainerBuilder;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Skyld\OatAssignment\Domain\User\Model\User;
use Skyld\OatAssignment\Repository\UserRepositoryInterface;
use Skyld\OatAssignment\Tests\DummyUserRepository;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

class FunctionalTestCase extends TestCase
{
    /** @var bool */
    protected $withMiddleware = true;

    /** @var Container */
    protected $container;

    /** @var User[] */
    private $userFixtures = [];

    /** @var Generator */
    private $faker;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->faker = Factory::create();
    }

    public function runApp($requestMethod, $requestUri, $requestData = null)
    {
        $environment = Environment::mock(
            [
                'REQUEST_METHOD' => $requestMethod,
                'REQUEST_URI' => $requestUri
            ]
        );

        $request = Request::createFromEnvironment($environment);

        if ($requestData !== null) {
            $request = $request->withParsedBody($requestData);
        }

        $response = new Response();

        $app = new class() extends App {
            protected function configureContainer(ContainerBuilder $builder): void
            {
                $builder->addDefinitions(
                    __DIR__ . '/../../../config/settings.php',
                    __DIR__ . '/../../../config/definitions.php'
                );
            }
        };

        if ($this->withMiddleware) {
            require __DIR__ . '/../../../config/middleware.php';
        }

        require __DIR__ . '/../../../config/routes.php';

        $this->container = $app->getContainer();

        $dummyUserRepository = new DummyUserRepository();
        foreach ($this->userFixtures as $fixture) {
            $dummyUserRepository->addFixture($fixture);
        }

        $this->container->set(UserRepositoryInterface::class, $dummyUserRepository);

        return $app->process($request, $response);
    }

    protected function createUserFixture(string $login = null): User
    {

        $fixture = new User(
            $login ?? $this->faker->unique()->userName,
            $this->faker->password,
            $this->faker->title,
            $this->faker->firstName,
            $this->faker->lastName,
            $this->faker->randomElement(['male', 'female']),
            $this->faker->email,
            $this->faker->imageUrl(),
            $this->faker->address
        );

        $this->userFixtures[] = $fixture;

        return $fixture;
    }

    protected function getResponseBody(ResponseInterface $response): array
    {
        return json_decode((string)$response->getBody(), true);
    }
}
