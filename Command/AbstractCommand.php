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

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
abstract class AbstractCommand extends ContainerAwareCommand
{
    /**
     * Renders a table with a pre-set format of a Key and a Value column,
     * allowing you to supply a simple one-dimensional key-value array as the $rows argument
     *
     * @param array           $rows   The keys and values to display in a table, as a one-dimensional array
     * @param OutputInterface $output
     */
    protected function renderTableKeyValue(array $rows, OutputInterface $output)
    {
        $finalRows = [];
        foreach ($rows as $x => $row) {
            $finalRows[] = [$x, $row];
        }

        $this->renderTable(['Key', 'Value'], $finalRows, $output);
    }

    /**
     * Renders a table from the given arguments and sends it to the output.
     *
     * @param array           $headers The headers to use for the table, leave empty to use the keys from the $rows argument.
     * @param array           $rows    The rows to fill the table with, each entry in the item should be an array containing the values for each column
     * @param OutputInterface $output  The output instance to send the table to.
     */
    protected function renderTable(array $headers, array $rows, OutputInterface $output)
    {
        if (empty($rows)) {
            return;
        }

        if (empty($headers)) {
            $firstRow = reset($rows);
            $headers = array_keys($firstRow);
        }

        /** @var Table $tableHelper */
        $tableHelper = $this->getHelper('table');
        $finalRows   = [];
        $i           = 0;
        $tableHelper->setHeaders($headers);
        foreach ($rows as $row) {
            foreach ($row as $column => $value) {
                if (!is_scalar($value)) {
                    $finalRows[$i][$column] = gettype($value);
                } else {
                    $finalRows[$i][$column] = $value;
                }
            }
            $i++;
        }
        $tableHelper->setRows($finalRows);
        $tableHelper->render($output);
    }
}
