<?php

namespace Aws\Infrastructure\Sns;

use Aws\Credentials\Credentials;
use Aws\Domain\Repositories\SnsFactoryInterface;
use Aws\Domain\Repositories\SnsServiceInterface;
use Aws\Infrastructure\Sns\Services\SnsBaseService;
use Aws\Sns\SnsClient;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;

class SnsFactory implements SnsFactoryInterface
{
    private string $baseUrl;
    private SnsClient $client;
    private array $services = [];
    private array $subscribedEndpoints = [];
    public function __construct (private ContainerInterface $container, private LoggerInterface $logger) {
        $this->baseUrl = env('ENVIRONMENT') === 'dev' ? env('SNS_WEBHOOK_URL_DEV') : env('SNS_WEBHOOK_URL_PROD');
        $credentials = new Credentials(env('AWS_KEY'), env('AWS_SECRET'));
        $this->client = new SnsClient([
            'region' => 'us-east-1',
            'version' => 'latest',
            'credentials' => $credentials
        ]);
        $this->listSubscriptions();
    }

    public function register(string $service): self
    {
        // TODO: Implement register() method.
        $this->services[] = $service;
        return $this;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function resolveServices (): void
    {
        if (count($this->services)) {
            foreach ($this->services as $service) {
                $snsService = $this->container->get($service);
                $this->subscribeService($snsService);
            }
        }
    }

    public function listSubscriptions (): void
    {
        $results = $this->client->listSubscriptions();
        $subscriptions = $results->get('Subscriptions');
        $this->subscribedEndpoints = array_column($subscriptions, 'Endpoint');
    }

    public function subscribeService (SnsServiceInterface $service): void
    {
        $endpoint = $service->getProtocol() === "https" ? $this->baseUrl.$service->getEndpoint() : $service->getEndpoint() ;
        if (!(in_array($endpoint, $this->subscribedEndpoints))) {
            $this->client->subscribe([
                'Protocol' => $service->getProtocol(),
                'Endpoint' => $endpoint,
                'TopicArn' => $service->getTopicArn()
            ]);
            $this->logger->debug("New Subscription for endpoint".$endpoint);
            return;
        }
        $this->logger->debug('Subscription for '.$endpoint.' already confirmed');
    }

    public function confirmSubscription ($token, $topicArn): \Aws\Result
    {
        $results = $this->client->confirmSubscription([
            'Token' => $token,
            'TopicArn' => $topicArn
        ]);
        $this->logger->debug('Confirmation for topic '.$topicArn.' confirmed');
        return $results;
    }
}