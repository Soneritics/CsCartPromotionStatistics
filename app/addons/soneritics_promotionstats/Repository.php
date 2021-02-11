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

/**
 * Repository for fetching promotion code data.
 * (Unfortunately, Cs Cart saves promo data serialized as order data.)
 * Class SoneriticsPromotionStatisticsRepository
 */
class SoneriticsPromotionStatisticsRepository
{
    /**
     * Get all codes and their usage.
     * @return PromotionStatisticLine[]
     */
    public function getCodesAndUsage(): array
    {
        $result = [];

        $rows = db_get_array("SELECT `data` FROM ?:order_data WHERE `type` = 'C'");
        if (!empty($rows)) {
            $structuredResult = [];

            foreach ($rows as $row) {
                $unserialized = unserialize($row['data']);

                if (!empty($unserialized)) {
                    foreach ($unserialized as $code => $promoData) {
                        if (!empty($promoData[0])) {
                            $promoId = $promoData[0];

                            if (!isset($structuredResult[$promoId])) {
                                $structuredResult[$promoId] = [];
                            }

                            if (!isset($structuredResult[$promoId][$code])) {
                                $structuredResult[$promoId][$code] = (new PromotionStatisticLine)
                                    ->setCode($code)
                                    ->setPromotionId($promoId)
                                    ->setPromotionName(fn_get_promotion_name($promoId));
                            }

                            $structuredResult[$promoId][$code]->increaseAmount();
                        }
                    }
                }
            }

            if (!empty($structuredResult)) {
                krsort($structuredResult);

                foreach ($structuredResult as $structuredResultData) {
                    if (!empty($structuredResultData)) {
                        ksort($structuredResultData);

                        foreach ($structuredResultData as $line) {
                            $result[] = $line;
                        }
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Get all order IDs for a specific code.
     * @param string $code
     * @return int[]
     */
    public function getOrderIdsForCode(string $code): array
    {
        $result = [];

        $rows = db_get_array(
            "SELECT order_id, `data` FROM ?:order_data WHERE `type` = 'C' AND `data` LIKE ?s",
            "%{$code}%"
        );

        if (!empty($rows)) {
            foreach ($rows as $row) {
                $unserialized = unserialize($row['data']);

                if (!empty($unserialized[$code])) {
                    $result[] = $row['order_id'];
                }
            }
        }

        return $result;
    }

    /**
     * Get all order IDs for a specific promotion.
     * @param int $promotionId
     * @return int[]
     */
    public function getOrderIdsForPromotion(int $promotionId): array
    {
        $result = [];

        $rows = db_get_array(
            "SELECT order_id, `data` FROM ?:order_data WHERE `type` = 'C' AND `data` LIKE ?s",
            "%:{$promotionId};%"
        );

        if (!empty($rows)) {
            foreach ($rows as $row) {
                $unserialized = unserialize($row['data']);

                foreach ($unserialized as $code => $promoData) {
                    if (!empty($promoData[0])) {
                        $promoId = $promoData[0];

                        if ($promoId == $promotionId) {
                            $result[] = $row['order_id'];
                        }
                    }
                }
            }
        }

        return $result;
    }
}
