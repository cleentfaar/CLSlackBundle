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

use CL\Slack\Payload\FilesUploadPayload;
use CL\Slack\Payload\FilesUploadPayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class FilesUploadCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('slack:files:upload');
        $this->setDescription('Create or upload an existing file to Slack');
        $this->addOption('path', 'p', InputOption::VALUE_REQUIRED, 'The path to the file to upload');
        $this->addOption('content', 'c', InputOption::VALUE_REQUIRED, 'The raw content of the file to upload (alternative for `--path`)');
        $this->addOption('filetype', 'ft', InputOption::VALUE_REQUIRED, 'Slack-internal file type identifier (e.g. `php`)');
        $this->addOption('filename', 'fn', InputOption::VALUE_REQUIRED, 'Filename of the file');
        $this->addOption('title', null, InputOption::VALUE_REQUIRED, 'Title of the file');
        $this->addOption('initial-comment', null, InputOption::VALUE_REQUIRED, 'Initial comment to add to the file');
        $this->addOption('channels', null, InputOption::VALUE_REQUIRED, 'Comma-separated list of channel IDs to share the file into');
        $this->setHelp(<<<EOT
The <info>slack:files:upload</info> command allows you to create or upload an existing file.

The type of data in the file will be intuited from the filename and the magic bytes in the file, for supported formats.
Using the `--filetype` option will override this behavior (if a valid type is given).

The file can also be shared directly into channels on upload, by specifying the `--channels` option.
Channel IDs should be comma separated if there is more than one.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/files.upload</comment>
EOT
        );
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'files.upload';
    }

    /**
     * {@inheritdoc}
     *
     * @param FilesUploadPayload $payload
     * @param InputInterface     $input
     */
    protected function configurePayload(PayloadInterface $payload, InputInterface $input)
    {
        if ($input->getOption('path')) {
            $content = file_get_contents($input->getOption('path'));
        } elseif ($input->getOption('content')) {
            $content = $input->getOption('content');
        } else {
            throw new \LogicException('Either the `--path` or the `--content` option must be used');
        }

        $payload->setContent($content);
        $payload->setChannels($input->getOption('channels'));
        $payload->setFilename($input->getOption('filename'));
        $payload->setFileType($input->getOption('filetype'));
        $payload->setTitle($input->getOption('title'));
    }

    /**
     * {@inheritdoc}
     *
     * @param FilesUploadPayloadResponse $payloadResponse
     * @param InputInterface             $input
     * @param OutputInterface            $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk($output, 'Successfully upload file to Slack:');
            $file = $payloadResponse->getFile();
            $this->renderKeyValueTable($output, $file);
        } else {
            $this->writeError($output, sprintf('Failed to upload file: %s', $payloadResponse->getErrorExplanation()));
        }
    }
}
