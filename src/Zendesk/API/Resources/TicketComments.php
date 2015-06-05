<?php

namespace Zendesk\API\Resources;

use Zendesk\API\Exceptions\CustomException;
use Zendesk\API\Exceptions\MissingParametersException;
use Zendesk\API\Http;

/**
 * The TicketComments class exposes comment methods for tickets
 * @package Zendesk\API
 */
class TicketComments extends ResourceAbstract
{

    const OBJ_NAME = 'comment';
    const OBJ_NAME_PLURAL = 'comments';

    /**
     * Returns all comments for a particular ticket
     *
     * @param array $queryParams
     *
     * @throws MissingParametersException
     * @throws ResponseException
     * @throws \Exception
     *
     * @return mixed
     */
    public function findAll(array $queryParams = array())
    {
        $chainedParameters = $this->getChainedParameters();
        if (array_key_exists(Tickets::class, $chainedParameters))
            $queryParams['ticket_id'] = $chainedParameters[Tickets::class];

        if (!$this->hasKeys($queryParams, array('ticket_id'))) {
            throw new MissingParametersException(__METHOD__, array('ticket_id'));
        }

        $this->endpoint = 'tickets/' . $queryParams['ticket_id'] . '/comments.json';

        return parent::findAll($queryParams);
    }

    /**
     * Make the specified comment private
     *
     * @param array $params
     *
     * @throws MissingParametersException
     * @throws ResponseException
     * @throws \Exception
     *
     * @return mixed
     */
    public function makePrivate(array $params = array())
    {
        $chainedParameters = $this->getChainedParameters();
        if (array_key_exists(Tickets::class, $chainedParameters)) {
            $params['ticket_id'] = $chainedParameters[Tickets::class];
        }
        if (array_key_exists(self::class, $chainedParameters)) {
            $params['id'] = $chainedParameters[self::class];
        }

        if (!$this->hasKeys($params, array('id', 'ticket_id'))) {
            throw new MissingParametersException(__METHOD__, array('id', 'ticket_id'));
        }

        $this->endpoint = 'tickets/' . $params['ticket_id'] . '/comments/' . $params['id'] . '/make_private.json';
        $response = Http::send_with_options($this->client, $this->endpoint, ['method' => 'PUT']);

        $this->client->setSideload(null);

        return $response;
    }

    /*
     * Syntactic sugar methods:
     * Handy aliases:
     */

    /**
     * @param array $params
     *
     * @return mixed|void
     * @throws CustomException
     */
    public function find($id = null, array $queryQueryParams = array())
    {
        throw new CustomException('Method ' . __METHOD__ . ' does not exist. Try $client->ticket(ticket_id)->comments()->findAll() instead.');
    }

}