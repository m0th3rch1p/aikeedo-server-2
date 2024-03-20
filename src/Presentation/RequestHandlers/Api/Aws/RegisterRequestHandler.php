<?php

namespace Presentation\RequestHandlers\Api\Aws;

use Aws\Application\Commands\ReadByCustomerIdAwsCommand;
use Aws\Application\Commands\UpdateAwsCommand;
use Aws\Domain\Entities\AwsEntity;
use Billing\Application\Commands\ActivateSubscriptionCommand;
use Billing\Application\Commands\CreateSubscriptionCommand;
use Billing\Application\Commands\ReadPlanByTitleCommand;
use Billing\Domain\ValueObjects\PaymentGateway;
use Billing\Domain\ValueObjects\TrialPeriodDays;
use Doctrine\ORM\NonUniqueResultException;
use Easy\Container\Attributes\Inject;
use Easy\Http\Message\RequestMethod;
use Easy\Http\Message\StatusCode;
use Easy\Router\Attributes\Route;
use Presentation\Exceptions\HttpException;
use Presentation\Exceptions\NotFoundException;
use Presentation\Response\Api\Auth\AuthResponse;
use Presentation\Response\JsonResponse;
use Presentation\Validation\ValidationException;
use Presentation\Validation\Validator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Shared\Domain\ValueObjects\CurrencyCode;
use Shared\Infrastructure\CommandBus\Dispatcher;
use Shared\Infrastructure\CommandBus\Exception\NoHandlerFoundException;
use User\Application\Commands\CreateUserCommand;
use User\Application\Commands\UpdateUserCommand;
use User\Domain\Exceptions\EmailTakenException;

#[Route(path: '/register', method: RequestMethod::POST)]
class RegisterRequestHandler extends AwsApi implements
    RequestHandlerInterface
{
    public function __construct(
        private Dispatcher $dispatcher,
        private Validator $validator,
        #[Inject('option.site.user_accounts_enabled')]
        private bool $userAccountsEnabled = true,
        #[Inject('option.site.user_signup_enabled')]
        private bool $userSignupEnabled = true
    ) {
    }

    /**
     * @throws ValidationException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // TODO: Implement handle() method.
        $this->validateRequest($request);

        $payload = (object) $request->getParsedBody();
        try {
            $cmd = new CreateUserCommand(
                $payload->email,
                $payload->first_name,
                $payload->last_name
            );

            $cmd->setPassword($payload->password);

            //Setup User Subscription
            $awsCmd = new ReadByCustomerIdAwsCommand($payload->c_id);
            $aws = $this->dispatcher->dispatch($awsCmd);

            $subscriptions = $aws->getAwsSubscriptions();

            $user = $this->dispatcher->dispatch($cmd);
            $plan = null;
            $totalTextTokens = 0;
            $totalImageTokens = 0;
            $totalAudioTokens = 0;
            foreach ($subscriptions as $subscription) {
                $planCmd = new ReadPlanByTitleCommand($subscription->getDimension());
                $plan = $this->dispatcher->dispatch($planCmd);

                $tokenUsage = $subscription->getQuantity() > 1 ?
                    -($plan->getTokenCredit() * ($subscription->getQuantity() - 1))
                    : 0;
                $totalTextTokens += $tokenUsage;

                $imageUsage = $subscription->getQuantity() > 1 ?
                    -($plan->getImageCredit() * ($subscription->getQuantity() - 1))
                    : 0;
                $totalImageTokens += $imageUsage;

                $audioUsage = $subscription->getQuantity() > 1 ?
                    -($plan->getAudioCredit() * ($subscription->getQuantity() - 1))
                    : 0;
                $totalAudioTokens += $audioUsage;
            }

            $currency = CurrencyCode::tryFrom($this->currency ?? 'USD')
                ?? CurrencyCode::USD;

            $sub = $user->subscribeToPlan(
                $plan,
                $currency,
                new PaymentGateway(),
                new TrialPeriodDays(null),
                $totalTextTokens,
                $totalImageTokens,
                $totalAudioTokens
            );

            $activateCmd = new ActivateSubscriptionCommand($user, $sub->getId());
            $this->dispatcher->dispatch($activateCmd);

            //Set User Entity To User
            $user->setAws($aws);
            $updateUserCmd = new UpdateUserCommand($user);
            $this->dispatcher->dispatch($updateUserCmd);

//            $subCmd = new CreateSubscriptionCommand($user, $plan, new PaymentGateway('aws'));
//            $response = $this->dispatcher->dispatch($subCmd);
        } catch (EmailTakenException $th) {
            throw new HttpException(
                message: $th->getMessage(),
                param: 'email'
            );
        } catch (NonUniqueResultException $e) {
            return new JsonResponse("You've already set up your account", StatusCode::CONFLICT);
        } catch (NoHandlerFoundException $e) {
            return new JsonResponse($e->getMessage(), StatusCode::INTERNAL_SERVER_ERROR);
        }

        return new AuthResponse($user);
    }

    /**
     * @throws ValidationException
     */
    public function validateRequest(ServerRequestInterface $req): void
    {
        if (!$this->userAccountsEnabled || !$this->userSignupEnabled) {
            throw new NotFoundException();
        }

        $this->validator->validateRequest($req, [
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
    }
}
