<?php
/*
 * Copyright (c)
 * Kirill chEbba Chebunin <iam@chebba.org>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */

namespace EventBand\Transport\Amqp\Definition;

/**
 * Description of ConnectionDefinition
 *
 * @author Kirill chEbba Chebunin <iam@chebba.org>
 * @license http://opensource.org/licenses/mit-license.php MIT
 */
interface ConnectionDefinition
{
    public function getHost();

    public function getPort();

    public function getUser();

    public function getPassword();

    public function getVirtualHost();

    public function getHeartbeat();

    public function getInsist();

    public function getLoginMethod();

    public function getLoginResponse();

    public function getLocale();

    public function getConnectionTimeout();

    public function getReadWriteTimeout();

    public function getKeepalive();

    public function getContext();
}
