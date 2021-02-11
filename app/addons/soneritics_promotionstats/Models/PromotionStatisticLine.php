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

class PromotionStatisticLine
{
    /**
     * The promotion code used.
     * @var string
     */
    private $code;

    /**
     * The number of times it's been used.
     * @var int
     */
    private $amount = 0;

    /**
     * The ID of the promotion.
     * @var int
     */
    private $promotionId;

    /**
     * The name of the promotion.
     * @var string
     */
    private $promotionName;

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return PromotionStatisticLine
     */
    public function setCode(string $code): PromotionStatisticLine
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     * @return PromotionStatisticLine
     */
    public function setAmount(int $amount): PromotionStatisticLine
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * Increase the total amount.
     * @param int $amount
     * @return $this
     */
    public function increaseAmount(int $amount = 1): PromotionStatisticLine
    {
        $this->amount += $amount;
        return $this;
    }

    /**
     * @return int
     */
    public function getPromotionId(): int
    {
        return $this->promotionId;
    }

    /**
     * @param int $promotionId
     * @return PromotionStatisticLine
     */
    public function setPromotionId(int $promotionId): PromotionStatisticLine
    {
        $this->promotionId = $promotionId;
        return $this;
    }

    /**
     * @return string
     */
    public function getPromotionName(): string
    {
        return $this->promotionName;
    }

    /**
     * @param string $promotionName
     * @return PromotionStatisticLine
     */
    public function setPromotionName(string $promotionName): PromotionStatisticLine
    {
        $this->promotionName = $promotionName;
        return $this;
    }
}