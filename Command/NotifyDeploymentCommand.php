<?php

/*
 * This file is part of CLSlackBundle.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\Bundle\SlackBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

class NotifyDeploymentCommand extends AbstractWebhookCommand
{
    /**
     * {@inheritdoc}
     */
    protected $defaultUsername = 'deploybot';

    /**
     * {@inheritdoc}
     */
    protected $defaultChannel = '#deployment';

    /**
     * {@inheritdoc}
     */
    protected $defaultIcon = ':truck:';

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();
        $this->setName('slack:notify-deployment');
        $this->setDescription('Notifies Slack that a new deployment has been made');
        $this->addArgument('project', InputArgument::REQUIRED, 'The name of the project that is being deployed');
        $this->addArgument(
            'target',
            InputArgument::REQUIRED,
            'The target/servername on which the project was deployed'
        );
        $this->addOption(
            'project-url',
            'url',
            InputOption::VALUE_REQUIRED,
            'The URL to a comparison between the previous revision and the current revision'
        );
        $this->addOption(
            'diff-url',
            null,
            InputOption::VALUE_REQUIRED,
            'The URL to a comparison between the previous revision and the current revision'
        );
        $this->addOption('changelog', 'l', InputOption::VALUE_REQUIRED, 'A list of changes for this deployment');
    }

    /**
     * @param InputInterface $input
     *
     * @return array
     */
    protected function gatherSentences(InputInterface $input)
    {
        $sentences = [];
        if ($input->getOption('project-url')) {
            $sentences[] = 'The <{{ project-url }}|{{ project }}> project has been deployed to \'{{ target }}\'.';
        } else {
            $sentences[] = 'The {{ project }}-project has been deployed to \'{{ target }}\'.';
        }

        if ($input->getOption('diff-url')) {
            $sentences[] = "\n\nThe diff of this deployment can be found <{{ diff-url }}|here>.";
        }

        if ($input->getOption('changelog')) {
            $sentences[] = "\n\nAdditionally, the following changelog was provided:\n\n{{ changelog }}.";
        }

        return $sentences;
    }

    /**
     * {@inheritdoc}
     */
    protected function createMessage(InputInterface $input)
    {
        $message   = implode(" ", $this->gatherSentences($input));
        $variables = [
            'project'     => $input->getArgument('project'),
            'target'      => $input->getArgument('target'),
            'project-url' => $input->getOption('project-url'),
            'diff-url'    => $input->getOption('diff-url'),
            'username'    => $input->getOption('username'),
            'channel'     => $input->getOption('channel'),
            'changelog'   => $input->getOption('changelog'),
        ];

        return parent::parseMessage($message, $variables);
    }
}
