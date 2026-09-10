<?php

namespace Tests\Feature;

use App\Repositories\BorrowingRepository;
use App\Repositories\BookRepository;
use App\Repositories\Contracts\BorrowingRepositoryInterface;
use App\Repositories\Contracts\BookRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Services\BookService;
use App\Services\BorrowingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use ReflectionProperty;
use Tests\TestCase;

class ArchitectureTest extends TestCase
{
    use RefreshDatabase;

    public function test_repository_interfaces_are_bound_to_implementations(): void
    {
        $this->assertInstanceOf(BookRepository::class, app(BookRepositoryInterface::class));
        $this->assertInstanceOf(BorrowingRepository::class, app(BorrowingRepositoryInterface::class));
        $this->assertInstanceOf(UserRepository::class, app(UserRepositoryInterface::class));
    }

    public function test_book_service_depends_on_the_repository_interface(): void
    {
        $mock = $this->mock(BookRepositoryInterface::class, function (MockInterface $mock): void {
            //
        });

        $bookService = $this->app->make(BookService::class);

        $this->assertSame($mock, $this->readProperty($bookService, 'bookRepository'));
    }

    public function test_borrowing_service_depends_on_repository_interfaces(): void
    {
        $bookMock = $this->mock(BookRepositoryInterface::class, function (MockInterface $mock): void {
            //
        });

        $borrowingMock = $this->mock(BorrowingRepositoryInterface::class, function (MockInterface $mock): void {
            //
        });

        $borrowingService = $this->app->make(BorrowingService::class);

        $this->assertSame($bookMock, $this->readProperty($borrowingService, 'bookRepository'));
        $this->assertSame($borrowingMock, $this->readProperty($borrowingService, 'borrowingRepository'));
    }

    public function test_auth_service_depends_on_the_user_repository_interface(): void
    {
        $mock = $this->mock(UserRepositoryInterface::class, function (MockInterface $mock): void {
            //
        });

        $authService = $this->app->make(AuthService::class);

        $this->assertSame($mock, $this->readProperty($authService, 'userRepository'));
    }

    public function test_controllers_do_not_contain_eloquent_queries_or_business_logic(): void
    {
        $files = glob(app_path('Http/Controllers/*.php'));

        $this->assertNotEmpty($files);

        foreach ($files as $file) {
            $source = file_get_contents($file);

            $this->assertStringNotContainsString('Book::', $source, "Eloquent query found in {$file}");
            $this->assertStringNotContainsString('Borrowing::', $source, "Eloquent query found in {$file}");
            $this->assertStringNotContainsString('User::', $source, "Eloquent query found in {$file}");
            $this->assertStringNotContainsString('DB::', $source, "Database facade usage found in {$file}");
        }
    }

    public function test_services_do_not_call_eloquent_queries_directly(): void
    {
        foreach ([BookService::class, BorrowingService::class, AuthService::class] as $service) {
            $this->assertStringNotContainsString(
                '->where(',
                file_get_contents((new \ReflectionClass($service))->getFileName()),
                "Eloquent query builder usage found in {$service}",
            );
        }
    }

    private function readProperty(object $object, string $property): mixed
    {
        $reflection = new ReflectionProperty($object, $property);

        return $reflection->getValue($object);
    }
}