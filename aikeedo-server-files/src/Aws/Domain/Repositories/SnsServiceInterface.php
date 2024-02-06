<?php

namespace Aws\Domain\Repositories;

interface SnsServiceInterface
{
    public function getEndpoint(): string;
    public function getProtocol(): string;

    public function getTopicArn(): string;
}