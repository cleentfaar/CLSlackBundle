<?php

namespace CL\Bundle\SlackBundle\Slack\Payload\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

interface TypeInterface
{
    /**
     * Sets the default options for this type.
     *
     * @param OptionsResolverInterface $resolver The resolver for the options.
    */
    public function setDefaultOptions(OptionsResolverInterface $resolver);
}
