<?php
declare(strict_types=1);

namespace App\Database\Entities\MailChimp;

use Doctrine\ORM\Mapping as ORM;
use EoneoPay\Utils\Str;

/**
 * @ORM\Entity()
 */
class MailChimpMember extends MailChimpEntity
{

    use MailChimpEntityTrait;

    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="string")
     *
     * @var string
     */
    private $id;

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @ORM\Column(name="email_address", type="string")
     *
     * @var string
     */
    private $emailAddress;

    public function setEmailAddress($email)
    {
        $this->emailAddress = $email;
        return $this;
    }

    public function getEmailAddress()
    {
        return $this->emailAddress;
    }


    /**
     * @ORM\Column(name="status", type="string")
     *
     * @var string
     */
    private $status;

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @ORM\Column(name="list_id", type="string")
     *
     * @var string
     */
    private $listId;

    public function setListId($listId)
    {
        $this->listId = $listId;
        return $this;
    }

    public function getListId()
    {
        return $this->listId;
    }

    /**
     * Get validation rules for mailchimp entity.
     *
     * @return array
     */
    public function getValidationRules(): array
    {
        return [
            'list_id'       => 'required|string|exists:' . MailChimpList::class . ',mailChimpId',
            'email_address' => 'required|email',
            'status'        => 'required|string|in:subscribed,unsubscribed,cleaned,pending',
        ];
    }

}
