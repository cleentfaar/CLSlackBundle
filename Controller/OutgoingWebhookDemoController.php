<?php

namespace CL\Bundle\SlackBundle\Controller;

use CL\Bundle\SlackBundle\Slack\OutgoingWebhook\Request\OutgoingWebhookRequest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * This controller contains an example of an action you could have in your project that will
 * get called by an outgoing webhook from Slack.
 *
 * The example is about a quiz that you can start from within a channel in Slack by typing:
 *
 *       "/ask", followed by a question, like "What is the answer to 2 + 2?"
 *
 * Slack would then send a request to the webhookAction below, so we can then answer it
 * and send that answer back to the user in Slack:
 *
 *      "4"
 *
 * Security:
 * As long as you use the OutgoingWebhookRequestFactory as indicated below to convert the incoming Symfony request
 * into a OutgoingWebhookRequest instance, you should be fine as far as safety is concerned since,
 * during the process of making that instance, the token from the original request is verified against the one
 * you have configured in your app/config. An InvalidTokenException is thrown otherwise.
 *
 * About handling errors/invalid arguments:
 * As long as you use the default (Symfony) implementation for handling exceptions,
 * any exceptions you throw will just cause a response to be returned that does not
 * have status code 200; this is enough for Slack to know something went wrong.
 * To learn more about what Slack does when a outgoing webhook fails, check out their documentation:
 * https://<yourteamhere>.slack.com/services/new/outgoing-webhook
 */
class OutgoingWebhookDemoController extends Controller
{
    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function webhookAction(Request $request)
    {
        $webhookRequest = $this->get('cl_slack.outgoing_webhook.request_factory')->create($request->query->all());
        $triggerWord    = $webhookRequest->getTriggerWord();
        switch ($triggerWord) {
            case 'ask':
                $text = $this->ask($webhookRequest);
                break;
            default:
                throw new \InvalidArgumentException(sprintf("Unknown trigger-word: %s", $triggerWord));
        }

        $jsonData = [
            'text' => $text
        ];

        return new JsonResponse($jsonData);
    }

    /**
     * @param OutgoingWebhookRequest $request
     *
     * @return string
     */
    public function ask(OutgoingWebhookRequest $request)
    {
        // i.e. "What is the answer to 2 + 2?"
        $question = $request->getText();
        $answer   = '4';

        return $answer;
    }
}
