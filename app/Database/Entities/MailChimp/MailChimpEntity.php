<?php
declare(strict_types=1);

namespace App\Database\Entities\MailChimp;

use App\Database\Entities\Entity;
use EoneoPay\Utils\Str;

abstract class MailChimpEntity extends Entity
{
    /**
     * Get validation rules for mailchimp entity.
     *
     * @return array
     */
    abstract public function getValidationRules(): array;

    /**
     * Get array representation of entity.
     *
     * @return array
     */
    abstract public function toArray(): array;

    /**
     * Get array representation of entity.
     *
     * @return array
     */
    abstract public function toMailChimpArray(): array;


}
