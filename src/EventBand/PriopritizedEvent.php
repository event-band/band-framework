<?php
/*
 * Copyright (c) 2012-2015 Digital Society Laboratory <http://digsolab.com/>.
 * 
 * All Rights Reserved.
 * Proprietary and Confidential.
 * 
 * This software is the confidential and proprietary information
 * of the Digital Society Laboratory. You shall not disclose such Confidential
 * Information and shall use it only in accordance with the terms
 * of the license agreement you entered into with the Digital Society Laboratory.
 * 
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 *
 */

namespace EventBand;

interface PriopritizedEvent extends Event
{

    public function getPriority();

}