<?php
declare(strict_types=1);

namespace App\Database\Entities\MailChimp;

use EoneoPay\Utils\Str;

trait MailChimpEntityTrait
{
    /**
     * Get array representation of entity.
     *
     * @return array
     */
    public function toArray(): array
    {
        $array = [];
        $str = new Str();

        foreach (\get_object_vars($this) as $property => $value) {
            $array[$str->snake($property)] = $value;
        }

        return $array;
    }

    /**
     * Get mailchimp array representation of entity.
     *
     * @return array
     */
    public function toMailChimpArray(): array
    {
        $array = [];

        foreach ($this->toArray() as $property => $value) {
            if ($value === null) {
                continue;
            }

            $array[$property] = $value;
        }

        return $array;
    }

}
