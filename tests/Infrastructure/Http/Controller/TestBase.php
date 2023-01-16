<?php


namespace CodeChallenge\Tests\Infrastructure\Http\Controller;


use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use function GuzzleHttp\Psr7\parse_query;

class TestBase extends WebTestCase
{
    /** @var EntityManager $entityManager */
    protected $entityManager;
    protected $client;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $kernel = $this->bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function assertResponseIsOk()
    {
        $responseText = $this->client->getResponse()->getContent();

        $responseContent = json_decode($responseText);

        if ($responseContent == null) {
            $filename = __DIR__ . "/response.html";
            echo "\nMalformed response, check it at $filename\n";
            file_put_contents($filename, $responseText);
        }
        if ($responseContent->status !== 'ok') {
            var_dump($responseContent);
        }
        $this->assertEquals('ok', $responseContent->status);
    }

    public function responseObject()
    {
        return json_decode($this->client->getResponse()->getContent());
    }

    protected function insert($table, array $data)
    {
        $this->entityManager->getConnection()->insert($table, $data);
    }

    protected function deleteTableRecords($table)
    {
        $this->entityManager->getConnection()->executeQuery("DELETE FROM $table");
    }
}
