<?php

/*
 * This file is part of the CLSlackBundle.
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

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
abstract class AbstractApiSearchCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->addArgument(
            'query',
            InputArgument::REQUIRED,
            'The query to search with.'
        );
        $this->addOption(
            'highlight',
            null,
            InputOption::VALUE_NONE,
            'Highlights the places in the results where the query matches with.'
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function inputToOptions(InputInterface $input, array $options)
    {
        $options['query']     = $input->getArgument('query');
        $options['highlight'] = $input->getOption('highlight') ? 1 : 0;

        return $options;
    }
}
