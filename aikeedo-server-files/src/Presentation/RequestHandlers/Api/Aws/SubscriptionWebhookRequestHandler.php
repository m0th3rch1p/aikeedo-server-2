<?php

namespace Presentation\RequestHandlers\Api\Aws;

use Aws\Application\Commands\ReadByCustomerIdAwsCommand;
use Aws\Domain\Helpers\SubscriptionSnsHelper;
use Aws\Infrastructure\Services\EntitlementService;
use Aws\Infrastructure\Services\SubscriptionSnsService;
use Aws\Infrastructure\Sns\SnsFactory;
use Billing\Application\Commands\ActivateSubscriptionCommand;
use Billing\Application\Commands\CancelSubscriptionCommand;
use Billing\Application\Commands\CreateSubscriptionCommand;
use Billing\Application\Commands\ReadPlanByTitleCommand;
use Easy\Http\Message\RequestMethod;
use Easy\Router\Attributes\Route;
use Presentation\Response\EmptyResponse;
use Presentation\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Shared\Infrastructure\CommandBus\Dispatcher;

#[Route(path: "/subscription/webhook", method: RequestMethod::POST)]
class SubscriptionWebhookRequestHandler extends AwsApi implements  RequestHandlerInterface
{
    public function __construct(private LoggerInterface $logger, private Dispatcher $dispatcher, private SnsFactory $snsFactory, private EntitlementService $entitlementService)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface {
        $data = json_decode($request->getBody()->getContents());
        $this->logger->info('Subscription SNS Notification');
        $this->logger->debug(json_encode($data));
        switch ($data->Type) {
            case 'SubscriptionConfirmation':
                //Confirm Subscription To Arn
                $this->snsFactory->confirmSubscription($data->Token, $data->TopicArn);
                $this->logger->debug("subscription added");
                break;
            case 'EntitlementUpdated':
                $this->logger->info('EntitlementUpdated');
                $this->logger->debug(json_encode($data));

                $customerId = $data->Message->CustomerIdentifier;
                $entitlementResults = $this->entitlementService->getEntitlementByCustomerId($customerId);
                $entitlements = $entitlementResults->get('Entitlements');

                if (!count($entitlements)) {
                    //Handle not active subscription
                    return new RedirectResponse(uri: '/');
                }

                $awsCmd = new ReadByCustomerIdAwsCommand($customerId);
                $aws = $this->dispatcher->dispatch($awsCmd);
                $user = $aws->getUser();

                $planCmd = new ReadPlanByTitleCommand($entitlements[0]['Dimension']);
                $plan = $this->dispatcher->dispatch($planCmd);

                $subscriptionCmd = new CreateSubscriptionCommand($user, $plan, 'aws');
                $subscription = $this->dispatcher->dispatch($subscriptionCmd);

                $activateSubCmd = new ActivateSubscriptionCommand($user, $subscription->getId());
                $this->dispatcher->dispatch($activateSubCmd);
                break;
            case 'SubscribeSuccess':
                $customerIdentifier = $data->Message->CustomerIdentifier;
                $awsCmd = new ReadByCustomerIdAwsCommand($customerIdentifier);
                $aws = $this->dispatcher->dispatch($awsCmd);

                $user = $aws->getUser();
                $subscription = $user->getSubscriptions()[0]->getId();

                $activateCmd = new ActivateSubscriptionCommand($user, $subscription);
                $this->dispatcher->dispatch($activateCmd);
                break;
            case 'SubscribeFail':
            case 'UnsubscribeSuccess':
                $customerIdentifier = $data->Message->CustomerIdentifier;
                $awsCmd = new ReadByCustomerIdAwsCommand($customerIdentifier);
                $aws = $this->dispatcher->dispatch($awsCmd);

                $user = $aws->getUser();
                $cancelCmd = new CancelSubscriptionCommand($user);
                $this->dispatcher->dispatch($cancelCmd);
                break;
            default:
                $this->logger->debug("Unhandled notification type");
        }
        //        $data = json_decode($request->getBody()->getContents());

        return new EmptyResponse();
    }
}