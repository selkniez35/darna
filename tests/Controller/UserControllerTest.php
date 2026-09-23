<?php

namespace App\Tests\Controller;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $entityManager;

    /** @var list<string> emails des utilisateurs créés par le test, supprimés en tearDown */
    private array $createdEmails = [];

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
    }

    protected function tearDown(): void
    {
        $repository = $this->entityManager->getRepository(User::class);
        foreach ($this->createdEmails as $email) {
            if ($user = $repository->findOneBy(['email' => $email])) {
                $this->entityManager->remove($user);
            }
        }
        $this->entityManager->flush();

        parent::tearDown();
    }

    #[DataProvider('protectedUrls')]
    public function testAnonymousCannotAccessUserPages(string $url): void
    {
        $this->client->request('GET', $url);

        self::assertResponseStatusCodeSame(401);
    }

    public static function protectedUrls(): iterable
    {
        yield 'index' => ['/user'];
        yield 'new' => ['/user/new'];
        yield 'show' => ['/user/1'];
        yield 'edit' => ['/user/1/edit'];
    }

    public function testRegularUserCannotAccessUserPages(): void
    {
        $this->client->loginUser($this->createUser(['ROLE_USER']));
        $this->client->request('GET', '/user');

        self::assertResponseStatusCodeSame(403);
    }

    public function testAdminCreatesUserWithHashedPassword(): void
    {
        $this->client->loginUser($this->createUser(['ROLE_ADMIN']));

        $email = $this->uniqueEmail();
        $this->client->request('GET', '/user/new');
        $this->client->submitForm('Save', [
            'user[email]' => $email,
            'user[plainPassword]' => 'motdepasse-secret',
            'user[displayName]' => 'Nouvel utilisateur',
            'user[phone]' => '0600000000',
            'user[createdAt]' => '2026-01-01T10:00',
            'user[updatedAt]' => '2026-01-01T10:00',
        ]);

        self::assertResponseRedirects('/user');

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        self::assertNotNull($user);
        self::assertNotSame('motdepasse-secret', $user->getPassword());
        self::assertTrue(
            static::getContainer()->get(UserPasswordHasherInterface::class)->isPasswordValid($user, 'motdepasse-secret')
        );
    }

    public function testEditWithoutPasswordKeepsCurrentHash(): void
    {
        $this->client->loginUser($this->createUser(['ROLE_ADMIN']));
        $user = $this->createUser(['ROLE_USER']);
        $previousHash = $user->getPassword();

        $this->client->request('GET', sprintf('/user/%d/edit', $user->getId()));
        $this->client->submitForm('Update', [
            'user[displayName]' => 'Nom modifié',
        ]);

        self::assertResponseRedirects('/user');

        $this->entityManager->clear();
        $user = $this->entityManager->getRepository(User::class)->find($user->getId());
        self::assertSame('Nom modifié', $user->getDisplayName());
        self::assertSame($previousHash, $user->getPassword());
    }

    public function testUserPagesDoNotExposePasswordHash(): void
    {
        $admin = $this->createUser(['ROLE_ADMIN']);
        $this->client->loginUser($admin);

        $this->client->request('GET', '/user');
        self::assertResponseIsSuccessful();
        self::assertStringNotContainsString($admin->getPassword(), $this->client->getResponse()->getContent());

        $this->client->request('GET', sprintf('/user/%d', $admin->getId()));
        self::assertResponseIsSuccessful();
        self::assertStringNotContainsString($admin->getPassword(), $this->client->getResponse()->getContent());
    }

    /**
     * @param list<string> $roles
     */
    private function createUser(array $roles): User
    {
        $now = new DateTimeImmutable();
        $user = (new User())
            ->setEmail($this->uniqueEmail())
            ->setRoles($roles)
            ->setDisplayName('Utilisateur de test')
            ->setIsActive(true)
            ->setIsVerified(true)
            ->setCreatedAt($now)
            ->setUpdatedAt($now);
        $user->setPassword(static::getContainer()->get(UserPasswordHasherInterface::class)->hashPassword($user, 'password'));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    private function uniqueEmail(): string
    {
        $email = uniqid('test-', true).'@example.com';
        $this->createdEmails[] = $email;

        return $email;
    }
}
