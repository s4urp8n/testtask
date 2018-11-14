<?php
declare(strict_types=1);

namespace App\Http\Controllers\MailChimp;

use App\Database\Entities\MailChimp\MailChimpList;
use App\Database\Entities\MailChimp\MailChimpMember;
use App\Http\Controllers\Controller;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mailchimp\Mailchimp;

class MembersController extends Controller
{
    /**
     * @var \Mailchimp\Mailchimp
     */
    private $mailChimp;

    /**
     * ListsController constructor.
     *
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \Mailchimp\Mailchimp $mailchimp
     */
    public function __construct(EntityManagerInterface $entityManager, Mailchimp $mailchimp)
    {
        parent::__construct($entityManager);

        $this->mailChimp = $mailchimp;
    }

    /**
     * Create MailChimp member.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        // Instantiate entity
        $member = new MailChimpMember($request->all());

        // Validate entity
        $validator = $this->getValidationFactory()
                          ->make($member->toMailChimpArray(), $member->getValidationRules());

        if ($validator->fails()) {
            // Return error response if validation failed
            return $this->errorResponse([
                                            'message' => 'Invalid data given',
                                            'errors'  => $validator->errors()
                                                                   ->toArray(),
                                        ]);
        }

        try {
            $response = $this->mailChimp->post('lists/' . $member->getListId() . '/members',
                                               $member->toMailChimpArray());

            $this->saveEntity($member->setId($response->get('id')));
        } catch (Exception $exception) {
            return $this->errorResponse(['message' => $exception->getMessage()]);
        }

        return $this->successfulResponse($member->toArray());
    }

    /**
     * Remove MailChimp member.
     *
     * @param string $memberId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove(string $memberId): JsonResponse
    {
        /** @var \App\Database\Entities\MailChimp\MailChimpMember|null $member */
        $member = $this->entityManager->getRepository(MailChimpMember::class)
                                      ->find($memberId);

        if ($member === null) {
            return $this->errorResponse(
                ['message' => \sprintf('MailChimpMember[%s] not found', $memberId)],
                404
            );
        }

        try {
            // Remove MailChimpMember from MailChimp
            $this->mailChimp->delete(\sprintf('lists/%s/members/%s', $member->getListId(), $member->getId()));
            // Remove MailChimpMember from database
            $this->removeEntity($member);
        } catch (Exception $exception) {
            return $this->errorResponse(['message' => $exception->getMessage()]);
        }

        return $this->successfulResponse([]);
    }

    /**
     * Retrieve and return MailChimp member.
     *
     * @param string $memberId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $memberId): JsonResponse
    {

        /** @var \App\Database\Entities\MailChimp\MailChimpList|null $list */
        $list = $this->entityManager->getRepository(MailChimpMember::class)
                                    ->find($memberId);

        if ($list === null) {
            return $this->errorResponse(
                ['message' => \sprintf('MailChimpMember[%s] not found', $memberId)],
                404
            );
        }

        return $this->successfulResponse($list->toArray());
    }

    /**
     * Update MailChimp member.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $memberId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $memberId): JsonResponse
    {
        /** @var \App\Database\Entities\MailChimp\MailChimpMember|null $member */
        $member = $this->entityManager->getRepository(MailChimpMember::class)
                                      ->find($memberId);

        if ($member === null) {
            return $this->errorResponse(
                ['message' => \sprintf('MailChimpMember[%s] not found', $memberId)],
                404
            );
        }

        // Update list properties
        $member->fill($request->all());

        // Validate entity
        $validator = $this->getValidationFactory()
                          ->make($member->toMailChimpArray(), $member->getValidationRules());

        if ($validator->fails()) {
            // Return error response if validation failed
            return $this->errorResponse([
                                            'message' => 'Invalid data given',
                                            'errors'  => $validator->errors()
                                                                   ->toArray(),
                                        ]);
        }


        try {
            // Update list into MailChimp
            $this->mailChimp->patch(
                \sprintf('lists/%s/members/%s', $member->getListId(), $member->getId()),
                $member->toMailChimpArray()
            );
            // Update list into database
            $this->saveEntity($member);
        } catch (Exception $exception) {
            return $this->errorResponse(['message' => $exception->getMessage()]);
        }

        return $this->successfulResponse($member->toArray());
    }
}
