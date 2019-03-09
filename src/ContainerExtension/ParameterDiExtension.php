<?php
declare(strict_types=1);

namespace Next\PluginRunner\ContainerExtension;

class ParameterDiExtension extends ParameterWithNamesDiExtension
{
    protected function getValues(array $configs): array
    {
        $values = parent::getValues($configs);
        return array_values($values);
    }
}
