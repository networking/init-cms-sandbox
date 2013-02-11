<?php

namespace Sandbox\InitCmsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SandboxInitCmsBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'NetworkingInitCmsBundle';
    }
}
