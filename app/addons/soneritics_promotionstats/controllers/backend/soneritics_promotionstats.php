<?php
/*
 * The MIT License
 *
 * Copyright 2021 Jordi Jolink.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/** @var string $mode */

use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

// Init variables for use in every controller function
$repository = new SoneriticsPromotionStatisticsRepository;

// Overview
if ($mode === 'overview') {
    $overview = $repository->getCodesAndUsage();
    Tygh::$app['view']->assign('overview', $overview);
}

// Orders for a certain code
if (substr($mode, 0, strlen('orders_')) === 'orders_') {
    $params = $_REQUEST;

    if ($mode == 'orders_promo') {
        $promotionId = (int)$_REQUEST['promoId'];
        $params['order_id'] = $repository->getOrderIdsForPromotion($promotionId);
        Tygh::$app['view']->assign('overview_title', fn_get_promotion_name($promotionId));
    } elseif ($mode == 'orders_code') {
        $code = $_REQUEST['code'];
        $params['order_id'] = $repository->getOrderIdsForCode($code);
        Tygh::$app['view']->assign('overview_title', $code);
    }

    if (fn_allowed_for('MULTIVENDOR')) {
        $params['company_name'] = true;
    }

    list($orders, $search, $totals) = fn_get_orders(
        $params,
        Registry::get('settings.Appearance.admin_elements_per_page'),
        true
    );

    # Set view variables
    Tygh::$app['view']->assign('orders', $orders);
    Tygh::$app['view']->assign('search', $search);
    Tygh::$app['view']->assign('totals', $totals);
}
