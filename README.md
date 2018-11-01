# Open Assessment Technologies S.A. - Assignment

Author: Daniel Halasz <daniel.halaasz@gmail.com>

## Scope

Create a REST web service which can return a single user or a paginated list of users.
The service should rely on a direct file data source (json, csv), but also have to be flexible to adapt easily to any possible other sources in the future.

Here is the link to the client which consumes the API: https://hr.oat.taocloud.org/client/

## Installation

```
$ composer install
```
## Run

To run locally:
```
$ composer run-script start
```

To run with Docker:
```
$ docker-compose up -d
```

## Specification

OpenAPI specification can be found at [definition/openapi.yaml](definition/openapi.yaml).

## Tests
To run full test suite:
```
$ vendor/bin/phpunit
```

To run full test suite with coverage:
```
$ vendor/bin/phpunit --coverage-html var/coverage
```

To see coverage report with excluded functional tests:
```
vendor/bin/phpunit --testsuite Unit,Integration  --coverage-html var/coverage
```

To run mutation test suite:
```
$ vendor/bin/infection
```

## Test Metrics

- `100%` Test Coverage
- `100%` MSI (Mutation Score Indicator)

## Implementation

Key features:

- **Slim 3** framework with **PHP-DI** bridge.
- Flexible filesystem based data source handling with **League/FlySystem** library.
- Powerful request validation with **Respect/Validation** library.
- Separated response transformation layer with **League/Fractal** library.
- Randomized user fixture handling in tests with **Fzaninotto/Faker** library.

### How to switch between data sources?

To switch between data sources just simply change the container definition of `Skyld\OatAssignment\Repository\UserRepositoryInterface`
in the [config/definitions.php](config/definitions.php) file.

Currently supported implementations:
- [UserJsonRepository](src/Repository/UserJsonRepository.php)
- [UserCsvRepository](src/Repository/UserCsvRepository.php)

### Possible improvements

The following features were not implemented due to the strict time frame and are possible points of improvements in the future.

- Database user repository implementation with **Doctrine**
- PSR-3 API logging with **Monolog**
- Describe API features with **PHPSpec**
- Improve validation error API response format
